		<div class="h_blue" id="border-top">
			<span class="logo"></span>
			<span class="title"><a href="/admin" title="Administration Home">Administration</a></span>
		</div>
<?= empty( $menu ) ? View::make( 'admin::partials.new.menu' ) : $menu ?>
		<div id="content-box">
			<div id="toolbar-box">
				<div class="m">
					<div class="pagetitle">
<?php

global $section_icon, $section_title;

$title = empty( $page_title )
	? ( empty( $section_title ) ? 'Administration' : $section_title )
	: $page_title;

?>
						<div id="icon-<?= empty( $section_icon ) ? 'home' : $section_icon ?>" class="icon32"><br /></div><h2><?= $title ?></h2>
					</div>
				</div>
			</div>
			<div id="element-box">
				<div class="m">
					<div class="adminform">
<?php

if( Session::has( 'preview' ) )
{
	$link = Session::get('preview');
	Session::forget('preview');
	print '						<pre class="message session-message"><b>Item saved - </b> ' . HTML::link( $link, 'Visit Item' ) . "</pre>\n";
}

if( ! empty( $errors ) ) : ?>
						<?= Form::errors( $errors ) ?>
<?php endif; ?>
						<div class="cpanel">
<?php

if( ! empty( $message ) ) print $message;
print $content

?>

						</div>
					</div>
				</div>
				<div class="clr"></div>
			</div>
		</div>
