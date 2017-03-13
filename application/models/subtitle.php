<?php

class Subtitle extends Eloquent {

	public static $accessible = array(
		'id',
		'film_id',
		'phrase',
		'created_at',
		'updated_at'
	);

	/* The attributes that should be excluded from to_array. */
	public static $hidden = array( 'id', 'film_id', 'created_at', 'updated_at' );

	public function film()
	{
		return $this->belongs_to( 'Film' );
	}

	public function get_phrase()
	{
		return Phrase::by_id( $this->get_attribute( 'phrase' ) );
	}

	public function set_phrase()
	{
		$current = $this->get_attribute( 'phrase' );
		if( is_numeric( $current ) )
		{
			$this->set_attribute( 'phrase', Phrase::by_phrase( $current ) );
		}
	}
}
