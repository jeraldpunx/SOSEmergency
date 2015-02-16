<?php

class UsersController extends \BaseController {
	public function index()
	{
		return "aw";
	}

	//REGISTER
	public function create()
	{
		return View::make('contents.register');
	}

	function storePU()
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
	    	$person_unit->contact_number	=	Input::get('contact_number');
			$person_unit->deviceID			=	Input::get('deviceID');
	    	$person_unit->save();

	    	$user = new User;
	    	$user->puid_ruid 				= 	$person_unit->id;
	    	$user->type 					=	Input::get('type');
			$user->username 				=	Input::get('username');
			$user->password 				=	Hash::make(Input::get('password'));
			$user->save();

			$returnedValue = array(
	        	'id' 	=>	$person_unit->id,
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

	function storeRU()
	{
		$input = Input::all();

	    $rules = array(
	    	'name' 					=> 	'required',
	    	'address' 				=> 	'required',
	    	'lat' 					=> 	'required',
	    	'resType' 				=> 	'required',
	    	'contact_number' 		=> 	'required',
	    	'deviceID' 				=> 	'required',
	    	'userType' 				=> 	'required',
	    	'username' 				=> 	'required|min:5|unique:users,username',
			'password' 				=> 	'required|confirmed',
			'password_confirmation' => 	'same:password',
	    );

	    $custom_error = array(
	    		'lat.required' 	=> 	"Please turn on your GPS."
	    	);

	    $validation = Validator::make($input, $rules, $custom_error);

	    if($validation->passes()) {

	    	$rescue_unit = new RescueUnit;
	    	$rescue_unit->name 				= 	Input::get('name');
			$rescue_unit->address 			= 	Input::get('address');
			$rescue_unit->lat 				= 	Input::get('lat');
			$rescue_unit->lng 				= 	Input::get('lng');
			$rescue_unit->email 			= 	Input::get('email');
			$rescue_unit->type 				= 	Input::get('resType');
			$rescue_unit->status 			= 	0;
			$rescue_unit->save();


			$ru_contact = new RUContact;
			$ru_contact->ru_id 				= 	$rescue_unit->id;
			$ru_contact->contact_number 	= 	Input::get('contact_number');
			$ru_contact->deviceID 			= 	Input::get('deviceID');
			$ru_contact->save();

	    	$user = new User;
	    	$user->puid_ruid 				= 	$rescue_unit->id;
	    	$user->type 					=	Input::get('userType');
			$user->username 				=	Input::get('username');
			$user->password 				=	Hash::make(Input::get('password'));
			$user->save();

			$returnedValue = array(
	        	'id' 	=>	$rescue_unit->id,
	        	'error'	=>	false
	        );
	    } else {
	    	$returnedValue = array(
	        	'error'		=>	true,
	        	'messages'	=>	$validation->messages()
	        );
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

	public function retrieveEmergencyCodes()
	{
		return EmergencyCode::all()->toArray();
	}

	public function checkRespondentStatus()
	{
		$rescue_unit = RescueUnit::find(Input::get('ru_id'));
		return $rescue_unit;
	}

	public function insertNewContact()
	{
		$contact 					= 	new RUContact;
		$contact->ru_id 			= 	Input::get('ru_id');
		$contact->contact_number 	= 	Input::get('contact_number');
		$contact->deviceID 			= 	Input::get('deviceID');
		$contact->save();

		$returnedValue = array(
        	'error'		=>	false
        );
	}
}