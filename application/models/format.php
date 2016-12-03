<?php

class Format extends Eloquent {

	public static $accessible = array(
		'id',
		'film_id',
		'ratio',
		'video',
		'panandscan',
		'fullframe',
		'widescreen',
		'anamorph',
		'dualside',
		'duallayer',
		'created_at',
		'updated_at'
	);

	/* The attributes that should be excluded from to_array. */
	public static $hidden = array( 'id', 'film_id', 'created_at', 'updated_at' );

	public function film()
	{
		return $this->belongs_to( 'Film' );
	}
}
