<?php

class Film extends Eloquent {

	public static $accessible = array(
		'id',
		'uid',
		'sorttitle',
		'stub',
		'created_at',
		'updated_at'
	);

	/* The attributes that should be excluded from to_array. */
	public static $hidden = array( 'id', 'created_at', 'updated_at' );

	public static function paginate_all()
	{
		$results = Cache::remember('film-all', function(){
			return Film::order_by('sorttitle')->lists('sorttitle','stub');
		}, 60*24);

		if( $results ) return Paginator::make( $results, count( $results ), 20, true );
	}

	public static function by_stub( $stub )
	{
		return Cache::remember('film-' . $stub, function() use( $stub ){
			return Film::with( array(
					'audio',
					'audio.content',
					'audio.format',
					'credit',
					'credit.person',
					'detail',
					'disc',
					'feature',
					'format',
					'genre',
					'genre.phrase',
					'manufacturer',
					'manufacturer.company',
					'rating',
					'role',
					'role.person',
					'studio',
					'studio.company',
					'subtitle'
				) )
				->where( 'stub', '=', $stub )
				->first()->to_array();
		}, 60*24);
	}

	public function audio(){
		return $this->has_many('Audio');
	}

	public function credit(){
		return $this->has_many('Credit');
	}

	public function detail()
	{
		return $this->has_one('Detail');
	}

	public function disc(){
		return $this->has_many('Disc');
	}

	public function feature()
	{
		return $this->has_one('Feature');
	}

	public function format()
	{
		return $this->has_one('Format');
	}

	public function genre(){
		return $this->has_many('Genre');
	}

	public function manufacturer(){
		return $this->has_one('Manufacturer');
	}

	public function role(){
		return $this->has_many('Role');
	}

	public function rating(){
		return $this->has_one('Rating');
	}

	public function studio(){
		return $this->has_many('Studio');
	}

	public function subtitle(){
		return $this->has_many('Subtitle');
	}

	public function seoStub()
	{
		$this->stub = htmlawed::makeSeoSlug( $this->sorttitle, '-' );
	}

	public function save()
	{
		if( empty( $this->stub ) )
		{
			$this->seoStub();
		}

		parent::save();
	}

	public static function format_link( $stub, $txt )
	{
		return '<a class="film-link" href="' . URI::to_route( 'film', array( $stub ), false ) . '">' . $txt . '</a>';
	}
}
