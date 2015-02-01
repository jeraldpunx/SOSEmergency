@extends('layout')

@section('style')
	html, body { height: 100%; margin: 0; padding: 0;}
	#maps { height: 500px; }
	#map-canvas  { height: 90%; }

	#ui_notifIt { padding: 8px !important; }
	#ui_notifIt > ul > li { font-size: 16px; line-height: 1.5; }
@stop

@section('content')
	<div id="maps">
		<div id="map-canvas"></div>
	</div>
	
	<form id="addMarkerForm">
		<label for="name">Name: </label>
		<input type="text" id="name" name="name" value="" placeholder="Name">
		<label for="address">Address: </label>
		<input type="text" id="address" name="address" value="" placeholder="Name">
		<label for="type">Type: </label>
		 <select id="type">
		  <option value="hospital">Hospital</option>
		  <option value="firecontrol">Fire Control</option>
		  <option value="police">Police</option>
		</select> 
		<!-- <input type="text" id="type" name="type" value="" required="" placeholder="Name"> -->
		<label for="email">Email Address: </label>
		<input type="text" id="email" name="email" value="" placeholder="Email">
		<input type="text" id="lat" name="lat" value="" placeholder="lat" readonly>
		<input type="text" id="lng" name="lng" value="" placeholder="lng" readonly>

		<div id="contactNumber">
			<div>
				Contact Number 1<br><input type="text" name="myContactNumber[]">
			</div>
		</div>
		<a id="addMoreCN" href="">+Add More</a>

		<button class="btn btn-embossed btn-primary" type="submit">Insert</button>
	</form>
@stop

@section('script')
	<script type="text/javascript"
	      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVd8ePTMrvKg0Rcic-k4MtdmI4-RQXDZU&libraries=places&callback=initialize">
	    </script>
	    
	<script src="{{ URL::asset('assets/js/map.js') }}"></script>
@stop

