<?php

// Default values
$fields = empty( $fields ) ? array() : $fields;

?><div class="form-wrap list"><?=
	Form::open( URL::current(), 'get' )
	?><div class="fields"><?php
		foreach( $fields as $field ) print is_string( $field ) ? $field : $field->render();
		?><div class="field-wrap action"><?=
			Form::submit( 'Update' )
			?><a href="<?= URL::current().'?reset=true' ?>" class="cancel submit"><span>Clear</span></a><?php
		?></div><?php
	?></div><?=
	Form::close()
?></div>