@extends('layout')

@section('style')
	<link href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css" rel="stylesheet">
	<style type="text/css">
		table {
		  table-layout: fixed;
		  width: 100px;
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
		<div class="row">
			<div class="container">
				<h2 class="">List View</h2>
				<a class="pull-right btn btn-embossed btn-danger active" href="#">List View</a>
				<a class="pull-right btn btn-embossed btn-danger" href="mapview">Map View</a>
			</div>
		</div>

		<div class="top-buffer container row">
			<p><strong>Respondents List</strong></p>
			@if ($markers->count())
			    <table class="table table-condensed" style="color: #fff">
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
					        			<a class="btn btn-embossed btn-xs btn-success" href="#">Contacts</a>
					        			<a class="btn btn-embossed btn-xs btn-info" href="#">Edit</a>
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
@stop

@section('script')
	<script type="text/javascript"
	      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVd8ePTMrvKg0Rcic-k4MtdmI4-RQXDZU&libraries=places&callback=initialize">
	    </script>
	    
	<script src="{{ URL::asset('assets/js/map.js') }}"></script>
	<script src="{{ URL::asset('assets/js/classie.js') }}"></script>
@stop

