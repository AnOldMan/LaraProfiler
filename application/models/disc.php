<?php

class Disc extends Eloquent {

	public static $accessible = array(
		'id',
		'film_id',
		'disc',
		'description',
		'disc_id',
		'label',
		'dual_layer',
		'dual_sided',
		'location',
		'slot',
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
