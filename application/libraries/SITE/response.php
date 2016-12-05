<?php namespace SITE;

use Laravel\Response as ParentClass,
	\Config;

class Response extends ParentClass
{
	public static function exit404()
	{
		header_remove();
		header( 'x', TRUE, 404 );
		exit(0);
	}

	/**
	 * Create a new download response instance.
	 *
	 * <code>
	 *		// Create a download response to a given file
	 *		return Response::serveFileContent('my content','my_name.ext');
	 *
	 *		// Create a download response with a given mime type
	 *		return Response::serveFileContent('<?xml?>','my_name.xml','text/xml');
	 * </code>
	 *
	 * @param  string    $path
	 * @param  string    $name
	 * @param  array     $headers
	 * @return Response
	 */
	public static function serveFileContent( $content, $name, $mime = '', $headers = array() )
	{
		Config::set( 'application.profiler', false );
		Config::set( 'database.profile', false );

		$mime = empty( $mime ) ? File::mime( File::extension( $name ) ) : $mime;
		// We'll set some sensible default headers, but merge the array given to
		// us so that the developer has the chance to override any of these
		// default headers with header values of their own liking.
		$headers = array_merge(array(
			'Content-Description'       => 'File Transfer',
			'Content-Type'              => $mime,
			'Content-Transfer-Encoding' => 'binary',
			'Expires'                   => 0,
			'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
			'Pragma'                    => 'public',
			'Content-Length'            => strlen( $content ),
		), $headers);

		// Once we create the response, we need to set the content disposition
		// header on the response based on the file's name. We'll pass this
		// off to the HttpFoundation and let it create the header text.
		$response = new static( $content, 200, $headers );

		// If the Content-Disposition header has already been set by the
		// merge above, then do not override it with our generated one.
		if ( ! isset( $headers['Content-Disposition'] ) ) {
			$d = $response->disposition( $name );
			$response = $response->header( 'Content-Disposition', $d );
		}

		return $response;
	}

	/**
	 * INTENDED FOR ONCE-OFF SERVING OF IMAGE UNTIL IT MAKES IT INTO FILESYSTEM
	 * HAS NO Etag OR ANY OTHER LAST-MODIFIED 304 OPTION[S]
	 *
	 * (FILE SHOULD BE THERE NEXT REQUEST)
	 */
	public static function serveImage( $sFileOut, $sFileName = '', $sMimeType = '' )
	{
		if( headers_sent() ) return;
		if( ! file_exists( $sFileOut ) ) self::exit404();
		if( empty( $sFileName ) ) $sFileName = basename( $sFileOut );
		if( empty( $sMimeType ) ) $sMimeType = File::mime( File::extension( $sFileOut ) );

		if( empty( $sMimeType ) ) self::exit404();

		Config::set('application.profiler', false);
		Config::set('database.profile', false);

		$headers = array(
			'Accept-Ranges' => 'bytes',
			'Access-Control-Allow-Headers' => 'X-File-Name, X-File-Type, X-File-Size',
			'Access-Control-Allow-Methods' => 'OPTIONS, HEAD, GET, POST, PUT, DELETE',
			'Access-Control-Allow-Origin' => '*',
			'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
			'Content-Disposition' => 'inline; filename="' . $sFileName . '"',
			'Content-Length' => @filesize( $sFileOut ),
			'Content-Type' => $sMimeType,
			'Last-Modified' => gmdate( 'r', @filemtime( $sFileOut ) ),
			'Expires' => '',
			'Pragma' => 'no-cache',
			'X-Content-Type-Options' => 'nosniff',
			'X-Powered-By' => ''
		);

		return new static( file_get_contents( $sFileOut ), 200, $headers );
	}

	/**
	 * INTENDED FOR ONCE-OFF SERVING OF IMAGE RESOURCE
	 * I.E. : $img = imagecreatetruecolor ( 50,50 );
	 *        ...
	 *        \Response::serveImageResource( $img, 90 );
	 *
	 * HAS NO Etag OR ANY OTHER LAST-MODIFIED 304 OPTION[S]
	 *
	 */
	public static function serveImageResource( $oImageResource, $jpeg_quality = null, $name = '' )
	{
		if( headers_sent() || empty( $oImageResource ) ) return;

		if( $name ) $sMimeType = File::mime( File::extension( $name ) );

		if( empty( $sMimeType ) )
		{
			if( function_exists( 'imagejpeg' ) ) $sMimeType = 'image/jpeg';
			elseif( function_exists( 'imagegif' ) ) $sMimeType = 'image/gif';
			else if( function_exists( 'imagepng' ) ) $sMimeType = 'image/x-png';
		}

		if( empty( $sMimeType ) ) self::exit404();

		Config::set('application.profiler', false);
		Config::set('database.profile', false);

	    ob_start();
	    switch ( $sMimeType )
	    {
			case 'image/gif':
				imagegif( $oImageResource );
				break;
			case 'image/x-png':
				imagepng( $oImageResource );
				break;
			default:
				imagejpeg( $oImageResource, null, empty( $jpeg_quality ) ? 90 : $jpeg_quality );
				break;
		}
		$image = ob_get_contents();
		ob_end_clean();

		$headers = array(
			'Accept-Ranges' => 'bytes',
			'Access-Control-Allow-Headers' => 'X-File-Name, X-File-Type, X-File-Size',
			'Access-Control-Allow-Methods' => 'OPTIONS, HEAD, GET, POST, PUT, DELETE',
			'Access-Control-Allow-Origin' => '*',
			'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
			'Content-Disposition' => 'inline; filename="' . $name . '"',
			'Content-Length' => strlen( $image ),
			'Content-Type' => $sMimeType,
			'Expires' => '',
			'Pragma' => 'no-cache',
			'X-Content-Type-Options' => 'nosniff',
			'X-Powered-By' => ''
		);

		return new static( $image, 200, $headers );
	}
}
