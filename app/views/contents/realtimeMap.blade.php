@extends('layout')

@section('content')
	<div id="main">
		<div id="maps">
			<div id="map-canvas" ></div>
		</div>

		<div class="container">
			<div class="logs">
				<h3>Logs</h3>
				 <textarea id="logdata" class="form-control" rows="3" disabled></textarea> 
			</div>
		</div>
	</div>

	<div class="loadingMapCountdown">
		<div class="text">
			<span id="timer">Loading...</span>
		</div>
	</div>
@stop



@section('script')
	<script type="text/javascript"
		  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVd8ePTMrvKg0Rcic-k4MtdmI4-RQXDZU&libraries=places&callback=initialize">
		</script>
	
	<script src="{{ URL::asset('assets/js/realtimemap.js') }}"></script>
	<script src="{{ URL::asset('assets/js/classie.js') }}"></script>
@stop

