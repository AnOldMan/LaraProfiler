<?php

class Company extends Eloquent {

	public static $accessible = array(
		'id',
		'name',
		'stub',
		'created_at',
		'updated_at'
	);

	/* The attributes that should be excluded from to_array. */
	public static $hidden = array( 'id', 'film_id', 'created_at', 'updated_at' );

	public static $list = false;

	public static function by_id( $id )
	{
		self::eagerload();
		if( isset( self::$list[$id] ) ) return self::$list[$id]['name'];
	}

	public static function by_stub( $stub )
	{
		self::eagerload();
		foreach( self::$list as $id => $data )
		{
			if( $data['stub'] == $stub ) return $id;
		}
	}

	public static function name_by_stub( $stub )
	{
		self::eagerload();
		foreach( self::$list as $id => $data )
		{
			if( $data['stub'] == $stub ) return $data['name'];
		}
	}

	public static function by_name( $name )
	{
		self::eagerload();
		foreach( self::$list as $id => $data )
		{
			if( $data['name'] == $name ) return $id;
		}
	}

	private static function eagerload()
	{
		if( self::$list === false )
		{
			self::$list = Cache::remember('company-all', function(){
				$return = array();
				if( $results = Company::all() )
				{
					foreach( $results as $o ) $return[$o->id] = array( 'name' => $o->name, 'stub' => $o->stub );
				}
				return $return;
			}, 60*24);
		}
	}

	public function seoStub()
	{
		$this->stub = htmlawed::makeSeoSlug( $this->name, '-' );
	}

	public function save()
	{
		if( empty( $this->stub ) )
		{
			$this->seoStub();
		}

		parent::save();
	}

	public static function link( $id, $type )
	{
		self::eagerload();
		$a = empty( self::$list[$id] ) ? array() : self::$list[$id];
		if( ! empty( $a['stub'] ) && ! empty( $a['name'] ) )
		{
			return self::format_link( $a['stub'],  $a['name'], $type );
		}
		return '';
	}

	public static function format_link( $stub, $txt, $type )
	{
		return '<a class="company-link" href="' . URI::to_route( $type, array( $stub ), false ) . '">' . $txt . '</a>';
	}
}
