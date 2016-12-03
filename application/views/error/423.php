<?php

print View::make('shells.main')
	->with( 'title', '423 - Denied' )
	->with( 'content', View::make('pages.423') )
	->render();