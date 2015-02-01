@extends('layout')

@section('content')
	<form id="login_form_header" method="POST" action="login">
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
@stop


@section('script')

@stop