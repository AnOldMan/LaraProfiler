<?php

/**
 * ### RETURNS FALSE ON SUCCESS, VALIDATOR ON FAILURE ###
 */
class Uservalidate extends User
{
	public static function invalid_login( $user = array() )
	{
		if( ! $user ) $user = Input::all();
		$rules = array(
			'username'	=> 'required',
			'password'	=> 'required',
			'captcha'	=> 'laracaptcha|required'
		);

		$messages = array(
			'laracaptcha' => 'The verify field was incorrect.',
		);

		return self::validator_make( $user, $rules, $messages );
	}

	public static function failed_login()
	{
		$rules = array(
			'username'	=> 'required'
		);

		$messages = array(
			'required' => 'Invalid username or password.',
		);

		return self::validator_make( array(), $rules, $messages );
	}

	public static function invalid_reset( $user = array() )
	{
		if( ! $user ) $user = Input::all();
		$rules = array(
			'email'			=> 'required|confirmed|exists:users,email',
			'captcha'		=> 'laracaptcha|required'
		);

		$messages = array(
			'laracaptcha' => 'The verify field was incorrect.',
		);

		return self::validator_make( $user, $rules, $messages );
	}

	public static function invalid_profile( $user = array() )
	{
		if( ! $user ) $user = Input::all();
		$rules = array(
			'firstname'	=> 'required|max:32|no_html|no_http',
			'lastname'	=> 'required|max:32|no_html|no_http',
			'email'		=> 'required|max:255|email|unique:users,email',
			'username'	=> 'required|max:64|alpha_dash|unique:users,username',
			'password'	=> 'required|confirmed|min:6|max:32|match:/[a-z]+/|match:/[A-Z]+/|match:/[0-9]+/'
		);

		$messages = array(
			'match' => 'The :attribute must contain at least one lowercase letter (a-z), one uppercase letter (A-Z), and one number (0-9).',
			'unique' => 'The :attribute you chose is not available, and has been reset.'
		);

		return self::validator_make( $user, $rules, $messages );
	}

	private static function validator_make( $user, $rules, $messages )
	{
		$validator = Validator::make( $user, $rules, $messages );

		if( $validator->valid() ) return false;

		return $validator;
	}
}
