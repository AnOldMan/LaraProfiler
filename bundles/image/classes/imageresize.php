<?php

/**
 * ImageResize
 *
 * Utility class for resizing images.
 */

class ImageResize
{

	private $image;
	private $width;
	private $height;

	public static function open( $file )
	{
		return new ImageResize( $file );
	}

	public function __construct( $file )
	{
		if( $image = $this->open_image( $file ) ) $this->set_image( $image );
	}

	private function set_image( $image )
	{
		if( !empty( $this->image) ) imagedestroy( $this->image );

		$this->image = $image;
		$this->width = imagesx( $this->image );
		$this->height = imagesy( $this->image );
	}

	private function open_image( $file )
	{
		// Bump up the memory
		ini_set( 'memory_limit', '256M' );

		// If $file isn't an array, we'll turn it into one
		if( ! is_array( $file ) )
		{
			$file = array(
				'type' => File::mime( strtolower( File::extension( $file ) ) ),
				'tmp_name' => $file
			);
		}

		if( ! File::exists( $file['tmp_name'] ) ) return false;

		$file['type'] = strtolower( File::get_mime( $file['tmp_name'] ) );
		if( ! $file['type'] || ! in_array( $file['type'], array( 'jpg', 'gif', 'png' ) ) )
		{
			$info = getimagesize( $file['tmp_name'] );
			$file['type'] = $info['mime'];
		}

		switch ( $file['type'] )
		{
			case File::mime('jpg'):
				return imagecreatefromjpeg( $file['tmp_name'] );

			case File::mime('gif'):
				return imagecreatefromgif( $file['tmp_name'] );

			case File::mime('png'):
				return imagecreatefrompng( $file['tmp_name'] );
		}

		return false;
	}

	public function save( $save_path , $image_quality = 95 )
	{
		if( !$this->image ) return false;

		$extension = strtolower( File::extension( $save_path ) );

		switch( $extension )
		{
			case 'jpg':
			case 'jpeg':
				if( imagetypes() & IMG_JPG ) imagejpeg( $this->image, $save_path , $image_quality );
				else return false;

				break;

			case 'gif':
				if( imagetypes() & IMG_GIF ) imagegif( $this->image, $save_path );
				else return false;

				break;

			case 'png':
				// Scale quality from 0-100 to 0-9
				$scale_quality = round( ( $image_quality / 100 ) * 9 );
				// Invert quality setting as 0 is best, not 9
				$invert_scale_quality = 9 - $scale_quality;

				if( imagetypes() & IMG_PNG ) imagepng( $this->image, $save_path , $invert_scale_quality );
				else return false;

				break;

			default:
				return false;
		}
		// Remove the resource for the resized image
		imagedestroy( $this->image );
		return true;
	}

	/**
	 * function thumb
	 *
	 * generate an thumbnail while preserving a portion of the original image. left
	 * top, right and bottom are to be float values between 0 and 1. These define
	 * the portion of the image to preserve. It is possible for params to be such that
	 * the preserved portion of the image must be cropped to some extent in order to
	 * meet the height/width requirements.
	 *
	 * @param width int
	 * @param height int
	 * @param left float 0.0 - 1.0
	 * @param top float 0.0 - 1.0
	 * @param right float 0.0 - 1.0
	 * @param bottom float 0.0 - 1.0
	 *
	 * @return this ImageResize object for chaining
	 */
	public function thumb( $width, $height, $left, $top, $right, $bottom )
	{
		if ( !$this->image ) return $this;

		// The preserve area has not been set, use crop
		if ( $right <= 0 || $bottom <= 0 ) return self::crop( $width, $height );

		// Calculate pixel coordinates of area to preserve
		$source_left = $left * $this->width;
		$source_top = $top * $this->height;
		$source_width = ( $right - $left ) * $this->width;
		$source_height = ( $bottom - $top ) * $this->height;

		// Make sure the source width is at least as big as the crop width
		if ($source_width < $width)
		{
			// Stay centered
			$source_left -= ($width - $source_width) / 2;
			$source_width = $width;
		}

		// Make sure the source height is at least as big as the crop height
		if ($source_height < $height)
		{
			// Stay centered
			$source_top -= ($height - $source_height) / 2;
			$source_height = $height;
		}

		$dest_ratio = $height / $width;
		$source_ratio = $source_height / $source_width;

		// Dest is more of a portrait shape, need to expand source height, top
		if ($dest_ratio > $source_ratio)
		{
			$source_height_adj = $source_width * $dest_ratio;
			$source_top -= abs($source_height_adj - $source_height) / 2;
			$source_height = $source_height_adj;
		}
		// Dest is more of a landscape shape, need to expand source width, left
		else
		{
			$source_width_adj = $source_height / $dest_ratio;
			$source_left -= abs($source_width_adj - $source_width) / 2;
			$source_width = $source_width_adj;
		}

		// Floor everything
		$source_left = floor( $source_left );
		$source_top = floor( $source_top );
		$source_width = floor( $source_width );
		$source_height = floor( $source_height );

		// Has the selection become too large? This may eliminate parts of the focal
		// area but we are doing the best we can given the constaints
		if ( $source_height > $this->height || $source_width > $this->width )
		{
			$scale = min( ( $this->height / $source_height ), ( $this->width / $source_width ) );
			$center_x = $source_left + ($source_width / 2);
			$center_y = $source_top + ($source_height / 2);
			$source_width = $scale * $source_width;
			$source_height = $scale * $source_height;
			$source_left = $center_x - ($source_width / 2);
			$source_top = $center_y - ($source_height / 2);
		}

		// Floor everything
		$source_left = floor( $source_left );
		$source_top = floor( $source_top );
		$source_width = floor( $source_width );
		$source_height = floor( $source_height );

		// Ensure selection is still within original image (left)
		$source_left = max($source_left, 0);
		$right = $source_left + $source_width;
		if ($right > $this->width) $source_left -= $right - $this->width;

		// Ensure selection is still within original image (right)
		$source_top = max($source_top, 0);
		$bottom = $source_top + $source_height;
		if ($bottom > $this->height) $source_top -= $bottom - $this->height;

		// Floor everything
		$source_left = floor( $source_left );
		$source_top = floor( $source_top );
		$source_width = floor( $source_width );
		$source_height = floor( $source_height );

		// Do the actual image conversion
		$image = imagecreatetruecolor( $width, $height );
		imagecopyresampled( $image, $this->image, 0, 0, $source_left, $source_top, $width, $height, $source_width, $source_height );

		// Swap out image data
		$this->set_image( $image );

		return $this;
	}

	/**
	 * function crop
	 *
	 * generates an image of the given dimensions while preserving as much as
	 * possible of the original image
	 *
	 * @param width int
	 * @param height int
	 *
	 * @return this ImageResize object for chaining
	 */
	public function crop( $width, $height )
	{
		if ( !$this->image ) return $this;

		$source_left = 0;
		$source_top = 0;
		$source_width = $this->width;
		$source_height = $this->height;

		$dest_ratio = $height / $width;
		$source_ratio = $this->height / $this->width;

		// Dest is more of a portrait shape, need to adjust source width, left
		if( $dest_ratio > $source_ratio )
		{
			$source_width = $this->height / $dest_ratio;
			$source_left = floor( ( $this->width - $source_width ) / 2 );
		}
		// Dest is more of a landscape shape, need to adjust source height, top
		else
		{
			$source_height = $this->width * $dest_ratio;
			$source_top = floor( ( $this->height - $source_height ) / 2 );
		}

		// Sanity check before returning result
		if( !( is_int( $width ) && is_int( $height ) && $width > 0 && $height > 0 ) )
		{
			Log::imageresize( 'scale - improper dimensions width: '.$width.' height: '.$height );
			return $this;
		}

		$image = imagecreatetruecolor($width, $height);
		imagecopyresampled($image, $this->image, 0, 0, $source_left, $source_top, $width, $height, $source_width, $source_height);

		$this->set_image( $image );

		return $this;
	}

	/**
	 * function scale
	 *
	 * generates an image that will fit within the given dimensions while preserving the entire image.
	 *
	 * @param width int
	 * @param height int
	 * @param upscale bool optional default false
	 *
	 * @return this ImageResize object for chaining
	 */
	public function scale( $width, $height, $upscale = false )
	{
		if ( !$this->image ) return $this;

		// If both height and width are provided, scale to fit within those constraints.
		// If only one is provided, scale to be within that dimension.
		// If neither have been provided, ignore this request.
		//
		if( $width > 0 && $height > 0 )
		{
			$scale = min( ($width / $this->width) , ($height / $this->height) );
		}
		else if( $width <= 0 && $height > 0 )
		{
			$scale = $height / $this->height;
		}
		elseif ( $height <= 0 && $width > 0 )
		{
			$scale = $width / $this->width;
		}
		else return $this;

		if( $scale > 1 && !$upscale ) return $this;

		$width = floor( $this->width*$scale );
		$height = floor( $this->height*$scale );

		$image = imagecreatetruecolor($width, $height);
		imagecopyresampled($image, $this->image, 0, 0, 0, 0, $width, $height, $this->width, $this->height);

		$this->set_image( $image);

		return $this;
	}
}
