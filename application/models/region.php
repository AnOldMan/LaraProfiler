<?php

class Region extends Eloquent {

	public static $accessible = array(
		'id',
		'film_id',
		'region',
		'created_at',
		'updated_at'
	);
}
