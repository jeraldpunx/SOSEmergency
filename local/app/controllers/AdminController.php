<?php

class AdminController extends \BaseController {

	

	public function createAdmin()
	{
		$input = Input::all();

		$rules = array(
			'username' 				=> 'required|min:5|unique:users,username',
			'password' 				=> 'required|confirmed',
			'password_confirmation' => 'same:password'
		);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$user = new User;
			$user->puid_ruid 				= 	Null;
			$user->type 					=	'admin';
			$user->username 				=	Input::get('username');
			$user->password 				=	Hash::make(Input::get('password'));
			$user->save();

			$returnedValue = array(
				'id' 	=>	$user->id,
				'error'	=>	false
			);
		} else {
			$returnedValue = array(
				'error'		=>	true,
				'messages'	=>	$validation->messages()
			);
		}

		return Response::json($returnedValue);
	}

	//JSON MARKERS
	public function markers()
	{
		//SET INTO ARRAY
		DB::setFetchMode(PDO::FETCH_ASSOC);
		$markers = RescueUnit::select('rescue_units.id as rescue_units_id', 'name', 'address', 'lat', 'lng', 'email', 'rescue_units.type as rescue_type', 'status', 'users.id as users_id', 'users.type as users_type', 'puid_ruid', 'username')
							->join('users', 'rescue_units.id', '=', 'users.puid_ruid')
							->where('users.type', '=', 'ru')
							->where('status', '=', 1)
							->orderBy('rescue_units.id')->get();
		DB::setFetchMode(PDO::FETCH_CLASS);

		return Response::JSON($markers);
	}

	/****************************************
	**	FOR CRUD MAP
	**
	****************************************/

	public function mapView()
	{
		return View::make('contents.mapView');
	}

	public function listView()
	{
		$markers = RescueUnit::select('rescue_units.id as rescue_units_id', 'name', 'address', 'lat', 'lng', 'email', 'rescue_units.type as rescue_type', 'status')
								->where('status', '=', 1)
								->orderBy('rescue_units.id')->get();
		return View::make('contents.listView')->with('markers', $markers);
	}

	public function editRU($id)
	{
		return "aw";
	}

	public function saveAddMarker()
	{
		$input = Input::all();

		$rules = array(
			'name'					=> 	'required',
			'address'				=> 	'required',
			'lat'					=> 	'required',
			'type'					=> 	'required',
			'username' 				=> 	'required|min:5|unique:users,username',
			'password' 				=> 	'required|confirmed',
			'password_confirmation' => 	'same:password',
		);

		$custom_error = array(
			'lat.required' => 'Please point the marker on map.'
		);

		$validation = Validator::make($input, $rules, $custom_error);
		if($validation->passes()) {
			$rescue_units 			= 	new RescueUnit();
			$rescue_units->name 	= 	Input::get('name');
			$rescue_units->address 	= 	Input::get('address');
			$rescue_units->lat 		= 	Input::get('lat');
			$rescue_units->lng 		= 	Input::get('lng');
			$rescue_units->email 	= 	Input::get('email');
			$rescue_units->type 	= 	Input::get('type');
			$rescue_units->status 	= 	Input::get('status');
			$rescue_units->save();

			switch (Input::get('type')) {
				case 'firecontrol':
					$ec_id 	= array(1, 3);
					break;
				case 'hospital':
					$ec_id 	= array(2, 5);
					break;
				case 'police':
					$ec_id 	= array(4, 6);
					break;
				case 'rescuevolunteer':
					$ec_id 	= array(1, 2);
					break;
			}

			if(is_array($ec_id)) {
				foreach ($ec_id as $ec) {
					$ru_ec 					= 	new RuEc();
					$ru_ec->ru_id 			= 	$rescue_units->id;
					$ru_ec->ec_id 			= 	$ec;
					$ru_ec->save();
				}
			}

			$user = new User;
			$user->puid_ruid 				= 	$rescue_units->id;
			$user->type 					=	'ru';
			$user->username 				=	Input::get('username');
			$user->password 				=	Hash::make(Input::get('password'));
			$user->save();
		
			$returnedValue = array(
				'error'		=>	false,
				'id' 		=> 	$rescue_units->id
			);
		} else {
			$returnedValue = array(
				'error'		=>	true,
				'messages'	=>	$validation->messages()
			);
		}
		return Response::json($returnedValue);
	}


	//edit markers
	public function editMarker($id)
	{
		// $rescue_units = RescueUnit::find($id);
		// return $rescue_units;
		$rescue_unit = RescueUnit::select('rescue_units.id as rescue_units_id', 'name', 'address', 'lat', 'lng', 'email', 'rescue_units.type as rescue_type', 'status', 'users.id as users_id', 'users.type as users_type', 'puid_ruid', 'username')
							->join('users', 'rescue_units.id', '=', 'users.puid_ruid')
							->where('users.type', '=', 'ru')
							->where('status', '=', 1)
							->where('rescue_units.id', '=', $id)
							->orderBy('rescue_units.id')->get()[0];
		return $rescue_unit;
	}

	//update borrowers
	public function saveEditMarker($id)
	{
		$input = Input::all();

		$rules = array(
			'name'					=> 	'required',
			'address'				=> 	'required',
			'type'					=> 	'required'
		);

		$validation = Validator::make($input, $rules);
		if($validation->passes()) {
			$rescue_units 			= 	RescueUnit::find($id);
			$rescue_units->name 	= 	Input::get('name');
			$rescue_units->address 	= 	Input::get('address');
			$rescue_units->lat 		= 	Input::get('lat');
			$rescue_units->lng 		= 	Input::get('lng');
			$rescue_units->email 	= 	Input::get('email');
			$rescue_units->type 	= 	Input::get('type');
			$rescue_units->save();

			$userID 			= 	User::select('id')->where('puid_ruid', '=', $id)->get()[0]->id;
			$user 				= 	User::find($userID);
			$user->username 	= 	Input::get('username');
			if(Input::get('password') && Input::get('password_confirmation'))
				$user->password 	= 	Hash::make(Input::get('password'));
			$user->save();
				
			$returnedValue = array(
				'error'		=>	false
			);
		} else {
			$returnedValue = array(
				'error'		=>	true,
				'messages'	=>	$validation->messages()
			);
		}
		return Response::json($returnedValue);
	}

	public function viewRU($id)
	{
		$rescue_units = RescueUnit::find($id);
		return View::make('contents.viewRU')->with('rescue_units', $rescue_units);
	}


	public function deleteMarker()
	{
		RescueUnit::find(Input::get('marker_id'))->delete();
		DB::table('ru_ec')->where('ru_id', '=', Input::get('marker_id'))->delete();
		DB::table('ru_contacts')->where('ru_id', '=', Input::get('marker_id'))->delete();
		DB::table('users')->where('puid_ruid', '=', Input::get('marker_id'))->where('type', '=', 'ru')->delete();
	}

	public function addContact()
	{
		$input = Input::all();

		$rules = array(
			'contact_number'		=> 	'required'
		);

		$validation = Validator::make($input, $rules);
		if($validation->passes()) {
			$ru_contacts 						= 	new RUContact();
			$ru_contacts->ru_id 				= 	Input::get('ru_id');
			$ru_contacts->contact_number 		= 	Input::get('contact_number');
			$ru_contacts->save();
			
			$returnedValue = array(
				'error'				=>	false,
				'contact_id' 		=> 	$ru_contacts->id,
				'contact_number' 	=> 	$ru_contacts->contact_number
			);
		} else {
			$returnedValue = array(
				'error'		=>	true,
				'messages'	=>	$validation->messages()
			);
		}
		return Response::json($returnedValue);
	}


	public function getMarkerContactInfo($id) 
	{
		return RUContact::where("ru_id", "=", $id)->get();
	}

	public function saveEditContact($id)
	{
		$input = Input::all();

		$rules = array(
			'contact_number'		=> 	'required'
		);

		$validation = Validator::make($input, $rules);
		if($validation->passes()) {
			$ru_contacts 						= 	RUContact::find($id);
			$ru_contacts->contact_number 		= 	Input::get('contact_number');
			$ru_contacts->save();

			$returnedValue = array(
				'error'		=>	false
			);
		} else {
			$returnedValue = array(
				'error'		=>	true,
				'messages'	=>	$validation->messages()
			);
		}
		return Response::json($returnedValue);
	}

	public function deleteContact()
	{
		$ru_contacts = RUContact::find(Input::get('contact_id'));
		$ru_contacts->delete();
	}

	public function viewReport()
	{
		return View::make('contents.viewReport');
	}


	/****************************************
	**	FOR REAL TIME MAP VIEW
	**
	****************************************/
	public function realTimeMap() 
	{
		return View::make('contents.realtimeMap');
	}

	public function reportQueue()
	{
		$report_queues = ReportsQueue::select("*")
							->orderBy('date_received')
							->get();
		foreach ($report_queues as $index => $report_queue) {
			if($report_queue->date_received < date('Y-m-d H:i:s')) {
				$report_queue_check = Report::select("*")
										->where('report_group', '=', $report_queue->report_group)
										->get();

				//CHECK THE GROUP						
				if($report_queue_check[0]->date_responded == "") {
					$report 				= 	new Report();
					$report->pu_id 		 	= 	$report_queue->pu_id;
					$report->ru_id 			= 	$report_queue->ru_id;
					$report->ec_id 			= 	$report_queue->ec_id;
					$report->lat 			= 	$report_queue->lat;
					$report->lng 			= 	$report_queue->lng;
					$report->date_reported 	= 	$report_queue->date_reported;
					$report->date_received 	= 	date('Y-m-d H:i:s');
					$report->mobile 		= 	$report_queue->mobile;
					$report->report_group 	= 	$report_queue->report_group;
					$report->save();
					helper::sendGCMToRU($report->ru_id, $report->pu_id, $report->ec_id, $report->lat, $report->lng, $report->id);
				}
				$delete_report_queue = ReportsQueue::find($report_queue->id);
				$delete_report_queue->delete();
			}
		}
	}

	public function getCurrentTime() 
	{
		return Response::json(date('Y-m-d H:i:s'));
	}

	public function getLiveReports($currentTime) 
	{
		$currentReport 	= 	DB::select(DB::raw("
				SELECT 
					r.id as r_id, 
					r.pu_id, 
						pu.name as pu_name, 
						pu.birth_date, 
						pu.gender, 
						pu.email as pu_email, 
						pu.contact_number as pu_contact_number, 
					r.lat as r_lat, r.lng as r_lng, 
					r.ru_id, 
						ru.name as ru_name,
						ru.lat as ru_lat,
						ru.lng as ru_lng,
					r.ec_id, 
						ec.description, 
						ec.color_hex,
					r.date_reported, r.date_responded 
				FROM 
					reports r, person_units pu, rescue_units ru, emergency_codes as ec 
				WHERE 
					r.pu_id = pu.id AND
					r.ru_id = ru.id AND
					r.ec_id = ec.id AND
					r.date_reported > '".$currentTime."'
			"));

		return Response::json($currentReport);
	}
}