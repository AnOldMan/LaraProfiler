<?php

if( isset( $aMenu ) ) :
	$base = arg(0);
	$out = array();
	foreach( $aMenu as $key => $txt )
	{
		$class = $key == $base ? ' class="current"' : '';
		$out[] = '						<li><a' . $class. ' href="/' . $key . '"><span class="site-menu menu-icon-'
					. $key . '"></span><span class="text">' . $txt . "</span></a></li>\n";
	}
?>
<div class="logo"><a href="/"><img src="/assets/shell/logo.png" alt="Home" title="Home" /></a></div>
<ul class="nav mobile-hidden clearfix">
<?= implode( "	<li class=\"divider\"></li>\n", $out ) ?>
</ul>
<?php
endif;

if( ! empty( $heading ) ) : ?>
<h1><?= $heading ?></h1>
<?php

endif;