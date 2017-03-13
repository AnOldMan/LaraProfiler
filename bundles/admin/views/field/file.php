<?php

if( empty( $name ) ) return '';

$class = empty( $class ) ? '' : $class;
$label = empty( $label ) ? ucfirst( $name ) : $label;

if( $class ) $class .= ' ';
$class .= 'field-' . $name;
if( isset( $required ) && $required ) $class .= ' required';

?><div class="field-wrap field-wrap-file <?= $class ?>"><?=
	Form::label( $name, $label, array( 'id' => $name . '-label' ) )
	?><div class="field-inner"><?php
		print Form::file( $name );
		if( isset( $errors ) ) print $errors->first( $name, '<p class="error">:message</p>' );
	?></div><?php
	if( ! empty( $help ) ) print '<p class="help">' . $help . '</p>';
?></div>