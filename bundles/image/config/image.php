<?php

return array(
	'source_path' => Config::get( 'application.files_path' ) . 'images' . DS,
	'cache_path' => Config::get( 'application.files_path' ) . 'imagecache' . DS,
	'cache_url' => Config::get( 'application.files_url' ) . 'imagecache/',
	'cover' => array(
		'operation' => 'fit',
		'width' => 420,
		'height' => 600,
		'subdir' => 'cover'
	),
	'coverthumb' => array(
		'operation' => 'fit',
		'width' => 420,
		'height' => 600,
		'subdir' => 'cover'
	)
);
