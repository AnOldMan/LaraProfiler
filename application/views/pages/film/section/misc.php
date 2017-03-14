<?php

if( empty( $data ) ) return '';

$tabs = array();

$tabs['Audio'] = array(
	'content' => empty( $data['audio'] )
		? ''
		: View::make( 'pages.film.partials.wrap' )
			->with( 'type', 'audio' )
			->with( 'data', $data['audio']  )
			->render(),
	'control' => 'Audio',
	'active' => true
);

$tabs['Subtitle'] = array(
	'content' => empty( $data['subtitle'] )
		? ''
		: View::make( 'pages.film.partials.wrap' )
			->with( 'type', 'subtitle' )
			->with( 'data', $data['subtitle']  )
			->render(),
	'control' => 'Subtitles',
	'active' => count( $tabs ) ? false : true
);

$tabs['Disc'] = array(
	'content' => empty( $data['disc'] )
		? ''
		: View::make( 'pages.film.partials.wrap' )
			->with( 'type', 'disc' )
			->with( 'data', $data['disc']  )
			->render(),
	'control' => 'Discs',
	'active' => count( $tabs ) ? false : true
);

$tabs['EasterEgg'] = array(
	'content' => ( empty( $data['detail'] ) || empty( $data['detail']['easteregg'] ) )
		? ''
		: View::make( 'pages.film.partials.wrap' )
			->with( 'type', 'easteregg' )
			->with( 'data', $data['detail']['easteregg']  )
			->render(),
	'control' => 'Easter Eggs',
	'active' => count( $tabs ) ? false : true
);

print View::make( 'partials.tabs' )->with( 'tabs', $tabs )->with( 'bottom', true )->render();