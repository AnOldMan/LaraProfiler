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

		return '<a href="' . $url . '"' . static::attributes( $attributes ) . '>' . $title . '</a>';
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

		return '<img src="' . $url . '"' . static::attributes( $attributes ) . '>';
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

		return '<i class="icon icon-' . $type . '"' . static::attributes( $attributes ) . '>' . $txt . '</i>';
	}

	/**
	 * Generate an ordered or un-ordered list.
	 *
	 * @param  string  $type
	 * @param  array   $list
	 * @param  array   $attributes
	 * @return string
	 */
	protected static function listing( $type, $list, $attributes = array() )
	{
		$html = $end = '';
		if( count( $list ) == 0 ) return $html;
		if( ! empty( $attributes['html'] ) )
		{
			unset( $attributes['html'] );
			$html = $end = "\n";
			$items = array();
			foreach( $list as $key => $value )
				$items[] = is_array( $value ) ? static::listing( $type, $value, array( 'html' => true ) ): $value;
			foreach( $items as $item )
			{
				$html .= "\t<li>";
				if( strpos( $item, "\n" ) !== false )
				{
					$item = static::indent( trim( $item ), 1 ) . "\n";
				}
				$html .= $item;
				$html .= "</li>\n";
			}
		}
		else
		{
			foreach( $list as $key => $value )
			{
				$html .= '<li>';
				if( is_array( $value ) )
				{
					if( ! is_int( $key ) ) $html .= static::entities( $key );
					$html .= static::listing( $type, $value );
				}
				else $html .= static::entities( $value );
				$html .= '</li>';
			}
		}

		return '<' . $type . static::attributes( $attributes ) . '>' . $html . '</' . $type . '>' . $end;
	}

	/**
	 * Generate a definition list.
	 *
	 * @param  array   $list
	 * @param  array   $attributes
	 * @return string
	 */
	public static function dl( $list, $attributes = array() )
	{
		$html = $end = '';
		if( count( $list ) == 0 ) return $html;
		
		if( ! empty( $attributes['html'] ) )
		{
			unset( $attributes['html'] );
			$html = $end = "\n";
			foreach( $list as $k => $item )
			{
				$ct = $cd = '';
				if( is_array( $item ) )
				{
					$dt = $item['dt'];
					$dd = $item['dd'];
					if( $base = empty( $attributes['class'] ) ? '' : $attributes['class'] )
					{
						$base = explode( ' ', $base );
						$base = array_pop( $base ) . '-';
					}
					$addn = empty( $item['class'] ) ? '' : ' ' . $item['class'];
					if( $addn && ! $base )
					{
						$base = $addn . '-';
						$addn == '';
					}
					if( $base )
					{
						$ct = ' class="' .  $base . 'title' . $addn . '"';
						$cd = ' class="' .  $base . 'data' . $addn . '"';
					}
				}
				else
				{
					$dt = $k;
					$dd = $item;
				}
				$html .= "\t<dt".$ct.'>';
				if(strpos( $dt, "\n" ) !== false)
				{
					$dt = "\n" . static::indent( trim( $dt ), 2 ) . "\n\t";
				}
				$html .= $dt;
				$html .= "</dt>\n";
				$html .= "\t<dd".$cd.'>';
				if(strpos( $dd, "\n" ) !== false)
				{
					$dd = "\n" . static::indent( trim( $dd ), 2 ) . "\n\t";
				}
				$html .= $dd;
				$html .= "</dd>\n";
			}
		}
		else
		{
			foreach( $list as $t => $d )
			{
				$html .= '<dt>' . static::entities( $t ) . '</dt>';
				$html .= '<dd>' . static::entities( $d ) . '</dd>';
			}
		}

		return '<dl' . static::attributes( $attributes ) . '>' . $html . '</dl>' . $end;
	}
	
	public static function indent( $string, $tabcount = 0 )
	{
		$tabcount = (int)$tabcount;
		if( ! $tabcount ) return $string;
		$t = '';
		for( $i = 0; $i < $tabcount; $i++ ) $t .= "\t";
		$a = explode( "\n", $string );
		foreach( $a as $k => $l ) if( trim( $l ) ) $a[$k] = $t . rtrim( $l ); else unset( $a[$k] );
		$a[] = '';
		return implode( "\n", $a );
	}
}
