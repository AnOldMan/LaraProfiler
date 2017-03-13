<?php

$tabs = array();

if( $cast = empty( $role ) ? false : View::make( 'pages.film.partials.wrap' )
	->with( 'type', 'role' )
	->with( 'data', $role )
	->render() ) $tabs['role'] = array(
		'content' => $cast,
		'control' => 'Cast',
		'active' => true
	);

if( $credit = empty( $credit ) ? false : View::make( 'pages.film.partials.wrap' )
	->with( 'type', 'credit' )
	->with( 'data', $credit )
	->render() ) $tabs['crew'] = array(
		'content' => $credit,
		'control' => 'Crew',
		'active' => count( $tabs ) ? false : true
	);

print View::make( 'partials.tabs' )->with( 'tabs', $tabs )->with( 'bottom', true )->render();