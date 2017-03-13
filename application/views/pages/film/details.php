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
if( empty ( $film['detail']['overview'] ) ) $film['detail']['overview'] = '';

if( $content = View::make( 'pages.film.section.general' )
	->with( 'film', $film )
	->render() )
{
	print htmlawed::indent( $content, 4 );
	print "\n";
}
else print "<!-- No General -->\n";
if( $content = View::make( 'pages.film.partials.wrap' )
	->with( 'type', 'feature' )
	->with( 'data', $film['feature'] )
	->render() )
{
	print htmlawed::indent( $content, 4 );
	print "\n";
}
else print "<!-- No Feature -->\n";
if( $content = View::make( 'pages.film.partials.wrap' )
	->with( 'type', 'overview' )
	->with( 'data', $film['detail']['overview'] )
	->render() )
{
	print htmlawed::indent( $content, 4 );
	print "\n";
}
else print "<!-- No Overview -->\n";
if( $content = View::make( 'pages.film.section.cast-crew' )
	->with( 'role', $film['role'] )
	->with( 'credit', $film['credit'] )
	->render() )
{
	print htmlawed::indent( $content, 4 );
	print "\n";
}
else print "<!-- No Cast/Crew -->\n";
if( $content = View::make( 'pages.film.section.cover' )
	->with( 'data', $film['detail'] )
	->render() )
{
	print htmlawed::indent( $content, 4 );
	print "\n";
}
else print "<!-- No Detail -->\n";
if( $content = View::make( 'pages.film.section.misc' )
	->with( 'data', $film )
	->render() )
{
	print htmlawed::indent( $content, 4 );
	print "\n";
}
else print "<!-- No Misc -->\n";