<?php

$cancel_url = Input::get( 'cancel_url', empty( $cancel_url ) ? '' : $cancel_url );

if( empty( $cancel_url ) && ! empty( $cancel_route ) ) $cancel_url = route( $cancel_route );

if( empty( $cancel_url ) ) return '';

?><div class="field-wrap action"><?=
	Form::submit( 'Save', array( 'class' => 'edit-save' ) )
	?><a href="<?= URL::to( $cancel_url ) ?>" class="submit cancel edit-cancel"><span>Cancel</span></a><?php
	if( ! empty( $delete_link ) ) print $delete_link;
	if( ! empty( $help ) ) print '<p class="help">' . $help . '</p>';
?></div>