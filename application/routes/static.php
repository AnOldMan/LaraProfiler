<?php


Route::get( '/', array(
	'as' => 'home',
	function()
	{
		return View::make('shells.main')
			->with( 'title', 'LaraProfiler' )
			->with( 'heading', 'Films' )
			->with( 'content', View::make('pages.film.list.films')
				->with( 'paginator', Film::paginate_all() )
			)
			->render();
	}
));

Route::get( 'styleguide', array(
	'as' => 'styleguide',
	function()
	{
        Config::set( 'application.profiler', false );
        Config::set( 'database.profile', false );
		return View::make('shells.main')
			->with( 'title', 'Styleguide' )
			->with( 'content', View::make('pages.styleguide') )
			->render();
	}
));
