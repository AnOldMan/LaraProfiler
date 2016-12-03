<?php

// use cache in case error is in default view[s]
$file = htmlawed::getFilesPath() . 'errorpage.html';
if( $cache = @file_get_contents( $file ) )
{
	print $cache;
	print '<!-- cached -->';
	exit;
}

$page = View::make('shells.main')
	->with( 'title', '500 - Internal Server Error' )
	->with( 'notopbar', true )
	->with( 'heading', 'Server Error: 500 (Internal Server Error)' )
	->with( 'content', View::make('pages.500') )
	->render();

@file_put_contents( $file, $page );

print $page;
exit;