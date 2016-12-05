<?php

class ImageCache
{

	public $source;
	public $dest;

	public static function source( $source, $dest = '' )
	{
		return new ImageCache( $source, $dest );
	}

	public function __construct( $source, $dest = '' )
	{
		$this->source = $this->cleanpath( $source );
		if( !$dest ) $dest = Config::get( 'image::image.cache_path' ) . basename( $source );
		$this->dest = $this->cleanpath( $dest );
	}

	public function thumb( $width, $height, $photo = array(), $quality = 90 )
	{
		if( ! File::exists( $this->source ) ) return false;
		if( ! File::exists( $this->dest ) )
		{
			$photo = $this->checkPhotoArray( $photo );
			Log::imagecache( 'building thumb: '. $this->dest );

			$oImageResize = ImageResize::open( $this->source );

			if( $width > $photo['width'] || $height > $photo['height'] ) $oImageResize->scale( $width, $height, true );

			$oImageResize->thumb( $width, $height, $photo['left'], $photo['top'], $photo['right'], $photo['bottom'] );

			if( $oImageResize->save( $this->dest, $quality ) ) return true;
		}

		return false;
	}

	public function crop( $width, $height, $photo = array(), $quality = 90 )
	{
		if( ! File::exists( $this->source ) ) return false;
		if( ! File::exists( $this->dest ) )
		{
			$photo = $this->checkPhotoArray( $photo );
			Log::imagecache( 'building crop: '. $this->dest );

			$oImageResize = ImageResize::open( $this->source );

			if( $width > $photo['width'] || $height > $photo['height'] ) $oImageResize->scale( $width, $height, true );

			if ( $photo['preserve'] ) $oImageResize->thumb( $width, $height, $photo['left'], $photo['top'], $photo['right'], $photo['bottom'] );

			$oImageResize->crop( $width, $height );

			if( $oImageResize->save( $this->dest, $quality ) ) return true;
		}

		return false;
	}

	public function scale( $width, $height, $photo = array(), $quality = 90 )
	{
		if( ! File::exists( $this->source ) ) return false;
		if( ! File::exists( $this->dest ) )
		{
			$photo = $this->checkPhotoArray( $photo );
			Log::imagecache('building scale: '. $this->dest );

			$oImageResize = ImageResize::open( $this->source );

			if( $width > $photo['width'] || $height > $photo['height'] ) $oImageResize->scale( $width, $height, true );

			if ( $photo['preserve'] ) $oImageResize->thumb( $width, $height, $photo['left'], $photo['top'], $photo['right'], $photo['bottom'] );

			$oImageResize->scale( $width, $height );

			if( $oImageResize->save( $this->dest, $quality ) ) return true;
		}
		return false;
	}

	public function checkPhotoArray( $photo )
	{
		if( ! is_array( $photo ) ) $photo = array();
		foreach( array( 'width', 'height', 'left', 'top', 'right', 'bottom' ) as $k ) if( empty( $photo[$k] ) ) $photo[$k] = 0;

		if( ! $photo['width'] || ! $photo['height'] )
		{
				$info = @getimagesize( $this->source );
				$photo['width'] = empty( $info[0] ) ? 0 : (int)$info[0];
				$photo['height'] = empty( $info[1] ) ? 0 : (int)$info[1];
		}

		$photo['preserve'] = ( $photo['left'] || $photo['top'] || $photo['right'] || $photo['bottom'] ) ? true : false;

		return $photo;
	}

	public function cleanpath( $str )
	{
		return preg_replace( '/\/+/', '/', preg_replace( '/\\\+/', '\\', $str ) );
	}
}
