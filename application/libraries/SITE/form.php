<?php namespace SITE;

use Laravel\Form as ParentClass;

class Form extends ParentClass
{
	/**
	 * Format all current error messages
	 *
	 * @param  object  $errors
	 *
	 * @return string
	 */
	public static function errors( $errors )
	{
		if( is_object( $errors ) && method_exists( $errors, 'all' ) )
		{
			if( ! $all = $errors->all( '<p>:message</p>' ) ) return '';
			return '<div class="message form-error"><span>Notice: </span>' . implode( '', $all ) . '</div>';
		}
		return '';
	}

	/**
	 * Create a HTML label element.
	 *
	 * <code>
	 *		// Create a label for the "email" input element
	 *		echo Form::label('email', 'E-Mail Address');
	 * </code>
	 *
	 * @param  string  $name
	 * @param  string  $value
	 * @param  array   $attributes
	 * @return string
	 */
	public static function label( $name, $value, $attributes = array() )
	{
		if( !empty( $attributes['for'] ) )
		{
			$clean = $attributes['for'];
			unset( $attributes['for'] );
		}
		else $clean = self::cleanID( $name );
		static::$labels[] = $clean;

		return '<label for="' . $clean . '"'
			. HTML::attributes( $attributes ) . '>'
			. HTML::entities( $value )
			. '</label>';
	}

	/**
	 * Determine the ID attribute for a form element.
	 *
	 * @param  string  $name
	 * @param  array   $attributes
	 * @return mixed
	 */
	protected static function id( $name, $attributes )
	{
		// If an ID has been explicitly specified in the attributes, we will
		// use that ID. Otherwise, we will look for an ID in the array of
		// label names so labels and their elements have the same ID.
		if( array_key_exists( 'id', $attributes ) )
		{
			return $attributes['id'];
		}

		if( in_array( $name, static::$labels ) )
		{
			return $name;
		}

		$clean = self::cleanID( $name );
		if( in_array( $clean, static::$labels ) )
		{
			return $clean;
		}
	}

	/**
	 * Clean out array brackets from name for id string.
	 *
	 * @param  string  $name
	 * @return mixed
	 */
	public static function cleanID( $name )
	{
		$name = str_replace( ']', '_', $name );
		$name = str_replace( '[', '_', $name );
		$name = preg_replace( '/_+/', '_', $name );
		return trim( $name, '_' );
	}
}
