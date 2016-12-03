<?php

if( ! Auth::guest() && arg(0) == 'admin' && Auth::user()->type == 'User' )
{
	print View::make( 'admin::page')
			->with( 'leftmenu', View::make( 'admin::partials.menu' ) )
			->with( 'page_title', '404 - Not Found' )
			->with( 'content', View::make('admin::partials.404' ) );
	return;
}

print View::make('shells.main')
	->with( 'title', '404 - Not Found' )
	->with( 'heading', 'Server Error: 404 (Page Not Found)' )
	->with( 'content', View::make('pages.404') )
	->render();