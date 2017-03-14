<?php
if( empty( $content ) )
{
	if( ! empty( $data ) ) $content = View::make( 'pages.film.partials.' . $type )->with( 'data', $data )->render();
}
if( empty( $content ) || empty( $type ) ) return '';

?>
<section class="wrap film-<?= $type ?>">
	<div class="debut"></div>
	<div class="inner">
<?= HTML::indent( $content, 2 ) ?>
	</div>
	<div class="finale"></div>
</section>