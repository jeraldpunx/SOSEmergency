@extends('layout')

@section('style')
	<style type="text/css">
		
		table {
		  table-layout: fixed;
		  width: 100%;
		}

		table .fix {
		  width: 19%;
		  white-space: nowrap;
		  overflow: hidden;         /* <- this does seem to be required */
		  text-overflow: ellipsis;
		}
	</style>
@stop

@section('content')
	<div id="main">
		<div class="container">
			<div class="row">
				<div class="titleArea">
					<h2 class="">List View</h2>
					<a class="pull-right btn btn-embossed btn-danger active" href="#">List View</a>
					<a class="pull-right btn btn-embossed btn-danger" href="{{ URL::route('mapview') }}">Map View</a>
				</div>
			</div>
			
			<hr>
			
			<div class="row">
				<div class="pull-right">
					<a class="btn btn-embossed btn-primary active" href="#">Rescue Unit</a>
					<a class="btn btn-embossed btn-primary" href="{{ URL::route('mapview') }}">Person Unit</a>
					<a class="btn btn-embossed btn-primary" href="{{ URL::route('mapview') }}">Report</a>
				</div>
			</div>

			<hr>

			<div class="top-buffer row listview">
				<a class="pull-right btn btn-embossed btn-success" href="{{ URL::route('mapview') }}">Add marker</a>
				<p><strong>Respondents List</strong></p>
				@if ($markers->count())
				    <table class="table table-condensed">
				        <thead>
				            <tr>
				                <th class="fix">Name</th>
						        <th class="fix">Address</th>
						        <th class="fix">Email</th>
						        <th class="fix">Rescue Type</th>
						        <th></th>
				            </tr>
				        </thead>

				        <tbody>
				        	@foreach ($markers as $marker)
				        	<tr>
				        		<td class="fix">{{ $marker->name }}</td>
				        		<td class="fix">{{ $marker->address }}</td>
				        		<td class="fix">{{ $marker->email }}</td>
				        		<td class="fix">
				        			@if($marker->rescue_type == 'hospital')
				        				{{ "Hospital" }}
				        			@elseif($marker->rescue_type == 'firecontrol')
				        				{{ "Fire Control" }}
				        			@elseif($marker->rescue_type == 'police')
				        				{{ "Police Station" }}
				        			@elseif($marker->rescue_type == 'rescuevolunteer')
				        				{{ "Rescue Volunteer" }}
				        			@endif
				        		</td>
				        		<td>
				        			<div class="col-md-12">
					        			<div class="input-group">
					        				<a data-target="#contactsMarkerModal" data-toggle="modal" data-id="{{ $marker->rescue_units_id }}" href="#" class="contacts btn btn-embossed btn-primary btn-xs">Contacts</a>
						        			<a class="btn btn-embossed btn-xs btn-info" href="{{ URL::route('editru', $marker->rescue_units_id ) }}">Edit</a>
											<a class="btn btn-embossed btn-xs btn-danger" href="mapView">Delete</a>
										</div>
									</div>
				        		</td>
		
				            </tr>
				            @endforeach
				              
				        </tbody>
				      
				    </table>
				@else
				    There are no markers
				@endif
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
						<table id="contactsTable" class="display" cellspacing="0" width="100%">
						</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-embossed btn-success" id="addNewContact" data-ruid="123">Add new</button>
						<button type="button" class="btn btn-embossed btn-danger" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@section('script')
	<script src="{{ URL::asset('assets/js/listview.js') }}"></script>
	<script src="{{ URL::asset('assets/js/classie.js') }}"></script>
@stop

