<?php

/**
 * Create an imagestyle-type image a-la Drupal
 *
 * files/images/imagecache/[Operation-]W[xH]/original.ext
 *    [Operation-]: type of imagestyle, optional, values 'crop', 'scale', 'fit' || '' (default)
 *    W: desired width, required
 *    xH: desired height, optional (default=width)
 *    original.ext: required - must be a file in application.files_path/images/photos directory
 *
 * feel free to build your own custom imagestyle-type [Operation-]
 *
 */
Route::get('files/imagecache/(:all)', function( $path )
{
	Config::set( 'application.profiler', false );
	Config::set( 'database.profile', false );

	/*
	 * files/imagecache/operation[-w[xh[xt[xr[xb[xl]]]]]][-subdir]/original.file
	 * hyphen separated options
	 * [0] -> operation (string)
	 * [1] -> w width (int), required when operation not in config
	 *        h height (int), optional = w if undefined
	 *        t crop offset (float), w required. CSS [all]/[tb lr]/[t lr b]/[t r b l] format as percent 0.5
	 * [2] -> source subdir (optional, use underscore for sub/sub)
	 * 
	 * files/imagecache/fit-75-myfiles/original.jpg
	 * will make a 75x75 scale of source_path/myfiles/original.jpg
	 * 
	 * files/imagecache/crop-75-myfiles/original.jpg
	 * will make a 75x75 crop of source_path/myfiles/original.jpg
	 * 
	 * files/imagecache/crop-75x100x0.4-myfiles/original.jpg
	 * will make a 75x100 crop of source_path/myfiles/original.jpg starting at x=40%, y=40%
	 */
	$parts = explode( '/', $path );
	if( count( $parts ) != 2 ) return Response::exit404();
	$config = Config::get( 'image::image' );
	if( ! $config ) return Response::exit404();
	
	$default_sub = 'photos';
	
	list( $type, $file ) = $parts;
	
	$photo = array(
		'image' => '',
		'width' => 0,
		'height' => 0,
		'top' => 0,
		'right' => 0,
		'bottom' => 0,
		'left' => 0
	);
	
	// configured in 
	if( ! empty( $config[$type] ) )
	{
		$operation = $config[$type]['operation'];
		$w = $config[$type]['width'];
		$h = $config[$type]['height'];
		$subdir = $config[$type]['subdir'];
	}
	else
	{
		$a = explode( '-', $type );
		$operation = array_shift( $a );
		$subdir = count( $a ) > 1 ? array_pop( $a ) : $default_sub;
		$a = array_shift( $a );
		$a = explode( 'x', $a );
		$w = (int)array_shift( $a );
		$h = $a ? (int)array_shift( $a ) : $w;
		if( $a )
		{
			// support CSS-style shorthand
			switch( count( $a ) )
			{
				case 1:
					$photo['top'] = $photo['left'] = $photo['bottom'] = $photo['right'] = (float)array_shift( $a );
					break;
				case 2:
					$photo['top'] = $photo['bottom'] = (float)array_shift( $a );
					$photo['left'] = $photo['right'] = (float)array_shift( $a );
					break;
				case 3:
					$photo['top'] = (float)array_shift( $a );
					$photo['left'] = $photo['right'] = (float)array_shift( $a );
					$photo['bottom'] = (float)array_shift( $a );
					break;
				case 4:
					$photo['top'] = (float)array_shift( $a );
					$photo['right'] = (float)array_shift( $a );
					$photo['bottom'] = (float)array_shift( $a );
					$photo['left'] = (float)array_shift( $a );
					break;
			}
			// sanity check
			foreach( array( 'top', 'right', 'bottom', 'left' ) as $k )
			{
				if( $photo[$k] > 1 ) $photo[$k] = $photo[$k] / 100;
				if( $photo[$k] > 1 || $photo[$k] < 0 )
				{
					$photo['top'] = $photo['left'] = $photo['bottom'] = $photo['right'] = 0;
					break;
				}
			}
		}
	}
	
	if( empty( $h ) ) $h = $w;
	if( empty( $subdir ) ) $subdir = $default_sub;

	if( ! $h || ! $w ) return Response::exit404();

	$photo['image'] = $config['source_path'] . $subdir . DS . $file;

	if( ! file_exists( $photo['image'] ) ) return Response::exit404();

	$dest = $config['cache_path'] . $type . DS;

	// this does it's own is_dir() test
	File::mkdir( $dest );
	$dest .= $file;

	$oImageCache = new ImageCache( $photo['image'], $dest );

	$bSuccess = false;
	switch( $operation )
	{
		case 'crop':
			$bSuccess = $oImageCache->crop( $w, $h, $photo );
			break;

		case 'scale':
			$bSuccess = $oImageCache->scale( $w, $h, $photo );
			break;

		case 'fit':
			$bSuccess = $oImageCache->thumb( $w, $h, $photo );
			break;

		default:
			return Response::exit404();
	}

	if( $bSuccess ) return Response::serveImage( $dest );
	return Response::exit404();
});
