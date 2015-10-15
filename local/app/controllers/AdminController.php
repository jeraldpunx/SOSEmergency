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
					$ec_id 	= array(1);
					break;
				case 'hospital':
					$ec_id 	= array(2, 3, 5, 6);
					break;
				case 'police':
					$ec_id 	= array(4, 6);
					break;
				case 'rescuevolunteer':
					$ec_id 	= array(1, 2, 3);
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

			DB::table('ru_ec')->where('ru_id', '=', $id)->delete();

			switch (Input::get('type')) {
				case 'firecontrol':
					$ec_id 	= array(1);
					break;
				case 'hospital':
					$ec_id 	= array(2, 3, 5, 6);
					break;
				case 'police':
					$ec_id 	= array(4, 6);
					break;
				case 'rescuevolunteer':
					$ec_id 	= array(1, 2, 3);
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
		if (is_null(DB::table('reports')->where('ru_id', '=', Input::get('marker_id')))) {
		    DB::table('reports')->where('ru_id', '=', Input::get('marker_id'))->delete();
		}

		if (is_null(DB::table('reports_queue')->where('ru_id', '=', Input::get('marker_id')))) {
		    DB::table('reports_queue')->where('ru_id', '=', Input::get('marker_id'))->delete();
		}
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

	public function listView()
	{
		$markers = RescueUnit::select('rescue_units.id as rescue_units_id', 'name', 'address', 'lat', 'lng', 'email', 'rescue_units.type as rescue_type', 'status')
								->where('status', '=', 1)
								->orderBy('rescue_units.id')->get();
		return View::make('contents.listView')->with('markers', $markers);
	}

	public function deleteRU($id)
	{
		return Redirect::back();
	}

	public function listPU()
	{
		$reports = EmergencyCode::all();
		return View::make('contents.listPU')->with('reports', $reports);
	}

	public function listReport()
	{

		$reports = Report::select("reports.id as report_id", "date_reported", "date_received", "date_responded","rescue_units.name as rescue_unit_name", "type", "person_units.name as person_unit_name", "description")
						->join("rescue_units", "rescue_units.id", "=", "reports.ru_id")
						->join("person_units", "person_units.id", "=", "reports.pu_id")
						->join("emergency_codes", "emergency_codes.id", "=", "reports.ec_id")
						->orderBy('date_reported', 'desc')
						->get();
		return View::make('contents.listReport')->with('reports', $reports);
	}

	public function listRequest()
	{
		$markers = RescueUnit::select('rescue_units.id as rescue_units_id', 'name', 'address', 'lat', 'lng', 'email', 'rescue_units.type as rescue_type', 'status')
								->where('status', '=', 0)
								->orderBy('rescue_units.id')->get();
		return View::make('contents.listRequest')->with('markers', $markers);
	}

	public function acceptRequest()
	{
		$requestedRU 				= 	RescueUnit::find(Input::get('ru_id'));
		$requestedRU->status 		= 	1;
		$requestedRU->save();
		return Redirect::back();
	}

	public function declineRequest()
	{
		RescueUnit::find(Input::get('ru_id'))->delete();
		DB::table('ru_ec')->where('ru_id', '=', Input::get('ru_id'))->delete();
		DB::table('ru_contacts')->where('ru_id', '=', Input::get('ru_id'))->delete();
		DB::table('users')->where('puid_ruid', '=', Input::get('ru_id'))->where('type', '=', 'ru')->delete();
		DB::table('reports')->where('ru_id', '=', Input::get('ru_id'))->delete();
		DB::table('reports_queue')->where('ru_id', '=', Input::get('ru_id'))->delete();
		return Redirect::back();
	}

	public function viewReport($id)
	{
		$reports = Report::select("reports.id as report_id", "pu_id", "ru_id", "ec_id", "reports.lat as report_lat", "reports.lng as report_lng", "date_reported", "date_received", "date_responded", "mobile", "report_group", "report_image", "rescue_units.name as rescue_unit_name", "address", "rescue_units.email as rescue_unit_email", "type", "person_units.name as person_unit_name", "birth_date", "gender", "person_units.email as person_unit_email", "contact_number", "color_name", "description", "color_hex")
						->join("rescue_units", "rescue_units.id", "=", "reports.ru_id")
						->join("person_units", "person_units.id", "=", "reports.pu_id")
						->join("emergency_codes", "emergency_codes.id", "=", "reports.ec_id")
						->where("reports.id", "=", $id)
						->get();

		return View::make('contents.viewReport')->with('reports', $reports);
	}

	public function listEmergencyCodes()
	{
		$emergencyCodes = EmergencyCode::all();
		return  View::make('contents.listEmergencyCodes')->with('emergencyCodes', $emergencyCodes);
	}

	public function addEmergencyCodes()
	{

		return View::make('contents.addEmergencyCodes');
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
					if($report_queue->report_image != "") {
						$report->report_image 	= 	$report_queue->report_image;
					}
					$report->save();
					helper::sendGCMToRU($report->ru_id, $report->pu_id, $report->ec_id, $report->lat, $report->lng, $report->id, $report_queue->report_image);
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