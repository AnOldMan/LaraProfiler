<?php

if( empty( $data ) ) return '';

$list = array();
if( ! empty( $data['video'] ) ) $list[] = array( 'li' => 'Video: ' . $data['video'], 'class' => 'format-video' );
if( ! empty( $data['ratio'] ) ) $list[] = array( 'li' => 'Ratio: ' . $data['ratio'], 'class' => 'format-ratio' );
foreach( array(
		'panandscan' => 'Pan-and-Scan',
		'fullframe' => 'Full-frame',
		'widescreen' => 'Widescreen',
		'anamorph' => 'Anamorphic',
		'dualside' => 'Dual-sided',
		'duallayer' => 'Dual-layer'
	) as $k => $t ) $list[] = HTML::icon( empty( $data[$k] ) ? 'unchecked' : 'checked', ( empty( $data[$k] ) ? '-' : '+' ) ) . $t;

if( empty( $list ) ) return '';

if( empty( $nolabel ) ) : ?>
<label class="label-format">Format:</label>
<?php endif;

print HTML::ul( $list, array( 'html' => true, 'class' => 'list-unstyled film-format' ) );
