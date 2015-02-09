<?php

class PersonUnit extends Eloquent {

	protected $table = 'person_units';
	protected $fillable = ['name', 'home_lat', 'home_lng', 'birth_date', 'gender', 'email', 'contact_number', 'deviceID'];
}