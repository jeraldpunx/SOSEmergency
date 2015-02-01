<?php

Route::get('register', 'UsersController@create');
Route::post('register', 'UsersController@store');

Route::get('login', 'UsersController@showLogin');
Route::post('login', 'UsersController@doLogin');

Route::get('tests', function(){ return View::make("contents.test"); });

Route::get('emergencyCodes', 'UsersController@retrieveEmergencyCodes');




Route::get('map', 'MapsController@index');
Route::get('markers', 'MapsController@markers');
Route::get('getMarkerContactInfo/{id}', 'MapsController@getMarkerContactInfo');
Route::post('saveMarker', 'MapsController@saveMarker');
Route::get('editMarker/{id}', 'MapsController@editMarker');
Route::get('deleteMarker', 'MapsController@deleteMarker');

Route::get('shortest', 'MapsController@shortest');

Route::get('pushMessage', 'MapsController@pushMessage');

Route::get('pushMessageS', function(){
	class GCMPushMessage {
	    var $url = 'https://android.googleapis.com/gcm/send';
	    var $serverApiKey = "";
	    var $devices = array();
	   
	    function testing(){
	        return "aw";
	    }
	    function GCMPushMessage($apiKeyIn){
	        $this->serverApiKey = $apiKeyIn;
	    }
	    function setDevices($deviceIds){
	   
	        if(is_array($deviceIds)){
	            $this->devices = $deviceIds;
	        } else {
	            $this->devices = array($deviceIds);
	        }
	   
	    }
	    function send($message, $data = false){
	       
	        if(!is_array($this->devices) || count($this->devices) == 0){
	            $this->error("No devices set");
	        }
	       
	        if(strlen($this->serverApiKey) < 8){
	            $this->error("Server API Key not set");
	        }
	       
	        $fields = array(
	            'registration_ids'  => $this->devices,
	            'data'              => array( "message" => $message ),
	        );
	       
	        if(is_array($data)){
	            foreach ($data as $key => $value) {
	                $fields['data'][$key] = $value;
	            }
	        }
	        $headers = array(
	            'Authorization: key=' . $this->serverApiKey,
	            'Content-Type: application/json'
	        );
	        $ch = curl_init();
	       
	        curl_setopt( $ch, CURLOPT_URL, $this->url );
	       
	        curl_setopt( $ch, CURLOPT_POST, true );
	        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
	        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	       
	        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
	       
	        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false);
	        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
	       
	        $result = curl_exec($ch);
	       
	        curl_close($ch);
	       
	        return $result;
	    }
	   
	    function error($msg){
	        echo "Android send notification failed with error:";
	        echo "\t" . $msg;
	        exit(1);
	    }
	}


	//$apiKey = "AIzaSyBVd8ePTMrvKg0Rcic-k4MtdmI4-RQXDZU"; //api key
	$apiKey = "AIzaSyB4VbRPOEzAiJ_wMPWY-Bvh3H5I6LqQ5x0"; //api key
	$devices = array('APA91bGKcBiB5Uj7CwgLnqYxSiVA7oid9XkOl7hdwn6_3ziGcxqvU8jSH7LXq24BxhoPLqQygpGxJPjiBtyRp6f6gwXtDj26noSRyeV_Tdq_3xzRSAwJmjL1NrqM0o9z2rHOIIHqzCm8ItoBACDmc'); //array of tokens
	$message = "ANG MAHIWAGANG MENSAHI"; //message o send

	$gcpm = new GCMPushMessage($apiKey);
	$gcpm->setDevices($devices);
	$response = $gcpm->send($message, array('title' => 'FUCKINGSHIT title')); //title of the message

	return $response;

});