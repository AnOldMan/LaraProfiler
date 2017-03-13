<?php

if( empty( $data ) ) return '';

$banner = array(); $color = '';
foreach( array(
	'media_bluray' => 'blue',
	'media_bluray-3d' => 'blue',
	'media_4k' => 'black',
	'media_hddvd' => 'burg',
	'media_dvd' => 'grey'
) as $k => $c )
{
	if( ! empty( $data[$k] ) )
	{
		$e = explode( '_', $k );
		$i = array_pop( $e );
		$t = strtoupper( $i );
		$banner[] = HTML::image( '/assets/images/banner/' . $i . '.png', $t, array( 'title' => $t ) );
		if( ! $color ) $color = $c;
	}
}
if( ! empty( $data['media_custom'] ) ) $banner[] = '<em>' . $data['media_custom'] . '</em>';
if( empty( $color ) ) $color = 'grey';

$path = Config::get( 'application.files_url' ) . 'imagecache/cover/';

$tabs = array(
	'Front' => array(
		'content' => HTML::image( $path . $data['uid'] . 'f.jpg', 'Front' ),
		'control' => 'Front',
		'active' => false
	),
	'Back' => array(
		'content' => HTML::image( $path . $data['uid'] . 'b.jpg', 'Back' ),
		'control' => 'Back',
		'active' => false
	)
);

print View::make( 'partials.tabs' )
	->with( 'tabs', $tabs )
	->with( 'banner', '<div class="banner banner-'. $color .'">' . implode( '<b>+</b>', $banner ) . '</div>' )
	->with( 'bottom', true )
	->render();