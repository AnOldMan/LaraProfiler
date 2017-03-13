<?php

class Adminvalidate extends User {

	/**
	 * Validate admin login ### returns false on success, message on failure ###
	 *
	 * @return false||string
	 */
	public static function validate_login()
	{
		$rules = array(
			'username'	=> 'required',
			'password'	=> 'required',
			'verify'	=> 'laracaptcha|required'
		);

		$messages = array(
			'laracaptcha' => 'The verify field was incorrect.',
		);

		$validation = Validator::make( Input::all(), $rules, $messages );

		if( $validation->valid() ) return false;

		return implode( ' ', $validation->errors->all( ':message' ) );
	}

	/**
	 * Validate admin password reset ### returns false on success, message on failure ###
	 *
	 * @return false||string
	 */
	public static function validate_reset()
	{
		$rules = array(
			'email_address'			=> 'required|exists:users,email',
			'confirm_email_address'	=> 'same:email_address|required',
			'verify'				=> 'laracaptcha|required'
		);

		$messages = array(
			'laracaptcha' => 'The verify field was incorrect.',
		);

		$validation = Validator::make( Input::all(), $rules, $messages );

		if( $validation->valid() ) return false;

		return implode( ' ', $validation->errors->all( ':message' ) );
	}
}
