<?php

/**
 |--------------------------------------------------------------------------
 |
 | Base class for models representing/containing an image. Encapuslates file
 | management logic and provides methods to create thumbed, cropped or
 | scaled versions of the image using the ImageCache class.
 |
 | Requires a base model class be defined in the application.
 |
 | Requires a string column used to generate the file name. The name of this
 | column is configurable.
 |
 | Requires the a string column named 'image' capable of containing the path
 | to the image file.
 |
 | If crop selection is used, requires four, floating point columns named
 | 'crop_top', 'crop_right', 'crop_bottom' and 'crop_left'.
 |
 |--------------------------------------------------------------------------
 */

abstract class Image extends ContentItem
{
	public function original()
	{
		if( empty( $this->image ) ) return '';
		$path = Config::get( 'application.files_url' ) . 'images/photos/';
		if( strpos( $this->image, '/' ) !== false ) $this->image = basename( $this->image );
		return $this->cleanpath( $path . $this->image );
	}

	public function original_src()
	{
		if( empty( $this->image ) ) return '';
		$path = htmlawed::getFilesPath( 'images' . DS . 'photos' );
		if( strpos( $this->image, '/' ) !== false ) $this->image = basename( $this->image );
		return $this->cleanpath( $path . $this->image );
	}

	public function original_path()
	{
		if( empty( $this->image ) ) return '';
		$path = Config::get( 'application.files_url' ) . 'images/photos/';
		if( strpos( $this->image, '/' ) !== false ) $this->image = basename( $this->image );
		return $this->cleanpath( $path . $this->image );
	}

	public function thumb( $width, $height = 0 )
	{
		$width = (int)$width;
		if( ! $width ) $width = 100;
		$url = Config::get( 'image::image.cache_url' ) . $width;
		$height = (int)$height;
		if( $height && $height != $width ) $url .= 'x' . $height;
		if( strpos( $this->image, '/' ) !== false ) $this->image = basename( $this->image );
		return $this->cleanpath( $url . '/' . $this->image );
	}

	public function crop( $width, $height, $quality=90 )
	{
		return ImageCache::source( $this->image )
				->crop( $width, $height, $quality );
	}

	public function scale( $width, $height, $quality=90 )
	{
		return ImageCache::source( $this->image )
				->scale( $width, $height, $quality );
	}

	public function fill( array $attributes, $raw = false )
	{
		parent::fill( $attributes, $raw );
		$this->image = basename( $this->image );
	}

	public function save()
	{
		$this->image = basename( $this->image );
		parent::save();
	}

	public function delete()
	{
		parent::delete();
	}

	public function cleanpath( $str )
	{
		return preg_replace( '/\/+/', '/', preg_replace( '/\\\+/', '\\', $str ) );
	}
}
