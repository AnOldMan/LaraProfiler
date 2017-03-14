<?php

class Credit extends Eloquent {

	public static $accessible = array(
		'id',
		'film_id',
		'person_id',
		'sortorder',
		'type_phrase',
		'sub_type_phrase',
		'creditedas',
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
			$results = Cache::remember('credit-'. $stub, function() use( $id ) {
				$films = self::where( 'person_id', '=', $id )->lists( 'film_id' );
				return Film::where_in( 'id', $films )->order_by('sorttitle')->lists('sorttitle','stub');
			}, 60*24);
			if( $results ) return Paginator::make( $results, count( $results ), 20, true );
		}
	}

	public function get_type_phrase()
	{
		return Phrase::by_id( $this->get_attribute( 'type_phrase' ) );
	}

	public function set_type_phrase()
	{
		$current = $this->get_attribute( 'type_phrase' );
		if( is_numeric( $current ) )
		{
			$this->set_attribute( 'type_phrase', Phrase::by_phrase( $current ) );
		}
	}

	public function get_sub_type_phrase()
	{
		return Phrase::by_id( $this->get_attribute( 'sub_type_phrase' ) );
	}

	public function set_sub_type_phrase()
	{
		$current = $this->get_attribute( 'sub_type_phrase' );
		if( is_numeric( $current ) )
		{
			$this->set_attribute( 'sub_type_phrase', Phrase::by_phrase( $current ) );
		}
	}

	public static function link( $id )
	{
		return Person::link( $id, 'credit' );
	}
}
