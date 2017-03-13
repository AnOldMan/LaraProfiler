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
		<?= Genre::format_link( $url, $title ) ?>
<?php
	endif;
endforeach; ?>
		</div>
	</div>
</div>
<?php if( empty( $paginator ) ) return; ?>
<div class="film-list">
	<h3><?= $paginator->total . ( $paginator->total == 1 ? ' film' : ' films' ) ?></h3>
	<ul class="list-unstyled">
<?php foreach( $paginator->results as $url => $title ) : ?>
		<li><?= Film::format_link( $url, $title ) ?></li>
<?php endforeach; ?>
	</ul>
	<?= $paginator->of_count() ?>

	<?= $paginator->links(2,0) ?>

</div>