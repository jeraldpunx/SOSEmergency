@extends('layout')

@section('style')
	<style type="text/css">
		.main-box {
			background: red;
			box-shadow: 0px 1px 1px rgba(0,0,0,0.1);
			margin-bottom: 16px;
			border-radius: 3px;
			background-clip: padding-box;
			padding: 20px;
		}
	</style>
@stop

@stop

@section('content')
	<div id="main">
		<div class="container" style="padding-top: 40px; padding-bottom: 40px;">
			<div class="row">
				<div class="titleArea">
					<h2 class="">Report View</h2>
				</div>
			</div>

			<hr>
			
			<div class="row">
				<div class="pull-left">
					<a class="btn btn-embossed btn-danger" href="{{ URL::route('listreport') }}"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>
				</div>
			</div>

			<hr>

			<?php $reports = $reports[0]; ?>
			<div class="row">
				<div class="col-md-4">
					<h4>Report Details</h4>
					<address>
						<strong>Color <span style="color:{{ $reports->color_hex }};">{{ $reports->color_name }}</span>: </strong>
						<a>{{ $reports->description }}</a>
					</address>

					<address>
						<strong>Date Reported</strong><br>
						<a>{{ $reports->date_reported }}</a>
					</address>

					<address>
						<strong>Date Reported</strong><br>
						<a>{{ $reports->date_reported }}</a>
					</address>

					<address>
						<strong>Date Received</strong><br>
						<a>{{ $reports->date_received }}</a>
					</address>

					<address>
						<strong>Date Responded</strong><br>
						<p>
						@if($reports->date_responded)
							{{ $reports->date_responded }}
						@else
							{{ "None" }}
						@endif
						</p>
					</address>
				</div>

				<div class="col-md-4">
					<h4>Person</h4>
					<address>
						<strong>Name</strong><br>
						<a>{{ $reports->person_unit_name }}</a>
					</address>

					<address>
						<strong>Birth Date</strong><br>
						<a>{{ $reports->birth_date }}</a>
					</address>

					<address>
						<strong>Gender</strong><br>
						<a>{{ $reports->gender }}</a>
					</address>

					<address>
						<strong>Email</strong><br>
						<a>{{ $reports->person_unit_email }}</a>
					</address>

					<address>
						<strong>Contact Number</strong><br>
						<a>{{ $reports->contact_number }}</a>
					</address>
				</div>

				<div class="col-md-4">
					<h4>Rescuer</h4>
					<address>
						<strong>Name</strong><br>
						<a>{{ $reports->rescue_unit_name }}</a>
					</address>

					<address>
						<strong>Addres</strong><br>
						<a>{{ $reports->address }}</a>
					</address>

					<address>
						<strong>Email</strong><br>
						<a>
						@if($reports->rescue_unit_email)
							{{ $reports->rescue_unit_email }}
						@else
							None
						@endif
						</a>
					</address>

					<address>
						<strong>Type</strong><br>
						<a>{{ $reports->type }}</a>
					</address>
				</div>
			</div>
			<br>
			<hr>
			@if($reports->report_image)
			<div class="row">
				<div class="container">
					<h3>Images</h3><br>
					<div class="report_image">
						<?php
							$report_image = explode(",",$reports->report_image);
						?>

						
						<div class="col-md-3 col-sm-4 col-xs-6"><img class="img-responsive" src="{{ URL::asset('uploads/' . $report_image[0]) }}"></div>
						<div class="col-md-3 col-sm-4 col-xs-6"><img class="img-responsive" src="{{ URL::asset('uploads/' . $report_image[1]) }}"></div>
						<div class="col-md-3 col-sm-4 col-xs-6"><img class="img-responsive" src="{{ URL::asset('uploads/' . $report_image[2]) }}"></div>
					</div>
				</div>
			</div>
			@endif
		</div>
	</div>
@stop


@section('script')
	<script src="{{ URL::asset('assets/js/classie.js') }}"></script>
@stop



