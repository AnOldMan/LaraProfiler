<?php namespace SITE;

use Laravel\Validator as ParentClass,
	htmlawed;

/**
 * Validator
 *
 * Extending the Laravel Validator class allows us to define more powerful validation functions than
 * with the Validator::register method.
 */
class Validator extends ParentClass
{
	/**
	 * Implicit Attributes
	 *
	 * Attributes with these rules will be validated even if no value is supplied.
	 */
	protected $implicit_attributes = array(
		'required',
		'accepted',
		'required_if_attribute'
	);

	/**
	 * Implicit
	 *
	 * By default Laravel will only validate an attribute if a value was supplied, or if the rule set is 'required' or 'accepted'.
	 * It'll just skip the validation, which makes 'require' being conditional impossible. Let's overwrite that to be more flexible.
	 */
	protected function implicit($rule)
	{
		return ( in_array( $rule, $this->implicit_attributes ) );
	}

	/**
	 * Replace all error message place-holders with actual values.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replace($message, $attribute, $rule, $parameters)
	{
		if( preg_match_all( '/\{([^\|]*)\|([^\}]*)\}/', $message, $m ) )
		{
			if( ! empty( $m[0] ) ) foreach( $m[0] as $k => $v )
			{
				$message = str_replace( $v, '', $message );
				$a = empty( $m[1][$k] ) ? '' : $m[1][$k];
				$r = empty( $m[2][$k] ) ? '' : $m[2][$k];
				if( $this->attribute( $attribute ) == $a ) $message = str_replace( ':attribute', $r, $message );
			}
		}

		$message = str_replace( ':attribute', $this->attribute( $attribute ), $message );

		if ( method_exists( $this, $replacer = 'replace_'.$rule ) )
		{
			$message = $this->$replacer( $message, $attribute, $rule, $parameters );
		}

		return $message;
	}

	/**
	 * Required if attribute
	 *
	 * Validate that a required attribute exists, only if another
	 * attribute satisfies the supplied conditions.
	 *
	 * i.e. 'What is your favourite PHP framework?' is only required if
	 * 'Do you use a framework?' is set to '1'.
	 */
	public function validate_required_if_attribute( $attribute, $value, $parameters )
	{
		$required = false;

		switch( $parameters[1] )
		{
			case '==':
				$required = $this->attributes[$parameters[0]] == $parameters[2];

			case '!=':
				$required = $this->attributes[$parameters[0]] != $parameters[2];

			case '===':
				$required = $this->attributes[$parameters[0]] === $parameters[2];

			case '!==':
				$required = $this->attributes[$parameters[0]] !== $parameters[2];

			case '<':
				$required = $this->attributes[$parameters[0]] < $parameters[2];

			case '<=':
				$required = $this->attributes[$parameters[0]] <= $parameters[2];

			case '>':
				$required = $this->attributes[$parameters[0]] > $parameters[2];

			case '>=':
				$required = $this->attributes[$parameters[0]] >= $parameters[2];

		}

		return $required ? $this->validate_required( $attribute, $value ) : true;
	}

	public function validate_is_date( $attribute, $value, $parameters )
	{
		$data = date_parse( $value );
		return empty( $data['errors'] );
	}

	public function validate_no_javascript( $attribute, $value, $parameters )
	{
		return preg_match( '/(.*)<\/?script(.*)>(.*)/i', $value ) ? false : true;
	}

	public function validate_no_html( $attribute, $value, $parameters )
	{
		if( is_array( $value ) ) $value = stripcslashes( var_export( $value, true ) );
		$value = html_entity_decode( $value, ENT_QUOTES );
		$value = urldecode( $value );
		return strip_tags( $value ) != $value ? false : true;
	}

	public function validate_no_http( $attribute, $value, $parameters )
	{
		if( is_array( $value ) ) $value = stripcslashes( var_export( $value, true ) );
		$value = html_entity_decode( $value, ENT_QUOTES );
		$value = urldecode( $value );
		return stristr( $value, 'http://' ) ? false : true;
	}

	public function validate_array_enum( $attribute, $value, $parameters )
	{
		if( is_array( $value ) && is_array( $parameters ) )
		{
			foreach( $value as $v ) if( ! in_array( $v, $parameters ) ) return false;
			return true;
		}
		return false;
	}
}
