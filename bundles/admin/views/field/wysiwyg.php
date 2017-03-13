<?php

if( empty( $name ) ) return '';

Asset::add( 'ckeditor', 'ckeditor/ckeditor.js', array(), array( 'display' => 'nomin' ) );

include_once( Bundle::path('admin') . 'libraries'.DS.'ckeditor'.DS.'ckeditor_php5.php' );
$oEditor = new EditorStart;

$class = empty( $class ) ? '' : $class;
$label = empty( $label ) ? ucfirst( $name ) : $label;

$value = Input::old( $name, ( isset( $value ) ? $value : ( isset( $default ) ? $default : '' ) ) );

if( $class ) $class .= ' ';
$class .= $name;
if( isset( $required ) && $required ) $class .= ' required';

?><div class="field-wrap <?= $class ?>"><?=
	Form::label( $name, $label, array( 'id' => $name . '-label' ) )
	?><div class="field-inner"><?php
		print $oEditor->showEditor( array(
			'name' => $name,
			'content' => $value,
			'width' => empty( $width ) ? 868 : $width,
			'height' => empty( $height ) ? 400 : $height,
			'toolbar' => empty( $toolbar ) ? '' : $toolbar
		) );
		if( isset( $errors ) ) print $errors->first( $name, '<p class="error">:message</p>' );
	?></div><?php
	if( ! empty( $help ) ) print '<p class="help">' . $help . '</p>';
?></div>