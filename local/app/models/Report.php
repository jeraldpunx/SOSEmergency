<?php

class Report extends \Eloquent {
	protected $table = 'reports';
	public $timestamps = false;
	protected $fillable = array("pu_id", "ru_id", "ec_id", "lat", "lng", "date_reported", "date_received", "date_responded", "mobile", "report_group");
}