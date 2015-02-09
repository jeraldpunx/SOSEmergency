<?php

class RescueUnit extends \Eloquent {
	protected $table = 'rescue_units';
	public $timestamps = false;
	protected $fillable = array("name", "address", "lat", "lng", "email", "type", "status");
}