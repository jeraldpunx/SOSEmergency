<?php

class RescueUnit extends \Eloquent {
	protected $table = 'rescue_units';
	protected $fillable = array("name", "address", "lat", "lng", "email", "type");
}