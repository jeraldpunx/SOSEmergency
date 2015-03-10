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
					<a class="btn btn-embossed btn-primary" href="{{ URL::route('listemergencycodes') }}">Emergency Codes</a>
					<a class="btn btn-embossed btn-primary active" href="#">Report</a>
				</div>
			</div>

			<hr>

			<div class="top-buffer row listview">
				<p><strong>Reports List</strong></p>
				@if ($reports->count())
				    <table class="table table-condensed">
				        <thead>
				            <tr>
				            	<th class="fix">Person Name</th>
				            	<th class="fix">Rescue Name</th>
				            	<th class="fix">Description</th>
				                <th class="fix">Date Reported</th>
						        <th class="fix">Date Received</th>
						        <th class="fix">Date Responded</th>
						        <th class="fix"></th>
				            </tr>
				        </thead>

				        <tbody>
				        	@foreach ($reports as $report)
				        	<tr>
				        		<td class="fix">{{ $report->person_unit_name }}</td>
				        		<td class="fix">{{ $report->rescue_unit_name }}</td>
				        		<td class="fix">{{ $report->description }}</td>
				        		<td class="fix">{{ $report->date_reported }}</td>
				        		<td class="fix">{{ $report->date_received }}</td>
				        		<td class="fix">
				        			@if( $report->date_responded)
				        				{{ $report->date_responded }}
									@else
										{{ "No Response" }}
									@endif
				        		</td>
				        		<td>
				        			<div class="col-md-12">
					        			<div class="input-group">
					        				<a href="viewreport/{{ $report->report_id }}" class="contacts btn btn-embossed btn-warning btn-xs">View More Details</a>
										</div>
									</div>
				        		</td>
				            </tr>
				            @endforeach
				              
				        </tbody>
				      
				    </table>
				@else
				    There are no reports
				@endif
			</div>
		</div>
	</div>
@stop

@section('script')
	<script src="{{ URL::asset('assets/js/classie.js') }}"></script>
@stop

