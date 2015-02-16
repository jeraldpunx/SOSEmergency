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

	public static function checkRespond($pu_id, $respondentArray, $ec_id, $latOrigin, $lngOrigin) {
		$timeLimit 	= 	20;

		$report 				= 	new Report();
		$report->pu_id 		 	= 	$pu_id;
		$report->ru_id 			= 	$respondentArray[0];
		$report->ec_id 			= 	$ec_id;
		$report->lat 			= 	$latOrigin;
		$report->lng 			= 	$lngOrigin;
		$report->date_reported 	= 	date('Y-m-d H:i:s');
		$report->save();
		helper::sendGCMToRU($respondentArray[0], $pu_id, $ec_id, $latOrigin, $lngOrigin, $report->id);

		foreach (array_slice($respondentArray, 1) as $key => $value) {
			sleep($timeLimit);
			$status 	=  	Report::find($report->id)->date_responded;
			if(!$status) {
				$report 				= 	new Report();
				$report->pu_id 		 	= 	$pu_id;
				$report->ru_id 			= 	$value;
				$report->ec_id 			= 	$ec_id;
				$report->lat 			= 	$latOrigin;
				$report->lng 			= 	$lngOrigin;
				$report->date_reported 	= 	date('Y-m-d H:i:s');
				$report->save();
				helper::sendGCMToRU($value, $pu_id, $ec_id, $latOrigin, $lngOrigin, $report->id);
			} else {
				break;
			}
		}
	}

	public static function sendGCMToRU($ru_id, $pu_id, $ec_id, $latOrigin, $lngOrigin, $reportID) {
		$devices 		= 	RUContact::select('deviceID')
								->where('ru_id', '=', $ru_id)
								->get();

		//Message to be sent
		$person 		=   PersonUnit::select('*')
									->where('id', '=', $pu_id)
									->get()[0];
		$emergency 		= 	EmergencyCode::select('*')
								->where('id', '=', $ec_id)
								->get()[0];
		//Sent to these devices
		$registrationIDs = array();
		foreach ($devices as $key => $value) {
			$regID = $devices[$key]->deviceID;
			array_push($registrationIDs, $regID);
		}


		$payload = array(
			'registration_ids' 	=> 	$registrationIDs,
			'data' 				=> array("message" 			=>	"By ". $person->name. ", " . $person->gender, 
										 "title" 			=>	"Emergency Code ".$emergency->color_name,
										 "person" 			=>	$person,
										 "emergency" 		=> 	$emergency,
										 "latOrigin" 		=> 	$latOrigin,
										 "lngOrigin" 		=> 	$lngOrigin,
										 "reportID" 		=> 	$reportID
									)
				);


		// Replace with the real server API key from Google APIs
		$apiKey = "AIzaSyB4VbRPOEzAiJ_wMPWY-Bvh3H5I6LqQ5x0";
		// Set POST variables
		$url = 'https://android.googleapis.com/gcm/send';
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


	public static function sendGCMToPU($pu_id) {
		//Message to be sent
		$person 		=   PersonUnit::select('*')
									->where('id', '=', $pu_id)
									->get()[0];
		//Sent to these devices
		$registrationIDs = array();
		array_push($registrationIDs, $person->deviceID);


		$payload = array(
			'registration_ids' 	=> 	$registrationIDs,
			'data' 				=> array("message" 			=>	"Help! is on it's way.", 
										 "title" 			=>	"SOSEmergency!!!"
									)
				);


		// Replace with the real server API key from Google APIs
		$apiKey = "AIzaSyB4VbRPOEzAiJ_wMPWY-Bvh3H5I6LqQ5x0";
		// Set POST variables
		$url = 'https://android.googleapis.com/gcm/send';
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