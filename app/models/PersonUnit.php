<?php

class PersonUnit extends Eloquent {

	protected $table = 'person_units';
	protected $fillable = ['name', 'birth_date', 'gender', 'email', 'contact_number', 'deviceID'];
}