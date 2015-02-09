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
		$lat 		= 	"10.287495055675077";
		$lng 		= 	"123.86252403259277";
		
		// $markers = RescueUnit::select('rescue_units.id as rescue_units_id', 'name', 'address', 'lat', 'lng', 'email', 'type', 'status', 'contact_number', 'deviceID')
		// 					->leftJoin('ru_contacts', function($leftJoin){
		// 							$leftJoin->on('ru_contacts.ru_id', '=', 'rescue_units.id');
							// })->whereIn( 'rescue_units.id', function($ru_ec){ 
							// 	$ru_ec->select('ru_id')
							// 		  ->from('ru_ec')
							// 		  ->where('ec_id', '=', function($emergency_codes){
							// 		  		$emergency_codes->select('id')
							// 		  						->from('emergency_codes')
							// 		  						->where('id', '=', 6);
							// 		  });
							// })->where('status', '!=', 0)->setBindings([$lat, $lng, $lat])->get();
		// return $markers;

		$latOrigin 		= 	10.3099568;
		$lngOrigin 		= 	123.8934193;
		$limit 			= 	3;
		$ec_id 			= 	6;
		$origin 		= 	$lat . "," . $lng;

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

		// $report = Report::find(1);
		// echo date('Y-m-d H:i:s', strtotime($report->date_reported. "+1 minutes"));
		// echo "<br>";
		// echo date('Y-m-d H:i:s', strtotime("+30 minutes"));

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

		return $markersByRadius[0]->rescue_units_id;


		// $result = helper::sendGCM($lat, $lng, $nearestMarker[0]->id, 'ru');
		// // return $result;

		// // // var_dump($nearestMarker);
		// $returnedValue = array(
	 //        	'error'		=>	false
		// );
		// return $result;

	}

	function pushMessage() {
		// Replace with the real server API key from Google APIs
		$apiKey = "AIzaSyB4VbRPOEzAiJ_wMPWY-Bvh3H5I6LqQ5x0";

	    // Replace with the real client registration IDs
		$registrationIDs = array( 
			"APA91bElZtomNwGwmz-ZhVyMLkue_enY8jmiK6mnIea9FxXa5GnS5K2rE3Nhx0RY9lyJ5Kt23lM2896cpi8kKhe8OvQ2zRkNHLhdwxGOmPTrbTDR6nfPdtfOvM5yz-jvZ_AavIYoek-xzi0hW9FFMt1_2ooYaak7ZYD_VngsDaiHfFzfVZRdFmM", 
			"APA91bE0STylonk5LEEhiFDVQAAUGYaVpqrS8x1QjYWrZAOQVI4lRlSoYk9dxMtWbxYBkmZxhi1ujHMJAUPK0d9D5jlyB4OaX6i62idHKc38sXl6jwrH-UQ19z4yu5ET832rIrbfFNhorTr7vKBii_R-7E9nfXJFf1RpjlQVHg0TevLQie6XYKw", 
			"APA91bGVC6RIgEy11zDZ3aql9gcxC_7FB33w4w-0oJ08fH_FTcHCqyz3G1U2mDATHsC6aoS2pmpS5jXpNMfDLkl_OOdeoPLohSYdGUrv_z_AvE6Or20lFm61OaRGDpnk1FYzLFSqgFImvk_NfZmoya1DSHBIfm1JUUKF15oAUUm8iDc5ifuKyHY", 
			"APA91bEEI_a6aA_orfFYDL_Qt7S-AZ00MUu-pul_GbsS2bJg18Olj8pYEU-jzHyX8aO8M0GCfoHxh9iHUvLwLt6NzlQWn1yWd-gpDIjTnyvTyV6gGm0UO1w0TQ4MYHnGeiiTqqUom33s0xbs7uQnT3ADTgdczL3p0w", 
			"APA91bE9SqKmE5gY59YubTYQWYUP_0--wwgRzUMonpu-5P4vk4TBQIoZvaV69wfbfVetrzuQehl2LHI11-UuoPnodRUbNMUYw5km4DO4nqy2QRRFbjItPtRC9bsfUEPIHV5nMrmNgEWFg4upBhAXdwUB7l5AnU8tzQ", 
			"APA91bFpi8sK-nKrSKELErm7YCl6g12bxyVYn-OwUH33dZy7a3q9W6I6SV6sPJz9h2jQqBSUPYowXH60aEuRpuZ0mS7GsPkI5GZu-JnY6kWJ85dhv8xp8c9IqWCGKdroclMLDTqdW-6rRPhEHLIoqTCykqeZQvjBWQ", 
			"APA91bETNfuk7IubFPFz8MSQiBAIEZ0diwv8bBG5bHIFrcahrCz8nFuOvFdnMOexeP-6e4iyDn7NVGgTOGx9o_ld8J6cVdVVSBP6tb0_saccxJJIm8Jvd-Nc_myese9BWEb91-FS4fnt0zhydrnxtLK0Ft6_xHslHA", 
			"APA91bEhRrMOFW7iRzmI6ym7ICt8GQFovicTWyx7AfNoDmJ-JJQ7nl0zJn0XIowY_u6IpWdPFhR8TVM6j4YuLXTWV0BcoOHypgrOq2K3BYj5Tk_wTZT4I7NG_YSkOhDmKu2mz-L6C8UFphvYyTN_p-2Wv7gQl-xfTw","APA91bHukJ65aY54Ip5LIVp4RMgPegjN-FM6Q5loB3Wa5-dDFjq4J7qgVocN8cGb4yFPgmLuS5O2yBi8Ojrv4jAZ2l8l7lyuTHgikkZvp8Q_gPhZO1A76rYbqrh2bWQG63NrE6_xweRPWfsGEV06if4-HhtpGd5CxA");

	    // Message to be sent
	    $name = "kevin";
	    $code = "red";
		$title = "SOSEmergency!!!";
		$message =  "Emergency needs!";


	    // Set POST variables
		$url = 'https://android.googleapis.com/gcm/send';

		$payload = array(
			'registration_ids' 	=> 	$registrationIDs,
			'data' => array("message" 	=> 	$message, 
							"title" 	=> 	$title,
							"name"		=>	"Kevin Rey Tabada",
							"lat" 		=> 	"12.00",
							"lng"		=> 	"13.00"
						));
		$headers = array(
			'Authorization: key=' . $apiKey,
			'Content-Type: application/json'
			);

	    // Open connection
		$ch = curl_init();

	    // Set the URL, number of POST vars, POST data
		curl_setopt( $ch, CURLOPT_URL, $url);
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
	    //curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields));

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    // curl_setopt($ch, CURLOPT_POST, true);
	    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $payload));

	    // Execute post
		$result = curl_exec($ch);

	    // Close connection
		curl_close($ch);
		echo $result;
	}


}