<?php

class UsersController extends \BaseController {
	public function index()
	{
		return View::make('contents.login');
	}

	//REGISTER
	public function create()
	{
		return View::make('contents.register');
	}

	//LOGIN
	public function showLogin()
	{
		return View::make('contents.login');
	}

	public function doLogin()
	{
		$user = array(
			'username' 		=>	Input::get('username'),
			'password' 		=>	Input::get('password'),
			'type'			=>	Input::get('type')
		);

	   
		if (Auth::attempt($user)) {
			$user 	= 	User::find(Auth::id())->puid_ruid;
			$returnedValue = array(
				'id' 		=>	$user,
				'error'		=>	false
			);
		} else {
			$returnedValue = array(
				'error'	=>	true
			);
		}

		return Response::json($returnedValue);
	}

	public function adminLogin()
	{
		$user = array(
			'username' 		=>	Input::get('username'),
			'password' 		=>	Input::get('password'),
			'type'			=>	'admin'
		);

		if (Auth::attempt($user)) {
			return Redirect::route('realtimemap');
		} else {
			return Redirect::route('adminlogin')
				->with('errors', 'Your username/password combination was incorrect.')
				->withInput();
		}
	}
}