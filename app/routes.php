<?php

Route::get('register', 'UsersController@create');
Route::post('register', 'UsersController@store');

Route::get('login', 'UsersController@showLogin');
Route::post('login', 'UsersController@doLogin');

Route::get('emergencyCodes', 'UsersController@retrieveEmergencyCodes');


Route::get('mapview', 'MapsController@mapView');
Route::get('listview', 'MapsController@listView');
Route::get('markers', 'MapsController@markers');


Route::post('saveMarker', 'MapsController@saveAddMarker');
Route::get('editMarker/{id}', 'MapsController@editMarker');
Route::post('editMarker/{id}', 'MapsController@saveEditMarker');
Route::post('deleteMarker', 'MapsController@deleteMarker');

Route::get('getMarkerContactInfo/{id}', 'MapsController@getMarkerContactInfo');
Route::post('editContact/{id}', 'MapsController@saveEditContact');
Route::post('deleteContact', 'MapsController@deleteContact');

Route::post('shortest', 'MapsController@shortest');
Route::post('responseEmergency', 'MapsController@responseEmergency');