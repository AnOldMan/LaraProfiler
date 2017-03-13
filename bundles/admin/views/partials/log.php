<?php

$date = Input::get( 'date', date( 'M j, Y' ) );

$datetime = date_create( $date );

$file = path('storage') . 'logs' . DS . $datetime->format( 'Y-m-d' ) . '.log';

$contents = File::get( $file );

$filter = Input::get( 'filter' );

if( $contents && $filter )
{
	$contents = explode( "\n", $contents );
	foreach( $contents as $k => $r ) if( ! stristr( $r, $filter ) ) unset( $contents[$k] );
	if( ! $contents ) $contents = 'No lines found containing search string.';
	else $contents = implode( "\n", $contents );
}

if( ! $contents ) $contents = 'No log file exists for ' . $date;

?>
							<?= Form::open( URL::to_route( 'view_logs' ), 'POST', array( 'class' => 'editorform' ) ) ?>

								<?= render( 'admin::field.date', array(
										'name' => 'date',
										'label' => 'Choose log file date :',
										'value' => $date
									)) ?>

								<?= render( 'admin::field.text', array(
										'name' => 'filter',
										'label' => 'Show only lines containing :',
										'value' => $filter
									)) ?>

								<div class="field-wrap action">
									<?= Form::submit( 'View file', array( 'class' => 'edit-add' ) ) ?>
								</div>

							<?= Form::close() ?>

							<div class="field-wrap">
								<div class="field-inner">
									<?= Form::textarea( 'contents', $contents, array( 'class' => 'logfile' ) ); ?>
								</div>
							</div>
