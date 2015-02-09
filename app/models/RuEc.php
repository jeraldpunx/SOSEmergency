<?php

class RuEc extends \Eloquent {
	protected $table = 'ru_ec';
	public $timestamps = false;
	protected $fillable = array('ru_id', 'ec_id');
}