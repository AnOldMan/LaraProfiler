<?php

if( empty( $name ) ) return '';

$class = empty( $class ) ? '' : $class;
$label = empty( $label ) ? ucfirst( $name ) : $label;
$limit = empty( $limit ) ? 0 : $limit;

$value = Input::old( $name, ( isset( $value ) ? $value : ( isset( $default ) ? $default : '' ) ) );

if( strtolower( $label ) === 'slug' ) $label = 'Name';

if( $class ) $class .= ' ';
$class .= 'field-' . $name;
if( isset( $required ) && $required ) $class .= ' required';

if( $limit ) $class .= ' text-limit-feedback" data-limit="' . $limit;

?><div class="field-wrap <?= $class ?>"><?=
	Form::label( $name, $label, array( 'id' => $name . '-label' ) )
	?><div class="field-inner"><?php
		print Form::textarea( $name, $value );
		if( isset( $errors ) ) print $errors->first( $name, '<p class="error">:message</p>' );
	?></div><?php
	if( ! empty( $help ) ) print '<p class="help">' . $help . '</p>';
?></div>