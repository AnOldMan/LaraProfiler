<?php

if( empty( $data ) ) return '';

$list = array();
foreach( array(
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
) as $k => $t ) $list[] = HTML::icon( empty( $data[$k] ) ? 'unchecked' : 'checked', ( empty( $data[$k] ) ? '-' : '+' ) ) . $t;

if( ! empty( $data['other'] ) )
{
	$e = explode( "\n", $data['other'] );
	foreach( $e as $t )
	{
		$t = trim( $t );
		if( $t ) $list[] = HTML::icon( 'checked', '+' ) . $t;
	}
}

if( empty( $list ) ) return '';

if( empty( $nolabel ) ) : ?>
<label class="label-feature">Features:</label>
<?php endif;

print HTML::ul( $list, array( 'html' => true, 'class' => 'list-unstyled film-feature' ) );
