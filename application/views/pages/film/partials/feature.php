<?php

if( empty( $data ) ) return '';

?>
<label>Features:</label>
<ul class="list-unstyled">
<?php foreach( array(
	'sceneaccess' => 'Scene Access',
	'comment' => 'Comments',
	'trailer' => 'Trailer',
	'bonustrailer' => 'Bonus Trailer',
	'gallery' => 'Gallery',
	'deleted' => 'Deleted Scenes',
	'makingof' => 'Making Of',
	'prodnotes' => 'Production Notes',
	'game' => 'Game',
	'dvdrom' => 'DVD',
	'multiangle' => 'Multi-angle',
	'musicvideos' => 'Music Video[s]',
	'interviews' => 'Interview[s]',
	'storyboard' => 'Storyboard',
	'outtakes' => 'Out-Takes',
	'closedcaptioned' => 'Closed Captioned',
	'thx' => 'THX',
	'pip' => 'PiP',
	'bdlive' => 'BD-Live',
	'digitalcopy' => 'Digital Copy'
) as $k => $t ) : ?>
	<li><?= HTML::icon( empty( $data[$k] ) ? 'unchecked' : 'checked', ( empty( $data[$k] ) ? '-' : '+' ) ) . $t ?></li>
<?php endforeach;
if( ! empty( $data['other'] ) ) :
	$e = explode( "\n", $data['other'] );
	foreach( $e as $t ) :
		$t = trim( $t );
		if( ! $t ) continue; ?>
	<li><?= HTML::icon( 'checked', '+' ) . $t  ?></li>
<?php
	endforeach;
endif; ?>
</ul>