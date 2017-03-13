<?php

if( empty( $items ) || ! is_array( $items ) ) return '';
if( empty( $columns ) || ! (int)$columns ) $columns = 1;
$columns = (int)$columns;

$c = count( $items );
$l = ceil( $c / $columns );
$split = array();

for( $i = 0; $i < $columns; $i++ ) $split[] = array_slice( $items, $i * $l, $l );

$class = empty( $class ) ? '' : $class
?>
													<div class="split-list <?= htmlawed::nominal( $columns ) ?>">
<?php
foreach( $split as $i => $ul ) : ?>
														<ul class="<?= $class . ( $i + 1 != $columns ? ' rightborder' : ' last' ) ?>">
<?php foreach( $ul as $li ) : ?>
															<?= $li ?>
<?php endforeach; ?>
														</ul>
<?php
endforeach;
?>
														<div class="clear"></div>
													</div>
