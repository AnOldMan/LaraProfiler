<?php
if( empty( $paginator ) ) return;
$list = array();
foreach( $paginator->results as $url => $title ) $list[] =  Film::format_link( $url, $title );
?>
<div class="film-list">
	<h3><?= $paginator->total . ( $paginator->total == 1 ? ' film' : ' films' ) ?></h3>
<?= HTML::indent( HTML::ul( $list, array( 'html' => true, 'class' => 'list-unstyled film-list' ) ), 1 ) ?>
	<?= $paginator->of_count() ?>

	<?= $paginator->links(2,0) ?>

</div>