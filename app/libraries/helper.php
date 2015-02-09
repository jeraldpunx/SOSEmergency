<?php

class helper {
    public static function calculateKM($origin, $destination) {
        //request the directions
        $link 		= 	"http://maps.googleapis.com/maps/api/directions/json?origin={$origin}&destination={$destination}&alternatives=true&sensor=false";
		$result 	=	json_decode(file_get_contents($link));
		if($result->status == "OK") {
			$routes 	= 	$result->routes;
			//sort the routes based on the distance
			usort($routes,create_function('$a,$b','return intval($a->legs[0]->distance->value) - intval($b->legs[0]->distance->value);'));

			return $routes[0]->legs[0]->distance->value;
		} else {
			return "Failed to get distance";
		}
    }

    public static function sendGCM($lat, $lng, $rescue_id, $user_type) {
    	$devices = User::select('deviceID')
    			   ->where('puid_ruid', '=', $rescue_id)
    			   ->where('type', '=', $user_type)
    			   ->get();

    	$registrationIDs = array();

    	foreach ($devices as $key => $value) {
    		$regID = $devices[$key]->deviceID;
    		array_push($registrationIDs, $regID);
    	}

    	// Replace with the real server API key from Google APIs
		$apiKey = "AIzaSyB4VbRPOEzAiJ_wMPWY-Bvh3H5I6LqQ5x0";

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
							"lat" 		=> 	$lat,
							"lng"		=> 	$lng
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
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $payload ));

	    // Execute post
		$result = curl_exec($ch);

	    // Close connection
		curl_close($ch);
		echo $result;
    }
}