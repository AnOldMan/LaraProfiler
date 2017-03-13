<?php

/*
$choices = array(
  array( 'label' => 'value' ),
  array( 'label' => 'value' ),
  // ...
)
$help = array(
  array( 'value' => 'help' ),
  array( 'value' => 'help' ),
  // ...
)
*/

if( empty( $name ) || empty( $choices ) || ! is_array( $choices ) ) return '';

if( empty( $help ) ) $help = array();
if( ! is_array( $help ) ) $help = array( 'general' => $help );

$class   = empty( $class ) ? '' : $class;

$value = Input::old( $name, ( isset( $value ) ? $value : ( isset( $default ) ? $default : '' ) ) );

if( $class ) $class .= ' ';
$class .= 'field-' . $name;

$outer = empty( $label ) ? 'fieldset radio ' : 'field-wrap radio ';

$inner = empty( $label ) ? 'field-wrap ' : 'field-inner ';

?><div class="<?= $outer . $class ?>"><?php
	if( ! empty( $label ) ) print "<label>$label</label>";
	foreach( $choices as $item )
	{
		foreach( $item as $label => $current )
		{
				$id = preg_replace( '/[^a-z0-9_]/', '', strtolower( strip_tags( $name . '_' . $label ) ) );
			?><div class="<?= $inner .  $class ?>"><?php
				?><input type="radio" id="<?= $id ?>" name="<?= $name ?>" value="<?= $current ?>"<?=
					$current == $value ? ' checked="checked"' : '' ?> /><?php
				?><label for="<?= $id ?>" id="<?= $id ?>-label"><?= $label ?></label><?php
			?></div><?php
			if( ! empty( $help[$current] ) ) print '<p class="help">' . $help[$current] . '</p>';
		}
	}
	if( isset( $errors ) ) print $errors->first( $name, '<p class="error">:message</p>' );
	if( ! empty( $help['general'] ) ) print '<p class="help">' . $help['general'] . '</p>';

?></div>