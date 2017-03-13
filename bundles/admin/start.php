<?php

/**
 |--------------------------------------------------------------------------
 | Auto-Loader Directories
 |--------------------------------------------------------------------------
 |
 */

Autoloader::directories(array(
	Bundle::path( 'admin' ) . 'models',
	Bundle::path( 'admin' ) . 'libraries'
));

Autoloader::namespaces(array(
    'Admin\Models' => Bundle::path( 'admin' ) . 'models',
    'Admin\Libraries' => Bundle::path( 'admin' ) . 'libraries',
));
