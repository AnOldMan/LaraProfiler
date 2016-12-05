<?php

// jquery, jquery ui and theme included in shell
Asset::style( 'jcrop-css', 'jquery/jcrop/css/jquery.Jcrop.min.css' )
	->script( 'jquery-imagesloaded', 'admin/jquery.imagesloaded.min.js', 'jquery' )
	->script( 'jcrop', 'jquery/jcrop/js/jquery.Jcrop.min.js', 'jquery' )
	->script( 'image-bundle', 'admin/image.js', 'jquery' )
	->script( 'jquery-ui-widget', 'jquery/fileupload/vendor/jquery.ui.widget.js', 'jquery-ui' )
	->script( 'jquery-iframe-transport', 'jquery/fileupload/jquery.iframe-transport.js', 'jquery-ui-widget' )
	->script( 'jquery-fileupload', 'jquery/fileupload/jquery.fileupload.js', 'jquery-iframe-transport' );

if( ! empty( $load_assets ) ) return;

$image       = empty( $image )       ? '' : $image;
$use_crop    = empty( $use_crop )    ? false : $use_crop;
$crop_top    = empty( $crop_top )    ? '' : $crop_top;
$crop_bottom = empty( $crop_bottom ) ? '' : $crop_bottom;
$crop_left   = empty( $crop_left )   ? '' : $crop_left;
$crop_right  = empty( $crop_right )  ? '' : $crop_right;

$image       = Input::old( 'image', $image );
$crop_top    = Input::old( 'crop_top', $crop_top );
$crop_right  = Input::old( 'crop_right', $crop_right );
$crop_bottom = Input::old( 'crop_bottom', $crop_bottom );
$crop_left   = Input::old( 'crop_left', $crop_left );

$image_class = $image ? '' : 'disabled';
$max_file_size = ini_get( 'post_max_size' );

$uploadtype = empty( $uploadtype ) ? 'image' : $uploadtype;

?><div class="field-wrap image"><?=
	Form::label('image', 'Image')
	?><div class="field-inner image-upload-widget" data-use-crop="<?= $use_crop ? 1 : 0 ?>"><?php
		?><div class="crop-selection <?= $image_class ?>"><?php
			?><img src="<?= $image ?>" /><?php
			if( $use_crop ) :
				print '<p class="help">Click and drag to select the portion of the image to preserve (thumb/crop).</p>';
				print Form::hidden( 'crop_top',    $crop_top );
				print Form::hidden( 'crop_bottom', $crop_bottom );
				print Form::hidden( 'crop_left',   $crop_left );
				print Form::hidden( 'crop_right',  $crop_right );
			endif;
		?></div><?=
		Form::hidden( 'image', $image )
		?><div class="upload-widget"><?php
			?><span>Upload Image</span><?php
			?><input type="file" name="files[]" data-url="/upload/<?= $uploadtype ?>" multiple /><?php
		?></div><?php
		?><span class="info">Max file size: <?= $max_file_size ?>B</span><?php
		?><span class="error"></span><?php
	?></div><?php
?></div>