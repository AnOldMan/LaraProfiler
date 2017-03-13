<?php

$theme = Input::get( 'theme', empty( Auth::user()->theme ) ? 'horizontal' : Auth::user()->theme );
$horizontal = false;
if( $theme == 'horizontal' )
{
	$theme = 'smoothness';
	$horizontal = true;
}

$menu = $horizontal
	? View::make( 'admin::partials.new.menu' )
	: View::make( 'admin::partials.leftmenu' );

global $section_title;

$page_title = empty( $page_title )
	? ( empty( $section_title ) ? 'Administration' : $section_title ) :
	$page_title;

Asset::style( 'jquery-ui-style', 'jquery-ui-' . $theme . '/jquery-ui.custom.css' )
	->style( 'admin', $horizontal ? 'admin-delta.css' : 'admin.css' )
	->style( 'jquery-ui-delta', 'jquery-ui-' . $theme . '/delta.css' )

	->script( 'jquery', 'jquery/jquery-1.8.0.js' )
	->script( 'jquery-ui', 'jquery-ui/jquery-ui.custom.js', 'jquery' )
	->script( 'admin-widget', 'admin/widget.js', 'jquery-ui' )
	->script( 'tagger', 'admin/tagger.js', 'jquery-ui' )
	->script( 'admin', 'admin/admin.js', 'jquery-ui' );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?= Config::get( 'application.sitename' ) ?> Administration</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?= Minifier::make() ?>

	</head>
	<body class="admin" id="minwidth-body">
<?= View::make( 'admin::partials' . ( $horizontal ? '.new' : '' ) . '.content' )
		->with( 'content', empty( $content ) ? View::make( 'admin::partials.home' ) : $content )
		->with( 'menu', $menu )
		->with( 'page_title', $page_title ) ?>
	</body>
</html>
