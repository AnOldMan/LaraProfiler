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

	public static function paginate( $stub )
	{
		return true;
	}

	public function seoStub()
	{
		$this->stub = htmlawed::makeSeoSlug( $this->fullname, '-' );
	}

	public function save()
	{
        if( empty( $this->fullname ) ) 
		{
			$this->fullname = $this->firstname . ' ' . $this->middlename . ' ' . $this->lastname;
        }

        if( empty( $this->stub ) ) 
		{
			$this->seoStub();
        }

		parent::save();
	}

	public static function link( $id )
	{
		if( $a = self::find( $id ) )
		{
			return self::format_link( $a->stub, $a->fullname, $a->birthyear );
		}
		return '';
	}

	public static function format_link( $stub, $name, $birth )
	{
		$birth = $birth ? ' <sub>('.$birth.')</sub>' : '';
		return '<a class="person-link" href="' . URI::to_route( 'person', array( $stub ), false ) . '">' . $name . $birth . '</a>';
	}
}
