<?php namespace SITE;

use Laravel\HTML as ParentClass,
	\URL;

class HTML extends ParentClass
{
	/**
	 * Generate a HTML link without escaping entities.
	 * Extends the native function HTML::link
	 *
	 * <code>
	 *		// Generate a link with html tags
	 *		echo HTML::link_raw('http://google.com', '<b>Google</b> search');
	 *
	 *		// Generate a link with an image
	 *		echo HTML::link_raw('http://google.com', '<img src="google.gif" />');
	 * </code>
	 *
	 * @param  string  $url
	 * @param  string  $title
	 * @param  array   $attributes
	 * @param  bool	$https
	 * @return string
	 */
	public static function link_raw( $url, $title = null, $attributes = array(), $https = null )
	{
		$url = URL::to( $url, $https );

		if( is_null( $title ) ) $title = $url;

		return '<a href="'.$url.'"'.static::attributes( $attributes ).'>'.$title.'</a>';
	}

	/**
	 * Generate an HTML image element.
	 * SET ABSOLUTE URLs TO CURRENT PATH, INSTEAD OF URL::to_asset($url);
	 *
	 * @param  string  $url
	 * @param  string  $alt
	 * @param  array   $attributes
	 * @return string
	 */
	public static function image( $url, $alt = '', $attributes = array() )
	{
		if( empty( $url ) || ! is_string( $url ) ) return '';
		if( $url[0] != '/' ) $url = URL::to_asset( $url );
		if( $alt ) $attributes['alt'] = $alt;

		return '<img src="'.$url.'"'.static::attributes( $attributes ).'>';
	}

	public static function icon( $type, $txt = '', $attributes = array() )
	{
		if( empty( $type ) || ! is_string( $type ) ) return '';
		if( strpos( $type, '-' ) !== false )
		{
			$e = explode( '-', $type );
			$type = $e[0] . ' icon-' . $type;
		}
		if( $txt && empty( $attributes['title'] ) ) $attributes['title'] = $txt;

		return '<i class="icon icon-'.$type.'"'.static::attributes( $attributes ).'>'.$txt.'</i>';
	}

}
