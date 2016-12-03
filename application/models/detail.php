<?php

class Detail extends Eloquent {

	public static $accessible = array(
		'id',
		'film_id',
		'locality',
		'media_dvd',
		'media_hddvd',
		'media_bluray',
		'media_custom',
		'upc',
		'collectionnumber',
		'collectiontype',
		'title',
		'disttrait',
		'originaltitle',
		'origin',
		'prodyear',
		'released',
		'runningtime',
		'casetype',
		'slipcover',
		'srpid',
		'srp',
		'overview',
		'easteregg',
		'purchcurrencyid',
		'purchprice',
		'purchdate',
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
