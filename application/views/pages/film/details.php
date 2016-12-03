<?php

if( empty( $film ) ) return;

if( $content = View::make( 'pages.film.partials.wrap' )
    ->with( 'type', 'detail' )
    ->with( 'uid', $film['uid'] )
    ->with( 'data', $film['detail'] )
    ->render() )
{
    print $content;
    print "\n";
}

foreach( array(
    'audio',
    'subtitle',
    'credit',
    'disc',
    'feature',
    'format',
    'genre',
    'manufacturer',
    'rating',
    'role',
    'studio'
    ) as $k ) {
    if( empty( $film[$k] ) ) continue;
    if( $content = View::make( 'pages.film.partials.wrap' )
            ->with( 'type', $k )
            ->with( 'uid', $film['uid'] )
            ->with( 'data', $film[$k] )
            ->render() )
    {
        print $content;
        print "\n";
    }
}