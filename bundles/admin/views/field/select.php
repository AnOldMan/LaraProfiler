<?php

if( empty( $name ) ) return '';

$class = empty( $class ) ? '' : $class;
$label = empty( $label ) ? ucfirst( $name ) : $label;

$value = Input::old( $name, ( isset( $value ) ? $value : ( isset( $default ) ? $default : '' ) ) );

if( $class ) $class .= ' ';
$class .= $name;
if( isset( $required ) && $required ) $class .= ' required';

if( isset( $select_one ) && $select_one ) $options = array( 0 => '--- Select One ---' ) + $options;

?><div class="field-wrap <?= $class ?>"><?=
	Form::label( $name, $label, array( 'id' => $name . '-label' ) )
	?><div class="field-inner"><?php
		print Form::select( $name, $options, $value );
		if( isset( $errors ) ) print $errors->first( $name, '<p class="error">:message</p>' );
	?></div><?php
	if( ! empty( $help ) ) print '<p class="help">' . $help . '</p>';
?></div>