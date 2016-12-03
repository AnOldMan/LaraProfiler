<?php

Route::get( 'film/(:any)', array(
	'as' => 'film',
	function( $stub )
	{
		if( $film = Film::by_stub( $stub ) )
		{
			return View::make('shells.main')
				->with( 'title', 'LaraProfiler' )
				->with( 'heading', $film['detail']['title'] )
				->with( 'content', View::make('pages.film.details')
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
				return View::make('shells.main')
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

Route::get( 'person/(:any)', array(
	'as' => 'person',
	function( $stub )
	{
		if( $results = Person::paginate( $stub ) )
		{
			kpr( $results );exit;
		}

		return Response::error('404');
	}
));

Route::get( 'company/(:any)', array(
	'as' => 'company',
	function( $stub )
	{
		if( $results = Company::paginate( $stub ) )
		{
			kpr( $results );exit;
		}
		
		return Response::error('404');
	}
));