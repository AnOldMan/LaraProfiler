<?php

class Feature extends Eloquent {

	public static $accessible = array(
		'id',
		'film_id',
		'sceneaccess',
		'comment',
		'trailer',
		'bonustrailer',
		'gallery',
		'deleted',
		'makingof',
		'prodnotes',
		'game',
		'dvdrom',
		'multiangle',
		'musicvideos',
		'interviews',
		'storyboard',
		'outtakes',
		'closedcaptioned',
		'thx',
		'pip',
		'bdlive',
		'digitalcopy',
		'other',
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