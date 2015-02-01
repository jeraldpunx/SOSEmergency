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

	    // Replace with the real client registration IDs
		// $registrationIDs = array( 
		// 	"APA91bElZtomNwGwmz-ZhVyMLkue_enY8jmiK6mnIea9FxXa5GnS5K2rE3Nhx0RY9lyJ5Kt23lM2896cpi8kKhe8OvQ2zRkNHLhdwxGOmPTrbTDR6nfPdtfOvM5yz-jvZ_AavIYoek-xzi0hW9FFMt1_2ooYaak7ZYD_VngsDaiHfFzfVZRdFmM", 
		// 	"APA91bE0STylonk5LEEhiFDVQAAUGYaVpqrS8x1QjYWrZAOQVI4lRlSoYk9dxMtWbxYBkmZxhi1ujHMJAUPK0d9D5jlyB4OaX6i62idHKc38sXl6jwrH-UQ19z4yu5ET832rIrbfFNhorTr7vKBii_R-7E9nfXJFf1RpjlQVHg0TevLQie6XYKw", 
		// 	"APA91bGVC6RIgEy11zDZ3aql9gcxC_7FB33w4w-0oJ08fH_FTcHCqyz3G1U2mDATHsC6aoS2pmpS5jXpNMfDLkl_OOdeoPLohSYdGUrv_z_AvE6Or20lFm61OaRGDpnk1FYzLFSqgFImvk_NfZmoya1DSHBIfm1JUUKF15oAUUm8iDc5ifuKyHY", 
		// 	"APA91bEEI_a6aA_orfFYDL_Qt7S-AZ00MUu-pul_GbsS2bJg18Olj8pYEU-jzHyX8aO8M0GCfoHxh9iHUvLwLt6NzlQWn1yWd-gpDIjTnyvTyV6gGm0UO1w0TQ4MYHnGeiiTqqUom33s0xbs7uQnT3ADTgdczL3p0w", 
		// 	"APA91bE9SqKmE5gY59YubTYQWYUP_0--wwgRzUMonpu-5P4vk4TBQIoZvaV69wfbfVetrzuQehl2LHI11-UuoPnodRUbNMUYw5km4DO4nqy2QRRFbjItPtRC9bsfUEPIHV5nMrmNgEWFg4upBhAXdwUB7l5AnU8tzQ", 
		// 	"APA91bFpi8sK-nKrSKELErm7YCl6g12bxyVYn-OwUH33dZy7a3q9W6I6SV6sPJz9h2jQqBSUPYowXH60aEuRpuZ0mS7GsPkI5GZu-JnY6kWJ85dhv8xp8c9IqWCGKdroclMLDTqdW-6rRPhEHLIoqTCykqeZQvjBWQ", 
		// 	"APA91bETNfuk7IubFPFz8MSQiBAIEZ0diwv8bBG5bHIFrcahrCz8nFuOvFdnMOexeP-6e4iyDn7NVGgTOGx9o_ld8J6cVdVVSBP6tb0_saccxJJIm8Jvd-Nc_myese9BWEb91-FS4fnt0zhydrnxtLK0Ft6_xHslHA", 
		// 	"APA91bEhRrMOFW7iRzmI6ym7ICt8GQFovicTWyx7AfNoDmJ-JJQ7nl0zJn0XIowY_u6IpWdPFhR8TVM6j4YuLXTWV0BcoOHypgrOq2K3BYj5Tk_wTZT4I7NG_YSkOhDmKu2mz-L6C8UFphvYyTN_p-2Wv7gQl-xfTw","APA91bHukJ65aY54Ip5LIVp4RMgPegjN-FM6Q5loB3Wa5-dDFjq4J7qgVocN8cGb4yFPgmLuS5O2yBi8Ojrv4jAZ2l8l7lyuTHgikkZvp8Q_gPhZO1A76rYbqrh2bWQG63NrE6_xweRPWfsGEV06if4-HhtpGd5CxA");

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
	    //curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields));

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    // curl_setopt($ch, CURLOPT_POST, true);
	    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $payload));

	    // Execute post
		$result = curl_exec($ch);

	    // Close connection
		curl_close($ch);
		// echo $result;
    }
}