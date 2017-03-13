<?php

/**
 * True 404 Handler
 *
 * @version $Revision$
 * @since $LastChangedDate$
 * @author $Author$
 *
 */

require_once( 'paths.php' );

/**
 * Add real 404 for files that are not pages and Apache cannot find.
 * We are only here if apache could not find file, so no need to check if( !exists( file ) ) {...}
 *
 * IE: browser looking for /someimage.jpg - we should not be doing a ton of parsing, instantiating classes, db, ect.
 *
 */
$e = arg('last');
// Bundle.LaraCaptcha
if( $e == 'captcha.jpg' )
{
	$_SERVER['REQUEST_URI'] = $e;
}
// Bundle.Image
elseif( arg(1) != 'imagecache' )
{
	$e = explode( '.', $e );
	$e = empty( $e[1] ) ? false : array_pop( $e );

	if( $e && in_array(
			$e,
			array( 'jpg', 'gif', 'png', 'svg', 'ico', 'css', 'xml', 'doc', 'docx', 'xls', 'xlsx' )
		) )
	{
	print '404'; exit;
		header( 'HTTP/1.0 404 Not Found' );
		exit(0);
	}
}

// NON-LARAVEL HELPER FUNCTIONS
/**
 * useful for debugging
 *
 * @param	any_type	$obj			- what to show
 *
 *
 * @return	(none)
 */
function kpr()
{
	if( ! function_exists( 'e' ) )
	{
		chdir(__DIR__);
		if( file_exists( 'application/libraries/krumo.php' ) )
		{
			include_once( 'application/libraries/krumo.php' );
			Krumo::disabled(false);
		}
	}

	if( \krumo::disabled() ) return;

	$args = func_get_args();

	\krumo::dump( count( $args ) > 1 ? $args : $args[0] );
}

/**
 * Same as Drupal->arg()
 *
 * @param (int) $index - urlpart key
 * @param (string) $path - urlpath to parse, default current uri
 * @return (string) || (array) on $index = NULL
 */
function arg( $index = null, $path = null )
{
	static $arguments;
	if( ! isset( $arguments ) ) $arguments = array();

	if( ! isset( $path ) )
	{
		$path = isset( $_GET['param'] ) ? $_GET['param'] : $_SERVER['REQUEST_URI'];
		// Strip off hash and query string
		$path = preg_replace( '/(#|\?).+$/', '', $path );
		// trim leading/trailing
		$path = trim( $path, '/' );
	}

	if( ! isset( $arguments[$path] ) ) $arguments[$path] = explode( '/', $path );

	if( $index === null ) return $arguments[$path];

	if( $index === 'full' )
	{
		return '/' . implode( '/', $arguments[$path] );
	}

	if( $index === 'last' )
	{
		// copy array so pop doesn't affect original
		$t = $arguments[$path];
		return array_pop( $t );
	}

	return isset( $arguments[$path][$index] ) ? $arguments[$path][$index] : '';
}

/**
 * Serve image file with mime type
 *
 * @param (string) $sFileName
 */
function serveFile( $file )
{
	$ext = substr( strrchr( strtolower( $file ), '.' ), 1 );
	$aTypes = array(
		// images
		'bmp' =>	'image/bmp',
		'gif' =>	'image/gif',
		'jpg' =>	'image/jpeg',
		'png' =>	'image/png',
		'svg' =>	'image/svg+xml',
		// documents
		'pdf' =>	'application/pdf',
		'css' =>	'text/plain',
		'xml' =>	'text/xml',
		'zip' =>	'application/x-compressed',
		// media
		'avi' =>	'video/avi',
		'flv' =>	'video/x-flv',
		'mp3' =>	'audio/mpeg3',
		'mp4' =>	'video/mp4',
		'ogg' =>	'application/ogg',
		'wav' =>	'audio/wav',
		// microsoft
		'ico' =>	'image/vnd.microsoft.icon',
		'doc' =>	'application/msword',
		'dot' =>	'application/msword',
		'docx' =>	'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'dotx' =>	'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
		'docm' =>	'application/vnd.ms-word.document.macroEnabled.12',
		'dotm' =>	'application/vnd.ms-word.template.macroEnabled.12',
		'xls' =>	'application/vnd.ms-excel',
		'xlt' =>	'application/vnd.ms-excel',
		'xla' =>	'application/vnd.ms-excel',
		'xlsx' =>	'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'xltx' =>	'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
		'xlsm' =>	'application/vnd.ms-excel.sheet.macroEnabled.12',
		'xltm' =>	'application/vnd.ms-excel.template.macroEnabled.12',
		'xlam' =>	'application/vnd.ms-excel.addin.macroEnabled.12',
		'xlsb' =>	'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
		'ppt' =>	'application/vnd.ms-powerpoint',
		'pot' =>	'application/vnd.ms-powerpoint',
		'pps' =>	'application/vnd.ms-powerpoint',
		'ppa' =>	'application/vnd.ms-powerpoint',
		'pptx' =>	'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'potx' =>	'application/vnd.openxmlformats-officedocument.presentationml.template',
		'ppsx' =>	'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
		'ppam' =>	'application/vnd.ms-powerpoint.addin.macroEnabled.12',
		'pptm' =>	'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
		'potm' =>	'application/vnd.ms-powerpoint.template.macroEnabled.12',
		'ppsm' =>	'application/vnd.ms-powerpoint.slideshow.macroEnabled.12'
	);
	if( key_exists( $ext, $aTypes ) ) $mime_type = $aTypes[$ext]; else return;

	$time = filemtime ( $file );
	$mod = gmdate( 'D, j M Y H:i:s T', $time );// Date: Wed, 16 Nov 2011 16:21:07 GMT
	$size = filesize( $file );// Content-Length: 19253
	$etag = md5( $time.$size );
	$etag = '"' . substr( $etag, 0, 4 ) . '-' . substr( $etag, 4, 4 ) . '-' .  substr( $etag, 8, 7 ) . '"';// Etag "b15d-4ab3-a602714"

	// clear headers
	header( 'Expires:', true );
	header( 'Cache-Control:', true );
	header( 'Pragma:', true );
	header( 'X-Powered-By:', true );

	// set headers
	header( "Last-Modified: $mod", true );
	header( "Etag: $etag", true );
	header( "Accept-Ranges: bytes", true );
	header( "Content-Length: $size", true );
	header( "Content-Type: $mime_type", true );

	if( function_exists( 'apache_request_headers' ) )
	{
		if( $headers = apache_request_headers() )
		{
			if( isset( $headers['If-None-Match'] ) )
			{
				if( $headers['If-None-Match'] == $etag ) notModified();
			}
			elseif( isset( $headers['If-Modified-Since'] ) ) if( $headers['If-Modified-Since'] == $mod ) notModified();
		}
	}

	print file_get_contents( $file );
	exit(0);
}

function notModified()
{
	header( 'x', true, 304 );
	exit(0);
}
