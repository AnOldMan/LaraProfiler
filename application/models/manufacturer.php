<?php

class Manufacturer extends Eloquent {

	public static $accessible = array(
		'id',
		'film_id',
		'company_id',
		'created_at',
		'updated_at'
	);

	/* The attributes that should be excluded from to_array. */
	public static $hidden = array( 'id', 'film_id', 'created_at', 'updated_at' );

	public function film()
	{
		return $this->belongs_to( 'Film' );
	}

	public function company()
	{
		return $this->belongs_to( 'Company' );
	}

	public static function paginate( $stub )
	{
		if( $id = Company::by_stub( $stub ) )
		{
			$results = Cache::remember('manufacturer-'. $stub, function() use( $id ) {
				$films = self::where( 'company_id', '=', $id )->lists( 'film_id' );
				return $films ? Film::where_in( 'id', $films )->order_by('sorttitle')->lists('sorttitle','stub') : null;
			}, 60*24);
			if( $results ) return Paginator::make( $results, count( $results ), 20, true );
		}
	}

	public static function link( $id )
	{
		return Company::link( $id, 'manufacturer' );
	}
}
