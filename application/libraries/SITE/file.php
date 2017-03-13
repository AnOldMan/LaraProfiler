<?php namespace SITE;

use Laravel\File as ParentClass;

class File extends ParentClass
{

	public static function upload_limit( $human = true )
	{
		$max_upload   = self::human_to_bytes( ini_get( 'upload_max_filesize' ) );
		$max_post	 = self::human_to_bytes( ini_get( 'post_max_size' ) );
		$memory_limit = self::human_to_bytes( ini_get( 'memory_limit' ) );

		Log::upload_limit(
			'upload_max_filesize:' . $max_upload
			. ' post_max_size:' . $max_post
			. ' memory_limit:' . $memory_limit
		);

		$limit = min( $max_upload, $max_post, $memory_limit );

		if ( $human )
			$limit = self::bytes_to_human( $limit );

		return $limit;
	}

	public static function get_mime( $path )
	{
		if( ! self::exists( $path ) ) return '';
		if( function_exists( 'finfo_open' ) )
		{
			$finfo = @finfo_open( FILEINFO_MIME_TYPE );
			$mime = @finfo_file( $finfo, $path );
			finfo_close( $finfo );
			return $mime;
		}
		return self::mime( self::extension( $path ) );
	}

	/**
	 * human_to_bytes - Converts human readable file size notation into bytes
	 *
	 * @param string $size
	 *
	 * @return int bytes
	 */
	public static function human_to_bytes( $size )
	{
		// Grab the last char, make sure it is lower case for the switch below
		$unit = substr( $size, -1 );
		$unit = strtolower( $unit );

		// Get the numeric value
		$value = (int) $size;

		// Multiply the value by the unit in order to get the final size
		switch ( $unit )
		{
			case 'g':
				$value *= 1024 * 1024 * 1024;
				break;

			case 'm':
				$value *= 1024 * 1024;
				break;

			case 'k':
				$value *= 1024;
				break;
		}

		return $value;
	}

	/**
	 * bytes_to_human - Converts bytes to human readable file size notation
	 *
	 * @param int $bytes
	 * @param char $unit optional - g, m, or k
	 * @param string $format optional - passed into sprintf to format the result
	 *
	 * @return string human readable file size notation
	 */
	public static function bytes_to_human( $bytes, $unit = false, $format = '%.1f' )
	{
		$giga = 1024 * 1024 * 1024;
		$mega = 1024 * 1024;
		$kilo = 1024;

		// Determin the unit of measure
		if ( !in_array( $unit, array( 'g', 'm', 'k' ) ) )
		{
			if ( $bytes >= $giga )
				$unit = 'g';
			else if ( $bytes >= $mega )
				$unit = 'm';
			else
				$unit = 'k';
		}

		// Determine the factor
		switch ( $unit )
		{
			case 'g':
				$factor = $giga;
				break;

			case 'm':
				$factor = $mega;
				break;

			default:
			case 'k':
				$factor = $kilo;
				break;
		}

		// Return the formatted result
		return sprintf( $format, $bytes / $factor ) . $unit;
	}
}
