<?php

$aMenu = User::menu();

if( ! empty( $aMenu ) && is_array( $aMenu ) )
{
	array_shift( $aMenu );// remove home
	if( Auth::user()->theme == 'horizontal' )
	{
		array_pop( $aMenu );// remove logout
?>
							<div class="icon-wrapper">
								<ul class="icon-link-list">
<?php
		foreach( $aMenu as $group ) if( $group ) foreach( $group as $item )
		{
?>
									<li class="icon link-<?php
										print $item['id'];
										if( ! empty( $item['subtitle'] ) ) print '" title="' . $item['subtitle'];
									?>"><a href="<?=
										$item['url']
									?>" class="submit"><span class="icon-image"></span><span><?=
										$item['title']
									?></span></a></li>
<?php
		}
?>
								</ul>
							</div>
<?php
	}
	else
	{
		foreach( $aMenu as $group )
		{
			if( $group ) foreach( $group as $item )
			{
?>
							<h3><?= $item['title'] ?></h3>
							<ul class="admin-home">
								<li class="admin-rowline"><p><?= $item['subtitle'] ?></p></li>
<?php 			if( $item['submenu'] ) foreach( $item['submenu'] as $submenu ) : ?>
								<li class="admin-rowline"><p><strong><?= $submenu['title'] ?>:</strong> <?= $submenu['subtitle'] ?></p></li>
<?php 			endforeach; ?>
							</ul>
							<br/>
<?php
			 }
		}
	}
}
