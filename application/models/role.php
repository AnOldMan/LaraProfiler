<?php

class Role extends Eloquent {

	public static $accessible = array(
		'id',
		'film_id',
		'person_id',
		'sortorder',
		'role',
		'creditedas',
		'voice',
		'uncredited',
		'created_at',
		'updated_at'
	);

	/* The attributes that should be excluded from to_array. */
	public static $hidden = array( 'id', 'film_id', 'created_at', 'updated_at' );

	public function film()
	{
		return $this->belongs_to( 'Film' );
	}

	public function person()
	{
		return $this->belongs_to( 'Person' );
	}

	public static function paginate( $stub )
	{
		if( $id = Person::by_stub( $stub ) )
		{
			$results = Cache::remember('role-'. $stub, function() use( $id ) {
				$films = Role::where( 'person_id', '=', $id )->lists( 'film_id' );
				return $films ? Film::where_in( 'id', $films )->order_by('sorttitle')->lists('sorttitle','stub') : null;
			}, 60*24);
			if( $results ) return Paginator::make( $results, count( $results ), 20, true );
		}
	}

	public static function link( $id )
	{
		return Person::link( $id, 'role' );
	}
}
