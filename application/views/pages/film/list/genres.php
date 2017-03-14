<?php if( empty( $select ) )  return ''; ?>
<div class="genre-select">
	<label>Available Genres:</label>
	<div class="list-select">
		<span class="current"><?= $select[$stub] ?></span>
		<div class="options">
<?php
foreach( $select as $url => $title ) :
	if( $url == $stub ) : ?>
		<span class="option selected"><?= $title ?></span>
<?php else : ?>
		<?= str_replace( 'genre-link', 'option', Genre::format_link( $url, $title ) ) ?>
<?php
	endif;
endforeach; ?>
		</div>
	</div>
</div>
<?php

if( empty( $paginator ) ) return;

print View::make( 'pages.film.list.paginator' )
		->with( 'paginator', $paginator )
		->render();