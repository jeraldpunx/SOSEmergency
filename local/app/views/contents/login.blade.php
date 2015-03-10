@extends('layout')

@section('content')
	<div id="main">
		<div class="container" style="padding-top: 40px; padding-bottom: 40px;">
			<div class="col-md-1"></div>
			<div class="col-md-4">
				<form id="login_form_header" method="POST" action="adminlogin">
					<div class="form-group">
						<label for="username">Username: </label>
						<input id="username" type="text" name="username" autofocus="" required="" placeholder="Enter your username" class="form-control">
					</div>

					<div class="form-group">
						<label for="password">Password: </label>
						<input id="password" type="password" value="" name="password" required="" placeholder="Password" class="form-control">
					</div>
					
					<input id="type" type="hidden" value="pu" name="type">
					<input type="submit" value="Login" class="btn btn-warning btn-lg btn-block">

					@foreach ($errors->all() as $error)
					   {{ $error }}<br>
					@endforeach
				</form>
			</div>
			<div class="col-md-5">
					<h3>SOS Emergency!</h3>
					<p>only admin have rights to login.</p>
			</div>
		</div>
	</div>
@stop


@section('script')

@stop