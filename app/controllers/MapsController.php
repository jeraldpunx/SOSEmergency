<?php

class MapsController extends \BaseController {

	public function index()
	{
		return View::make('contents.map');
	}


	public function markers()
	{
		DB::setFetchMode(PDO::FETCH_ASSOC);
		// $markers = DB::table('rescue_units')
		// 			->SELECT(['rescue_units.id as rescue_units_id', 'name', 'address', 'lat', 'lng', 'email', 'rescue_units.type as rescue_type', 'users.id as users_id', 'username', 'contact_number', 'deviceID'])
		// 			->leftJoin('users', 'users.puid_ruid', '=', 'rescue_units.id')
		// 			->on('users.type', '=', 'ru')
		// 			->orderBy('rescue_units_id')
		// 			->get();
		$markers = RescueUnit::select('rescue_units.id as rescue_units_id', 'name', 'address', 'lat', 'lng', 'email', 'rescue_units.type as rescue_type', 'users.id as users_id', 'username', 'contact_number', 'deviceID')
					->leftJoin('users', function($leftJoin){
						$leftJoin->on('users.puid_ruid', '=', 'rescue_units.id')
								 ->on(DB::raw('users.type'), DB::raw('='), DB::raw('"ru"'));
					})->get();
		DB::setFetchMode(PDO::FETCH_CLASS);
		// return $markers = $markers;


		$newMarkers = array();
		for ($i = 0; $i < count($markers); $i++) {
		    $rescue_units_id 	= 	$markers[$i]["rescue_units_id"];
		    $name 				= 	$markers[$i]["name"];
			$address 			=	$markers[$i]["address"];
			$lat 				=	$markers[$i]["lat"];
			$lng 				=	$markers[$i]["lng"];
			$email 				=	$markers[$i]["email"];
			$rescue_type 		=	$markers[$i]["rescue_type"];
			$users_id 			=	$markers[$i]["users_id"];
			$deviceID 			=	$markers[$i]["deviceID"];


		    if (!array_key_exists($rescue_units_id, $newMarkers)) { // Add new object to result
		        $newMarkers[$rescue_units_id] = array(
		            "rescue_units_id" 	=> 	$rescue_units_id,
		            "name" 				=> 	$name,
					"address" 			=>	$address,
					"lat" 				=>	$lat,
					"lng" 				=>	$lng,
					"email" 			=>	$email,
					"rescue_type"		=> 	$rescue_type,
					"users_id" 			=> 	$users_id,
		            "username" 			=> 	array(), 
		            "contact_number" 	=> 	array(),
		        );
		    }
		    // Add this cellWidth to object
		    $newMarkers[$rescue_units_id]["username"][$markers[$i]["users_id"]] = $markers[$i]["username"];
		    $newMarkers[$rescue_units_id]["contact_number"][$markers[$i]["users_id"]] = $markers[$i]["contact_number"];
		}
		return Response::JSON($newMarkers);
		// return RescueUnit::all()->toArray();


	// 		select * from rescue_units 
	// left join users on users.puid_ruid = rescue_units.id and users.type = 'ru' order by rescue_units.id


	// 		select * from rescue_units 
	// left join ru_contacts on ru_contacts.ru_id = rescue_units.id

	}

	public function getMarkerContactInfo($id) {
		return RUContact::where("ru_id", "=", $id)->get();
	}


	public function saveMarker()
	{
		// if (Request::ajax()) {
		// 	$rescue_unit 				= new RescueUnit();
		// 	$rescue_unit->name 			= Input::get('name');
		// 	$rescue_unit->address 		= Input::get('address');
		// 	$rescue_unit->type 			= Input::get('type');
		// 	$rescue_unit->email 		= Input::get('email');
		// 	$rescue_unit->lat 			= Input::get('lat');
		// 	$rescue_unit->lng 			= Input::get('lng');
		// 	$rescue_unit->save();
		// 	// if($rescue_unit)
		// 	// return Response::json('marker_id'=>$rescue_unit->id, 'status'=>'OK');
		// }

		$input = Input::all();

	    $rules = array(
	    	'name' 					=> 'required',
	        'address' 				=> 'required|min:5|unique:users,username',
			'type' 					=> 'required|confirmed',
			'lat' 					=> 'same:password',
			'lng' 					=> 'required'
	    );

	    $validation = Validator::make($input, $rules);

	    if($validation->passes()) {
	    	$rescue_unit 				= new RescueUnit();
			$rescue_unit->name 			= Input::get('name');
			$rescue_unit->address 		= Input::get('address');
			$rescue_unit->type 			= Input::get('type');
			$rescue_unit->email 		= Input::get('email');
			$rescue_unit->lat 			= Input::get('lat');
			$rescue_unit->lng 			= Input::get('lng');
			$rescue_unit->save();

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

	//edit markers
	public function editMarker($id)
	{
		$rescue_units = RescueUnit::find($id);

		return $rescue_units;
	}
	//update borrowers
	// public function updateMarker($id)
	// {
	//     $borrowers = Borrower::find($id);

	//     $borrowers->borrower_code = Input::get('borrower_code');
	// 	$borrowers->first_name = Input::get('first_name');
	// 	$borrowers->last_name = Input::get('last_name');
	// 	$borrowers->penalty = Input::get('penalty');

	// 	$borrowers->save();

	// 	return Redirect::to('borrowers')
	// 			->with('flash_error', 'Successfully updated.')
 //        		->with('flash_color', '#27ae60');
 //    }

	public function deleteMarker()
	{
		$rescue_unit 	=	RescueUnit::find(20);
		if($rescue_unit == Null)
			return "aw";
		// if (Request::ajax()) {
		// 	RescueUnit::find(Input::get('marker_id'))->delete();
		// }
	}

	public function shortest()
	{
		// if (Request::ajax()) {
		// 	$report 				= new Report();
		// 	$report->pu_id 			= Input::get('pu_id');
		// 	$report->ec_id 			= Input::get('ec_id');
		// 	$report->lat 			= Input::get('lat');
		// 	$report->lng 			= Input::get('lng');
		// 	$report->save();
		// }
		

		$lat 		= 	Input::get('lat');
		$lng 		= 	Input::get('lng');

		$type 		= 	"hospital";
		$limit 		= 	3;
		$origin 	= 	$lat . "," . $lng;

		//QUERY 3 NEARBY RESPONDENT BY RADIUS
		$markersByRadius = RescueUnit::select(
               DB::raw("*, ( 6371 * acos( cos( radians(?) ) *
							 cos( radians( lat ) ) * 
							 cos( radians( lng ) - radians(?) ) + 
							 sin( radians(?) ) *
							 sin( radians( lat ) ) )
                            ) AS distance"))
			   ->whereRaw('type = ?')
               ->having("distance", "<", "25")
               ->orderBy("distance")
               ->take($limit)
               ->setBindings([$lat, $lng, $lat, $type])
               ->get();

        //GET KM BY USING ROAD
		for($i=0; $i<$limit; $i++) {
			$destLatLng 			= 	$markersByRadius[$i]->lat . "," . $markersByRadius[$i]->lng;
			$distanceByKM			=	helper::calculateKM($origin,$destLatLng);

			echo $distanceByKM . " = " . $markersByRadius[$i]->name . "<br>";
			$markersByRadius[$i]->distanceByKM = $distanceByKM;
		}

		$nearestMarker =  json_decode($markersByRadius);

		//SORT LOW TO HIGH BY KM
		usort($nearestMarker, function($a, $b) { 
		    return $a->distanceByKM < $b->distanceByKM ? -1 : 1; 
		});
		helper::sendGCM($lat, $lng, $nearestMarker[0]->id, 'ru');

		// var_dump($nearestMarker);
		$returnedValue = array(
	        	'error'		=>	false,
		);
		return Response::json($returnedValue);

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