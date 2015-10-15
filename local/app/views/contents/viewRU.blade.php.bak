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
		<div class="container">
			{{ $rescue_units }}
			<div class="row">
				<div class="col-md-6">
					<address>
						<strong>Name</strong><br>
						<a>{{ $rescue_units->name }}</a>
					</address>

					<address>
						<strong>Address</strong><br>
						<a>{{ $rescue_units->address }}</a>
					</address>

					<address>
						<strong>Email</strong><br>
						<a>{{ $rescue_units->email }}</a>
					</address>

					<address>
						<strong>Type</strong><br>
						<a>{{ $rescue_units->type }}</a>
					</address>
				</div>

				<div class="col-md-6">
					<div class="main-box clearfix">
						<address>
							<strong>Name</strong><br>
							<a>{{ $rescue_units->name }}</a>
						</address>

						<address>
							<strong>Address</strong><br>
							<a>{{ $rescue_units->address }}</a>
						</address>

						<address>
							<strong>Email</strong><br>
							<a>{{ $rescue_units->email }}</a>
						</address>

						<address>
							<strong>Type</strong><br>
							<a>{{ $rescue_units->type }}</a>
						</address>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop


@section('script')
	<script src="{{ URL::asset('assets/js/classie.js') }}"></script>
@stop



