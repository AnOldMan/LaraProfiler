<?php

class Audio extends Eloquent {

	public static $accessible = array(
		'id',
		'film_id',
		'content_phrase',
		'format_phrase',
		'created_at',
		'updated_at'
	);

	/* The attributes that should be excluded from to_array. */
	public static $hidden = array( 'id', 'film_id', 'created_at', 'updated_at' );

	public function film()
	{
		return $this->belongs_to( 'Film' );
	}

	public function content()
	{
		return $this->belongs_to( 'Phrase', 'content_phrase' );
	}

	public function format()
	{
		return $this->belongs_to( 'Phrase', 'format_phrase' );
	}
}
