<?php

class ReportsQueue extends \Eloquent {
	protected $table = 'reports_queue';
	public $timestamps = false;
	protected $fillable = array("pu_id", "ru_id", "ec_id", "lat", "lng", "date_reported", "date_received", "mobile", "report_group");
}