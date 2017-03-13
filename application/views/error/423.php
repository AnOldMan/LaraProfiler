<?php

print View::make('shells.main')
	->with( 'title', '423 - Denied' )
	->with( 'content', htmlawed::indent( View::make('error.content.423')->render(), 4 ) )
	->render();