<?php namespace SITE;

use \Laravel\URI as ParentClass,
	\URL,
	\Router,
	\Config,
	\Exception;

class URI extends ParentClass
{
	public static $index = null;

	public static function to_route( $name, $parameters = array() )
	{
		if ( is_null( $route = Router::find( $name ) ) )
		{
			Log::URI( 'Undefined to_route : ' . $name );
			return '';
		}
		$uri = trim( URL::transpose( key( $route ), $parameters ), '/' );
		if ( is_null( self::$index ) )
		{
			self::$index = rtrim( Config::get( 'application.index', '' ), '/' );
		}
		if( self::$index ) {
			$uri = self::$index . '/' . $uri;
		}
		return '/' . trim( $uri, '/' );
	}
}
