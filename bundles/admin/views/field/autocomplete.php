<?php

if( empty( $name ) ) return '';

Asset::script( 'admin-widget', 'admin/widget.js', 'jquery-ui' );

$class = empty( $class ) ? '' : $class;
$label = empty( $label ) ? ucfirst( $name ) : $label;

if( $class ) $class .= ' ';
$class .= 'field-' . $name;
if( isset( $required ) && $required ) $class .= ' required';

?><div class="field-wrap <?= $class ?>"><?=
	Form::label( $name, $label, array( 'id' => $name . '-label' ) )
	?><div class="field-inner autocomplete-input-widget <?=
											empty( $sortable ) ? '' : 'sortable'
			?>" data-input-label="Add <?=
											e( $label )
			?>" data-input-name="<?=
											$name
			?>" data-selected-items="<?=
											e( json_encode( empty( $selected_items ) ? '' : $selected_items ) )
			?>" data-all-items="<?=
											e( json_encode( empty( $all_items ) ? '' : $all_items ) )
			?>" data-multiple="<?=
											( isset( $multiple ) && $multiple ) ? 1 : 0
			?>"><?php
		?><ul class="items"></ul><?php
			print Form::text( $name );
			if( isset( $errors ) ) print $errors->first( $name, '<p class="error">:message</p>' );
		?></div><?php
	if( ! empty( $help ) ) print '<p class="help">' . $help . '</p>';
?></div>