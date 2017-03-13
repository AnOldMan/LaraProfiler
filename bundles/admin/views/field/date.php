<?php

if( empty( $name ) ) return '';

$class = empty( $class ) ? '' : $class;
$label = empty( $label ) ? ucfirst( $name ) : $label;

if( $class ) $class .= ' ';
$class .= $name;
if( isset( $required ) && $required ) $class .= ' required';

$datetime = date_create( $value );
if( ! $datetime ) $datetime = date_create();

$date = Input::old( $name . '_date', $datetime->format( 'M j, Y' ) );
$time = Input::old( $name . '_time', $datetime->format( 'g:i:s A' ) );

Asset::script( 'admin-widget', 'admin/widget.js', 'jquery-ui' );

?><div class="field-wrap <?= $class ?> date-input-widget"><?=
	Form::label( $name, $label, array( 'id' => $name . '-label' ) )
	?><div class="field-inner"><?php
		print Form::text( $name . '_date', $date, array( 'class' => 'date' ) );
		if( isset( $show_time ) && $show_time ) print Form::text( $name . '_time', $time, array( 'class' => 'time' ) );
		print Form::hidden( $name, $date . ' ' . $time, array( 'class' => 'datetime' ) );
		if( isset( $errors ) ) print $errors->first( $name, '<p class="error">:message</p>' );
	?></div><?php
	if( ! empty( $help ) ) print '<p class="help">' . $help . '</p>';
?></div>