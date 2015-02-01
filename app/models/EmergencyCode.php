<?php

class EmergencyCode extends Eloquent {

	protected $table = 'emergency_codes';
	public $timestamps = false;
	protected $fillable = ['color_name', 'desciprtion', 'icon', 'color_hex'];

}