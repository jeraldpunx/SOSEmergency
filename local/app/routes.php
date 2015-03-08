<?php

Route::get('/', ['as'=>'home',    'uses'=>'UsersController@index']);

Route::get('register', 'UsersController@create');


Route::get('login', 'UsersController@showLogin');
Route::post('login', 'UsersController@doLogin');

Route::get('adminlogin', 					['as'=>'adminlogin',		'uses'=>'UsersController@showLogin']);
Route::post('adminlogin', 					'UsersController@adminLogin');
Route::post('createadmin',					'AdminController@createAdmin');

Route::get('realtimemap', 					['as'=>'realtimemap',		'uses'=>'AdminController@realTimeMap']);
Route::get('reportqueue', 					'AdminController@reportQueue');
Route::get('getcurrenttime', 				'AdminController@getCurrentTime');
Route::get('getlivereports/{currentTime}', 	'AdminController@getLiveReports');


Route::get('listview', 						['as'=>'listview',		'uses'=>'AdminController@listView']);
Route::get('editru/{id}', 					['as'=>'editru', 		'uses'=>'AdminController@editRU']);

Route::get('mapview', 						['as'=>'mapview',		'uses'=>'AdminController@mapView']);
Route::get('markers', 						'AdminController@markers');
Route::post('saveMarker', 					'AdminController@saveAddMarker');
Route::get('editMarker/{id}', 				'AdminController@editMarker');
Route::post('editMarker/{id}', 				'AdminController@saveEditMarker');
Route::get('viewru/{id}', 					['as'=>'viewru', 		'uses'=>'AdminController@viewRU']);///////
Route::post('deleteMarker', 				'AdminController@deleteMarker');
Route::get('getMarkerContactInfo/{id}', 	'AdminController@getMarkerContactInfo');
Route::post('addContact', 					'AdminController@addContact');
Route::post('editContact/{id}', 			'AdminController@saveEditContact');
Route::post('deleteContact', 				'AdminController@deleteContact');
Route::get('viewreport', 					'AdminController@viewReport');

Route::post('register', 					'RestController@storePU');
Route::post('request', 						'RestController@storeRU');
Route::post('checkrespondentcontact', 		'RestController@checkRespondentContact');
Route::post('insertnewcontact', 			'RestController@insertNewContact');
Route::get('emergencyCodes', 				'RestController@retrieveEmergencyCodes');
Route::post('shortest', 					'RestController@shortest');
Route::post('responseEmergency', 			'RestController@responseEmergency');
Route::post('upload', 						'RestController@uploads');


Route::get('test', function(){
	echo Input::get('aw');
	echo 'aw';
});