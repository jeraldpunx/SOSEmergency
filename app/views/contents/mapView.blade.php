@extends('layout')


@section('content')
	<div id="main">
		<div class="row">
			<div class="container titleArea">
				<h2 class="">Map View</h2>
				<a class="pull-right btn btn-embossed btn-danger" href="listview">List View</a>
				<a class="pull-right btn btn-embossed btn-danger active" href="#">Map View</a>
			</div>
		</div>


		<div class="top-buffer">
			<div id="maps">
				<div id="map-canvas" ></div>
			</div>
		</div>
		



		<div class="row top-buffer container">
			<div id="markerArea">
				<form id="markerForm">
					<h3 id="formTitle">Add Marker</h3>
					<div class="form-group">
						<label for="name">Name: *</label>
						<input class="form-control" type="text" id="name" name="name" value="" placeholder="Name">
					</div>
					<div class="form-group">
						<label for="address" class="control-label">Address: *</label>
						<input class="form-control" type="text" id="address" name="address" value="" placeholder="Address">
					</div>
					<div class="form-group">
						<label for="type">Type: *</label>
						<select id="type" class="form-control">
							<option value="hospital">Hospital</option>
							<option value="firecontrol">Fire Control</option>
							<option value="police">Police</option>
							<option value="rescuevolunteer">Rescue Volunteer</option>
						</select>
					</div>
					<div class="form-group">
						<label for="email">Email Address: </label>
						<input class="form-control" type="text" id="email" name="email" value="" placeholder="Email">
					</div>
					<input type="hidden" id="lat" name="lat" value="" placeholder="lat">
					<input type="hidden" id="lng" name="lng" value="" placeholder="lng">
					<input type="hidden" id="rescue_units_id" name="" value="">
					<div class="form-group pull-right">
						<button class="btn btn-embossed btn-danger" type="submit">Save</button>
					</div>
				</form>
			</div>
		</div>

		
		<!-- contactsModal -->
		<div class="modal fade" id="contactsMarkerModal" tabindex="-1" role="dialog" aria-labelledby="contactsMarkerModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="contactsMarkerModalLabel">Contact Number</h4>
					</div>
					<div class="modal-body" id="modalBodyContact">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-embossed btn-danger" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@section('script')
	<script type="text/javascript"
	      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVd8ePTMrvKg0Rcic-k4MtdmI4-RQXDZU&libraries=places&callback=initialize">
	    </script>
	    
	<script src="{{ URL::asset('assets/js/mapview.js') }}"></script>
	<script src="{{ URL::asset('assets/js/classie.js') }}"></script>
@stop

