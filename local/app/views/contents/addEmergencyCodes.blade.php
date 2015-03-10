@extends('layout')
@section('content')

	{{ Form::open(array('url' => 'foo/bar')) }}
		<div id="main">
			<div class="row">
				<div class="container titleArea">
					<a class="pull-right btn btn-embossed btn-danger" href="{{ URL::route('listview') }}">List View</a>
					<a class="pull-right btn btn-embossed btn-danger active" href="#">Map View</a>
				</div>
			</div>

			<div class="row top-buffer container">
				<div id="markerArea">
					<form id="markerForm">
						<h3 id="formTitle">Add Emergency Codes</h3>
						<div class="form-group">
							<label for="name">Color Name: *</label>
							<input class="form-control" type="text" id="name" name="name" value="" placeholder="Name">
						</div>
						<div class="form-group">
							<label for="address" class="control-label">Description: *</label>
							<input class="form-control" type="text" id="address" name="address" value="" placeholder="Address">
						</div>
						<div class="form-group">
							<label for="email">Icon: </label>
							<input class="form-control" type="text" id="email" name="email" value="" placeholder="Email">
						</div>
						<div class="form-group">
							<label for="username">Color Hex: </label>
							<input class="form-control" type="text" id="username" name="username" value="" placeholder="Username">
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
		</div>
	{{ Form::close() }}
@stop
@section('script') 
	<script src="{{ URL::asset('assets/js/mapview.js') }}"></script>
	<script src="{{ URL::asset('assets/js/classie.js') }}"></script>
@stop

