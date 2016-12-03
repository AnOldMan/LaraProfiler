<?php

class Genre extends Eloquent {

	public static $accessible = array(
		'id',
		'film_id',
		'phrase_id',
		'created_at',
		'updated_at'
	);
	public static $activelist = false;
	public static $selectlist = false;

	/* The attributes that should be excluded from to_array. */
	public static $hidden = array( 'id', 'film_id', 'created_at', 'updated_at' );

	public function film()
	{
		return $this->belongs_to( 'Film' );
	}

	public function phrase()
	{
		return $this->belongs_to( 'Phrase' );
	}

	public static function paginate( $stub )
	{
		$id = Phrase::by_stub( $stub );
		if( $id && key_exists( $id, self::active() ) )
		{
			$results = Cache::remember('genre-'. $stub, function() use( $id ) {
				$films = self::where( 'phrase_id', '=', $id )->lists( 'film_id' );
				return Film::where_in( 'id', $films )->order_by('sorttitle')->lists('sorttitle','stub');
			}, 60*24);
			if( $results ) return Paginator::make( $results, count( $results ), 20, true );
		}
	}

	public static function active()
	{
		if( self::$activelist === false )
		{
			self::$activelist = Cache::remember('genre-active', function(){
				if( $genres = Genre::group_by( 'phrase_id' )->lists( 'phrase_id' ) )
				{
					$results = Phrase::where_in( 'id', $genres )->order_by( 'stub' )->lists( 'stub', 'id' );
				}
				return $results ? $results : array();
			}, 60*24);
		}
		return self::$activelist;
	}

	public static function select()
	{
		if( self::$selectlist === false )
		{
			self::$selectlist = Cache::remember('genre-select', function(){
				$list = Genre::active();
				$results = $list
					? Phrase::where_in( 'id', array_keys( $list ) )->order_by( 'stub' )->lists( 'phrase', 'stub' )
					: false;
				return $results ? $results : array();
			}, 60*24);
		}
		return self::$selectlist;
	}

	public static function link( $id )
	{
		$a = self::active();
		$stub = empty( $a[$id] ) ? '' : $a[$id];
		$a = self::select();
		$txt = empty( $a[$stub] ) ? '' : $a[$stub];
		if( $stub && $txt )
		{
			return self::format_link( $stub, $txt );
		}
		return '';
	}

	public static function format_link( $stub, $txt )
	{
		return '<a class="genre-link" href="' . URI::to_route( 'genre', array( $stub ), false ) . '">' . $txt . '</a>';
	}
}
