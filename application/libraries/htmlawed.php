<?php

/**
 * htmLawed 1.1.14 converted to class and extra helper functions added
 *
 * @version $Revision$
 * @since $LastChangedDate$
 * @author $Author$
 *
 */

/**
 * htmLawed 1.2.beta.7, 19 January 2015
 * Copyright Santosh Patnaik
 * Dual licensed with LGPL 3 and GPL 2+
 * A PHP Labware internal utility - http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/beta
 */

class htmlawed
{
	// htmLawed::law($in, $config, $spec);
	public static function law($t, $C=1, $S=array())
	{
		$oLawed = new htmLawed();
		return $oLawed->lawed($t, $C, $S);
	}

	/**
	 * cleans up string after an editor post
	 *
	 * @param	(string)	$string			- string to clean
	 *
	 * @return	(string)
	 */
	public static function cleanupContent( $string )
	{
		$string = trim( $string );
		if( ! $string ) return '';
		// remove environment paths
		$string = self::clean_environments( $string );
		if( ! $string ) return '';
		// remove urlencoding
		$string = urldecode( $string );
		// remove script
		$string = preg_replace( '~<\s*\bscript\b[^>]*>(.*?)<\s*\/\s*script\s*>~is', '', $string );
		// remove css
		$string = preg_replace( '~<\s*\bstyle\b[^>]*>(.*?)<\s*\/\s*style\s*>~is', '', $string );
		// remove comments
		$string = preg_replace( '/<!--(.*?)-->/s', '', $string );
		if( ! $string ) return '';
		// grab pre - exempt from further beautification
		if( preg_match_all( '/<pre[^>]*>(.*?)<\/pre>/is', $string, $prematches ) )
		{
			// remove empties
			foreach( $prematches[1] as $key => $pre ) if( !trim( $pre ) )
			{
				$string = str_replace( $prematches[0][$key], '', $string );
				unset( $prematches[1][$key] );
			}
			foreach( $prematches[1] as $key => $pre ) $string = str_replace( $pre, "[prematch-$key]", $string );
		}
		// remove tabs ( editor adds them to 'beautify' html )
		$string = str_replace( "\t", ' ', $string );
		// clear excess whitespace
		$string = preg_replace( '/\s+/', ' ', $string );
		// strips newlines from start of string
		$ord = ord( $string );
		while( $ord == 10 || $ord == 13 )
		{
			$string = substr( $string, 1 );
			$ord = ord( $string );
			if( strlen( $string ) < 2 ) break;
		}
		// strips newlines from end of string
		$ord = ord( substr( $string, -1, 1 ) );
		while( $ord == 10 || $ord == 13 )
		{
			$string = substr( $string, 0, -1 );
			$ord = ord( substr( $string, -1, 1 ) );
			if( strlen( $string ) < 2 ) break;
		}
		if( stristr( $string, 'mso' ) ) $string = self::cleanMSO( $string );
		$string = self::beautify( $string, 7 );
		// re-insert pre content
		if( ! empty( $prematches[1] ) ) foreach( $prematches[1] as $key => $pre ) $string = str_replace( "[prematch-$key]", $pre, $string );
		return self::purify( $string );
	}

	public static function indent( $string, $tabcount = 0 )
	{
		$tabcount = (int)$tabcount;
		if( ! $tabcount ) return $string;
		$tabs = '';
		for( $i = 0; $i < $tabcount; $i++ ) $tabs .= "\t";
		$lines = explode( "\n", $string );
		foreach( $lines as &$line ) if( trim( $line ) ) $line = $tabs . $line; else $line = '';
		return implode( "\n", $lines );
	}

	/**
	 * Attempts to strip MSO ( MicroSoftOffice ) formatting from a string
	 *
	 * @param	(string)	$string			- string to clean
	 *
	 * @return	(string)
	 */
	public static function cleanMSO( $string )
	{
		// remove script[s]
		$string = preg_replace( '/(<script[^>]*>[^<]*<\/script>)/Uis', '', $string );
		// remove style[s]
		$string = preg_replace( '/(<style[^>]*>[^<]*<\/style>)/Uis', '', $string );
		// remove meta[s]
		$string = preg_replace( '/<meta([^>].*)>/Uis', '', $string );
		$string = str_ireplace( '</meta>', '', $string );
		// remove link[s]
		$string = preg_replace( '/<link([^>].*)>/Uis', '', $string );
		$string = str_ireplace( '</link>', '', $string );
		// remove comment[s] ( <!--[if gte mso xx]><xml> ... <![endif]--> )
		$string = preg_replace( '/<!--(.*)-->/Uis', '', $string );
		// remove mso-specific tags
		$string = preg_replace( '/class="Mso([^"].*)"/Uis', '', $string );
		$string = preg_replace( '/mso-([^;].*);/Uis', '', $string );
		$string = preg_replace( '/mso-([^"].*)"/Uis', '"', $string );
		// remove mso class
		$string = str_ireplace( '<o:p>', '<p>', $string );
		$string = str_ireplace( 'margin: 0in 0in 0pt;', '', $string );
		$string = str_ireplace( '</o:p>', '</p>', $string );
		// fix font styles
		$string = str_ireplace( '&quot;', "'", $string );
		// clear excess whitespace
		$string = preg_replace( '/\s+/', ' ', $string );
		$string = str_replace( " \n", "\n", $string );
		// clear empty styles
		$string = str_ireplace( 'style=" "', '', $string );
		$string = str_ireplace( 'style=""', '', $string );
		// strips newlines from start of string
		$ord = ord( $string );
		while( $ord == 10 || $ord == 13 || $ord == 32 )
		{
			$string = substr( $string, 1 );
			$ord = ord( $string );
			if( strlen( $string ) < 2 ) break;
		}
		if( substr( $string, 0, 4 ) == '</p>' ) $string = substr( $string, 4 );
		return $string;
	}

	/**
	 * Uses htmLawed ( php version of tidy ) to clean and format html
	 *
	 * @param	(string)	$string			- string to clean
	 * @param	(int)		$tabcount		- indentaion tabs
	 *
	 * @return	(string)
	 */
	public static function beautify( $string, $tabcount = false )
	{
		// compress
		$string = str_replace( "\r", '', $string );
		$a = explode( "\n", $string );
		$out = ' ';
		foreach( $a as $r )
		{
			$r = trim( $r );
			if( $r == '' ) continue;
			if( substr( $r, 0, 1 ) != '<' ) if( substr( $out, -1, 1 ) != '>' ) $r = ' ' . $r;
			$out .= $r;
		}
		$out = trim( $out );
		if( !$out ) return '';
		// clean invalid html
		if( get_magic_quotes_gpc() ) $out = stripcslashes( $out );
		// htmLawed works best if everything is in a wrapper
		$in = '<div>' . $out . '</div>';
		$config = array(
			'tidy' => '1t1n',
			'hook_tag' => 'filter_tag_callback'
		);
		$spec = 'img=-width,-height,-style';
		$out = self::law( $in, $config, $spec );
		// remove wrapper
		$a = explode( "\n", $out );
		//   newline			 <div>   ... ...  </div>	   newline
		if( count($a) > 4 )
		{
			array_shift($a); array_shift($a); array_pop($a); array_pop($a);
		}
		// increase/decrease tabination
		if( is_numeric( $tabcount ) )
		{
			// we've already added two
			if( $tabcount > 2 )
			{
				$tabcount -= 2;
				$tabs = '';
				for( $i = 0; $i < $tabcount; $i++ ) $tabs .= "\t";
				foreach( $a as $k => $r ) $a[$k] = $tabs . $r;
			}
			// remove 1
			elseif( $tabcount == 1 ) foreach( $a as $k => $r ) $a[$k] = substr( $r, 1 );
			// remove both
			elseif( $tabcount === 0 ) foreach( $a as $k => $r ) $a[$k] = substr( $r, 2 );
		}
		// return
		$out = implode( "\n", $a );
		return $out;
	}

	/**
	 * Explode string and trim all array values, dropping string values that are empty
	 * -- will implodeClean first if passed array --
	 * very useful for cleaning posts
	 *
	 * @param	(string)	$delimiter		- string to explode on
	 * @param	(string)	$string			- string to explode, numeric will be cast as (int)
	 * @param	(bool)		$bZero			- keep (int)0 values
	 *
	 * @return	(array)
	 */
	public static function explodeClean( $delimiter, $string, $bZero = false )
	{
		$return = array();
		if( !is_string( $delimiter ) || empty( $string ) ) return $return;
		if( is_array( $string ) ) $string = self::implodeClean( $delimiter, $string, $bZero );
		if( !is_string( $string ) ) return $return;
		$a = explode( $delimiter, $string );
		foreach( $a as $k => $v )
		{
			if( is_numeric( $v ) )
			{
				if( $v ) $return[] = (int)$v;
				elseif( $bZero ) $return[] = 0;
			}
			else
			{
				$v = trim( $v );
				if( $v ) $return[] = $v;
			}
		}
		return $return;
	}

	/**
	 * Implode string after trimming all array values, dropping string values that are empty
	 * -- will explodeClean first if passed string --
	 * very useful for cleaning posts
	 *
	 * @param	(string)	$glue			- string to implode with
	 * @param	(array)		$array			- array to implode, numeric will be cast as (int)
	 * @param	(bool)		$bZero			- keep (int)0 values
	 *
	 * @return	(string)
	 */
	public static function implodeClean( $glue, $array, $bZero = false )
	{
		if( !is_string( $glue ) || empty( $array ) ) return '';
		if( is_string( $array ) ) $array = self::explodeClean( $glue, $array, $bZero );
		if( !is_array( $array ) ) return '';
		foreach( $array as $k => $v )
		{
			if( is_numeric( $v ) )
			{
				if( $v ) $array[$k] = (int)$v;
				elseif( $bZero ) $array[$k] = 0;
				else unset( $array[$k] );
			}
			else
			{
				$v = trim( $v );
				if( $v ) $array[$k] = $v; else unset( $array[$k] );
			}
		}
		return implode( $glue, $array );
	}

	/**
	 * Clean text and make seo friendly - with no '/'
	 *
	 * @param	(string)	$string			- string to clean
	 *
	 * @return	(string)
	 */
	public static function makeSeoSlug( $string, $spacer = '_' )
	{
		if( ! is_string( $string ) ) $string = (string)$string;
		$string = strtolower( html_entity_decode( $string ) );
		$string = trim( $string );
		$string = preg_replace( '/\s+/', $spacer, $string );
		$string = str_replace( "'", '-', $string );
		$string = str_replace( '.', '-', $string );
		$string = preg_replace( '/[^a-z0-9_\-]/', $spacer, $string );
		$string = preg_replace( '/_+/', '_', $string );
		$string = preg_replace( '/\-+/', '-', $string );
		$string = str_replace( '_-_', '-', $string );
		$string = trim( $string, '-' );
		$string = trim( $string, '_' );
		if( $string === '-' || $string === '_' ) return '';
		return preg_replace( '/-the$/', '', $string );
	}

	/**
	 * Clean text and make seo friendly
	 *
	 * @param	(string)	$string			- string to clean
	 *
	 * @return	(string)
	 */
	public static function makeSeoUrl( $string, $allowdots = false )
	{
		if( ! is_string( $string ) ) $string = (string)$string;
		$string = self::clean_environments( $string );
		if( ! $string ) return '/';
		if( stripos( $string, 'http://' ) !== false )
		{
			$temp = parse_url( $string );
			if( ! empty( $temp['path'] ) )
			{
				$string = $temp['path'];
				if( ! empty( $temp['query'] ) ) $string .= '?' . $temp['query'];
			}
		}
		if( $string == '/' ) return '/';
		if( substr( $string, -1, 1 ) == '/' ) $end = '/'; else $end = '';
		$string = strtolower( html_entity_decode( $string ) );
		$array = self::explodeClean( '/', $string );
		$preg = $allowdots ? '/[^a-z0-9_\-\/\.]/' : '/[^a-z0-9_\-\/]/';
		foreach( $array as $k => $a )
		{
			$a = preg_replace( $preg, '_', $a );
			$a = preg_replace( '/\s+/', '_', $a );
			$a = preg_replace( '/_+/', '_', $a );
			$a = str_replace( '_-_', '-', $a );
			$a = trim( $a, '-' );
			$a = trim( $a, '_' );
			if( empty( $a ) ) unset( $array[$k] ); else $array[$k] = $a;
		}
		if( empty( $array ) ) return '/';
		return '/' . implode( '/', $array ) . $end;
	}

	public static function cleanSubPath( $url, $start = '' )
	{
		$url = self::makeSeoUrl( $url );
		if( $start )
		{
			$start = preg_replace( '/\/+/', '/', '/' . $start . '/' );
			while( strpos( $url, $start ) !== false ) $url = str_ireplace( $start, '/', $url );
			$url = $start . $url;
		}
		return preg_replace( '/\/+/', '/', preg_replace( '/\\\+/', '\\', $url ) );
	}

	/**
	 * Clean and add a microtime stamp to an upload file name
	 *
	 * @param	(string)	$filename		- name of file
	 * @param	(int)		$maxlength		- max length of cleaned name ( minus extension )
	 *
	 * @return	(string)
	 */
	public static function cleanFileName( $filename, $maxlength = false )
	{
		$ts = str_replace( '.', '-', (int)LARAVEL_START );
		if( ! $ts ) $ts = time() . '-' . rand( 1000, 9999 );
		$filename = $ts . '_' . strtolower( $filename );
		$filename = preg_replace( '/[^a-z0-9_\-\.]/', '_', strtolower( $filename ) );
		$filename = preg_replace( '/\s+/', '_', $filename );
		$filename = preg_replace( '/_+/', '_', $filename );
		$filename = str_replace( '_-_', '-', $filename );
		$filename = trim( $filename, '-' );
		$filename = trim( $filename, '_' );
		if( $maxlength && strlen( $filename ) > ( $maxlength + 4 ) )
		{
			$a = explode( '.', $filename );
			$ext = array_pop( $a );
			$filename = substr( implode( '.', $a ), 0, $maxlength ) . '.' . $ext;
		}
		return $filename;
	}

	/**
	 * Get system path to files directory
	 *
	 * @param	(string)	$subdir			- subdirectory of path
	 *
	 * @return	(string)
	 */
	public static function getFilesPath( $subdir = false )
	{
		$path = Config::get( 'application.files_path' );
		if( $subdir ) $path .= $subdir . DS;
		return preg_replace( '/\/+/', '/', preg_replace( '/\\\+/', '\\', $path ) );
	}

	/**
	 * Get web path to files directory
	 *
	 * @param	(string)	$subdir			- subdirectory of path
	 *
	 * @return	(string)
	 */
	public static function getFilesWeb( $subdir = false )
	{
		$path = Config::get( 'application.files_url' );
		if( $subdir ) $path .= $subdir . '/';
		return preg_replace( '/\/+/', '/', preg_replace( '/\\\+/', '\\', $path ) );
	}

	/**
	 * Cleans environment path[s] from string
	 *
	 * @param	(string)	$string			- string to clean
	 *
	 * @return	(string)
	 */
	public static function clean_environments( $string )
	{
		if( ! is_string( $string ) || strlen( $string ) < 18 ) return $string;
		global $environments;
		$patterns = array();
		if( ! empty( $environments ) && is_array( $environments ) )
		{
			foreach( $environments as $environment )
			{
				if( ! empty( $environment ) && is_array( $environment ) )
				{
					foreach( $environment as $pattern ) if( is_string( $pattern ) )
					{
						$patterns[] = '(http://' . $pattern . ')';
					}
				}
			}
		}
		if( ! $patterns )
		{
			if( class_exists( 'URL', true ) ) return str_ireplace( URL::base(), '', $string );
			return $string;
		}
		$pregs = array();
		foreach( $patterns as $pattern )
		{
			$pattern = str_replace( '.*.', '.[^\.]#\.', $pattern );
			$pattern = str_replace( '*', '[^\.]#[\.]?', $pattern );
			$pattern = str_replace( '#', '*', $pattern );
			$pattern = str_replace( '.', '\.', $pattern );
			$pattern = str_replace( '/', '\/', $pattern );
			// put longer patterns first, so we don't chop a partial
			$k = strlen($pattern);
			if( empty( $pregs[$k] ) )
				$pregs[$k] = $pattern;
			else
				$pregs[] = $pattern;// not great, but better than nothing
			krsort( $pregs );
		}
		foreach( $pregs as $pattern ) $string = @preg_replace( $pattern, '', $string );
		$string = str_ireplace( 'href=""', 'href="/"', $string );
		return $string;
	}

	/**
	 * Cleans html, newlines, and excess whitespace from string; encodes special chars
	 *
	 * @param	(string)	$string			- string to clean
	 *
	 * @return	(string)
	 */
	public static function cleanAndTrim( $string )
	{
		// de-lf list
		$string = str_replace( "\r", ' ', $string );
		$string = str_replace( "\n", ' ', $string );
		$string = strip_tags( $string );
		// strip extra whitespace
		$string = preg_replace( '/\s\s+/', ' ', $string );
		$string = trim( $string );
		// replace just '&' that are NOT html special chars
		$string = preg_replace( "/&(?!([a-zA-Z]+|#[0-9]+|#x[0-9a-fA-F]+);)/", "&amp;", $string );
		return $string;
	}

	/**
	 * Limit the number of characters in a string.
	 *
	 * <code>
	 *		// Returns "Tay..."
	 *		echo Str::limit('Taylor Otwell', 3);
	 *
	 *		// Limit the number of characters and append a custom ending
	 *		echo Str::limit('Taylor Otwell', 3, '---');
	 * </code>
	 *
	 * @param  string  $value
	 * @param  int	 $limit
	 * @param  string  $end
	 * @return string
	 */
	public static function titleLimit( $value, $limit = 100, $end = '...' )
	{
		if( ! $limit || ( $limit - strlen( $end ) ) < 0 ) return $value;
		if( class_exists( 'Str', true ) )
		{
			if( Str::length( $value ) <= $limit ) return $value;
			$short = Str::limit( $value, ( $limit - strlen( $end ) ), $end );
		}
		else
		{
			if( strlen( $value ) <= $limit ) return $value;
			$short = substr( $value, 0, ( $limit - strlen( $end ) ) ) . $end;
		}
		return '<span class="title-limit" title="' . str_replace( '"', '&quot;', $value ) . '">'.$short.'</span>';
	}

	/**
	 * Clean up and shorten a description string for grid
	 *
	 * @param	(string)		$str			- string to work on
	 * @param	(int)			$len			- maximum string length
	 *
	 * @return	(string)
	 */
	public static function cleanShortenString( $str, $len )
	{
		$str = strip_tags( $str );
		if( strlen( $str ) > $len )
		{
			$str = substr( $str, 0, $len );
			$str = substr( $str, 0, strrpos( $str, ' ' ) ) . '...';
		}
		return self::purify( $str );
	}

	/**
	 * Clean possible php code from string
	 *
	 * @param	(string)		$str			- string to work on
	 *
	 * @return	(string)
	 */
	public static function cleanPHP( $str )
	{
		$str = str_ireplace( '\x3f', '?', $str );
		$str = str_ireplace( '\x3e', '>', $str );
		$str = str_ireplace( '\x3c', '<', $str );
		$str = str_ireplace( '\x28', '(', $str );
		$str = preg_replace( '/eval[\s\S]*\(/i', 'e v a l (', $str );
		$str = strtr( $str, array( '<'.'?' => '&lt; ?', '?'.'>' => '? &gt;', "\x00" => '\x00', "\x1a" => '\x1a' ) );
		return $str;
	}

	/**
	 * Removes newlines and excess whitespace from string
	 * ### WITHOUT $spaces DOES NOT REPLACE NEWLINES WITH SPACES ### so "sometext\nmoretext" will render as "sometextmoretext"
	 *
	 * @param	(string)	$string			- string to clean
	 *
	 * @return	(string)
	 */
	public static function removeWhiteSpace( $string, $spaces = true )
	{
		// de-lf
		$string = str_replace( "\r", '', $string );
		$a = self::explodeClean( "\n", $string );
		foreach( $a as $k => $l ) $a[$k] = trim( $l );
		$string = self::implodeClean( ( $spaces ? ' ' : '' ), $a );
		// strip extra whitespace
		$string = str_replace( "\t", ' ', $string );
		$string = preg_replace( '/\s\s+/', ' ', $string );
		$string = trim( $string );
		return $string;
	}

	public static function cleanEncode( $string )
	{
		// in case string already encoded: eliminates possiblitly of double-encode
		$string = html_entity_decode( $string, ENT_QUOTES );

		// strips all non UTF-8 characters from string
		//$string = iconv( 'UTF-8', 'UTF-8//IGNORE', $string );
		// OR encode based on DB types :
		// $string = mb_convert_encoding( $string, 'ISO-8859-1', 'UTF-8' );
		// THIS may help if DB type is not working:
		//$string = mb_convert_encoding( $string, 'HTML-ENTITIES', 'UTF-8' );
		$string = htmlentities( $string , ENT_QUOTES, 'UTF-8');
		return self::purify( $string );
	}

	/**
	 * htmlentities() on steroids
	 *
	 * @param	(string)	$string			- string to encode
	 *
	 * @return	(string)
	 */
	public static function purify( $string )
	{
		static $purify_trans_tbl;
		if( !isset( $purify_trans_tbl ) ) $purify_trans_tbl = array (
			// WTF??
			chr(195).chr(162).chr(226).chr(130).chr(172).chr(226).chr(132).chr(162) => "'",
			chr(195).chr(162).chr(226).chr(130).chr(172).chr(239).chr(191).chr(189) => '&rdquo;',
			chr(195).chr(162).chr(226).chr(130).chr(172).chr(226).chr(128).chr(156) => '&rdquo;',
			chr(195).chr(162).chr(226).chr(130).chr(172).chr(197).chr(147).chr(118) => '&ldquo;',

			// common multi-bytes
			chr(226).chr(128).chr(147) => '&ndash;',
			chr(226).chr(128).chr(148) => '&mdash;',
			chr(226).chr(128).chr(152) => '&lsquo;',
			chr(226).chr(128).chr(153) => '&rsquo;',
			chr(226).chr(128).chr(154) => '&sbquo;',
			chr(226).chr(128).chr(156) => '&ldquo;',
			chr(226).chr(128).chr(157) => '&rdquo;',
			chr(226).chr(128).chr(158) => '&bdquo;',
			chr(226).chr(128).chr(160) => '&dagger;',
			chr(226).chr(128).chr(161) => '&Dagger;',
			chr(226).chr(128).chr(162) => '&bull;',
			chr(226).chr(128).chr(166) => '&hellip;',
			chr(226).chr(128).chr(176) => '&permil;',
			chr(226).chr(128).chr(185) => '&lsaquo;',
			chr(226).chr(128).chr(186) => '&rsaquo;',

			chr(226).chr(130).chr(172) => '&euro;',

			chr(226).chr(132).chr(162) => '&trade;',

			chr(194).chr(161) => '&iexcl;',		chr(194).chr(162) => '&cent;',
			chr(194).chr(163) => '&pound;',		chr(194).chr(164) => '&curren;',
			chr(194).chr(165) => '&yen;',		chr(194).chr(166) => '&brvbar;',
			chr(194).chr(167) => '&sect;',		chr(194).chr(168) => '&uml;',
			chr(194).chr(169) => '&copy;',		chr(194).chr(170) => '&ordf;',
			chr(194).chr(171) => '&laquo;',		chr(194).chr(172) => '&not;',
			chr(194).chr(174) => '&reg;',		chr(194).chr(175) => '&macr;',
			chr(194).chr(176) => '&deg;',		chr(194).chr(177) => '&plusmn;',
			chr(194).chr(178) => '&sup2;',		chr(194).chr(179) => '&sup3;',
			chr(194).chr(180) => '&acute;',		chr(194).chr(181) => '&micro;',
			chr(194).chr(182) => '&para;',		chr(194).chr(183) => '&middot;',
			chr(194).chr(184) => '&cedil;',		chr(194).chr(185) => '&sup1;',
			chr(194).chr(186) => '&ordm;',		chr(194).chr(187) => '&raquo;',
			chr(194).chr(188) => '&frac14;',	chr(194).chr(189) => '&frac12;',
			chr(194).chr(190) => '&frac34;',	chr(194).chr(191) => '&iquest;',

			chr(195).chr(128) => '&Agrave;',	chr(195).chr(129) => '&Aacute;',
			chr(195).chr(130) => '&Acirc;',		chr(195).chr(131) => '&Atilde;',
			chr(195).chr(132) => '&Auml;',		chr(195).chr(133) => '&Aring;',
			chr(195).chr(134) => '&AElig;',		chr(195).chr(135) => '&Ccedil;',
			chr(195).chr(136) => '&Egrave;',	chr(195).chr(137) => '&Eacute;',
			chr(195).chr(138) => '&Ecirc;',		chr(195).chr(139) => '&Euml;',
			chr(195).chr(140) => '&Igrave;',	chr(195).chr(141) => '&Iacute;',
			chr(195).chr(142) => '&Icirc;',		chr(195).chr(143) => '&Iuml;',
			chr(195).chr(144) => '&ETH;',		chr(195).chr(145) => '&Ntilde;',
			chr(195).chr(146) => '&Ograve;',	chr(195).chr(147) => '&Oacute;',
			chr(195).chr(148) => '&Ocirc;',		chr(195).chr(149) => '&Otilde;',
			chr(195).chr(150) => '&Ouml;',		chr(195).chr(151) => '&times;',
			chr(195).chr(152) => '&Oslash;',	chr(195).chr(153) => '&Ugrave;',
			chr(195).chr(154) => '&Uacute;',	chr(195).chr(155) => '&Ucirc;',
			chr(195).chr(156) => '&Uuml;',		chr(195).chr(157) => '&Yacute;',
			chr(195).chr(158) => '&THORN;',		chr(195).chr(159) => '&szlig;',
			chr(195).chr(160) => '&agrave;',	chr(195).chr(161) => '&aacute;',
			chr(195).chr(162) => '&acirc;',		chr(195).chr(163) => '&atilde;',
			chr(195).chr(164) => '&auml;',		chr(195).chr(165) => '&aring;',
			chr(195).chr(166) => '&aelig;',		chr(195).chr(167) => '&ccedil;',
			chr(195).chr(168) => '&egrave;',	chr(195).chr(169) => '&eacute;',
			chr(195).chr(170) => '&ecirc;',		chr(195).chr(171) => '&euml;',
			chr(195).chr(172) => '&igrave;',	chr(195).chr(173) => '&iacute;',
			chr(195).chr(174) => '&icirc;',		chr(195).chr(175) => '&iuml;',
			chr(195).chr(176) => '&eth;',		chr(195).chr(177) => '&ntilde;',
			chr(195).chr(178) => '&ograve;',	chr(195).chr(179) => '&oacute;',
			chr(195).chr(180) => '&ocirc;',		chr(195).chr(181) => '&otilde;',
			chr(195).chr(182) => '&ouml;',		chr(195).chr(183) => '&divide;',
			chr(195).chr(184) => '&oslash;',	chr(195).chr(185) => '&ugrave;',
			chr(195).chr(186) => '&uacute;',	chr(195).chr(187) => '&ucirc;',
			chr(195).chr(188) => '&uuml;',		chr(195).chr(189) => '&yacute;',
			chr(195).chr(190) => '&thorn;',		chr(195).chr(191) => '&yuml;',

			chr(197).chr(146) => '&OElig;',		chr(197).chr(147) => '&oelig;',
			chr(197).chr(160) => '&Scaron;',	chr(197).chr(161) => '&scaron;',
			chr(197).chr(184) => '&Yuml;',		chr(198).chr(146) => '&fnof;',

			chr(203).chr(134) => '&circ;',		chr(203).chr(156) => '&tilde;',

			// in default htmlentities: ( we don't want to do these to html )
			// chr(34) => '&quot;',	chr(38) => '&amp;',		chr(60) => '&lt;',		chr(62) => '&gt;',
			// not in default htmlentities:
			chr(128) => '&euro;',							chr(130) => '&sbquo;',	chr(131) => '&fnof;',
			chr(132) => '&bdquo;',	chr(133) => '&hellip;',	chr(134) => '&dagger;',	chr(135) => '&Dagger;',
			chr(136) => '&circ;',	chr(137) => '&permil;',	chr(138) => '&Scaron;',	chr(139) => '&lsaquo;',
			chr(140) => '&OElig;',							chr(142) => '&#381;',
									chr(145) => '&lsquo;',	chr(146) => '&rsquo;',	chr(147) => '&mdash;',
			chr(148) => '&rdquo;',	chr(149) => '&bull;',	chr(150) => '&ndash;',	chr(151) => '&mdash;',
			chr(152) => '&tilde;',	chr(153) => '&trade;',	chr(154) => '&scaron;',
			chr(156) => '&oelig;',							chr(158) => '&#382;',	chr(159) => '&Yuml;',
			// in default htmlentities:
			chr(160) => '&nbsp;',	chr(161) => '&iexcl;',	chr(162) => '&cent;',	chr(163) => '&pound;',
			chr(164) => '&curren;',	chr(165) => '&yen;',	chr(166) => '&brvbar;',	chr(167) => '&sect;',
			chr(168) => '&uml;',	chr(169) => '&copy;',	chr(170) => '&ordf;',	chr(171) => '&laquo;',
			chr(172) => '&not;',	chr(173) => '&shy;',	chr(174) => '&reg;',	chr(175) => '&macr;',
			chr(176) => '&deg;',	chr(177) => '&plusmn;',	chr(178) => '&sup2;',	chr(179) => '&sup3;',
			chr(180) => '&acute;',	chr(181) => '&micro;',	chr(182) => '&para;',	chr(183) => '&middot;',
			chr(184) => '&cedil;',	chr(185) => '&sup1;',	chr(186) => '&ordm;',	chr(187) => '&raquo;',
			chr(188) => '&frac14;',	chr(189) => '&frac12;',	chr(190) => '&frac34;',	chr(191) => '&iquest;',
			chr(192) => '&Agrave;',	chr(193) => '&Aacute;',	chr(194) => '&Acirc;',	chr(195) => '&Atilde;',
			chr(196) => '&Auml;',	chr(197) => '&Aring;',	chr(198) => '&AElig;',	chr(199) => '&Ccedil;',
			chr(200) => '&Egrave;',	chr(201) => '&Eacute;',	chr(202) => '&Ecirc;',	chr(203) => '&Euml;',
			chr(204) => '&Igrave;',	chr(205) => '&Iacute;',	chr(206) => '&Icirc;',	chr(207) => '&Iuml;',
			chr(208) => '&ETH;',	chr(209) => '&Ntilde;',	chr(210) => '&Ograve;',	chr(211) => '&Oacute;',
			chr(212) => '&Ocirc ;',	chr(213) => '&Otilde;',	chr(214) => '&Ouml;',	chr(215) => '&times;',
			chr(216) => '&Oslash;',	chr(217) => '&Ugrave;',	chr(218) => '&Uacute;',	chr(219) => '&Ucirc;',
			chr(220) => '&Uuml;',	chr(221) => '&Yacute;',	chr(222) => '&THORN;',	chr(223) => '&szlig;',
			chr(224) => '&agrave;',	chr(225) => '&aacute;',	chr(226) => '&acirc;',	chr(227) => '&atilde;',
			chr(228) => '&auml;',	chr(229) => '&aring;',	chr(230) => '&aelig ;',	chr(231) => '&ccedil;',
			chr(232) => '&egrave;',	chr(233) => '&eacute;',	chr(234) => '&ecirc;',	chr(235) => '&euml;',
			chr(236) => '&igrave;',	chr(237) => '&iacute;',	chr(238) => '&icirc;',	chr(239) => '&iuml;',
			chr(240) => '&eth;',	chr(241) => '&ntilde;',	chr(242) => '&ograve;',	chr(243) => '&oacute;',
			chr(244) => '&ocirc;',	chr(245) => '&otilde;',	chr(246) => '&ouml;',	chr(247) => '&divide;',
			chr(248) => '&oslash;',	chr(249) => '&ugrave;',	chr(250) => '&uacute;',	chr(251) => '&ucirc;',
			chr(252) => '&uuml;',	chr(253) => '&yacute;',	chr(254) => '&thorn;',	chr(255) => '&yuml;'
		);
		return strtr( $string, $purify_trans_tbl );
	}

	/**
	 * Common stop words ( mysql uses them, for instance )
	 *
	 */
	public static function stopwords()
	{
		return array (
			'0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
			'a', 'about', 'after', 'all', 'along', 'also', 'am', 'an', 'and', 'answer', 'any', 'anybody', 'anywhere', 'and', 'are', 'aren\'t', 'as', 'ask', 'at', 'ate',
			'b', 'based', 'be', 'because', 'been', 'before', 'beg', 'being', 'both', 'bring', 'but', 'by',
			'c', 'call', 'called', 'can', 'can\'t', 'carry', 'clean', 'consider', 'could', 'couldn\'t',
			'd', 'did', 'didn\'t', 'do', 'does', 'don\'t',
			'e', 'each', 'either', 'else', 'era', 'even', 'ever', 'every',
			'f', 'find', 'for', 'found', 'from', 'full', 'funny',
			'g', 'gave', 'get', 'give', 'given', 'god',
			'h', 'had', 'has', 'have', 'haven\'t', 'having', 'he', 'her', 'hers', 'here', 'help', 'hi', 'his', 'him', 'hold', 'hoo', 'how', 'href', 'hurt',
			'i', 'id', 'if', 'ill', 'i\'ll', 'i\'m', 'in', 'into', 'is', 'it', 'its', 'it\'s',
			'j', 'jag', 'jar', 'jaw', 'job', 'jog', 'just',
			'k', 'ken', 'kinder', 'kit',
			'l', 'lap', 'led', 'less', 'lie', 'lid', 'like', 'lust',
			'm', 'me', 'much', 'mum', 'must', 'my', 'myself',
			'n', 'nib', 'nil', 'nod', 'none', 'nor', 'not', 'nun',
			'o', 'of', 'off', 'often', 'ohm', 'oho', 'on', 'oops', 'or', 'ore', 'ours',
			'p', 'pap', 'peg', 'phi', 'please', 'pull', 'pus', 'push', 'put', 'ply',
			'q', 'question',
			'r', 'ran', 'rag', 'rat', 'rather', 'ray', 'recent', 'rev', 'rid', 'rug', 'rum',
			's', 'said', 'say', 'see', 'sex', 'she', 'should', 'shouldn\'t', 'shy', 'sib', 'sic', 'sin', 'sip', 'sir', 'sit', 'sitting', 'so', 'sob', 'some', 'soon', 'son', 'such', 'sum',
			't', 'take', 'tap', 'tax', 'than', 'thank', 'that', 'the', 'theirs', 'them', 'then', 'there', 'these', 'this', 'they', 'those', 'though', 'through', 'thus', 'thy', 'tie', 'tit', 'to', 'tog', 'try', 'tun', 'type',
			'u', 'until', 'untrue', 'ups', 'us', 'usage', 'use', 'useful', 'users', 'using',
			'v', 'vat', 'via', 'vim', 'vex', 'vow',
			'w', 'want', 'was', 'waste', 'wash', 'we', 'well', 'went', 'were', 'what', 'when', 'where', 'whether', 'which', 'who', 'whom', 'whose', 'why', 'will', 'with', 'within', 'without', 'woe', 'won', 'woo', 'worse', 'worst', 'would', 'wow', 'write', 'www',
			'x',
			'y', 'ya', 'yen', 'yep', 'yet', 'yon', 'you',
			'z', 'zeal', 'zed', 'zinc', 'zip'
		);
	}

	/**
	 * Clean a keyword string
	 *
	 * @param	(string)	$string			- string to clean
	 *
	 * @return	(csv)
	 */
	public static function stripCommonKeywords( $string )
	{
		if( empty( $string ) ) return '';
		// de-csv list
		$string = str_replace( ',', ' ', $string );
		// de-linefeed list
		$string = self::cleanAndTrim( $string );
		if( empty( $string ) ) return '';
		// remove non-standard characters & hmtl entities
		$string = html_entity_decode( $string, ENT_QUOTES );
		$search = array( chr(145), chr(146), chr(147), chr(148), chr(151), '&lsquo;', '&rsquo;', '&ldquo;', '&rdquo;', '&mdash;' );
		$replace = array( "'",	"'",	'',	'',	'-', "'",	"'",	'',	'',	'-' );
		$string = str_replace( $search, $replace, $string );
		$string = preg_replace( "/[^a-zA-Z0-9\-\'\s]/", '', $string );
		if( empty( $string ) ) return '';
		// set stopwords
		$stopwords = self::stopwords();
		$out = array();
		// create array of words
		$aTemp = self::explodeClean( ' ', strtolower( $string ) );
		// drop numbers, stopwords, single characters, and existing keywords
		if( count( $aTemp ) > 0 ) foreach( $aTemp as $k => $a )
		{
			if( is_numeric( $a ) ) continue;
			if( strlen( $a ) == 1 ) continue;
			if( in_array( $a, $stopwords ) ) continue;
			if( in_array( $a, $replace ) ) continue;
			$out[] = $a;
		}
		// remove duplicates
		$out = array_unique( $out );
		// re-create csv list
		if( count( $out ) ) return implode( ', ', $out );
		else return '';
	}

	/**
	 * Format dates
	 *
	 * @param	(string)		$startdate		- start date in '2011-12-22' format
	 * @param	(string)		$enddate		- end date in '2011-12-24' format || empty/false
	 * @param	(string)		$conditional	- conditional in 'every [[day of week]]' format || empty/false
	 * @return	(string)
	 */
	public static function eventDate( $startdate, $enddate = '', $recurrence = '' )
	{
		$date = '';
		if( !$enddate ) $enddate = $startdate;
		if( !$startdate ) return '';
		$start = strtotime( $startdate );
		if( $enddate != $startdate )
		{
			$start = getdate( $start );
			$end = getdate( strtotime( $enddate ) );
			if( $recurrence )
			{
				// [recurrence] => every 1st [[day of week]]
				if( stristr( $recurrence, '[[day of week]]' ) ) $date .= ucfirst( str_replace( '[[day of week]]', $start['weekday'], $recurrence ) ) . ' from<br/>';
			}
			if( $start['year'] != $end['year'] ) $date .= "{$start['month']} {$start['mday']}, {$start['year']} - {$end['month']} {$end['mday']} {$end['year']}";
			elseif( $start['month'] != $end['month'] ) $date .= "{$start['month']} {$start['mday']} - {$end['month']} {$end['mday']}, {$end['year']}";
			else $date .= "{$start['month']} {$start['mday']} - {$end['mday']}, {$end['year']}";
		}
		else $date = date( 'F j, Y', $start );

		return $date;
	}

	/**
	 * Convert an int to an english string : 9999 -> nine-thousand-nine-hundred-ninety-ninth
	 *
	 * @param	(int)			$int			- number to convert
	 * @param	(bool)			$cb				- callback, no nth on string
	 * @return	(string)
	 */
	public static function nominal( $int, $cb = false )
	{
		$int = (int)$int;
		if( ! $int || $int > 999999999 ) return '';

		$ret = '';
		// Millions (giga)
		if( $Gn = floor( $int / 1000000 ) )
		{
			$ret .= self::nominal( $Gn, true ) . '-million';
			$int -= $Gn * 1000000;
			if( $int ) $ret .= '-';
			else return $ret . 'th';
		}
		// Thousands (kilo)
		if( $kn = floor( $int / 1000 ) )
		{
			$ret .= self::nominal( $kn, true ) . '-thousand';
			$int -= $kn * 1000;
			if( $int ) $ret .= '-';
			else return $ret . 'th';
		}
		// Hundreds (hecto)
		if( $Hn = floor( $int / 100 ) )
		{
			$ret .=  self::nominal( $Hn, true ) . '-hundred';
			$int -= $Hn * 100;
			if( $int ) $ret .= '-';
			else return $ret . 'th';
		}
		$digit = array( '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen' );
		$digith = array( '', 'first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth', 'eleventh', 'twelfth', 'thirteenth', 'fourteenth', 'fiftheenth', 'sixteenth', 'seventeenth', 'eighteenth', 'nineteenth' );
		if( $int < 20 ) return $ret . ( $cb ? $digit[$int] : $digith[$int] );
		$ten = array( '', '', 'twenty', 'thirty', 'fourty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety' );
		$tenth = array( '', '', 'twentieth', 'thirtieth', 'fortieth', 'fiftieth', 'sixtieth', 'seventieth', 'eightieth', 'ninetieth' );
		$m = ( $int % 10 );
		$t = ( $int - $m ) / 10;
		if( ! $m ) return $ret . ( $cb ? $ten[$t] : $tenth[$t] );
		$ret .= $ten[$t] . '-';
		return $ret . ( $cb ? $digit[$m] : $digith[$m] );
	}

	/**
	 * Get list of current Laravel routes for application
	 *
	 * @return	(array)
	 */
	public static function get_routes()
	{
		$exclude = Config::get( 'application.reserved', array() );
		/* Force every bundle route file to be parsed */
		foreach( Bundle::all() as $bundle => $d ) if( ! Bundle::routed( $bundle ) ) Bundle::routes( $bundle );
		$routes = Router::routes();
		$routes = $routes['GET'];
		$return = array();
		foreach( $routes as $url => $d )
		{
			$parts = explode( '/', $url );
			if( ! in_array( $parts[0], $exclude ) &&  strpos( $url, '.' ) === false )
			{
				foreach( $parts as $k => $v )
				{
					if( strpos( $v, '(' ) !== false ) unset( $parts[$k] );
				}
				if( $parts ) $return[] = '/' . trim( implode( '/', $parts ), '/' );
			}
		}
		$return = array_unique( $return );
		sort( $return );
		return $return;
	}

	/**
	 * Convert bytes to GB/MB/KB based on size
	 *
	 * @param	(int)			$bytes
	 * @return	(string)
	 */
	public static function formatSizeUnits( $bytes )
	{
		$bytes = (int)$bytes;

		if( $bytes >= 1073741824 )
			return number_format( $bytes / 1073741824, 2 ) . ' GB';

		if( $bytes >= 1048576 )
			return number_format( $bytes / 1048576, 2 ) . ' MB';

		if( $bytes >= 1024 )
			return number_format( $bytes / 1024, 2 ) . ' KB';

		if( $bytes > 1 )
			return $bytes . ' bytes';

		if( $bytes == 1 )
			return '1 byte';

		return '0 bytes';
	}

	/**
	 * Create document.write() for inline javascript creation of elements
	 * @param	(string)		$txt			- string to write
	 * @param 	(string)		$base			- alllows substitution of relative with absolute urls
	 *
	 * @return	(string)
	 */
	public static function document_write( $txt, $base = '/' )
	{
		$ret = '';
		$txt = str_ireplace( 'src="/', 'src="' . $base, $txt );
		$txt = str_ireplace( 'href="/', 'href="' . $base, $txt );
		$txt = str_ireplace( 'action="/', 'action="' . $base, $txt );
		$txt = explode( "\n", $txt );
		foreach( $txt as $l )
		{
			$l = trim( $l ); if( ! $l ) continue;
			$ret .= "document.write('" . str_replace( "'", "\'", $l ) . "');\n";
		}
		return $ret;
	}

	/**
	 * #######################################################################################################################################################################################
	 */
	public $data = array();

	function lawed($t, $C=1, $S=array())
	{
		$C = is_array($C) ? $C : array();
		if(!empty($C['valid_xhtml'])){
			$C['elements'] = empty($C['elements']) ? '*-acronym-big-center-dir-font-isindex-s-strike-tt' : $C['elements'];
			$C['make_tag_strict'] = isset($C['make_tag_strict']) ? $C['make_tag_strict'] : 2;
			$C['xml:lang'] = isset($C['xml:lang']) ? $C['xml:lang'] : 2;
		}
		// config eles
		$e = array('a'=>1, 'abbr'=>1, 'acronym'=>1, 'address'=>1, 'applet'=>1, 'area'=>1, 'article'=>1, 'aside'=>1, 'audio'=>1, 'b'=>1, 'bdi'=>1, 'bdo'=>1, 'big'=>1, 'blockquote'=>1,
				'br'=>1, 'button'=>1, 'canvas'=>1, 'caption'=>1, 'center'=>1, 'cite'=>1, 'code'=>1, 'col'=>1, 'colgroup'=>1, 'command'=>1, 'data'=>1, 'datalist'=>1, 'dd'=>1, 'del'=>1,
				'details'=>1, 'dfn'=>1, 'dir'=>1, 'div'=>1, 'dl'=>1, 'dt'=>1, 'em'=>1, 'embed'=>1, 'fieldset'=>1, 'figcaption'=>1, 'figure'=>1, 'font'=>1, 'footer'=>1, 'form'=>1, 'h1'=>1,
				'h2'=>1, 'h3'=>1, 'h4'=>1, 'h5'=>1, 'h6'=>1, 'header'=>1, 'hgroup'=>1, 'hr'=>1, 'i'=>1, 'iframe'=>1, 'img'=>1, 'input'=>1, 'ins'=>1, 'isindex'=>1, 'kbd'=>1, 'keygen'=>1,
				'label'=>1, 'legend'=>1, 'li'=>1, 'link'=>1, 'main'=>1, 'map'=>1, 'mark'=>1, 'menu'=>1, 'meta'=>1, 'meter'=>1, 'nav'=>1, 'noscript'=>1, 'object'=>1, 'ol'=>1, 'optgroup'=>1,
				'option'=>1, 'output'=>1, 'p'=>1, 'param'=>1, 'pre'=>1, 'progress'=>1, 'q'=>1, 'rb'=>1, 'rbc'=>1, 'rp'=>1, 'rt'=>1, 'rtc'=>1, 'ruby'=>1, 's'=>1, 'samp'=>1, 'script'=>1,
				'section'=>1, 'select'=>1, 'small'=>1, 'source'=>1, 'span'=>1, 'strike'=>1, 'strong'=>1, 'style'=>1, 'sub'=>1, 'summary'=>1, 'sup'=>1, 'table'=>1, 'tbody'=>1, 'td'=>1,
				'textarea'=>1, 'tfoot'=>1, 'th'=>1, 'thead'=>1, 'time'=>1, 'tr'=>1, 'track'=>1, 'tt'=>1, 'u'=>1, 'ul'=>1, 'var'=>1, 'video'=>1, 'wbr'=>1); // 118 incl. deprecated & some Ruby

		if(!empty($C['safe'])){
			unset($e['applet'], $e['audio'], $e['canvas'], $e['embed'], $e['iframe'], $e['object'], $e['script'], $e['video']);
		}
		$x = !empty($C['elements']) ? str_replace(array("\n", "\r", "\t", ' '), '', $C['elements']) : '*';
		if($x == '-*'){$e = array();}
		elseif(strpos($x, '*') === false){$e = array_flip(explode(',', $x));}
		else{
			if(isset($x[1])){
				preg_match_all('`(?:^|-|\+)[^\-+]+?(?=-|\+|$)`', $x, $m, PREG_SET_ORDER);
				for($i=count($m); --$i>=0;){$m[$i] = $m[$i][0];}
				foreach($m as $v){
					if($v[0] == '+'){$e[substr($v, 1)] = 1;}
					if($v[0] == '-' && isset($e[($v = substr($v, 1))]) && !in_array('+'. $v, $m)){unset($e[$v]);}
				}
			}
		}
		$C['elements'] =& $e;
		// config attrs
		$x = !empty($C['deny_attribute']) ? str_replace(array("\n", "\r", "\t", ' '), '', $C['deny_attribute']) : '';
		$x = array_flip((isset($x[0]) && $x[0] == '*') ? explode('-', $x) : explode(',', $x. (!empty($C['safe']) ? ',on*' : '')));
		if(isset($x['on*'])){
			unset($x['on*']);
			$x += array('onabort'=>1, 'onblur'=>1, 'oncanplay'=>1, 'oncanplaythrough'=>1, 'onchange'=>1, 'onclick'=>1, 'oncontextmenu'=>1, 'oncuechange'=>1, 'ondblclick'=>1, 'ondrag'=>1,
					'ondragend'=>1, 'ondragenter'=>1, 'ondragleave'=>1, 'ondragover'=>1, 'ondragstart'=>1, 'ondrop'=>1, 'ondurationchange'=>1, 'onemptied'=>1, 'onended'=>1, 'onerror'=>1,
					'onfocus'=>1, 'oninput'=>1, 'oninvalid'=>1, 'onkeydown'=>1, 'onkeypress'=>1, 'onkeyup'=>1, 'onload'=>1, 'onloadeddata'=>1, 'onloadedmetadata'=>1, 'onloadstart'=>1,
					'onmousedown'=>1, 'onmousemove'=>1, 'onmouseout'=>1, 'onmouseover'=>1, 'onmouseup'=>1, 'onmousewheel'=>1, 'onpause'=>1, 'onplay'=>1, 'onplaying'=>1, 'onprogress'=>1,
					'onratechange'=>1, 'onreadystatechange'=>1, 'onreset'=>1, 'onscroll'=>1, 'onseeked'=>1, 'onseeking'=>1, 'onselect'=>1, 'onshow'=>1, 'onstalled'=>1, 'onsubmit'=>1,
					'onsuspend'=>1, 'ontimeupdate'=>1, 'onvolumechange'=>1, 'onwaiting'=>1);
		}
		$C['deny_attribute'] = $x;
		// config URL
		$x = (isset($C['schemes'][2]) && strpos($C['schemes'], ':'))
		? strtolower($C['schemes'])
		: 'href: aim, feed, file, ftp, gopher, http, https, irc, mailto, news, nntp, sftp, ssh, telnet; *:file, http, https';
		$C['schemes'] = array();
		foreach(explode(';', str_replace(array(' ', "\t", "\r", "\n"), '', $x)) as $v){
			$x = $x2 = null; list($x, $x2) = explode(':', $v, 2);
			if($x2){$C['schemes'][$x] = array_flip(explode(',', $x2));}
		}
		if(!isset($C['schemes']['*'])){$C['schemes']['*'] = array('file'=>1, 'http'=>1, 'https'=>1,);}
		if(!empty($C['safe']) && empty($C['schemes']['style'])){$C['schemes']['style'] = array('!'=>1);}
		$C['abs_url'] = isset($C['abs_url']) ? $C['abs_url'] : 0;
		if(!isset($C['base_url']) or !preg_match('`^[a-zA-Z\d.+\-]+://[^/]+/(.+?/)?$`', $C['base_url'])){
			$C['base_url'] = $C['abs_url'] = 0;
		}
		// config rest
		$C['and_mark'] = empty($C['and_mark']) ? 0 : 1;
		$C['anti_link_spam'] = (isset($C['anti_link_spam']) && is_array($C['anti_link_spam'])
				&& count($C['anti_link_spam']) == 2
				&& (empty($C['anti_link_spam'][0]) or $this->hl_regex($C['anti_link_spam'][0]))
				&& (empty($C['anti_link_spam'][1]) or $this->hl_regex($C['anti_link_spam'][1])))
				? $C['anti_link_spam'] : 0;
		$C['anti_mail_spam'] = isset($C['anti_mail_spam']) ? $C['anti_mail_spam'] : 0;
		$C['balance'] = isset($C['balance']) ? (bool)$C['balance'] : 1;
		$C['cdata'] = isset($C['cdata']) ? $C['cdata'] : (empty($C['safe']) ? 3 : 0);
		$C['clean_ms_char'] = empty($C['clean_ms_char']) ? 0 : $C['clean_ms_char'];
		$C['comment'] = isset($C['comment']) ? $C['comment'] : (empty($C['safe']) ? 3 : 0);
		$C['css_expression'] = empty($C['css_expression']) ? 0 : 1;
		$C['direct_list_nest'] = empty($C['direct_list_nest']) ? 0 : 1;
		$C['hexdec_entity'] = isset($C['hexdec_entity']) ? $C['hexdec_entity'] : 1;
		$C['hook'] = (!empty($C['hook']) && function_exists($C['hook'])) ? $C['hook'] : 0;
		$C['hook_tag'] = (!empty($C['hook_tag']) && function_exists($C['hook_tag'])) ? $C['hook_tag'] : 0;
		$C['keep_bad'] = isset($C['keep_bad']) ? $C['keep_bad'] : 6;
		$C['lc_std_val'] = isset($C['lc_std_val']) ? (bool)$C['lc_std_val'] : 1;
		$C['make_tag_strict'] = isset($C['make_tag_strict']) ? $C['make_tag_strict'] : 1;
		$C['named_entity'] = isset($C['named_entity']) ? (bool)$C['named_entity'] : 1;
		$C['no_deprecated_attr'] = isset($C['no_deprecated_attr']) ? $C['no_deprecated_attr'] : 1;
		$C['parent'] = isset($C['parent'][0]) ? strtolower($C['parent']) : 'body';
		$C['show_setting'] = !empty($C['show_setting']) ? $C['show_setting'] : 0;
		$C['style_pass'] = empty($C['style_pass']) ? 0 : 1;
		$C['tidy'] = empty($C['tidy']) ? 0 : $C['tidy'];
		$C['unique_ids'] = isset($C['unique_ids']) && (!preg_match('`\W`', $C['unique_ids'])) ? $C['unique_ids'] : 1;
		$C['xml:lang'] = isset($C['xml:lang']) ? $C['xml:lang'] : 0;

		if(isset( $this->data['C'])){$reC = $this->data['C'];}
		$this->data['C'] = $C;
		$S = is_array($S) ? $S : $this->hl_spec($S);
		if(isset( $this->data['S'])){$reS = $this->data['S'];}
		$this->data['S'] = $S;

		$t = preg_replace('`[\x00-\x08\x0b-\x0c\x0e-\x1f]`', '', $t);
		if($C['clean_ms_char']){
			$x = array("\x7f"=>'', "\x80"=>'&#8364;', "\x81"=>'', "\x83"=>'&#402;', "\x85"=>'&#8230;', "\x86"=>'&#8224;', "\x87"=>'&#8225;', "\x88"=>'&#710;', "\x89"=>'&#8240;',
					"\x8a"=>'&#352;', "\x8b"=>'&#8249;', "\x8c"=>'&#338;', "\x8d"=>'', "\x8e"=>'&#381;', "\x8f"=>'', "\x90"=>'', "\x95"=>'&#8226;', "\x96"=>'&#8211;', "\x97"=>'&#8212;',
					"\x98"=>'&#732;', "\x99"=>'&#8482;', "\x9a"=>'&#353;', "\x9b"=>'&#8250;', "\x9c"=>'&#339;', "\x9d"=>'', "\x9e"=>'&#382;', "\x9f"=>'&#376;');
			$x = $x + ($C['clean_ms_char'] == 1
					? array("\x82"=>'&#8218;', "\x84"=>'&#8222;', "\x91"=>'&#8216;', "\x92"=>'&#8217;', "\x93"=>'&#8220;', "\x94"=>'&#8221;')
					: array("\x82"=>'\'', "\x84"=>'"', "\x91"=>'\'', "\x92"=>'\'', "\x93"=>'"', "\x94"=>'"'));
			$t = strtr($t, $x);
		}
		if($C['cdata'] or $C['comment']){$t = preg_replace_callback('`<!(?:(?:--.*?--)|(?:\[CDATA\[.*?\]\]))>`sm', array( $this, 'hl_cmtcd' ), $t);}
		$t = preg_replace_callback('`&amp;([a-zA-Z][a-zA-Z0-9]{1,30}|#(?:[0-9]{1,8}|[Xx][0-9A-Fa-f]{1,7}));`', array( $this, 'hl_ent' ), str_replace('&', '&amp;', $t));
		if($C['unique_ids'] && !isset( $this->data['hl_Ids'])){ $this->data['hl_Ids'] = array();}
		if($C['hook']){$t = $C['hook']($t, $C, $S);}
		if($C['show_setting'] && preg_match('`^[a-z][a-z0-9_]*$`i', $C['show_setting'])){
			$this->data[$C['show_setting']] = array('config'=>$C, 'spec'=>$S, 'time'=>microtime());
		}
		// main
		$t = preg_replace_callback('`<(?:(?:\s|$)|(?:[^>]*(?:>|$)))|>`m', array( $this, 'hl_tag' ), $t);
		$t = $C['balance'] ? $this->hl_bal($t, $C['keep_bad'], $C['parent']) : $t;
		$t = (($C['cdata'] or $C['comment']) && strpos($t, "\x01") !== false) ? str_replace(array("\x01", "\x02", "\x03", "\x04", "\x05"), array('', '', '&', '<', '>'), $t) : $t;
		$t = $C['tidy'] ? $this->hl_tidy($t, $C['tidy'], $C['parent']) : $t;
		unset($C, $e);
		if(isset($reC)){ $this->data['C'] = $reC;}
		if(isset($reS)){ $this->data['S'] = $reS;}
		return $t;
		// eof
	}

	function hl_attrval($t, $p)
	{
		// check attr val against $S
		$o = 1; $l = strlen($t);
		foreach($p as $k=>$v){
			switch($k){
				case 'maxlen':if($l > $v){$o = 0;}
				break; case 'minlen': if($l < $v){$o = 0;}
				break; case 'maxval': if((float)($t) > $v){$o = 0;}
				break; case 'minval': if((float)($t) < $v){$o = 0;}
				break; case 'match': if(!preg_match($v, $t)){$o = 0;}
				break; case 'nomatch': if(preg_match($v, $t)){$o = 0;}
				break; case 'oneof':
					$m = 0;
					foreach(explode('|', $v) as $n){if($t == $n){$m = 1; break;}}
					$o = $m;
					break; case 'noneof':
						$m = 1;
						foreach(explode('|', $v) as $n){if($t == $n){$m = 0; break;}}
						$o = $m;
						break; default:
							break;
			}
			if(!$o){break;}
		}
		return ($o ? $t : (isset($p['default']) ? $p['default'] : 0));
		// eof
	}

	function hl_bal($t, $do=1, $in='div')
	{
		// balance tags
		// by content
		$cB = array('blockquote'=>1, 'form'=>1, 'map'=>1, 'noscript'=>1); // Block
		$cE = array('area'=>1, 'br'=>1, 'col'=>1, 'command'=>1, 'embed'=>1, 'hr'=>1, 'img'=>1, 'input'=>1, 'isindex'=>1, 'keygen'=>1, 'link'=>1, 'meta'=>1, 'param'=>1, 'source'=>1,
				'track'=>1, 'wbr'=>1); // Empty
		$cF = array('a'=>1, 'article'=>1, 'aside'=>1, 'audio'=>1, 'button'=>1, 'canvas'=>1, 'del'=>1, 'details'=>1, 'div'=>1, 'dd'=>1, 'fieldset'=>1, 'figure'=>1, 'footer'=>1,
				'header'=>1, 'iframe'=>1, 'ins'=>1, 'li'=>1, 'main'=>1, 'menu'=>1, 'nav'=>1, 'noscript'=>1, 'object'=>1, 'section'=>1, 'style'=>1, 'td'=>1, 'th'=>1, 'video'=>1);
		// Flow; later context-wise dynamic move of ins & del to $cI
		$cI = array('abbr'=>1, 'acronym'=>1, 'address'=>1, 'b'=>1, 'bdi'=>1, 'bdo'=>1, 'big'=>1, 'caption'=>1, 'cite'=>1, 'code'=>1, 'data'=>1, 'datalist'=>1, 'dfn'=>1, 'dt'=>1,
				'em'=>1, 'figcaption'=>1, 'font'=>1, 'h1'=>1, 'h2'=>1, 'h3'=>1, 'h4'=>1, 'h5'=>1, 'h6'=>1, 'hgroup'=>1, 'i'=>1, 'kbd'=>1, 'label'=>1, 'legend'=>1, 'mark'=>1,
				'meter'=>1, 'output'=>1, 'p'=>1, 'pre'=>1, 'progress'=>1, 'q'=>1, 'rb'=>1, 'rt'=>1, 's'=>1, 'samp'=>1, 'small'=>1, 'span'=>1, 'strike'=>1, 'strong'=>1, 'sub'=>1,
				'summary'=>1, 'sup'=>1, 'time'=>1, 'tt'=>1, 'u'=>1, 'var'=>1); // Inline
		$cN = array(
				'a'=>array('a'=>1, 'address'=>1, 'button'=>1, 'details'=>1, 'embed'=>1, 'keygen'=>1, 'label'=>1, 'select'=>1, 'textarea'=>1),
				'address'=>array('address'=>1, 'article'=>1, 'aside'=>1, 'header'=>1, 'keygen'=>1, 'footer'=>1, 'nav'=>1, 'section'=>1),
				'button'=>array('a'=>1, 'address'=>1, 'button'=>1, 'details'=>1, 'embed'=>1, 'fieldset'=>1, 'form'=>1, 'iframe'=>1, 'input'=>1, 'keygen'=>1, 'label'=>1, 'select'=>1, 'textarea'=>1),
				'fieldset'=>array('fieldset'=>1),
				'footer'=>array('header'=>1, 'footer'=>1),
				'form'=>array('form'=>1),
				'header'=>array('header'=>1, 'footer'=>1),
				'label'=>array('label'=>1),
				'main'=>array('main'=>1),
				'meter'=>array('meter'=>1),
				'noscript'=>array('script'=>1),
				'pre'=>array('big'=>1, 'font'=>1, 'img'=>1, 'object'=>1, 'script'=>1, 'small'=>1, 'sub'=>1, 'sup'=>1),
				'progress'=>array('progress'=>1),
				'rb'=>array('ruby'=>1),
				'rt'=>array('ruby'=>1),
				'time'=>array('time'=>1)
		); // Illegal
		$cN2 = array_keys($cN);
		$cS = array(
				'colgroup'=>array('col'=>1),
				'datalist'=>array('option'=>1),
				'dir'=>array('li'=>1),
				'dl'=>array('dd'=>1, 'dt'=>1),
				'hgroup'=>array('h1'=>1, 'h2'=>1, 'h3'=>1, 'h4'=>1, 'h5'=>1, 'h6'=>1),
				'menu'=>array('li'=>1),
				'ol'=>array('li'=>1),
				'optgroup'=>array('option'=>1),
				'option'=>array('#pcdata'=>1),
				'rbc'=>array('rb'=>1),
				'rp'=>array('#pcdata'=>1),
				'rtc'=>array('rt'=>1),
				'ruby'=>array('rb'=>1, 'rbc'=>1, 'rp'=>1, 'rt'=>1, 'rtc'=>1),
				'select'=>array('optgroup'=>1, 'option'=>1),
				'script'=>array('#pcdata'=>1),
				'table'=>array('caption'=>1, 'col'=>1, 'colgroup'=>1, 'tfoot'=>1, 'tbody'=>1, 'tr'=>1, 'thead'=>1),
				'tbody'=>array('tr'=>1),
				'tfoot'=>array('tr'=>1),
				'textarea'=>array('#pcdata'=>1),
				'thead'=>array('tr'=>1),
				'tr'=>array('td'=>1, 'th'=>1),
				'ul'=>array('li'=>1)
		); // Specific - immediate parent-child
		if( $this->data['C']['direct_list_nest']){$cS['ol'] = $cS['ul'] = $cS['menu'] += array('menu'=>1, 'ol'=>1, 'ul'=>1);}
		$cO = array(
				'address'=>array('p'=>1),
				'applet'=>array('param'=>1),
				'audio'=>array('source'=>1, 'track'=>1),
				'blockquote'=>array('script'=>1),
				'details'=>array('summary'=>1),
				'fieldset'=>array('legend'=>1, '#pcdata'=>1),
				'figure'=>array('figcaption'=>1),'form'=>array('script'=>1),
				'map'=>array('area'=>1),
				'object'=>array('param'=>1, 'embed'=>1),
				'video'=>array('source'=>1, 'track'=>1)
		); // Other
		$cT = array('colgroup'=>1, 'dd'=>1, 'dt'=>1, 'li'=>1, 'option'=>1, 'p'=>1, 'td'=>1, 'tfoot'=>1, 'th'=>1, 'thead'=>1, 'tr'=>1); // Omitable closing
		// block/inline type; a/ins/del both type; #pcdata: text
		$eB = array(
				'a'=>1, 'address'=>1, 'article'=>1, 'aside'=>1, 'blockquote'=>1, 'center'=>1, 'del'=>1, 'details'=>1, 'dir'=>1, 'dl'=>1, 'div'=>1, 'fieldset'=>1, 'figure'=>1,
				'footer'=>1, 'form'=>1, 'ins'=>1, 'h1'=>1, 'h2'=>1, 'h3'=>1, 'h4'=>1, 'h5'=>1, 'h6'=>1, 'header'=>1, 'hr'=>1, 'isindex'=>1, 'main'=>1, 'menu'=>1, 'nav'=>1, 'noscript'=>1,
				'ol'=>1, 'p'=>1, 'pre'=>1, 'section'=>1, 'style'=>1, 'table'=>1, 'ul'=>1
		);
		$eI = array(
				'#pcdata'=>1, 'a'=>1, 'abbr'=>1, 'acronym'=>1, 'applet'=>1, 'audio'=>1, 'b'=>1, 'bdi'=>1, 'bdo'=>1, 'big'=>1, 'br'=>1, 'button'=>1, 'canvas'=>1, 'cite'=>1, 'code'=>1,
				'command'=>1, 'data'=>1, 'datalist'=>1, 'del'=>1, 'dfn'=>1, 'em'=>1, 'embed'=>1, 'figcaption'=>1, 'font'=>1, 'i'=>1, 'iframe'=>1, 'img'=>1, 'input'=>1, 'ins'=>1,
				'kbd'=>1, 'label'=>1, 'link'=>1, 'map'=>1, 'mark'=>1, 'meta'=>1, 'meter'=>1, 'object'=>1, 'output'=>1, 'progress'=>1, 'q'=>1, 'ruby'=>1, 's'=>1, 'samp'=>1, 'select'=>1,
				'script'=>1, 'small'=>1, 'span'=>1, 'strike'=>1, 'strong'=>1, 'sub'=>1, 'summary'=>1, 'sup'=>1, 'textarea'=>1, 'time'=>1, 'tt'=>1, 'u'=>1, 'var'=>1, 'video'=>1, 'wbr'=>1
		);
		$eN = array(
				'a'=>1, 'address'=>1, 'article'=>1, 'aside'=>1, 'big'=>1, 'button'=>1, 'details'=>1, 'embed'=>1, 'fieldset'=>1, 'font'=>1, 'footer'=>1, 'form'=>1, 'header'=>1,
				'iframe'=>1, 'img'=>1, 'input'=>1, 'keygen'=>1, 'label'=>1, 'meter'=>1, 'nav'=>1, 'object'=>1, 'progress'=>1, 'ruby'=>1, 'script'=>1, 'select'=>1, 'small'=>1, 'sub'=>1,
				'sup'=>1, 'textarea'=>1, 'time'=>1
		); // Exclude from specific ele; $cN values
		$eO = array(
				'area'=>1, 'caption'=>1, 'col'=>1, 'colgroup'=>1, 'command'=>1, 'dd'=>1, 'dt'=>1, 'hgroup'=>1, 'keygen'=>1, 'legend'=>1, 'li'=>1, 'optgroup'=>1, 'option'=>1,
				'param'=>1, 'rb'=>1, 'rbc'=>1, 'rp'=>1, 'rt'=>1, 'rtc'=>1, 'script'=>1, 'source'=>1, 'tbody'=>1, 'td'=>1, 'tfoot'=>1, 'thead'=>1, 'th'=>1, 'tr'=>1, 'track'=>1
		); // Missing in $eB & $eI
		$eF = $eB + $eI;

		// $in sets allowed child
		$in = ((isset($eF[$in]) && $in != '#pcdata') or isset($eO[$in])) ? $in : 'div';
		if(isset($cE[$in])){
			return (!$do ? '' : str_replace(array('<', '>'), array('&lt;', '&gt;'), $t));
		}
		if(isset($cS[$in])){$inOk = $cS[$in];}
		elseif(isset($cI[$in])){$inOk = $eI; $cI['del'] = 1; $cI['ins'] = 1;}
		elseif(isset($cF[$in])){$inOk = $eF; unset($cI['del'], $cI['ins']);}
		elseif(isset($cB[$in])){$inOk = $eB; unset($cI['del'], $cI['ins']);}
		if(isset($cO[$in])){$inOk = $inOk + $cO[$in];}
		if(isset($cN[$in])){$inOk = array_diff_assoc($inOk, $cN[$in]);}

		$t = explode('<', $t);
		$ok = $q = array(); // $q seq list of open non-empty ele
		ob_start();

		for($i=-1, $ci=count($t); ++$i<$ci;){
			// allowed $ok in parent $p
			if($ql = count($q)){
				$p = array_pop($q);
				$q[] = $p;
				if(isset($cS[$p])){$ok = $cS[$p];}
				elseif(isset($cI[$p])){$ok = $eI; $cI['del'] = 1; $cI['ins'] = 1;}
				elseif(isset($cF[$p])){$ok = $eF; unset($cI['del'], $cI['ins']);}
				elseif(isset($cB[$p])){$ok = $eB; unset($cI['del'], $cI['ins']);}
				if(isset($cO[$p])){$ok = $ok + $cO[$p];}
				if(isset($cN[$p])){$ok = array_diff_assoc($ok, $cN[$p]);}
			}else{$ok = $inOk; unset($cI['del'], $cI['ins']);}
			// bad tags, & ele content
			if(isset($e) && ($do == 1 or (isset($ok['#pcdata']) && ($do == 3 or $do == 5)))){
				echo '&lt;', $s, $e, $a, '&gt;';
			}
			if(isset($x[0])){
				if(strlen(trim($x)) && (($ql && isset($cB[$p])) or (isset($cB[$in]) && !$ql))){
					echo '<div>', $x, '</div>';
				}
				elseif($do < 3 or isset($ok['#pcdata'])){echo $x;}
				elseif(strpos($x, "\x02\x04")){
					foreach(preg_split('`(\x01\x02[^\x01\x02]+\x02\x01)`', $x, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $v){
						echo (substr($v, 0, 2) == "\x01\x02" ? $v : ($do > 4 ? preg_replace('`\S`', '', $v) : ''));
					}
				}elseif($do > 4){echo preg_replace('`\S`', '', $x);}
			}
			// get markup
			if(!preg_match('`^(/?)([a-z1-6]+)([^>]*)>(.*)`sm', $t[$i], $r)){$x = $t[$i]; continue;}
			$s = null; $e = null; $a = null; $x = null; list($all, $s, $e, $a, $x) = $r;
			// close tag
			if($s){
				if(isset($cE[$e]) or !in_array($e, $q)){continue;} // Empty/unopen
				if($p == $e){array_pop($q); echo '</', $e, '>'; unset($e); continue;} // Last open
				$add = ''; // Nesting - close open tags that need to be
				for($j=-1, $cj=count($q); ++$j<$cj;){
					if(($d = array_pop($q)) == $e){break;}
					else{$add .= "</{$d}>";}
				}
				echo $add, '</', $e, '>'; unset($e); continue;
			}
			// open tag
			// $cB ele needs $eB ele as child
			if(isset($cB[$e]) && strlen(trim($x))){
				$t[$i] = "{$e}{$a}>";
				array_splice($t, $i+1, 0, 'div>'. $x); unset($e, $x); ++$ci; --$i; continue;
			}
			if((($ql && isset($cB[$p])) or (isset($cB[$in]) && !$ql)) && !isset($eB[$e]) && !isset($ok[$e])){
				array_splice($t, $i, 0, 'div>'); unset($e, $x); ++$ci; --$i; continue;
			}
			// if no open ele, $in = parent; mostly immediate parent-child relation should hold
			if(!$ql or !isset($eN[$e]) or !array_intersect($q, $cN2)){
				if(!isset($ok[$e])){
					if($ql && isset($cT[$p])){echo '</', array_pop($q), '>'; unset($e, $x); --$i;}
					continue;
				}
				if(!isset($cE[$e])){$q[] = $e;}
				echo '<', $e, $a, '>'; unset($e); continue;
			}
			// specific parent-child
			if(isset($cS[$p][$e])){
				if(!isset($cE[$e])){$q[] = $e;}
				echo '<', $e, $a, '>'; unset($e); continue;
			}
			// nesting
			$add = '';
			$q2 = array();
			for($k=-1, $kc=count($q); ++$k<$kc;){
				$d = $q[$k];
				$ok2 = array();
				if(isset($cS[$d])){$q2[] = $d; continue;}
				$ok2 = isset($cI[$d]) ? $eI : $eF;
				if(isset($cO[$d])){$ok2 = $ok2 + $cO[$d];}
				if(isset($cN[$d])){$ok2 = array_diff_assoc($ok2, $cN[$d]);}
				if(!isset($ok2[$e])){
					if(!$k && !isset($inOk[$e])){continue 2;}
					$add = "</{$d}>";
					for(;++$k<$kc;){$add = "</{$q[$k]}>{$add}";}
					break;
				}
				else{$q2[] = $d;}
			}
			$q = $q2;
			if(!isset($cE[$e])){$q[] = $e;}
			echo $add, '<', $e, $a, '>'; unset($e); continue;
		}

		// end
		if($ql = count($q)){
			$p = array_pop($q);
			$q[] = $p;
			if(isset($cS[$p])){$ok = $cS[$p];}
			elseif(isset($cI[$p])){$ok = $eI; $cI['del'] = 1; $cI['ins'] = 1;}
			elseif(isset($cF[$p])){$ok = $eF; unset($cI['del'], $cI['ins']);}
			elseif(isset($cB[$p])){$ok = $eB; unset($cI['del'], $cI['ins']);}
			if(isset($cO[$p])){$ok = $ok + $cO[$p];}
			if(isset($cN[$p])){$ok = array_diff_assoc($ok, $cN[$p]);}
		}else{$ok = $inOk; unset($cI['del'], $cI['ins']);}
		if(isset($e) && ($do == 1 or (isset($ok['#pcdata']) && ($do == 3 or $do == 5)))){
			echo '&lt;', $s, $e, $a, '&gt;';
		}
		if(isset($x[0])){
			if(strlen(trim($x)) && (($ql && isset($cB[$p])) or (isset($cB[$in]) && !$ql))){
				echo '<div>', $x, '</div>';
			}
			elseif($do < 3 or isset($ok['#pcdata'])){echo $x;}
			elseif(strpos($x, "\x02\x04")){
				foreach(preg_split('`(\x01\x02[^\x01\x02]+\x02\x01)`', $x, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $v){
					echo (substr($v, 0, 2) == "\x01\x02" ? $v : ($do > 4 ? preg_replace('`\S`', '', $v) : ''));
				}
			}elseif($do > 4){echo preg_replace('`\S`', '', $x);}
		}
		while(!empty($q) && ($e = array_pop($q))){echo '</', $e, '>';}
		$o = ob_get_contents();
		ob_end_clean();
		return $o;
		// eof
	}

	function hl_cmtcd($t)
	{
		// comment/CDATA sec handler
		$t = $t[0];
		if(!($v = $this->data['C'][$n = $t[3] == '-' ? 'comment' : 'cdata'])){return $t;}
		if($v == 1){return '';}
		if($n == 'comment'){
			if(substr(($t = preg_replace('`--+`', '-', substr($t, 4, -3))), -1) != ' '){$t .= ' ';}
		}
		else{$t = substr($t, 1, -1);}
		$t = $v == 2 ? str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), $t) : $t;
		return str_replace(array('&', '<', '>'), array("\x03", "\x04", "\x05"), ($n == 'comment' ? "\x01\x02\x04!--$t--\x05\x02\x01" : "\x01\x01\x04$t\x05\x01\x01"));
		// eof
	}

	function hl_ent($t)
	{
		// entitity handler
		$t = $t[1];
		static $U = array('quot'=>1,'amp'=>1,'lt'=>1,'gt'=>1);
		static $N = array(
				'fnof'=>'402', 'Alpha'=>'913', 'Beta'=>'914', 'Gamma'=>'915', 'Delta'=>'916', 'Epsilon'=>'917', 'Zeta'=>'918', 'Eta'=>'919', 'Theta'=>'920', 'Iota'=>'921',
				'Kappa'=>'922', 'Lambda'=>'923', 'Mu'=>'924', 'Nu'=>'925', 'Xi'=>'926', 'Omicron'=>'927', 'Pi'=>'928', 'Rho'=>'929', 'Sigma'=>'931', 'Tau'=>'932', 'Upsilon'=>'933',
				'Phi'=>'934', 'Chi'=>'935', 'Psi'=>'936', 'Omega'=>'937', 'alpha'=>'945', 'beta'=>'946', 'gamma'=>'947', 'delta'=>'948', 'epsilon'=>'949', 'zeta'=>'950', 'eta'=>'951',
				'theta'=>'952', 'iota'=>'953', 'kappa'=>'954', 'lambda'=>'955', 'mu'=>'956', 'nu'=>'957', 'xi'=>'958', 'omicron'=>'959', 'pi'=>'960', 'rho'=>'961', 'sigmaf'=>'962',
				'sigma'=>'963', 'tau'=>'964', 'upsilon'=>'965', 'phi'=>'966', 'chi'=>'967', 'psi'=>'968', 'omega'=>'969', 'thetasym'=>'977', 'upsih'=>'978', 'piv'=>'982', 'bull'=>'8226',
				'hellip'=>'8230', 'prime'=>'8242', 'Prime'=>'8243', 'oline'=>'8254', 'frasl'=>'8260', 'weierp'=>'8472', 'image'=>'8465', 'real'=>'8476', 'trade'=>'8482', 'alefsym'=>'8501',
				'larr'=>'8592', 'uarr'=>'8593', 'rarr'=>'8594', 'darr'=>'8595', 'harr'=>'8596', 'crarr'=>'8629', 'lArr'=>'8656', 'uArr'=>'8657', 'rArr'=>'8658', 'dArr'=>'8659', 'hArr'=>'8660',
				'forall'=>'8704', 'part'=>'8706', 'exist'=>'8707', 'empty'=>'8709', 'nabla'=>'8711', 'isin'=>'8712', 'notin'=>'8713', 'ni'=>'8715', 'prod'=>'8719', 'sum'=>'8721',
				'minus'=>'8722', 'lowast'=>'8727', 'radic'=>'8730', 'prop'=>'8733', 'infin'=>'8734', 'ang'=>'8736', 'and'=>'8743', 'or'=>'8744', 'cap'=>'8745', 'cup'=>'8746', 'int'=>'8747',
				'there4'=>'8756', 'sim'=>'8764', 'cong'=>'8773', 'asymp'=>'8776', 'ne'=>'8800', 'equiv'=>'8801', 'le'=>'8804', 'ge'=>'8805', 'sub'=>'8834', 'sup'=>'8835', 'nsub'=>'8836',
				'sube'=>'8838', 'supe'=>'8839', 'oplus'=>'8853', 'otimes'=>'8855', 'perp'=>'8869', 'sdot'=>'8901', 'lceil'=>'8968', 'rceil'=>'8969', 'lfloor'=>'8970', 'rfloor'=>'8971',
				'lang'=>'9001', 'rang'=>'9002', 'loz'=>'9674', 'spades'=>'9824', 'clubs'=>'9827', 'hearts'=>'9829', 'diams'=>'9830', 'apos'=>'39',  'OElig'=>'338', 'oelig'=>'339',
				'Scaron'=>'352', 'scaron'=>'353', 'Yuml'=>'376', 'circ'=>'710', 'tilde'=>'732', 'ensp'=>'8194', 'emsp'=>'8195', 'thinsp'=>'8201', 'zwnj'=>'8204', 'zwj'=>'8205', 'lrm'=>'8206',
				'rlm'=>'8207', 'ndash'=>'8211', 'mdash'=>'8212', 'lsquo'=>'8216', 'rsquo'=>'8217', 'sbquo'=>'8218', 'ldquo'=>'8220', 'rdquo'=>'8221', 'bdquo'=>'8222', 'dagger'=>'8224',
				'Dagger'=>'8225', 'permil'=>'8240', 'lsaquo'=>'8249', 'rsaquo'=>'8250', 'euro'=>'8364', 'nbsp'=>'160', 'iexcl'=>'161', 'cent'=>'162', 'pound'=>'163', 'curren'=>'164',
				'yen'=>'165', 'brvbar'=>'166', 'sect'=>'167', 'uml'=>'168', 'copy'=>'169', 'ordf'=>'170', 'laquo'=>'171', 'not'=>'172', 'shy'=>'173', 'reg'=>'174', 'macr'=>'175',
				'deg'=>'176', 'plusmn'=>'177', 'sup2'=>'178', 'sup3'=>'179', 'acute'=>'180', 'micro'=>'181', 'para'=>'182', 'middot'=>'183', 'cedil'=>'184', 'sup1'=>'185', 'ordm'=>'186',
				'raquo'=>'187', 'frac14'=>'188', 'frac12'=>'189', 'frac34'=>'190', 'iquest'=>'191', 'Agrave'=>'192', 'Aacute'=>'193', 'Acirc'=>'194', 'Atilde'=>'195', 'Auml'=>'196',
				'Aring'=>'197', 'AElig'=>'198', 'Ccedil'=>'199', 'Egrave'=>'200', 'Eacute'=>'201', 'Ecirc'=>'202', 'Euml'=>'203', 'Igrave'=>'204', 'Iacute'=>'205', 'Icirc'=>'206',
				'Iuml'=>'207', 'ETH'=>'208', 'Ntilde'=>'209', 'Ograve'=>'210', 'Oacute'=>'211', 'Ocirc'=>'212', 'Otilde'=>'213', 'Ouml'=>'214', 'times'=>'215', 'Oslash'=>'216',
				'Ugrave'=>'217', 'Uacute'=>'218', 'Ucirc'=>'219', 'Uuml'=>'220', 'Yacute'=>'221', 'THORN'=>'222', 'szlig'=>'223', 'agrave'=>'224', 'aacute'=>'225', 'acirc'=>'226',
				'atilde'=>'227', 'auml'=>'228', 'aring'=>'229', 'aelig'=>'230', 'ccedil'=>'231', 'egrave'=>'232', 'eacute'=>'233', 'ecirc'=>'234', 'euml'=>'235', 'igrave'=>'236',
				'iacute'=>'237', 'icirc'=>'238', 'iuml'=>'239', 'eth'=>'240', 'ntilde'=>'241', 'ograve'=>'242', 'oacute'=>'243', 'ocirc'=>'244', 'otilde'=>'245', 'ouml'=>'246',
				'divide'=>'247', 'oslash'=>'248', 'ugrave'=>'249', 'uacute'=>'250', 'ucirc'=>'251', 'uuml'=>'252', 'yacute'=>'253', 'thorn'=>'254', 'yuml'=>'255'
		);
		if($t[0] != '#'){
			return ( $this->data['C']['and_mark'] ? "\x06" : '&')
			. (isset($U[$t])
					? $t
					: (isset($N[$t])
							? (! $this->data['C']['named_entity']
									? '#'. ( $this->data['C']['hexdec_entity'] > 1
											? 'x'. dechex($N[$t])
											: $N[$t])
									: $t)
							: 'amp;'. $t)). ';';
		}
		if(($n = ctype_digit($t = substr($t, 1))
				? intval($t)
				: hexdec(substr($t, 1))) < 9
				|| ($n > 13 && $n < 32)
				|| $n == 11
				|| $n == 12
				|| ($n > 126 && $n < 160 && $n != 133)
				|| ($n > 55295 && ($n < 57344
						|| ($n > 64975 && $n < 64992)
						|| $n == 65534
						|| $n == 65535
						|| $n > 1114111)
				))
		{
			return ( $this->data['C']['and_mark'] ? "\x06" : '&'). "amp;#{$t};";
		}
		return ( $this->data['C']['and_mark'] ? "\x06" : '&'). '#'
				. (((ctype_digit($t) && $this->data['C']['hexdec_entity'] < 2) || ! $this->data['C']['hexdec_entity'])
						? $n
						: 'x'. dechex($n)). ';';
		// eof
	}

	function hl_prot($p, $c=null){
		// check URL scheme
		$b = $a = '';
		if($c == null){$c = 'style'; $b = $p[1]; $a = $p[3]; $p = trim($p[2]);}
		$c = isset( $this->data['C']['schemes'][$c])
		? $this->data['C']['schemes'][$c]
		: $this->data['C']['schemes']['*'];
		static $d = 'denied:';
		if(isset($c['!']) && substr($p, 0, 7) != $d){$p = "$d$p";}
		if(isset($c['*']) or !strcspn($p, '#?;') or (substr($p, 0, 7) == $d)){return "{$b}{$p}{$a}";} // All ok, frag, query, param
		if(preg_match('`^([^:?[@!$()*,=/\'\]]+?)(:|&#(58|x3a);|%3a|\\\\0{0,4}3a).`i', $p, $m) && !isset($c[strtolower($m[1])])){ // Denied prot
			return "{$b}{$d}{$p}{$a}";
		}
		if( $this->data['C']['abs_url']){
			if( $this->data['C']['abs_url'] == -1 && strpos($p, $this->data['C']['base_url']) === 0){ // Make url rel
				$p = substr($p, strlen( $this->data['C']['base_url']));
			}elseif(empty($m[1])){ // Make URL abs
				if(substr($p, 0, 2) == '//'){$p = substr( $this->data['C']['base_url'], 0, strpos( $this->data['C']['base_url'], ':')+1). $p;}
				elseif($p[0] == '/'){$p = preg_replace('`(^.+?://[^/]+)(.*)`', '$1', $this->data['C']['base_url']). $p;}
				elseif(strcspn($p, './')){$p = $this->data['C']['base_url']. $p;}
				else{
					preg_match('`^([a-zA-Z\d\-+.]+://[^/]+)(.*)`', $this->data['C']['base_url'], $m);
					$p = preg_replace('`(?<=/)\./`', '', $m[2]. $p);
					while(preg_match('`(?<=/)([^/]{3,}|[^/.]+?|\.[^/.]|[^/.]\.)/\.\./`', $p)){
						$p = preg_replace('`(?<=/)([^/]{3,}|[^/.]+?|\.[^/.]|[^/.]\.)/\.\./`', '', $p);
					}
					$p = $m[1]. $p;
				}
			}
		}
		return "{$b}{$p}{$a}";
		// eof
	}

	function hl_regex($p)
	{
		// ?regex
		if(empty($p)){return 0;}
		if($t = ini_get('track_errors')){$o = isset($php_errormsg) ? $php_errormsg : null;}
		else{ini_set('track_errors', 1);}
		unset($php_errormsg);
		if(($d = ini_get('display_errors'))){ini_set('display_errors', 0);}
		preg_match($p, '');
		if($d){ini_set('display_errors', 1);}
		$r = isset($php_errormsg) ? 0 : 1;
		if($t){$php_errormsg = isset($o) ? $o : null;}
		else{ini_set('track_errors', 0);}
		return $r;
		// eof
	}

	function hl_spec($t)
	{
		// final $spec
		$s = array();
		$t = str_replace(
				array("\t", "\r", "\n", ' '),
				'',
				preg_replace_callback(
						'/"(?>(`.|[^"])*)"/sm',
						create_function(
								'$m',
								'return substr(str_replace(array(";", "|", "~", " ", ",", "/", "(", ")", \'`"\'), array("\x01", "\x02", "\x03", "\x04", "\x05", "\x06", "\x07", "\x08", "\""), $m[0]), 1, -1);'
						),
						trim($t)
				)
		);
		for($i = count(($t = explode(';', $t))); --$i>=0;){
			$w = $t[$i];
			if(empty($w) or ($e = strpos($w, '=')) === false or !strlen(($a =  substr($w, $e+1)))){continue;}
			$y = $n = array();
			foreach(explode(',', $a) as $v){
				if(!preg_match('`^([a-z:\-\*]+)(?:\((.*?)\))?`i', $v, $m)){continue;}
				if(($x = strtolower($m[1])) == '-*'){$n['*'] = 1; continue;}
				if($x[0] == '-'){$n[substr($x, 1)] = 1; continue;}
				if(!isset($m[2])){$y[$x] = 1; continue;}
				foreach(explode('/', $m[2]) as $m){
					if(empty($m) or ($p = strpos($m, '=')) == 0 or $p < 5){$y[$x] = 1; continue;}
					$y[$x][strtolower(substr($m, 0, $p))] = str_replace(
							array("\x01", "\x02", "\x03", "\x04", "\x05", "\x06", "\x07", "\x08"),
							array(";", "|", "~", " ", ",", "/", "(", ")"),
							substr($m, $p+1)
					);
				}
				if(isset($y[$x]['match']) && !hl_regex($y[$x]['match'])){unset($y[$x]['match']);}
				if(isset($y[$x]['nomatch']) && !hl_regex($y[$x]['nomatch'])){unset($y[$x]['nomatch']);}
			}
			if(!count($y) && !count($n)){continue;}
			foreach(explode(',', substr($w, 0, $e)) as $v){
				if(!strlen(($v = strtolower($v)))){continue;}
				if(count($y)){$s[$v] = $y;}
				if(count($n)){$s[$v]['n'] = $n;}
			}
		}
		return $s;
		// eof
	}

	function hl_tag($t){
		// tag/attribute handler
		$t = $t[0];
		// invalid < >
		if($t == '< '){return '&lt; ';}
		if($t == '>'){return '&gt;';}
		if(!preg_match('`^<(/?)([a-zA-Z][a-zA-Z1-6]*)([^>]*?)\s?>$`m', $t, $m)){
			return str_replace(array('<', '>'), array('&lt;', '&gt;'), $t);
		}elseif(!isset( $this->data['C']['elements'][($e = strtolower($m[2]))])){
			return (( $this->data['C']['keep_bad']%2) ? str_replace(array('<', '>'), array('&lt;', '&gt;'), $t) : '');
		}
		// attr string
		$a = str_replace(array("\n", "\r", "\t"), ' ', trim($m[3]));
		// tag transform
		static $eD = array('acronym'=>1, 'applet'=>1, 'big'=>1, 'center'=>1, 'dir'=>1, 'font'=>1, 'isindex'=>1, 's'=>1, 'strike'=>1, 'tt'=>1); // Deprecated
		if( $this->data['C']['make_tag_strict'] && isset($eD[$e])){
			$trt = $this->hl_tag2($e, $a, $this->data['C']['make_tag_strict']);
			if(!$e){return (( $this->data['C']['keep_bad']%2) ? str_replace(array('<', '>'), array('&lt;', '&gt;'), $t) : '');}
		}
		// close tag
		static $eE = array(
				'area'=>1, 'br'=>1, 'col'=>1, 'command'=>1, 'embed'=>1, 'hr'=>1, 'img'=>1, 'input'=>1,
				'isindex'=>1, 'keygen'=>1, 'link'=>1, 'meta'=>1, 'param'=>1, 'source'=>1, 'track'=>1, 'wbr'=>1
		); // Empty ele
		if(!empty($m[1])){
			return (!isset($eE[$e])
					? (empty( $this->data['C']['hook_tag'])
							? "</$e>" : $this->data['C']['hook_tag']($e))
					: (( $this->data['C']['keep_bad'])%2
							? str_replace(array('<', '>'), array('&lt;', '&gt;'), $t)
							: ''));
		}

		// open tag & attr
		static $aN = array(
				'abbr'=>array('td'=>1, 'th'=>1),
				'accept-charset'=>array('form'=>1),
				'accept'=>array('form'=>1, 'input'=>1),
				'action'=>array('form'=>1),
				'align'=>array('caption'=>1, 'embed'=>1, 'applet'=>1, 'iframe'=>1, 'img'=>1, 'input'=>1, 'object'=>1, 'legend'=>1, 'table'=>1, 'hr'=>1, 'div'=>1,
						'h1'=>1, 'h2'=>1, 'h3'=>1, 'h4'=>1, 'h5'=>1, 'h6'=>1, 'p'=>1, 'col'=>1, 'colgroup'=>1, 'tbody'=>1, 'td'=>1, 'tfoot'=>1, 'th'=>1, 'thead'=>1, 'tr'=>1),
				'alt'=>array('applet'=>1, 'area'=>1, 'img'=>1, 'input'=>1),
				'archive'=>array('applet'=>1, 'object'=>1),
				'async'=>array('script'=>1),
				'autocomplete'=>array('input'=>1),
				'autofocus'=>array('button'=>1, 'input'=>1, 'keygen'=>1, 'select'=>1, 'textarea'=>1),
				'autoplay'=>array('audio'=>1, 'video'=>1),
				'axis'=>array('td'=>1, 'th'=>1),
				'bgcolor'=>array('embed'=>1, 'table'=>1, 'tr'=>1, 'td'=>1, 'th'=>1),
				'border'=>array('table'=>1, 'img'=>1, 'object'=>1),
				'bordercolor'=>array('table'=>1, 'td'=>1, 'tr'=>1),
				'cellpadding'=>array('table'=>1),
				'cellspacing'=>array('table'=>1),
				'challenge'=>array('keygen'=>1),
				'char'=>array('col'=>1, 'colgroup'=>1, 'tbody'=>1, 'td'=>1, 'tfoot'=>1, 'th'=>1, 'thead'=>1, 'tr'=>1),
				'charoff'=>array('col'=>1, 'colgroup'=>1, 'tbody'=>1, 'td'=>1, 'tfoot'=>1, 'th'=>1, 'thead'=>1, 'tr'=>1),
				'charset'=>array('a'=>1, 'script'=>1),
				'checked'=>array('command'=>1, 'input'=>1),
				'cite'=>array('blockquote'=>1, 'q'=>1, 'del'=>1, 'ins'=>1),
				'classid'=>array('object'=>1),
				'clear'=>array('br'=>1),
				'code'=>array('applet'=>1),
				'codebase'=>array('object'=>1, 'applet'=>1),
				'codetype'=>array('object'=>1),
				'color'=>array('font'=>1),
				'cols'=>array('textarea'=>1),
				'colspan'=>array('td'=>1, 'th'=>1),
				'compact'=>array('dir'=>1, 'dl'=>1, 'menu'=>1, 'ol'=>1, 'ul'=>1),
				'content'=>array('meta'=>1),
				'controls'=>array('audio'=>1, 'video'=>1),
				'coords'=>array('area'=>1, 'a'=>1),
				'crossorigin'=>array('img'=>1),
				'data'=>array('object'=>1),
				'datetime'=>array('del'=>1, 'ins'=>1, 'time'=>1),
				'declare'=>array('object'=>1),
				'default'=>array('track'=>1),
				'defer'=>array('script'=>1),
				'dirname'=>array('input'=>1, 'textarea'=>1),
				'disabled'=>array('button'=>1, 'command'=>1, 'fieldset'=>1, 'input'=>1, 'keygen'=>1, 'optgroup'=>1, 'option'=>1, 'select'=>1, 'textarea'=>1),
				'download'=>array('a'=>1),
				'enctype'=>array('form'=>1),
				'face'=>array('font'=>1),
				'flashvars'=>array('embed'=>1),
				'for'=>array('label'=>1, 'output'=>1),
				'form'=>array('button'=>1, 'fieldset'=>1, 'input'=>1, 'keygen'=>1, 'label'=>1, 'object'=>1, 'output'=>1, 'select'=>1, 'textarea'=>1),
				'formaction'=>array('button'=>1, 'input'=>1),
				'formenctype'=>array('button'=>1, 'input'=>1),
				'formmethod'=>array('button'=>1, 'input'=>1),
				'formnovalidate'=>array('button'=>1, 'input'=>1),
				'formtarget'=>array('button'=>1, 'input'=>1),
				'frame'=>array('table'=>1),
				'frameborder'=>array('iframe'=>1),
				'headers'=>array('td'=>1, 'th'=>1),
				'height'=>array('canvas'=>1, 'embed'=>1, 'iframe'=>1, 'input'=>1, 'td'=>1, 'th'=>1, 'img'=>1, 'object'=>1, 'applet'=>1, 'video'=>1),
				'high'=>array('meter'=>1),
				'href'=>array('a'=>1, 'area'=>1, 'link'=>1),
				'hreflang'=>array('a'=>1, 'area'=>1, 'link'=>1),
				'hspace'=>array('applet'=>1, 'embed'=>1, 'img'=>1, 'object'=>1),
				'icon'=>array('command'=>1),
				'ismap'=>array('img'=>1, 'input'=>1),
				'keyparams'=>array('keygen'=>1),
				'keytype'=>array('keygen'=>1),
				'kind'=>array('track'=>1),
				'label'=>array('command'=>1, 'menu'=>1, 'option'=>1, 'optgroup'=>1, 'track'=>1),
				'language'=>array('script'=>1),
				'list'=>array('input'=>1),
				'longdesc'=>array('img'=>1, 'iframe'=>1),
				'loop'=>array('audio'=>1, 'video'=>1),
				'low'=>array('meter'=>1),
				'marginheight'=>array('iframe'=>1),
				'marginwidth'=>array('iframe'=>1),
				'max'=>array('input'=>1, 'meter'=>1, 'progress'=>1),
				'maxlength'=>array('input'=>1, 'textarea'=>1),
				'media'=>array('a'=>1, 'area'=>1, 'link'=>1, 'source'=>1, 'style'=>1),
				'mediagroup'=>array('audio'=>1, 'video'=>1),
				'method'=>array('form'=>1),
				'min'=>array('input'=>1, 'meter'=>1),
				'model'=>array('embed'=>1),
				'multiple'=>array('input'=>1, 'select'=>1),
				'muted'=>array('audio'=>1, 'video'=>1),
				'name'=>array('button'=>1, 'embed'=>1, 'fieldset'=>1, 'keygen'=>1, 'output'=>1, 'textarea'=>1, 'applet'=>1, 'select'=>1, 'form'=>1, 'iframe'=>1, 'img'=>1, 'a'=>1,
						'input'=>1, 'object'=>1, 'map'=>1, 'param'=>1),
				'nohref'=>array('area'=>1),
				'noshade'=>array('hr'=>1),
				'novalidate'=>array('form'=>1),
				'nowrap'=>array('td'=>1, 'th'=>1),
				'object'=>array('applet'=>1),
				'open'=>array('details'=>1),
				'optimum'=>array('meter'=>1),
				'pattern'=>array('input'=>1),
				'ping'=>array('a'=>1),
				'placeholder'=>array('input'=>1, 'textarea'=>1),
				'pluginspage'=>array('embed'=>1),
				'pluginurl'=>array('embed'=>1),
				'poster'=>array('video'=>1),
				'pqg'=>array('keygen'=>1),
				'preload'=>array('audio'=>1, 'video'=>1),
				'prompt'=>array('isindex'=>1),
				'radiogroup'=>array('command'=>1),
				'readonly'=>array('textarea'=>1, 'input'=>1),
				'rel'=>array('a'=>1, 'area'=>1, 'link'=>1),
				'required'=>array('input'=>1, 'select'=>1, 'textarea'=>1),
				'rev'=>array('a'=>1),
				'reversed'=>array('ol'=>1),
				'rows'=>array('textarea'=>1),
				'rowspan'=>array('td'=>1, 'th'=>1),
				'rules'=>array('table'=>1),
				'sandbox'=>array('iframe'=>1),
				'scope'=>array('td'=>1, 'th'=>1),
				'scoped'=>array('style'=>1),
				'scrolling'=>array('iframe'=>1),
				'seamless'=>array('iframe'=>1),
				'selected'=>array('option'=>1),
				'shape'=>array('area'=>1, 'a'=>1),
				'size'=>array('hr'=>1, 'font'=>1, 'input'=>1, 'select'=>1),
				'sizes'=>array('link'=>1),
				'span'=>array('col'=>1, 'colgroup'=>1),
				'src'=>array('audio'=>1, 'embed'=>1, 'iframe'=>1, 'img'=>1, 'input'=>1, 'source'=>1, 'script'=>1, 'track'=>1, 'video'=>1),
				'srcdoc'=>array('iframe'=>1),
				'srclang'=>array('track'=>1),
				'standby'=>array('object'=>1),
				'start'=>array('ol'=>1),
				'step'=>array('input'=>1),
				'summary'=>array('table'=>1),
				'target'=>array('a'=>1, 'area'=>1, 'form'=>1),
				'type'=>array('a'=>1, 'area'=>1, 'command'=>1, 'embed'=>1, 'link'=>1, 'menu'=>1, 'object'=>1, 'param'=>1, 'script'=>1, 'source'=>1, 'style'=>1, 'input'=>1, 'li'=>1,
						'ol'=>1, 'ul'=>1, 'button'=>1),
				'typemustmatch'=>array('object'=>1),
				'usemap'=>array('img'=>1, 'input'=>1, 'object'=>1),
				'valign'=>array('col'=>1, 'colgroup'=>1, 'tbody'=>1, 'td'=>1, 'tfoot'=>1, 'th'=>1, 'thead'=>1, 'tr'=>1),
				'value'=>array('data'=>1, 'input'=>1, 'meter'=>1, 'option'=>1, 'param'=>1, 'progress'=>1, 'button'=>1, 'li'=>1),
				'valuetype'=>array('param'=>1),
				'vspace'=>array('applet'=>1, 'embed'=>1, 'img'=>1, 'object'=>1),
				'width'=>array('canvas'=>1, 'embed'=>1, 'hr'=>1, 'iframe'=>1, 'img'=>1, 'input'=>1, 'object'=>1, 'table'=>1, 'td'=>1, 'th'=>1, 'applet'=>1, 'col'=>1,
						'colgroup'=>1, 'pre'=>1, 'video'=>1),
				'wmode'=>array('embed'=>1),
				'wrap'=>array('textarea'=>1)
		); // Ele-specific
				static $aNE = array(
						'checkbox'=>1, 'checked'=>1, 'command'=>1, 'compact'=>1, 'declare'=>1, 'defer'=>1, 'default'=>1, 'disabled'=>1, 'ismap'=>1, 'itemscope'=>1, 'multiple'=>1, 'nohref'=>1,
						'noresize'=>1, 'noshade'=>1, 'nowrap'=>1, 'open'=>1, 'radio'=>1, 'readonly'=>1, 'required'=>1, 'reversed'=>1, 'selected'=>1
				); // Empty
				static $aNP = array(
						'action'=>1, 'cite'=>1, 'classid'=>1, 'codebase'=>1, 'data'=>1, 'href'=>1, 'itemtype'=>1, 'longdesc'=>1, 'model'=>1, 'pluginspage'=>1, 'pluginurl'=>1, 'usemap'=>1
				); // Need scheme check; excludes style, on* & src
				static $aNU = array(
						'accesskey'=>1, 'aria-activedescendant'=>1, 'aria-atomic'=>1, 'aria-autocomplete'=>1, 'aria-busy'=>1, 'aria-checked'=>1, 'aria-controls'=>1, 'aria-describedby'=>1,
						'aria-disabled'=>1, 'aria-dropeffect'=>1, 'aria-expanded'=>1, 'aria-flowto'=>1, 'aria-grabbed'=>1, 'aria-haspopup'=>1, 'aria-hidden'=>1, 'aria-invalid'=>1, 'aria-label'=>1,
						'aria-labelledby'=>1, 'aria-level'=>1, 'aria-live'=>1, 'aria-multiline'=>1, 'aria-multiselectable'=>1, 'aria-orientation'=>1, 'aria-owns'=>1, 'aria-posinset'=>1,
						'aria-pressed'=>1, 'aria-readonly'=>1, 'aria-relevant'=>1, 'aria-required'=>1, 'aria-selected'=>1, 'aria-setsize'=>1, 'aria-sort'=>1, 'aria-valuemax'=>1,
						'aria-valuemin'=>1, 'aria-valuenow'=>1, 'aria-valuetext'=>1, 'class'=>1, 'contenteditable'=>1, 'contextmenu'=>1, 'dir'=>1, 'draggable'=>1, 'dropzone'=>1, 'hidden'=>1,
						'id'=>1, 'inert'=>1, 'itemid'=>1, 'itemprop'=>1, 'itemref'=>1, 'itemscope'=>1, 'itemtype'=>1, 'lang'=>1, 'spellcheck'=>1, 'style'=>1, 'tabindex'=>1, 'title'=>1,
						'translate'=>1, 'onabort'=>1, 'onblur'=>1, 'oncanplay'=>1, 'oncanplaythrough'=>1, 'onchange'=>1, 'onclick'=>1, 'oncontextmenu'=>1, 'oncuechange'=>1, 'ondblclick'=>1,
						'ondrag'=>1, 'ondragend'=>1, 'ondragenter'=>1, 'ondragleave'=>1, 'ondragover'=>1, 'ondragstart'=>1, 'ondrop'=>1, 'ondurationchange'=>1, 'onemptied'=>1, 'onended'=>1,
						'onerror'=>1, 'onfocus'=>1, 'oninput'=>1, 'oninvalid'=>1, 'onkeydown'=>1, 'onkeypress'=>1, 'onkeyup'=>1, 'onload'=>1, 'onloadeddata'=>1, 'onloadedmetadata'=>1,
						'onloadstart'=>1, 'onmousedown'=>1, 'onmousemove'=>1, 'onmouseout'=>1, 'onmouseover'=>1, 'onmouseup'=>1, 'onmousewheel'=>1, 'onpause'=>1, 'onplay'=>1, 'onplaying'=>1,
						'onprogress'=>1, 'onratechange'=>1, 'onreadystatechange'=>1, 'onreset'=>1, 'onscroll'=>1, 'onseeked'=>1, 'onseeking'=>1, 'onselect'=>1, 'onshow'=>1, 'onstalled'=>1,
						'onsubmit'=>1, 'onsuspend'=>1, 'ontimeupdate'=>1, 'onvolumechange'=>1, 'onwaiting'=>1, 'role'=>1, 'translate'=>1, 'xmlns'=>1, 'xml:base'=>1, 'xml:lang'=>1, 'xml:space'=>1
				); // Univ

				if( $this->data['C']['lc_std_val']){
					// predef attr vals for $eAL & $aNE ele
					static $aNL = array(
							'all'=>1, 'auto'=>1, 'baseline'=>1, 'bottom'=>1, 'button'=>1, 'captions'=>1, 'center'=>1, 'chapters'=>1, 'char'=>1, 'checkbox'=>1, 'circle'=>1, 'col'=>1,
							'colgroup'=>1, 'color'=>1, 'cols'=>1, 'data'=>1, 'date'=>1, 'datetime'=>1, 'datetime-local'=>1, 'default'=>1, 'descriptions'=>1, 'email'=>1, 'file'=>1,
							'get'=>1, 'groups'=>1, 'hidden'=>1, 'image'=>1, 'justify'=>1, 'left'=>1, 'ltr'=>1, 'metadata'=>1, 'middle'=>1, 'month'=>1, 'none'=>1, 'number'=>1, 'object'=>1,
							'password'=>1, 'poly'=>1, 'post'=>1, 'preserve'=>1, 'radio'=>1, 'range'=>1, 'rect'=>1, 'ref'=>1, 'reset'=>1, 'right'=>1, 'row'=>1, 'rowgroup'=>1, 'rows'=>1,
							'rtl'=>1, 'search'=>1, 'submit'=>1, 'subtitles'=>1, 'tel'=>1, 'text'=>1, 'top'=>1, 'url'=>1, 'week'=>1
					);
					static $eAL = array(
							'a'=>1, 'area'=>1, 'bdo'=>1, 'button'=>1, 'col'=>1, 'fieldset'=>1, 'form'=>1, 'img'=>1, 'input'=>1, 'object'=>1, 'ol'=>1, 'optgroup'=>1, 'option'=>1, 'param'=>1,
							'script'=>1, 'select'=>1, 'table'=>1, 'td'=>1, 'textarea'=>1, 'tfoot'=>1, 'th'=>1, 'thead'=>1, 'tr'=>1, 'track'=>1, 'xml:space'=>1
					);
					$lcase = isset($eAL[$e]) ? 1 : 0;
				}

				$depTr = 0;
				if( $this->data['C']['no_deprecated_attr']){
					// dep attr:applicable ele
					static $aND = array(
							'align'=>array('caption'=>1, 'div'=>1, 'h1'=>1, 'h2'=>1, 'h3'=>1, 'h4'=>1, 'h5'=>1, 'h6'=>1, 'hr'=>1, 'img'=>1, 'input'=>1, 'legend'=>1, 'object'=>1, 'p'=>1, 'table'=>1),
							'bgcolor'=>array('table'=>1, 'td'=>1, 'th'=>1, 'tr'=>1),
							'border'=>array('object'=>1),
							'bordercolor'=>array('table'=>1, 'td'=>1, 'tr'=>1),
							'clear'=>array('br'=>1),
							'compact'=>array('dl'=>1, 'ol'=>1, 'ul'=>1),
							'height'=>array('td'=>1, 'th'=>1),
							'hspace'=>array('img'=>1, 'object'=>1),
							'language'=>array('script'=>1),
							'name'=>array('a'=>1, 'form'=>1, 'iframe'=>1, 'img'=>1, 'map'=>1),
							'noshade'=>array('hr'=>1),
							'nowrap'=>array('td'=>1, 'th'=>1),
							'size'=>array('hr'=>1),
							'vspace'=>array('img'=>1, 'object'=>1),
							'width'=>array('hr'=>1, 'pre'=>1, 'td'=>1, 'th'=>1)
					);
					static $eAD = array(
							'a'=>1, 'br'=>1, 'caption'=>1, 'div'=>1, 'dl'=>1, 'form'=>1, 'h1'=>1, 'h2'=>1, 'h3'=>1, 'h4'=>1, 'h5'=>1, 'h6'=>1, 'hr'=>1, 'iframe'=>1, 'img'=>1, 'input'=>1,
							'legend'=>1, 'map'=>1, 'object'=>1, 'ol'=>1, 'p'=>1, 'pre'=>1, 'script'=>1, 'table'=>1, 'td'=>1, 'th'=>1, 'tr'=>1, 'ul'=>1
					);
					$depTr = isset($eAD[$e]) ? 1 : 0;
				}

				// attr name-vals
				if(strpos($a, "\x01") !== false){$a = preg_replace('`\x01[^\x01]*\x01`', '', $a);} // No comment/CDATA sec
				$mode = 0; $a = trim($a, ' /'); $aA = array();
				while(strlen($a)){
					$w = 0;
					switch($mode){
						case 0: // Name
							if(preg_match('`^[a-zA-Z][^\s=]+`', $a, $m)){
								$nm = strtolower($m[0]);
								$w = $mode = 1; $a = ltrim(substr_replace($a, '', 0, strlen($m[0])));
							}
							break; case 1:
								if($a[0] == '='){ // =
									$w = 1; $mode = 2; $a = ltrim($a, '= ');
								}else{ // No val
									$w = 1; $mode = 0; $a = ltrim($a);
									$aA[$nm] = '';
								}
								break; case 2: // Val
									if(preg_match('`^((?:"[^"]*")|(?:\'[^\']*\')|(?:\s*[^\s"\']+))(.*)`', $a, $m)){
										$a = ltrim($m[2]); $m = $m[1]; $w = 1; $mode = 0;
										$aA[$nm] = trim(str_replace('<', '&lt;', ($m[0] == '"' or $m[0] == '\'') ? substr($m, 1, -1) : $m));
									}
									break;
					}
					if($w == 0){ // Parse errs, deal with space, " & '
						$a = preg_replace('`^(?:"[^"]*("|$)|\'[^\']*(\'|$)|\S)*\s*`', '', $a);
						$mode = 0;
					}
				}
				if($mode == 1){$aA[$nm] = '';}

				// clean attrs
				$rl = isset( $this->data['S'][$e]) ? $this->data['S'][$e] : array();
				$a = array(); $nfr = 0;
				foreach($aA as $k=>$v){
					if(((isset( $this->data['C']['deny_attribute']['*'])
							? isset( $this->data['C']['deny_attribute'][$k])
							: !isset( $this->data['C']['deny_attribute'][$k]))
							&& (isset($aN[$k][$e]) or isset($aNU[$k])
									or preg_match('`data-((?!xml)[^:]+$)`', $k))
							&& !isset($rl['n'][$k]) && !isset($rl['n']['*']))
							or isset($rl[$k])){
						if(isset($aNE[$k])){$v = $k;}
						elseif(!empty($lcase) && (($e != 'button' or $e != 'input') or $k == 'type')){ // Rather loose but ?not cause issues
							$v = (isset($aNL[($v2 = strtolower($v))])) ? $v2 : $v;
						}
						if($k == 'style' && ! $this->data['C']['style_pass']){
							if(false !== strpos($v, '&#')){
								static $sC = array(
										'&#x20;'=>' ', '&#32;'=>' ', '&#x45;'=>'e', '&#69;'=>'e', '&#x65;'=>'e', '&#101;'=>'e', '&#x58;'=>'x', '&#88;'=>'x', '&#x78;'=>'x', '&#120;'=>'x',
										'&#x50;'=>'p', '&#80;'=>'p', '&#x70;'=>'p', '&#112;'=>'p', '&#x53;'=>'s', '&#83;'=>'s', '&#x73;'=>'s', '&#115;'=>'s', '&#x49;'=>'i', '&#73;'=>'i',
										'&#x69;'=>'i', '&#105;'=>'i', '&#x4f;'=>'o', '&#79;'=>'o', '&#x6f;'=>'o', '&#111;'=>'o', '&#x4e;'=>'n', '&#78;'=>'n', '&#x6e;'=>'n', '&#110;'=>'n',
										'&#x55;'=>'u', '&#85;'=>'u', '&#x75;'=>'u', '&#117;'=>'u', '&#x52;'=>'r', '&#82;'=>'r', '&#x72;'=>'r', '&#114;'=>'r', '&#x4c;'=>'l', '&#76;'=>'l',
										'&#x6c;'=>'l', '&#108;'=>'l', '&#x28;'=>'(', '&#40;'=>'(', '&#x29;'=>')', '&#41;'=>')', '&#x20;'=>':', '&#32;'=>':', '&#x22;'=>'"', '&#34;'=>'"',
										'&#x27;'=>"'", '&#39;'=>"'", '&#x2f;'=>'/', '&#47;'=>'/', '&#x2a;'=>'*', '&#42;'=>'*', '&#x5c;'=>'\\', '&#92;'=>'\\'
								);
								$v = strtr($v, $sC);
							}
							$v = preg_replace_callback('`(url(?:\()(?: )*(?:\'|"|&(?:quot|apos);)?)(.+?)((?:\'|"|&(?:quot|apos);)?(?: )*(?:\)))`iS', array( $this, 'hl_prot' ), $v);
							$v = ! $this->data['C']['css_expression'] ? preg_replace('`expression`i', ' ', preg_replace('`\\\\\S|(/|(%2f))(\*|(%2a))`i', ' ', $v)) : $v;
						}elseif(isset($aNP[$k]) or strpos($k, 'src') !== false or $k[0] == 'o'){
							# double-quoted char is soft-hyphen; appears here as "�" or hyphen or something else depending on viewing software
							$v = str_replace("�", ' ', (strpos($v, '&') !== false ? str_replace(array('&#xad;', '&#173;', '&shy;'), ' ', $v) : $v));
							$v = $this->hl_prot($v, $k);
							if($k == 'href'){ // X-spam
								if( $this->data['C']['anti_mail_spam'] && strpos($v, 'mailto:') === 0){
									$v = str_replace('@', htmlspecialchars( $this->data['C']['anti_mail_spam']), $v);
								}elseif( $this->data['C']['anti_link_spam']){
									$r1 = $this->data['C']['anti_link_spam'][1];
									if(!empty($r1) && preg_match($r1, $v)){continue;}
									$r0 = $this->data['C']['anti_link_spam'][0];
									if(!empty($r0) && preg_match($r0, $v)){
										if(isset($a['rel'])){
											if(!preg_match('`\bnofollow\b`i', $a['rel'])){$a['rel'] .= ' nofollow';}
										}elseif(isset($aA['rel'])){
											if(!preg_match('`\bnofollow\b`i', $aA['rel'])){$nfr = 1;}
										}else{$a['rel'] = 'nofollow';}
									}
								}
							}
						}
						if(isset($rl[$k]) && is_array($rl[$k]) && ($v = $this->hl_attrval($v, $rl[$k])) === 0){continue;}
						$a[$k] = str_replace('"', '&quot;', $v);
					}
				}
				if($nfr){$a['rel'] = isset($a['rel']) ? $a['rel']. ' nofollow' : 'nofollow';}

				// rqd attr
				static $eAR = array(
						'area'=>array('alt'=>'area'),
						'bdo'=>array('dir'=>'ltr'),
						'command'=>array('label'=>''),
						'form'=>array('action'=>''),
						'img'=>array('src'=>'', 'alt'=>'image'),
						'map'=>array('name'=>''),
						'optgroup'=>array('label'=>''),
						'param'=>array('name'=>''),
						'style'=>array('scoped'=>''),
						'textarea'=>array('rows'=>'10', 'cols'=>'50')
				);
				if(isset($eAR[$e])){
					foreach($eAR[$e] as $k=>$v){
						if(!isset($a[$k])){$a[$k] = isset($v[0]) ? $v : $k;}
					}
				}

				// depr attrs
				if($depTr){
					$c = array();
					foreach($a as $k=>$v){
						if($k == 'style' or !isset($aND[$k][$e])){continue;}
						if($k == 'align'){
							unset($a['align']);
							if($e == 'img' && ($v == 'left' or $v == 'right')){$c[] = 'float: '. $v;}
							elseif(($e == 'div' or $e == 'table') && $v == 'center'){$c[] = 'margin: auto';}
							else{$c[] = 'text-align: '. $v;}
						}elseif($k == 'bgcolor'){
							unset($a['bgcolor']);
							$c[] = 'background-color: '. $v;
						}elseif($k == 'border'){
							unset($a['border']); $c[] = "border: {$v}px";
						}elseif($k == 'bordercolor'){
							unset($a['bordercolor']); $c[] = 'border-color: '. $v;
						}elseif($k == 'clear'){
							unset($a['clear']); $c[] = 'clear: '. ($v != 'all' ? $v : 'both');
						}elseif($k == 'compact'){
							unset($a['compact']); $c[] = 'font-size: 85%';
						}elseif($k == 'height' or $k == 'width'){
							unset($a[$k]); $c[] = $k. ': '. ($v[0] != '*' ? $v. (ctype_digit($v) ? 'px' : '') : 'auto');
						}elseif($k == 'hspace'){
							unset($a['hspace']); $c[] = "margin-left: {$v}px; margin-right: {$v}px";
						}elseif($k == 'language' && !isset($a['type'])){
							unset($a['language']);
							$a['type'] = 'text/'. strtolower($v);
						}elseif($k == 'name'){
							if( $this->data['C']['no_deprecated_attr'] == 2 or ($e != 'a' && $e != 'map')){unset($a['name']);}
							if(!isset($a['id']) && !preg_match('`\W`', $v)){$a['id'] = $v;}
						}elseif($k == 'noshade'){
							unset($a['noshade']); $c[] = 'border-style: none; border: 0; background-color: gray; color: gray';
						}elseif($k == 'nowrap'){
							unset($a['nowrap']); $c[] = 'white-space: nowrap';
						}elseif($k == 'size'){
							unset($a['size']); $c[] = 'size: '. $v. 'px';
						}elseif($k == 'vspace'){
							unset($a['vspace']); $c[] = "margin-top: {$v}px; margin-bottom: {$v}px";
						}
					}
					if(count($c)){
						$c = implode('; ', $c);
						$a['style'] = isset($a['style']) ? rtrim($a['style'], ' ;'). '; '. $c. ';': $c. ';';
					}
				}
				// unique ID
				if( $this->data['C']['unique_ids'] && isset($a['id'])){
					if(preg_match('`\s`', ($id = $a['id'])) or (isset( $this->data['hl_Ids'][$id]) && $this->data['C']['unique_ids'] == 1)){unset($a['id']);
					}else{
						while(isset( $this->data['hl_Ids'][$id])){$id = $this->data['C']['unique_ids']. $id;}
					 $this->data['hl_Ids'][($a['id'] = $id)] = 1;
					}
				}
				// xml:lang
				if( $this->data['C']['xml:lang'] && isset($a['lang'])){
					$a['xml:lang'] = isset($a['xml:lang']) ? $a['xml:lang'] : $a['lang'];
					if( $this->data['C']['xml:lang'] == 2){unset($a['lang']);}
				}
				// for transformed tag
				if(!empty($trt)){
					$a['style'] = isset($a['style']) ? rtrim($a['style'], ' ;'). '; '. $trt : $trt;
				}
				// return with empty ele /
				if(empty( $this->data['C']['hook_tag'])){
					$aA = '';
					foreach($a as $k=>$v){$aA .= " {$k}=\"{$v}\"";}
					return "<{$e}{$aA}". (isset($eE[$e]) ? ' /' : ''). '>';
				}
				else{return $this->data['C']['hook_tag']($e, $a);}
				// eof
	}

	function hl_tag2(&$e, &$a, $t=1)
	{
		// transform tag
		if($e == 'big'){$e = 'span'; return 'font-size: larger;';}
		if($e == 's' or $e == 'strike'){$e = 'span'; return 'text-decoration: line-through;';}
		if($e == 'tt'){$e = 'code'; return '';}
		if($e == 'center'){$e = 'div'; return 'text-align: center;';}
		static $fs = array(
				'0'=>'xx-small', '1'=>'xx-small', '2'=>'small', '3'=>'medium', '4'=>'large', '5'=>'x-large', '6'=>'xx-large', '7'=>'300%',
				'-1'=>'smaller', '-2'=>'60%', '+1'=>'larger', '+2'=>'150%', '+3'=>'200%', '+4'=>'300%'
		);
		if($e == 'font'){
			$a2 = '';
			if(preg_match('`face\s*=\s*(\'|")([^=]+?)\\1`i', $a, $m) or preg_match('`face\s*=(\s*)(\S+)`i', $a, $m)){
				$a2 .= ' font-family: '. str_replace('"', '\'', trim($m[2])). ';';
			}
			if(preg_match('`color\s*=\s*(\'|")?(.+?)(\\1|\s|$)`i', $a, $m)){
				$a2 .= ' color: '. trim($m[2]). ';';
			}
			if(preg_match('`size\s*=\s*(\'|")?(.+?)(\\1|\s|$)`i', $a, $m) && isset($fs[($m = trim($m[2]))])){
				$a2 .= ' font-size: '. $fs[$m]. ';';
			}
			$e = 'span'; return ltrim($a2);
		}
		if($e == 'acronym'){$e = 'abbr'; return '';}
		if($e == 'dir'){$e = 'ul'; return '';}
		if($t == 2){$e = 0; return 0;}
		return '';
		// eof
	}

	function hl_tidy($t, $w, $p)
	{
		// Tidy/compact HTM
		if(strpos(' pre,script,textarea', "$p,")){return $t;}
		$t = preg_replace(
				array('`(<\w[^>]*(?<!/)>)\s+`', '`\s+`', '`(<\w[^>]*(?<!/)>) `'),
				array(' $1', ' ', '$1'),
				preg_replace_callback(
						array('`(<(!\[CDATA\[))(.+?)(\]\]>)`sm', '`(<(!--))(.+?)(-->)`sm', '`(<(pre|script|textarea)[^>]*?>)(.+?)(</\2>)`sm'),
						create_function('$m', 'return $m[1]. str_replace(array("<", ">", "\n", "\r", "\t", " "), array("\x01", "\x02", "\x03", "\x04", "\x05", "\x07"), $m[3]). $m[4];'),
						$t
				)
		);
		if(($w = strtolower($w)) == -1){
			return str_replace(array("\x01", "\x02", "\x03", "\x04", "\x05", "\x07"), array('<', '>', "\n", "\r", "\t", ' '), $t);
		}
		$s = strpos(" $w", 't') ? "\t" : ' ';
		$s = preg_match('`\d`', $w, $m) ? str_repeat($s, $m[0]) : str_repeat($s, ($s == "\t" ? 1 : 2));
		$N = preg_match('`[ts]([1-9])`', $w, $m) ? $m[1] : 0;
		$a = array('br'=>1);
		$b = array('button'=>1, 'command'=>1, 'input'=>1, 'option'=>1, 'param'=>1, 'track'=>1);
		$c = array(
				'audio'=>1, 'canvas'=>1, 'caption'=>1, 'dd'=>1, 'dt'=>1, 'figcaption'=>1, 'h1'=>1, 'h2'=>1, 'h3'=>1, 'h4'=>1, 'h5'=>1, 'h6'=>1, 'isindex'=>1, 'label'=>1, 'legend'=>1,
				'li'=>1, 'object'=>1, 'p'=>1, 'pre'=>1, 'style'=>1, 'summary'=>1, 'td'=>1, 'textarea'=>1, 'th'=>1, 'video'=>1
		);
		$d = array(
				'address'=>1, 'article'=>1, 'aside'=>1, 'blockquote'=>1, 'center'=>1, 'colgroup'=>1, 'datalist'=>1, 'details'=>1, 'dir'=>1, 'div'=>1, 'dl'=>1, 'fieldset'=>1,
				'figure'=>1, 'footer'=>1, 'form'=>1, 'header'=>1, 'hgroup'=>1, 'hr'=>1, 'iframe'=>1, 'main'=>1, 'map'=>1, 'menu'=>1, 'nav'=>1, 'noscript'=>1, 'ol'=>1, 'optgroup'=>1,
				'rbc'=>1, 'rtc'=>1, 'ruby'=>1, 'script'=>1, 'section'=>1, 'select'=>1, 'table'=>1, 'tbody'=>1, 'tfoot'=>1, 'thead'=>1, 'tr'=>1, 'ul'=>1
		);
		$T = explode('<', $t);
		$X = 1;
		while($X){
			$n = $N;
			$t = $T;
			ob_start();
			if(isset($d[$p])){echo str_repeat($s, ++$n);}
			echo ltrim(array_shift($t));
			for($i=-1, $j=count($t); ++$i<$j;){
				$r = ''; list($e, $r) = explode('>', $t[$i]);
				$x = $e[0] == '/' ? 0 : (substr($e, -1) == '/' ? 1 : ($e[0] != '!' ? 2 : -1));
				$y = !$x ? ltrim($e, '/') : ($x > 0 ? substr($e, 0, strcspn($e, ' ')) : 0);
				$e = "<$e>";
				if(isset($d[$y])){
					if(!$x){
						if($n){echo "\n", str_repeat($s, --$n), "$e\n", str_repeat($s, $n);}
						else{++$N; ob_end_clean(); continue 2;}
					}
					else{echo "\n", str_repeat($s, $n), "$e\n", str_repeat($s, ($x != 1 ? ++$n : $n));}
					echo $r; continue;
				}
				$f = "\n". str_repeat($s, $n);
				if(isset($c[$y])){
					if(!$x){echo $e, $f, $r;}
					else{echo $f, $e, $r;}
				}elseif(isset($b[$y])){echo $f, $e, $r;
				}elseif(isset($a[$y])){echo $e, $f, $r;
				}elseif(!$y){echo $f, $e, $f, $r;
				}else{echo $e, $r;}
			}
			$X = 0;
		}
		$t = str_replace(array("\n ", " \n"), "\n", preg_replace('`[\n]\s*?[\n]+`', "\n", ob_get_contents()));
		ob_end_clean();
		if(($l = strpos(" $w", 'r') ? (strpos(" $w", 'n') ? "\r\n" : "\r") : 0)){
			$t = str_replace("\n", $l, $t);
		}
		return str_replace(array("\x01", "\x02", "\x03", "\x04", "\x05", "\x07"), array('<', '>', "\n", "\r", "\t", ' '), $t);
		// eof
	}

	function hl_version()
	{
		// rel
		return '1.2.beta.7';
		// eof
	}
}
