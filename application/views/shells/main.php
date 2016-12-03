<?php

Asset::style( 'main-css', 'main.css' )
	->script( 'main-js', 'main.js' );

$title = empty( $title ) ? '' : $title;
$heading = empty( $heading ) ? $title : $heading;

$header = empty( $header )
	? View::make( 'regions.header' )
		->with( 'heading', $heading )
		->with( 'topbar', empty( $notopbar ) )
		->render()
	: $header;

$content = empty( $content )
	? View::make( 'error.404' )
		->render()
	: $content;

$footer = empty( $footer )
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
			<section class="row">
<?= $header ?>
			</section>
		</header>
<?php endif;
	  if( ! empty( $content ) ) : ?>
		<main class="container">
			<section class="row">
<?=  $content; ?>

			</section>
		</main>
<?php endif;
	  if( ! empty( $footer ) ) : ?>
		<footer class="container">
			<section class="row">
<?=  $footer; ?>
			</section>
		</footer>
<?php endif; ?>
	</body>
</html>