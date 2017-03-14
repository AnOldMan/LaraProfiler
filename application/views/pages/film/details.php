<?php

if( empty( $film ) ) return '';

foreach( array(
	'audio',
	'subtitle',
	'credit',
	'detail',
	'disc',
	'feature',
	'format',
	'genre',
	'manufacturer',
	'rating',
	'role',
	'studio'
	) as $k ) if( empty( $film[$k] ) ) $film[$k] = array();

$film['detail']['uid'] = empty( $film['uid'] ) ? '' : $film['uid'];
if( empty ( $film['detail']['overview'] ) ) $film['detail']['overview'] = ' ';

?>
<div class="grid-half">
<?php
if ( $content = View::make( 'pages.film.partials.wrap' )
	->with( 'type', 'general' )
	->with( 'content', View::make( 'pages.film.section.general' )
		->with( 'film', $film )
		->render() )
	->render() ) print HTML::indent( $content, 1 );
else print "\t<!-- NO GENERAL -->\n";

if ( $content = View::make( 'pages.film.partials.wrap' )
	->with( 'type', 'feature' )
	->with( 'data', $film['feature'] )
	->render() ) print HTML::indent( $content, 1 );
else print "\t<!-- NO FEATURE -->\n";

if ( $content = View::make( 'pages.film.partials.wrap' )
	->with( 'type', 'overview' )
	->with( 'data', $film['detail']['overview'] )
	->render() ) print HTML::indent( $content, 1 );
else print "\t<!-- NO OVERVIEW -->\n";

if ( $content = View::make( 'pages.film.partials.wrap' )
	->with( 'type', 'cast-crew' )
	->with( 'content', View::make( 'pages.film.section.cast-crew' )
		->with( 'role', $film['role'] )
		->with( 'credit', $film['credit'] )
		->render() )
	->render() ) print HTML::indent( $content, 1 );
else print "\t<!-- NO CAST/CREW -->\n";

?></div>
<div class="grid-half">
<?php
if ( $content = View::make( 'pages.film.partials.wrap' )
	->with( 'type', 'cover' )
	->with( 'content', View::make( 'pages.film.section.cover' )
		->with( 'data', $film['detail'] )
		->render() )
	->render() ) print HTML::indent( $content, 1 );
else print "\t<!-- NO COVER -->\n";

if ( $content = View::make( 'pages.film.partials.wrap' )
	->with( 'type', 'misc' )
	->with( 'content', View::make( 'pages.film.section.misc' )
		->with( 'data', $film )
		->render() )
	->render() ) print HTML::indent( $content, 1 );
else print "\t<!-- NO MISC -->\n";

?></div>