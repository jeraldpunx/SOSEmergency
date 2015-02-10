<?php

class UsersController extends \BaseController {
	//REGISTER
	public function create()
	{
		return View::make('contents.register');
	}

	function store()
	{
		$input = Input::all();

	    $rules = array(
	    	'type' 					=> 'required',
	        'username' 				=> 'required|min:5|unique:users,username',
			'password' 				=> 'required|confirmed',
			'password_confirmation' => 'same:password',
			'name' 					=> 'required',
			'email'					=> 'required',
			'birth_date'			=> 'required',
			'contact_number'		=> 'required',
			'gender'				=> 'required'
	    );

	    $validation = Validator::make($input, $rules);

	    if($validation->passes()) {
	    	$person_unit = new PersonUnit;
	    	$person_unit->name				=	Input::get('name');
	    	$person_unit->birth_date		=	Input::get('birth_date');
	    	$person_unit->gender 			=	Input::get('gender');
	    	$person_unit->email 			=	Input::get('email');
	    	$person_unit->contact_number			=	Input::get('contact_number');
			$person_unit->deviceID					=	Input::get('deviceID');
	    	$person_unit->save();

	    	$user = new User;
	    	$user->puid_ruid 				= 	$person_unit->id;
	    	$user->type 					=	Input::get('type');
			$user->username 				=	Input::get('username');
			$user->password 				=	Hash::make(Input::get('password'));
			$user->save();

			$returnedValue = array(
	        	'id' 	=>	$user->id,
	        	'error'	=>	false
	        );
	    } else {
	    	$returnedValue = array(
	        	'error'		=>	true,
	        	'messages'	=>	$validation->messages()
	        );
	    	//return Redirect::back()->withErrors($validation)->withInput();  
	    }
	    return Response::json($returnedValue);
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
        	$returnedValue = array(
	        	'id' 	=>	Auth::id(),
	        	'error'	=>	false
	        );
            
        } else {
        	$returnedValue = array(
	        	'error'	=>	true
	        );
        }

        return Response::json($returnedValue);
	}

	public function retrieveEmergencyCodes() {
		return EmergencyCode::all()->toArray();
	}
}