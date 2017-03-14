<?php

Asset::style( 'main-css', 'main.css' )
	->script( 'main-js', 'main.js' );

$title = empty( $title ) ? '' : $title;
$heading = ! isset( $heading ) ? $title : $heading;

$header = ! isset( $header )
	? View::make( 'regions.header' )
		->with( 'heading', $heading )
		->with( 'topbar', empty( $notopbar ) )
		->render()
	: $header;

$content = ! isset( $content )
	? View::make( 'error.404' )
		->render()
	: $content;

$footer = ! isset( $footer )
	? View::make( 'regions.footer' )
		->render()
	: $footer;

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
<?php if( ! empty( $title ) ) : ?>
		<title><?= $title ?></title>
<?php endif; ?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="initial-scale=1,user-scalable=yes" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<?php if( ! empty( $description ) ) : ?>
		<meta name="description" content="<?= $description; ?>" />
<?php endif;
	  if( ! empty( $keywords ) ) : ?>
		<meta name="keywords" content="<?= $keywords; ?>" />
<?php endif; ?>

		<?= Asset::styles() ?>
		<?= Asset::scripts() ?>

	</head>
	<body>
<?php if( ! empty( $header ) ) : ?>
		<header class="container">
			<div class="row">
<?= htmlawed::indent( $header, 4 ) ?>

			</div>
		</header>
<?php endif;
	  if( ! empty( $content ) ) : ?>
		<main class="container">
			<div class="row">
<?= htmlawed::indent( $content, 4 ) ?>

			</div>
		</main>
<?php endif;
	  if( ! empty( $footer ) ) : ?>
		<footer class="container">
			<div class="row">
<?= htmlawed::indent( $footer, 4 ) ?>

			</div>
		</footer>
<?php endif; ?>
	</body>
</html>