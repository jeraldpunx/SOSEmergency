<?php

Route::get('/', ['as'=>'home',    'uses'=>'UsersController@index']);

Route::get('register', 'UsersController@create');
Route::post('register', 'UsersController@storePU');

Route::get('login', 'UsersController@showLogin');
Route::post('login', 'UsersController@doLogin');

Route::post('request', 'UsersController@storeRU');
Route::post('checkrespondentstatus', 'UsersController@checkRespondentStatus');
Route::post('insertnewcontact', 'UsersController@insertNewContact');

Route::get('emergencyCodes', 'UsersController@retrieveEmergencyCodes');


Route::get('realtimemap', 					'MapsController@realTimeMap');
Route::get('getcurrenttime', 				'MapsController@getCurrentTime');
Route::get('getlivereports/{currentTime}', 	'MapsController@getLiveReports');
Route::get('mapview', 						'MapsController@mapView');
Route::get('listview', 						'MapsController@listView');
Route::get('markers', 						'MapsController@markers');


Route::post('saveMarker', 'MapsController@saveAddMarker');
Route::get('editMarker/{id}', 'MapsController@editMarker');
Route::post('editMarker/{id}', 'MapsController@saveEditMarker');
Route::post('deleteMarker', 'MapsController@deleteMarker');

Route::get('getMarkerContactInfo/{id}', 'MapsController@getMarkerContactInfo');
Route::post('editContact/{id}', 'MapsController@saveEditContact');
Route::post('deleteContact', 'MapsController@deleteContact');

Route::post('shortest', 'MapsController@shortest');
Route::post('responseEmergency', 'MapsController@responseEmergency');