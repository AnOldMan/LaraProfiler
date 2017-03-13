<?php

// Login
Route::get( 'admin/login', array(
	'as' => 'admin_login',
	function()
	{
		return View::make( 'admin::page' )
			->with( 'page_title', 'Log In' )
			->with( 'content', View::make( 'admin::form.login' ) );
	}
));

// Login Post
Route::post( 'admin/login', array(
	function()
	{
		if( ! $error = Adminvalidate::validate_login() )
		{
			$auth = Auth::attempt( array(
					'username' => Input::get( 'username' ),
					'password' => Input::get( 'password' )
			) );
		}
		else $auth = '';

		if( ! empty( $auth ) && Auth::user()->enabled )
		{
			DB::table( 'users' )
				->where( 'id', '=', Auth::user()->id )
				->update( array( 'session' => (int)(LARAVEL_START + ( Config::get( 'session.lifetime', 30 ) * 60 ) ) ) );

			if( Session::has( 'post_login' ) )
			{
				$post_login = Session::get( 'post_login' );
				Session::forget( 'post_login' );

				if( trim( $post_login, '/' ) != 'admin/login' ) return Redirect::to( $post_login );
			}
			return Redirect::to_route( 'admin_home' );
		}
		elseif( ! $error ) $error = 'Invalid user or password.';

		return View::make( 'admin::page' )
			->with( 'page_title', 'Login: Authentication Error' )
			->with( 'content', View::make( 'admin::form.login' )
				->with( 'error', $error )
			);
	}
));

// Logout
Route::get( 'admin/logout', array(
	'as' => 'admin_logout',
	function()
	{
		Auth::logout();
		return Redirect::to_route( 'homepage' );
	}
));

//PasswordReset
Route::get( 'admin/reset-password', array(
	'as' => 'password_reset',
	function()
	{
		return View::make( 'admin::page' )
			->with( 'page_title', 'Password Reset' )
			->with( 'content', View::make( 'admin::form.password-reset' ) );
	}
));

// Save Password Reset
Route::post( 'admin/reset-password', array(
	'as' => 'password_reset_save',
	function()
	{
		if( $check = Adminvalidate::validate_reset() )
		{
			$error = $check;
		}
		// pull user for this email
		elseif( $user = User::where( 'email', '=', Input::get( 'email_address', '' ) )->first() )
		{
			// Reset the users password and send them an email.
			$user->reset_password( true );
			$error = 'A temporary password has been set and emailed to you.';
		}
		// something went wrong
		else $error = 'There was an error.';

		return View::make( 'admin::page' )
			->with( 'page_title', 'Password Reset' )
			->with( 'content', View::make( 'admin::form.password-reset' )
				->with( 'error', $error )
			);
	}
));
