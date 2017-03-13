<?php

// List Users
Route::get( 'admin/users', array(
	'as' => 'user_list',
	'before' => 'admin::auth|admin::can:edit_user',
	function()
	{
		$model = Config::get( 'auth.model' ) . 'List';

		$fields = array();
		// Search field
		$fields[] = View::make( 'admin::field.text' )
			->with( 'name', 'search' )
			->with( 'value', $model::get( 'search' ) );

		// Type
		$fields[] = View::make( 'admin::field.select' )
			->with( 'label', 'Type' )
			->with( 'name', 'type' )
			->with( 'options', array( '' => '--- Any ---')  + User::types() )
			->with( 'value', $model::get( 'type' ) );

		// Pagesize select
		$paginator_select = Config::get( 'application.paginator_select', array() );
		$fields[] = View::make( 'admin::field.select' )
			->with( 'label', 'Per Page' )
			->with( 'name', 'pagesize' )
			->with( 'options', $paginator_select )
			->with( 'value', $model::get( 'pagesize' ) );

		// Build the filter form
		$form = View::make( 'admin::field.list-filters' )
			->with( 'fields', $fields );

		return View::make( 'admin::page' )
			->with( 'content', View::make( 'admin::list.user' )
				->with( 'paginator', $model::items() )
				->with( 'form', $form )
			)
			->with( 'page_title', 'Users' );
	}
));

Route::get( 'admin/users/online', array(
	'as' => 'user_online',
	'before' => 'admin::auth',
	function()
	{
		return View::make( 'admin::page' )
			->with( 'content', View::make( 'admin::list.online' )
				->with( 'users', User::where( 'session', '>', time() )
					->get()
				)
			)
			->with( 'page_title', 'Users Online' );
	}
));

// Edit User
Route::get( 'admin/user/edit/(:num?)', array(
	'as' => 'user_edit',
	'before' => 'admin::auth|admin::can:edit_user',
	function( $id = 0 )
	{
		$model = Config::get( 'auth.model' );

		// Are we permitted to edit this user
		if( ! $model::auth_can_edit_user( $id ) )
		{
			return Redirect::to_route( 'admin_home' );
		}

		$item = $model::find( (int)$id ) ?: new $model;

		$template = 'user';
		if( $item->exists )
		{
			$title = ( Auth::user()->id == $id )
				? 'Edit Personal Profile'
				: 'Edit User: ' . $item->firstname . ' ' . $item->lastname;

			if( $item->type == $item::setting_type( 'listing' ) )
			{
				$template .= '-listing';
				$title = 'Edit User: ' . trim( $item->email, '-' );
			}
			elseif( $item->type == $item::setting_type( 'site' ) )
			{
				$template .= '-site';
			}
		}
		else $title = 'Add User';

		return View::make( 'admin::page' )
			->with( 'content', View::make( 'admin::form.' . $template )
				->with( 'user', $item )
			)
			->with( 'page_title', $title );
	}
));

// Save User
Route::post( 'admin/user/save', array(
	'as' => 'user_save',
	'before' => 'admin::auth|admin::can:edit_user',
	function()
	{
		$id = Input::get( 'id', 0 );

		// Are we permitted to edit this user
		if( ! User::auth_can_edit_user( $id ) )
		{
			return Redirect::to_route( 'admin_home' );
		}

		$model = Config::get( 'auth.model' );

		$item = $model::find( (int)$id ) ?: new $model;

		// Fill with input, checkboxes require special consideration
		$item->fill( Input::get() );
		$item->enabled = Input::get( 'enabled', 0 );
		$item->purge( 'send_email' );

		// Only those permitted to edit all
		// users can set the group_id and enabled
		if( ! Auth::user()->can( 'edit_user' ) )
		{
			$item->purge( 'group_id' );
			$item->purge( 'enabled' );
		}

		// Validate and redirect on error
		$validator = $item->validator();
		if( $validator->fails() )
		{
			Input::flash();

			return Redirect::to_route( 'user_edit', $id )
					->with_errors( $validator );
		}

		// send email to new user
		if( ! $item->exists && Input::get( 'send_email', 0 ) == 1 )
		{
			Bundle::start( 'messages' );

			$domain = preg_replace( '/^http(s|):\/\//', '', URL::base() );

			$template = $item->type == 1 ? 'admin::email.user-created' : 'admin::email.user-created-site';
			$body = View::make( $template )
				->with( 'domain', $domain )
				->with( 'user', $item )
				->with( 'admin', Auth::user() )
				->render();

			Message::to( $item->email )
				->from( 'admin@' . $domain )
				->subject( 'Account created on ' . $domain )
				->body( $body )
				->html( true )
				->send();

			$item->reset = Hash::make( $item->password );
			$item->password = User::random_password();
		}

		$item->save();

		return Redirect::to_route( 'user_list' );
	}
));

// User Confirm Delete
Route::get( 'admin/user/delete/(:num)', array(
	'as' => 'user_confirm_delete',
	'before' => 'admin::auth|admin::can:edit_user',
	function( $id = 0 )
	{
		$model = Config::get( 'auth.model' );

		$item = $model::find( (int)$id ) ?: new $model;

		if( ! $item->exists ) return Redirect::to_route( 'user_list' );

		return View::make( 'admin::page' )
			->with( 'content', View::make( 'admin::partials.delete' )
				->with( 'name', $item->firstname.' '.$item->lastname )
				->with( 'id', $item->id )
				->with( 'type', 'user' )
			)
			->with( 'page_title', 'Remove ' . $item->name . '?' );
	}
));

// User Delete
Route::post( 'admin/user/delete', array(
	'as' => 'user_delete',
	'before' => 'admin::auth|admin::can:edit_user',
	function()
	{
		$model = Config::get( 'auth.model' );

		$item = $model::find( (int)Input::get( 'id', 0 ) ) ?: new $model;
		if( $item->exists ) $item->delete();

		return Redirect::to_route( 'user_list' );
	}
));

/*
|--------------------------------------------------------------------------
| Composers
|--------------------------------------------------------------------------
*/

View::composer( 'admin::form.user', function( $view )
{
	$view->with( 'groups', Group::lists( 'name', 'id' ) );
});

/*
|--------------------------------------------------------------------------
| Named Views
|--------------------------------------------------------------------------
*/

View::name( 'admin::form.user', 'admin-form-user' );
