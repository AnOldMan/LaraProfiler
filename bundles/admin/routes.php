<?php

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

// Admin Homepage
Route::get( 'admin', array(
	'as' => 'admin_home',
	'before' => 'admin::auth',
	function()
	{
		return View::make('admin::page')
			->with('leftmenu', View::make('admin::partials.leftmenu') )
			->with('content', View::make('admin::partials.home') );
	}
));

Route::get( 'admin/clearcache', array(
	'as' => 'clear_cache',
	'before' => 'admin::auth',
	function()
	{
		$message = Cache::clear();
		Log::cache( $message );
		// set message for display
		$messages = new Messages;
		$messages->add( 'notice', $message );
		if( $src = Input::get( 'src', false ) ) return Redirect::to( $src )->with_errors( $messages );
		return Redirect::to_route( 'admin_home' )->with_errors( $messages );
	}
));

/*
|--------------------------------------------------------------------------
| Ajax
|--------------------------------------------------------------------------
*/

// Check valid url
Route::post( '/admin/checkurl', array(
	'before' => 'admin::auth',
	function()
	{
		Config::set( 'application.profiler', false );
		Config::set( 'database.profile', false );

		$json = array(
			'query' => (string)Input::get( 'url', '' ),
			'model' => ucfirst( (string)Input::get( 'type', '' ) ),
			'id' => (int)Input::get( 'id', 0 ),
			'error' => 'Selected url is not available.',
			'url' => ''
		);
		if( ! $json['query'] ) $json['error'] = 'No url set.';
		elseif( ! $json['model'] ) $json['error'] = 'No type set.';
		elseif( ! class_exists( $json['model'], true ) ) $json['error'] = 'Invalid type set.';
		else
		{
			if( $prefix = ContentItem::get_prefix( $json['model'] ) ) $json['url'] = Htmlawed::cleanSubPath( $json['query'], $prefix );
			else $json['url'] = Htmlawed::makeSeoUrl( $json['query'] );
			if( $document = $json['model']::where( 'url', '=', $json['url'] )->first( array( 'id', 'url' ) ) )
			{
				$json['match'] = $json['model'] . '() match';
				if( $document->id == $json['id'] )
				{
					if( $document->url == $json['query'] ) $json['error'] = 'Selected url is the same as existing url.';
					elseif( $json['query'] != $json['url'] ) $json['error'] = 'SEO version of url is available.';
					else $json['error'] = 'Selected url is available.';
				}
			}
			elseif( CheckAlias::is_avail( 'url', $json['url'], array( $json['model'] ) ) )
			{
				if( $json['query'] != $json['url'] ) $json['error'] = 'SEO version of url is available.';
				else $json['error'] = 'Selected url is available.';
				$json['match'] = 'CheckAlias::is_avail() passed';
			}
			else $json['match'] = 'CheckAlias::is_avail() failed';
		}

		return json_encode( $json );
	}
));


/*
|--------------------------------------------------------------------------
| Bundle Routes
|--------------------------------------------------------------------------
|
| Edit this file to extend admin functionality per application.
|
|--------------------------------------------------------------------------
*/

$base = Bundle::path('admin') . 'routes/';
include_once( $base . '_include.php' );


/*
|--------------------------------------------------------------------------
| Composers
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/

// Admin Authorization
Route::filter( 'admin::auth', function ()
{
	if( Auth::guest() )
	{
		Log::filter( 'admin::auth' . "\t" . Request::ip() . "\t" . str_replace( URL::base(), '', URL::current() ) );
		$login_uri	 = 'admin/login';
		$current_uri = URI::current();
		$back_uri = Request::referrer();

		// If we are currently trying to login, do nothing
		if( $login_uri == $current_uri ) return;

		// If the current request is a GET request, redirect the user to this page
		// after login.
		if( Request::method() == 'GET' && $current_uri != $login_uri )
			Session::put( 'post_login', $current_uri );

		// Next see if the previous request was a GET. If so send the user to that
		// page.
		//
		// The only situation I can think of that would put us in a situation where
		// the previous request was not a GET would be a failed form submission.
		//
		// This could be solved by:
		// - Reworking all the POST routines to redirect to the GET route while
		//		flashing the input data to the session.
		// - Reworking all the form views to use the flashed data if it exists for
		//		input persistence across failed form submissions.
		//
		else
		{
			$route = Router::route( 'GET', $back_uri );
			if( ! empty( $route ) && $route != $login_uri )
				Session::put( 'post_login', $back_uri );
		}

		return Redirect::to( $login_uri );
	}
	else
	{
		$user = Auth::user();

		// Generally log admin actions
		Log::admin( $user->firstname . ' ['.$user->id.']' . "\t" . Request::ip() . "\t" . str_replace( URL::base(), '', URL::current() ) );
	}
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Route::get('/', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter( 'before', function()
{
	if( Auth::user() )
	{
		// enabled / disabled
		if( ! Auth::user()->enabled )
		{
			Auth::logout();
			return Redirect::to_route( 'homepage' );
		}

		// updates session lifetime in db ( track logged-in users )
		if( Auth::user()->session < LARAVEL_START )
		{
			DB::table( Config::get( 'auth.table', 'users' ) )
				->where('id', '=', Auth::user()->id )
				->update( array( 'session' => (int)(LARAVEL_START + ( Config::get( 'session.lifetime', 30 ) * 60 ) ) ) );
		}

		// only user type 1 can access admin
		if( Auth::user()->type != 1 && arg(0) == 'admin' )
		{
			return Response::error( '404' );
		}
	}
});

// Granular Permissions
Route::filter( 'admin::can', function( $slug )
{
	$user = Auth::user();
	if( Auth::guest() || ! $user->can( $slug ) )
	{
		if( $user ) $slug .= ' ( ' . $user->firstname . ' )';
		Log::filter( 'admin::can:' . $slug . ' failed' );
		return Redirect::to_route( 'admin_home' );
	}
});

/*
|--------------------------------------------------------------------------
| Named Views
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Validation Rules
|
| Error messages defined in application/language/en/validation.php
|--------------------------------------------------------------------------
*/


Validator::register( 'url_avail', function( $attribute, $value, $parameters )
{
	return CheckAlias::is_avail( $attribute, $value, $parameters );
});

Validator::register( 'not_prefix', function( $attribute, $value, $parameters )
{
	return CheckAlias::not_prefix( $attribute, $value, $parameters );
});


Validator::register( 'url_inactive', function( $attribute, $value, $parameters )
{
	return PathAlias::is_active_url( $value ) ? false : true;
});

Validator::register( 'url_active', function( $attribute, $value, $parameters )
{
	return PathAlias::is_active_url( $value );
});
