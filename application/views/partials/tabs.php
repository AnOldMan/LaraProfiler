<?php

if( empty( $tabs ) ) return '';

/*
array(
	'_key' => array(
		'content' => string (tab content text),
		'control' => string (tab control text),
		'active' => bool (tab is active)
	),
	...
)
*/

$bottom = isset ( $bottom ) ? $bottom : false;

$active = false;
foreach( $tabs as $k => $d )
{
	if( ! isset( $d['content'] ) || empty( $d['control'] ) ) unset( $tabs[$k] );
	else
	{
		$tabs[$k]['id'] = 'tabContent_' . (string)$k;
		$tabs[$k]['contentclass'] = array( 'tab-content' );
		$tabs[$k]['linkclass'] = array( 'tab-control' );
		if( ! $active && ! empty( $d['active'] ) )
		{
			$active = true;
			$tabs[$k]['contentclass'][] = 'active';
			$tabs[$k]['linkclass'][] = 'active';
		}
		$tabs[$k]['link'] = '<a class="tab-link" href="#' . $tabs[$k]['id'] . '">' . (string)$tabs[$k]['control'] . '</a>';
	}
}
if( empty( $tabs ) ) return '';

if( ! $active )
{
	foreach( $tabs as $k => $d )
	{
		$tabs[$k]['contentclass'][] = 'active';
		$tabs[$k]['linkclass'][] = 'active';
		break;
	}
}

$tabClass = array( 'tab-section', $bottom ? 'tab-bottom' : 'tab-top' );
if( ! empty( $context ) ) $tabClass[] = $context;

$count = count( $tabs ) > 1;

?>
<div class="<?= implode( ' ', $tabClass ) ?>">
<?php if( ! empty ( $banner ) ) : ?>
	<div class="tab-banner">
<?= htmlawed::indent( (string)$banner, 2 ) ?>

	</div>
<?php endif;
	 if( $count && ! $bottom  ) : ?>
	<ul class="tab-controls">
<?php   foreach( $tabs as $d ) : ?>
		<li class="<?= implode( ' ', $d['linkclass'] ) ?>"><?= $d['link'] ?></li>
<?php   endforeach; ?>
	</ul>
<?php endif; ?>
	<div class="tab-display">
<?php foreach( $tabs as $d ) : ?>
		<div id="<?= $d['id'] ?>" class="<?= implode( ' ', $d['contentclass'] ) ?>">
<?= htmlawed::indent( (string)$d['content'], 3 ) ?>

		</div>
<?php endforeach; ?>
	</div>
<?php if( $count && $bottom  ) : ?>
	<ul class="tab-controls">
<?php   foreach( $tabs as $d ) : ?>
		<li class="<?= implode( ' ', $d['linkclass'] ) ?>"><?= $d['link'] ?></li>
<?php   endforeach; ?>
	</ul>
<?php endif; ?>
</div>