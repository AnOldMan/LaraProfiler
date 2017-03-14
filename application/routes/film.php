<?php

Route::get( 'film/(:any)', array(
	'as' => 'film',
	function( $stub )
	{
		if( $film = Film::by_stub( $stub ) )
		{
			return View::make( 'shells.main' )
				->with( 'title', empty( $film['detail']['title'] ) ? 'LaraProfiler' : $film['detail']['title'] )
				->with( 'heading', '' )
				->with( 'content', View::make( 'pages.film.details' )
					->with( 'film', $film )
				)
				->render();
		}

		return Response::error('404');
	}
));

Route::get( 'genre/(:any)', array(
	'as' => 'genre',
	function( $stub )
	{
		$select = Genre::select();
		if( ! empty( $select[$stub] ) )
		{
			$current = $select[$stub];
			if( $results = Genre::paginate( $stub ) )
			{
				return View::make( 'shells.main' )
					->with( 'title', 'LaraProfiler' )
					->with( 'heading', $select[$stub] )
					->with( 'content', View::make('pages.film.list.genres')
						->with( 'paginator', $results )
						->with( 'select', $select )
						->with( 'stub', $stub )
					)
					->render();
			}
		}

		return Response::error('404');
	}
));

Route::get( 'role/(:any)', array(
	'as' => 'role',
	function( $stub )
	{
		if( $results = Role::paginate( $stub ) )
		{
			return View::make( 'shells.main' )
				->with( 'title', 'LaraProfiler' )
				->with( 'heading', Person::name_by_stub( $stub ) )
				->with( 'content', View::make('pages.film.list.paginator')
					->with( 'paginator', $results )
				)
				->render();
		}

		return Response::error( '404' );
	}
));

Route::get( 'credit/(:any)', array(
	'as' => 'credit',
	function( $stub )
	{
		if( $results = Credit::paginate( $stub ) )
		{
			return View::make( 'shells.main' )
				->with( 'title', 'LaraProfiler' )
				->with( 'heading', Person::name_by_stub( $stub ) )
				->with( 'content', View::make('pages.film.list.paginator')
					->with( 'paginator', $results )
				)
				->render();
		}

		return Response::error( '404' );
	}
));

Route::get( 'studio/(:any)', array(
	'as' => 'studio',
	function( $stub )
	{
		if( $results = Studio::paginate( $stub ) )
		{
			return View::make( 'shells.main' )
				->with( 'title', 'LaraProfiler' )
				->with( 'heading', Company::name_by_stub( $stub ) )
				->with( 'content', View::make('pages.film.list.paginator')
					->with( 'paginator', $results )
				)
				->render();
		}

		return Response::error( '404' );
	}
));

Route::get( 'manufacturer/(:any)', array(
	'as' => 'manufacturer',
	function( $stub )
	{
		if( $results = Manufacturer::paginate( $stub ) )
		{
			return View::make( 'shells.main' )
				->with( 'title', 'LaraProfiler' )
				->with( 'heading', Company::name_by_stub( $stub ) )
				->with( 'content', View::make('pages.film.list.paginator')
					->with( 'paginator', $results )
				)
				->render();
		}

		return Response::error( '404' );
	}
));