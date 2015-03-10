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

	public static function insertToQueue($pu_id, $respondentArray, $ec_id, $latOrigin, $lngOrigin, $mobile, $report_image) {
		$reportGroup = helper::generateRandomString(8);
		while(helper::isUsed($reportGroup)) {
			$reportGroup = helper::generateRandomString(8);
		}

		$report 				= 	new Report();
		$report->pu_id 		 	= 	$pu_id;
		$report->ru_id 			= 	$respondentArray[0];
		$report->ec_id 			= 	$ec_id;
		$report->lat 			= 	$latOrigin;
		$report->lng 			= 	$lngOrigin;
		$report->date_reported 	= 	date('Y-m-d H:i:s');
		$report->date_received 	= 	date('Y-m-d H:i:s');
		$report->mobile 		= 	$mobile;
		$report->report_group 	= 	$reportGroup;
		if($report_image != "") {
			$report->report_image 	= 	$report_image;
		}
		$report->save();

		helper::sendGCMToRU($respondentArray[0], $pu_id, $ec_id, $latOrigin, $lngOrigin, $report->id, $report_image);

		$secPerLoop 	= 	30;
		$currentAddSec 	= 	$secPerLoop;
		foreach (array_slice($respondentArray, 1) as $key => $ru_id) {
			$status 	=  	Report::find($report->id)->date_responded;
			if(!$status) {
				$reportqueue 					= 	new ReportsQueue();
				$reportqueue->pu_id 		 	= 	$pu_id;
				$reportqueue->ru_id 			= 	$ru_id;
				$reportqueue->ec_id 			= 	$ec_id;
				$reportqueue->lat 				= 	$latOrigin;
				$reportqueue->lng 				= 	$lngOrigin;
				$reportqueue->date_reported 	= 	date('Y-m-d H:i:s');
				$reportqueue->date_received 	= 	date('Y-m-d H:i:s', time() + $currentAddSec);
				$reportqueue->mobile 			= 	$mobile;
				$reportqueue->report_group 		= 	$reportGroup;
				if($report_image != "") {
					$reportqueue->report_image 	= 	$report_image;
				}
				$reportqueue->save();

				$currentAddSec 	= $currentAddSec + $secPerLoop;
			} else {
				break;
			}
		}
	}

	// public static function checkRespond($pu_id, $respondentArray, $ec_id, $latOrigin, $lngOrigin) {
	// 	$timeLimit 	= 	20;

	// 	$report 				= 	new Report();
	// 	$report->pu_id 		 	= 	$pu_id;
	// 	$report->ru_id 			= 	$respondentArray[0];
	// 	$report->ec_id 			= 	$ec_id;
	// 	$report->lat 			= 	$latOrigin;
	// 	$report->lng 			= 	$lngOrigin;
	// 	$report->date_reported 	= 	date('Y-m-d H:i:s');
	// 	$report->save();
	// 	helper::sendGCMToRU($respondentArray[0], $pu_id, $ec_id, $latOrigin, $lngOrigin, $report->id);

	// 	foreach (array_slice($respondentArray, 1) as $key => $value) {
	// 		sleep($timeLimit);
	// 		$status 	=  	Report::find($report->id)->date_responded;
	// 		if(!$status) {
	// 			$report 				= 	new Report();
	// 			$report->pu_id 		 	= 	$pu_id;
	// 			$report->ru_id 			= 	$value;
	// 			$report->ec_id 			= 	$ec_id;
	// 			$report->lat 			= 	$latOrigin;
	// 			$report->lng 			= 	$lngOrigin;
	// 			$report->date_reported 	= 	date('Y-m-d H:i:s');
	// 			$report->save();
	// 			helper::sendGCMToRU($value, $pu_id, $ec_id, $latOrigin, $lngOrigin, $report->id);
	// 		} else {
	// 			break;
	// 		}
	// 	}
	// }

	public static function sendGCMToRU($ru_id, $pu_id, $ec_id, $latOrigin, $lngOrigin, $reportID, $report_image) {
		$ru_contact 		= 	RUContact::select('*')
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
		$registrationIDs 	= 	array();
		$smsSends 		 	=	array();
		foreach ($ru_contact as $key => $value) {
			if($ru_contact[$key]->deviceID != "") {
				$regID = $ru_contact[$key]->deviceID;
				array_push($registrationIDs, $regID);
			} else {
				$contactData = array("mobile_number" => $ru_contact[$key]->contact_number, "message_id" => helper::generateMessageId());
				array_push($smsSends, $contactData);
			}
		}

		if(!empty($registrationIDs)){
			$payload = array(
				'registration_ids' 	=> 	$registrationIDs,
				'data' 				=> array("message" 			=>	"By ". $person->name. ", " . $person->gender, 
											 "title" 			=>	"Emergency Code ".$emergency->color_name,
											 "person" 			=>	$person,
											 "emergency" 		=> 	$emergency,
											 "latOrigin" 		=> 	$latOrigin,
											 "lngOrigin" 		=> 	$lngOrigin,
											 "reportID" 		=> 	$reportID, 
											 "report_image" 	=> 	$report_image
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
		} else if(!empty($smsSends)) {
			//SEND MESSAGE ALL TO THOSE NO DEVICE ID
			helper::sendSMStoRU($smsSends, $person->name, $person->gender, $person->contact_number,$emergency->color_name, $latOrigin.",".$lngOrigin);
		}
	}

	public static function sendSMStoRU($smsSends, $name, $gender, $contact_number, $color_name, $latLng) {
		$link 		= 	"http://maps.google.com/maps/api/geocode/json?address={$latLng}&sensor=false";
		$result 	=	json_decode(file_get_contents($link));
		if($result->status == "OK") {
			$address_components 	= $result->results[0]->address_components;
			$address_components_len = count($address_components);
			$address = "";
			foreach ($address_components as $key => $address_component ) {
				if($key == 0) {
					$address .= $address_component->short_name;
				} else if($key != $address_components_len - 1) {
					$address .= ", " . $address_component->short_name;
				}
			}

			$smsMessage = $name."(".$gender.")[".$contact_number."] reported Code ". $color_name ." on " . $address . ".";

			foreach ($smsSends as $key => $smsSend) {
				$smsSend = array_merge($smsSend, array("message" => substr($smsMessage, 0, 420), "message_type" => "SEND", "shortcode" => "2929030951", "client_id" => "98224d40ea8968337fd12a2422fdb2201377dcc735927f2cd9d465edd2ec60bf", "secret_key" => "f7ff80ab4fc4f9643211f0a7748db4fc3d049b8d805fa8fbb7fcc031794d77cf"));
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, "https://post.chikka.com/smsapi/request");
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($smsSend));
				// curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				$response = curl_exec($curl);
				curl_close($curl);
				var_dump($response);
			}
		}
	}

	public static function generateMessageId()
	{
	  // Cache last timestamp and unique id
	  static $lastTimestamp, $uniqueId;

	  // Generate
	  $newId = time();
	  // If same as last timestamp
	  if ($newId == $lastTimestamp) {
		// Increment unique id
		$uniqueId++;
	  } else {
		// Reset unique id
		$uniqueId = 0;
	  }

	  // Set last timestamp as new id
	  $lastTimestamp = $newId;
	  // Append uniqueid left padded with 0s
	  $newId .= str_pad($uniqueId, 6, '0', STR_PAD_LEFT);
	  // Return
	  return $newId;
	}


	public static function sendGCMToPU($pu_id, $mobile) {
		//Message to be sent
		$person 		=   PersonUnit::select('*')
									->where('id', '=', $pu_id)
									->get()[0];

		if($mobile == 0) {
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
		} else {
			$smsMessage = "SOSEmergency!!! Help! is on it's way.";
			$smsSend = array("mobile_number" => $person->contact_number, "message_id" => helper::generateMessageId(), "message" => substr($smsMessage, 0, 420), "message_type" => "SEND", "shortcode" => "2929030951", "client_id" => "98224d40ea8968337fd12a2422fdb2201377dcc735927f2cd9d465edd2ec60bf", "secret_key" => "f7ff80ab4fc4f9643211f0a7748db4fc3d049b8d805fa8fbb7fcc031794d77cf");
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, "https://post.chikka.com/smsapi/request");
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($smsSend));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$response = curl_exec($curl);
			curl_close($curl);
		}
	}

	public static function generateRandomString($length) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return strtoupper($randomString);
	}

	public static function isUsed($reportGroup) {
		$query = Report::select('*')
					->where('report_group', '=', $reportGroup)
					->count();

		if($query < 1) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
}