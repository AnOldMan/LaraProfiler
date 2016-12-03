<?php namespace SITE;

use Laravel\Input as ParentClass,
	\Request;

class Input extends ParentClass
{
	/**
	* Fill input array with values for checkboxes. Addresses situations
	* where checkbox input do not set any value if unchecked. This will
	* set a 0 value for those checkboxes if no input is set.
	*
	* @param $fields array of strings
	*
	* @return null
	*/
	public static function checkboxes( array $fields )
	{
		$data = array();
		foreach ( $fields as $field )
			$data[$field] = self::get( $field, 0 );
		Input::merge( $data );
	}

	/**
	 * Get an item from the input data, compare against valid/allowed inputs.
	 *
	 * Will ONLY return value[s] supplide in $valid.
	 * Default will be first element of $valid.
	 *
	 * This method is used for all request verbs (GET, POST, PUT, and DELETE).
	 *
	 * <code>
	 *		// Return only input from valid array, first item is default value
	 *		$email = Input::get_valid( 'name', array( 'Taylor', 'Jones', 'Smith' ) );
	 * </code>
	 *
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	public static function get_valid( $key, $valid = array() )
	{
		$default = array_values( (array)$valid );
		$default = array_shift( $valid );
		$value = array_get( Request::foundation()->request->all(), $key );
		if( is_null( $value ) )
		{
			$value = array_get( static::query(), $key, $default );
		}
		if( ! in_array( $value, (array)$valid ) ) $value = $default;

		return $value;
	}
}
