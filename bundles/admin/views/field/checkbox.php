<?php

if( empty( $name ) ) return '';

$class = empty( $class ) ? '' : $class;
$label = empty( $label ) ? ucfirst( $name ) : $label;

$value = Input::old( $name, ( isset( $value ) ? $value : ( isset( $default ) ? $default : '' ) ) );

if( strtolower( $label ) === 'slug' ) $label = 'Name';

if( $class ) $class .= ' ';
$class .= 'field-' . $name;
if( ! empty( $left ) ) $class .= ' checkbox-left';

?><div class="field-wrap <?= $class ?>"><?php
if( empty( $left ) )
{
	print Form::label( $name, $label, array( 'id' => $name . '-label' ) );
	?><div class="field-inner"><?php
		print Form::checkbox( $name, 1, $value, array( 'id' => $name ) );
		if( isset( $errors ) ) print $errors->first( $name, '		<p class="error">:message</p>' );
}
else
{
	?><div class="field-inner"><?php
		print Form::checkbox( $name, 1, $value, array( 'id' => $name ) );
		if( isset( $errors ) ) print $errors->first( $name, '<p class="error">:message</p>' );
		print Form::label( $name, $label, array( 'id' => $name . '-label' ) );
}
	?></div><?php
	if( ! empty( $help ) ) print '<p class="help">' . $help . '</p>';
?></div>