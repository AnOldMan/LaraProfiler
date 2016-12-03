<?php

class Phrase extends Eloquent {

	public static $accessible = array(
		'id',
		'phrase',
		'stub',
		'created_at',
		'updated_at'
	);

	public static $list = false;

	public static function by_id( $id )
	{
		self::eagerload();
		if( isset( self::$list[$id] ) ) return self::$list[$id]['phrase'];
	}

	public static function stub_by_id( $id )
	{
		self::eagerload();
		if( isset( self::$list[$id] ) ) return self::$list[$id]['stub'];
	}
	
	public static function by_stub( $stub )
	{
		self::eagerload();
		foreach( self::$list as $id => $data )
		{
			if( $data['stub'] == $stub ) return $id;
		}
	}

	public static function by_phrase( $phrase )
	{
		self::eagerload();
		foreach( self::$list as $id => $data )
		{
			if( $data['phrase'] == $phrase ) return $id;
		}
	}

	public static function get( $id = null, $default = null )
	{
		self::eagerload();
		if( ! isset( self::$list[$id] ) ) self::set( $id, $default );
		return self::$list[$id];
	}

	public static function set( $id, $phrase )
	{
		if( empty( $id ) ) return;
		if( ! $result = self::get( $id ) )
		{
			$result = new self;
			$result->phrase = $phrase;
		}
		$result->phrase = $phrase;
		$result->seoStub();
		$result->save();
		self::$list[$result->id] = array( 'phrase' => $result->phrase, 'stub' => $result->stub );
	}

	private static function eagerload()
	{
		if( self::$list === false )
		{
			self::$list = Cache::remember('phrase-all', function(){
				$return = array();
				if( $results = parent::all() )
				{
					foreach( $results as $o ) $return[$o->id] = array( 'phrase' => $o->phrase, 'stub' => $o->stub );
				}
				return $return;
			}, 60*24);
		}
	}

	public function seoStub()
	{
		$this->stub = htmlawed::makeSeoSlug( $this->phrase, '-' );
	}

	public function save()
	{
        if( empty( $this->stub ) ) $this->seoStub();
		parent::save();
	}
}
