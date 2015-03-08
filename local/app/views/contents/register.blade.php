@extends('layout')

@section('content')
    <form id="login_form_header" method="POST" action="register">
    	<div class="form-group">
			<label for="username">Username: </label>
			<input id="username" type="text" name="username" autofocus="" required="" placeholder="Enter your username" class="form-control">
		</div>

		<div class="form-group">
			<label for="password">Password: </label>
			<input id="password" type="password" value="" name="password" required="" placeholder="Password" class="form-control">
		</div>

		<div class="form-group">
			<label for="password_confirmation">Confirm Password: </label>
			<input id="password_confirmation" type="password" value="" name="password_confirmation" required="" placeholder="Confirm Password" class="form-control">
		</div>

		<div class="form-group">
			<label for="type">Type: </label>
			<input id="type" type="text" name="type" autofocus="" required="" placeholder="Type" class="form-control">
		</div>



		<div class="form-group">
			<label for="name">Full Name: </label>
			<input id="name" type="text" name="name" autofocus="" required="" placeholder="Full Name" class="form-control">
		</div>


		<div class="form-group">
			<label for="birth_date">Birth Date: </label>
			<input id="birth_date" type="text" name="birth_date" autofocus="" required="" placeholder="Birth Date" class="form-control">
		</div>

		<div class="form-group">
			<label for="contact_number">Contact Number: </label>
			<input id="contact_number" type="text" name="contact_number" autofocus="" required="" placeholder="Contact Number" class="form-control">
		</div>

		<div class="form-group">
			<label for="gender">Gender: </label>
			<input id="gender" type="text" name="gender" autofocus="" required="" placeholder="Gender" class="form-control">
		</div>

		<div class="form-group">
			<label for="email">Email: </label>
			<input id="email" type="text" name="email" autofocus="" required="" placeholder="Email" class="form-control">
		</div>

		<div class="form-group">
			<label for="deviceID">Device ID: </label>
			<input id="deviceID" type="text" name="deviceID" autofocus="" required="" placeholder="Device ID" class="form-control">
		</div>
	
		<input type="submit" value="Register" class="btn btn-warning btn-lg btn-block">

		@foreach ($errors->all() as $error)
           {{ $error }}<br>        
        @endforeach
    </form>
@stop


@section('script')

@stop