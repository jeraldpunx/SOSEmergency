<?php

class RestController extends \BaseController {
	function storePU()
	{
		$input = Input::all();

		$rules = array(
			'type' 					=> 'required',
			'username' 				=> 'required|min:5|unique:users,username',
			'password' 				=> 'required|confirmed',
			'password_confirmation' => 'same:password',
			'name' 					=> 'required',
			'email'					=> 'required',
			'birth_date'			=> 'required',
			'contact_number'		=> 'required',
			'gender'				=> 'required'
		);

		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			$person_unit = new PersonUnit;
			$person_unit->name				=	Input::get('name');
			$person_unit->birth_date		=	Input::get('birth_date');
			$person_unit->gender 			=	Input::get('gender');
			$person_unit->email 			=	Input::get('email');
			$person_unit->contact_number	=	Input::get('contact_number');
			$person_unit->deviceID			=	Input::get('deviceID');
			$person_unit->save();

			$user = new User;
			$user->puid_ruid 				= 	$person_unit->id;
			$user->type 					=	Input::get('type');
			$user->username 				=	Input::get('username');
			$user->password 				=	Hash::make(Input::get('password'));
			$user->save();

			$returnedValue = array(
				'id' 	=>	$person_unit->id,
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

	function storeRU()
	{
		$input = Input::all();

		$rules = array(
			'name' 					=> 	'required',
			'address' 				=> 	'required',
			'lat' 					=> 	'required',
			'resType' 				=> 	'required',
			'contact_number' 		=> 	'required',
			'deviceID' 				=> 	'required',
			'userType' 				=> 	'required',
			'username' 				=> 	'required|min:5|unique:users,username',
			'password' 				=> 	'required|confirmed',
			'password_confirmation' => 	'same:password',
		);

		$custom_error = array(
				'lat.required' 	=> 	"Please turn on your GPS."
			);

		$validation = Validator::make($input, $rules, $custom_error);

		if($validation->passes()) {

			$rescue_unit = new RescueUnit;
			$rescue_unit->name 				= 	Input::get('name');
			$rescue_unit->address 			= 	Input::get('address');
			$rescue_unit->lat 				= 	Input::get('lat');
			$rescue_unit->lng 				= 	Input::get('lng');
			$rescue_unit->email 			= 	Input::get('email');
			$rescue_unit->type 				= 	Input::get('resType');
			$rescue_unit->status 			= 	0;
			$rescue_unit->save();


			$ru_contact = new RUContact;
			$ru_contact->ru_id 				= 	$rescue_unit->id;
			$ru_contact->contact_number 	= 	Input::get('contact_number');
			$ru_contact->deviceID 			= 	Input::get('deviceID');
			$ru_contact->save();

			$user = new User;
			$user->puid_ruid 				= 	$rescue_unit->id;
			$user->type 					=	Input::get('userType');
			$user->username 				=	Input::get('username');
			$user->password 				=	Hash::make(Input::get('password'));
			$user->save();

			switch (Input::get('resType')) {
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
					$ru_ec->ru_id 			= 	$rescue_unit->id;
					$ru_ec->ec_id 			= 	$ec;
					$ru_ec->save();
				}
			}

			$returnedValue = array(
				'id' 	=>	$rescue_unit->id,
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

	public function checkRespondentContact()
	{
		$ru_contact = RUContact::select("*")
							->where('ru_id', '=', Input::get('ru_id'))
							->where('deviceID', '=', Input::get('deviceID'))
							->get();
		if($ru_contact->isEmpty()) {
			$returnedValue = array(
				'registered'		=>	false
			);
		} else {
			$returnedValue = array(
				'registered'		=>	true
			);
		}
		return Response::json($returnedValue);
	}

	public function insertNewContact()
	{
		$ru_contact = RUContact::select("*")
							->where('ru_id', '=', Input::get('ru_id'))
							->where('contact_number', '=', Input::get('contact_number'))
							->get();

		$input = Input::all();

		$rules = array(
			'contact_number' 		=> 	'required'
		);
		$validation = Validator::make($input, $rules);

		if($validation->passes()) {
			if($ru_contact->isEmpty()) {
				$contact 					= 	new RUContact;
				$contact->ru_id 			= 	Input::get('ru_id');
				$contact->contact_number 	= 	Input::get('contact_number');
				$contact->deviceID 			= 	Input::get('deviceID');
				$contact->save();
			} else {
				$contact 					= 	RUContact::find($ru_contact[0]->id);
				$contact->ru_id 			= 	Input::get('ru_id');
				$contact->contact_number 	= 	Input::get('contact_number');
				$contact->deviceID 			= 	Input::get('deviceID');
				$contact->save();
			}

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

	public function uploads()
	{
		$images = Input::get('images');

		foreach ($images as $key => $image) {
			$srcArr = explode('/', $image["src"]);
			$outputFile = $srcArr[count($srcArr)-1];

			$file = explode(',', $image["binary"]); 
			$base64_string = $file[1]; 

			$saveto = public_path()."/uploads/".$outputFile; 
			$ifp = fopen( $saveto, "wb" ); 
			fwrite( $ifp, base64_decode($base64_string) ); 
			fclose( $ifp );
		}
	}
	
	public function retrieveEmergencyCodes()
	{
		return EmergencyCode::all()->toArray();
	}

	// public function shortest()
	// {
	// 	$pu_id 			= 	Input::get('pu_id');
	// 	$ec_id 			= 	Input::get('ec_id');
	// 	$latOrigin 		= 	Input::get('latOrigin');
	// 	$lngOrigin 		= 	Input::get('lngOrigin');

	// 	$limit 			= 	3;
	// 	$origin 		= 	$latOrigin . "," . $lngOrigin;

	// 	//QUERY GET DISTANCE BY CIRCLE
	// 	$markersByRadius = DB::select(
	// 		DB::raw(
	// 			"SELECT rescue_units.id as 'rescue_units_id', name, address, lat, lng, email, type, status, 
	// 				( 6371 * acos( cos( radians(:latOne) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(:lng) ) + sin( radians(:latTwo) ) * sin( radians( lat ) ) ) ) AS distance 
	// 			FROM rescue_units WHERE rescue_units.id IN (
	// 									SELECT ru_id FROM ru_ec WHERE ec_id = (
	// 											SELECT id FROM emergency_codes WHERE ID = :ec_id
	// 									)
	// 								) AND status = 1 order by distance LIMIT 3"
	// 		), array(
	// 			'latOne' 	=> 	$latOrigin,
	// 			'lng' 		=> 	$lngOrigin,
	// 			'latTwo' 	=> 	$latOrigin,
	// 			'ec_id' 	=> 	$ec_id
	// 	));

	// 	//GET KM BY USING ROAD
	// 	foreach ($markersByRadius as $key => $value) {
	// 		$destLatLng 	= 	$value->lat . "," . $value->lng;
	// 		$distanceByKM			=	helper::calculateKM($origin,$destLatLng);
	// 		$value->distanceByKM = $distanceByKM;
	// 	}
	// 	//SORT LOW TO HIGH BY KM
	// 	usort($markersByRadius, function($a, $b) { 
	// 		return $a->distanceByKM < $b->distanceByKM ? -1 : 1; 
	// 	});

	// 	$respondentArray 	= 	array();
	// 	foreach ($markersByRadius as $markers => $value) {
	// 		$respondentID 	= 	$markersByRadius[$markers]->rescue_units_id;
	// 		array_push($respondentArray, $respondentID);
	// 	}


	// 	//SENDNOW
	// 	helper::checkRespond($pu_id, $respondentArray, $ec_id, $latOrigin, $lngOrigin);
	// }

	public function shortest()
	{
		$message_type = Input::get("message_type");
		$imageString = "";
		if (strtoupper($message_type) == "INCOMING") {
			$message = explode(",", Input::get("message"));

	        $message = explode(",",$message);
	        $message = $message[3];
	        $pu_id 			= 	$message[0];
			$ec_id 			= 	$message[1];
			$latOrigin 		= 	$message[2];
			$lngOrigin 		= 	$message[3];
		} else {
			$images = Input::get('images');
			if($images) {
				foreach ($images as $key => $image) {
					$srcArr = explode('/', $image["src"]);
					$outputFile = $srcArr[count($srcArr)-1];

					$file = explode(',', $image["binary"]); 
					$base64_string = $file[1]; 

					$saveto = public_path()."/uploads/".$outputFile; 
					$ifp = fopen( $saveto, "wb" ); 
					fwrite( $ifp, base64_decode($base64_string) ); 
					fclose( $ifp );
					if($key == 0) {
						$imageString .= $outputFile;
					} else {
						$imageString .= ("," . $outputFile);
					}
				}
			}
			$pu_id 			= 	Input::get('pu_id');
			$ec_id 			= 	Input::get('ec_id');
			$latOrigin 		= 	Input::get('latOrigin');
			$lngOrigin 		= 	Input::get('lngOrigin');
		}
		
		$limit 			= 	3;
		$origin 		= 	$latOrigin . "," . $lngOrigin;

		//QUERY GET DISTANCE BY CIRCLE
		$markersByRadius = DB::select(
			DB::raw(
				"SELECT rescue_units.id as 'rescue_units_id', name, address, lat, lng, email, type, status, 
					( 
						6371 * acos( cos( radians(:latOne) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(:lng) ) + sin( radians(:latTwo) ) * sin( radians( lat ) ) ) 
					) AS distance 
				FROM rescue_units WHERE rescue_units.id IN (
										SELECT ru_id FROM ru_ec WHERE ec_id = (
												SELECT id FROM emergency_codes WHERE ID = :ec_id
										)
									) AND status = 1 order by distance LIMIT :limit"
			), array(
				'latOne' 	=> 	$latOrigin,
				'lng' 		=> 	$lngOrigin,
				'latTwo' 	=> 	$latOrigin,
				'ec_id' 	=> 	$ec_id,
				'limit' 	=> 	$limit
		));

		//GET KM BY USING ROAD
		foreach ($markersByRadius as $key => $value) {
			$destLatLng 	= 	$value->lat . "," . $value->lng;
			$distanceByKM			=	helper::calculateKM($origin,$destLatLng);
			$value->distanceByKM = $distanceByKM;
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

		//Add TO QUEUE
		if($imageString)
			helper::insertToQueue($pu_id, $respondentArray, $ec_id, $latOrigin, $lngOrigin, 0, $imageString);
		else
			helper::insertToQueue($pu_id, $respondentArray, $ec_id, $latOrigin, $lngOrigin, 0, NULL);
	}

	public function responseEmergency() 
	{
		$reports 	= 	Report::find(Input::get('report_id'));
		if(!$reports->date_responded) {
			$reports->date_responded 		= 	date('Y-m-d H:i:s');
			$reports->save();
			helper::sendGCMToPU($reports->pu_id, $reports->mobile);

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