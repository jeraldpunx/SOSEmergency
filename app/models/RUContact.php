<?php

class RUContact extends \Eloquent {
	protected $table = 'ru_contacts';
	public $timestamps = false;
	protected $fillable = array('ru_id', 'contact_number', 'deviceID');
}