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
		<div class="container" style="padding-top: 40px; padding-bottom: 40px;">
			<div class="row">
				<div class="titleArea">
					<h2 class="">List View</h2>
				</div>
			</div>
			
			<hr>
			
			<div class="row">
				<div class="pull-right">
					<a class="btn btn-embossed btn-primary" href="{{ URL::route('listview') }}">Rescue Unit</a>
					<a class="btn btn-embossed btn-primary" href="{{ URL::route('listpu') }}">Person Unit</a>
					<a class="btn btn-embossed btn-primary active" href="#">Emergency Codes</a>
					<a class="btn btn-embossed btn-primary" href="{{ URL::route('listreport') }}">Report</a>
				</div>
			</div>

			<hr>

			<div class="top-buffer row listview">
				<a class="pull-right btn btn-embossed btn-success" href="{{ URL::route('mapview') }}">Add Emergency Code</a>
				<p><strong>Emergency Code List</strong></p>
				@if ($emergencyCodes->count())
				    <table class="table table-condensed">
				        <thead>
				            <tr>
				                <th class="fix">Color Name</th>
						        <th class="fix">Description</th>
						        <th class="fix">Icon</th>
						        <th class="fix">Color Hex</th>
				            </tr>
				        </thead>

				        <tbody>
				        	@foreach ($emergencyCodes as $emergencyCode)
				        	<tr>
				        		<td class="fix">{{ $emergencyCode->color_name }}</td>
				        		<td class="fix">{{ $emergencyCode->description }}</td>
				        		<td class="fix">{{ $emergencyCode->icon }}</td>		
				        		<td class="fix">{{ $emergencyCode->color_hex }}</td>
				        		<td>
				        			<div class="col-md-12">
					        			<div class="input-group">
						        			<a class="btn btn-embossed btn-xs btn-info" href="">Edit</a>
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

