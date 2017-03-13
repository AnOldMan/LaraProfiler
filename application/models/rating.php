<?php

class Rating extends Eloquent {

	public static $accessible = array(
		'id',
		'film_id',
		'system',
		'rating',
		'age',
		'variant',
		'details',
		'stub',
		'created_at',
		'updated_at'
	);

	/* The attributes that should be excluded from to_array. */
	public static $hidden = array( 'id', 'film_id', 'created_at', 'updated_at' );

	public function film()
	{
		return $this->belongs_to( 'Film' );
	}

	public function seoStub()
	{
		$this->stub = htmlawed::makeSeoSlug( $this->system . '-' . $this->rating, '-' );
	}

	public function save()
	{
		if( empty( $this->stub ) ) $this->seoStub();
		parent::save();
	}
}
