<?php

class Person extends Eloquent {

	public static $accessible = array(
		'id',
		'firstname',
		'middlename',
		'lastname',
		'fullname',
		'stub',
		'birthyear',
		'created_at',
		'updated_at'
	);

	/* The attributes that should be excluded from to_array. */
	public static $hidden = array( 'created_at', 'updated_at' );

	public function credit()
	{
		return $this->has_many('Credit');
	}

	public function role()
	{
		return $this->has_many('Role');
	}

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
			if( $data['stub'] == $stub ) return $data['fullname'];
		}
	}

	public static function by_name( $name )
	{
		self::eagerload();
		foreach( self::$list as $id => $data )
		{
			if( $data['fullname'] == $fullname ) return $id;
		}
	}

	private static function eagerload()
	{
		if( self::$list === false )
		{
			self::$list = Cache::remember('person-all', function(){
				$return = array();
				if( $results = Person::all() )
				{
					foreach( $results as $o ) $return[$o->id] = array( 'fullname' => $o->fullname, 'birthyear' => $o->birthyear, 'stub' => $o->stub );
				}
				return $return;
			}, 60*24);
		}
	}

	public function seoStub()
	{
		$this->stub = htmlawed::makeSeoSlug( $this->fullname, '-' );
	}

	public function save()
	{
		if( empty( $this->fullname ) )
		{
			$this->fullname = htmlawed::implodeClean( ' ', array( $this->firstname, $this->middlename, $this->lastname ) );
		}

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
		if( ! empty( $a['stub'] ) && ! empty( $a['fullname'] ) )
		{
			return self::format_link( $a['stub'],  $a['fullname'],  $a['birthyear'], $type );
		}
		return '';
	}

	public static function format_link( $stub, $name, $birth, $type )
	{
		$birth = $birth ? ' <sub>(' . $birth . ')</sub>' : '';
		return '<a class="company-link" href="' . URI::to_route( $type, array( $stub ), false ) . '">' . $name . $birth . '</a>';
	}
}
