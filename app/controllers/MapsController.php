<?php

class MapsController extends \BaseController {

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


	public function markers()
	{
		if(Request::ajax()) {
			//SET INTO ARRAY
			DB::setFetchMode(PDO::FETCH_ASSOC);
			$markers = RescueUnit::select('rescue_units.id as rescue_units_id', 'name', 'address', 'lat', 'lng', 'email', 'rescue_units.type as rescue_type', 'status')
								->where('status', '=', 1)
								->orderBy('rescue_units.id')->get();
			DB::setFetchMode(PDO::FETCH_CLASS);

			return Response::JSON($markers);
		}
	}


	public function saveAddMarker()
	{
		$input = Input::all();

	    $rules = array(
	    	'name'					=> 	'required',
			'address'				=> 	'required',
			'lat'					=> 	'required',
			'type'					=> 	'required'
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
		$rescue_units = RescueUnit::find($id);

		return $rescue_units;
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

	public function deleteMarker()
	{
		$rescue_units = RescueUnit::find(Input::get('marker_id'));
		$rescue_units->delete();
	}

	public function getMarkerContactInfo($id) {
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

	public function shortest()
	{
		$pu_id 			= 	Input::get('pu_id');
		$ec_id 			= 	Input::get('ec_id');
		$latOrigin 		= 	Input::get('latOrigin');
		$lngOrigin 		= 	Input::get('lngOrigin');

		$limit 			= 	3;
		$origin 		= 	$latOrigin . "," . $lngOrigin;

		//QUERY GET DISTANCE BY CIRCLE
		$markersByRadius = DB::select(
           	DB::raw(
           		"SELECT rescue_units.id as 'rescue_units_id', name, address, lat, lng, email, type, status, 
           			( 6371 * acos( cos( radians(:latOne) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(:lng) ) + sin( radians(:latTwo) ) * sin( radians( lat ) ) ) ) AS distance 
           		FROM rescue_units WHERE rescue_units.id IN (
           								SELECT ru_id FROM ru_ec WHERE ec_id = (
           										SELECT id FROM emergency_codes WHERE ID = :ec_id
           								)
           							) AND status = 1 order by distance LIMIT 3"
        	), array(
        		'latOne' 	=> 	$latOrigin,
        		'lng' 		=> 	$lngOrigin,
        		'latTwo' 	=> 	$latOrigin,
        		'ec_id' 	=> 	$ec_id
        ));


        //GET KM BY USING ROAD
		for($i=0; $i<$limit; $i++) {
			$destLatLng 			= 	$markersByRadius[$i]->lat . "," . $markersByRadius[$i]->lng;
			$distanceByKM			=	helper::calculateKM($origin,$destLatLng);

			$markersByRadius[$i]->distanceByKM = $distanceByKM;
		}

		//SORT LOW TO HIGH BY KM
		usort($markersByRadius, function($a, $b) { 
		    return $a->distanceByKM < $b->distanceByKM ? -1 : 1; 
		});

		$respondentArray 	= 	array();
		foreach ($markersByRadius as $markers => $value) {
			$respondentID 	= 	$markersByRadius[$markers]->rescue_units_id;
			array_push($respondentArray, $respondentID);
		}


		//SENDNOW
		helper::checkRespond($pu_id, $respondentArray, $ec_id, $latOrigin, $lngOrigin);
	}

	public function responseEmergency() {

		$ru_contacts 	= 	Report::find(Input::get('report_id'));
		if(!$ru_contacts->date_responded) {
			$ru_contacts->date_responded 		= 	date('Y-m-d H:i:s');
			$ru_contacts->save();

			$returnedValue = array(
					'accepted' 	=> 	true
				);
		} else {
			$returnedValue = array(
					'accepted' 	=> 	false
				);
		}

		return $returnedValue;
	}
}