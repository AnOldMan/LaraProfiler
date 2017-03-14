<?php

if( empty( $data ) ) return '';
$list = array();
foreach( $data as $d )
{
	if( empty( $d['label'] ) || empty( $d['description'] ) ) continue;
	$description = array(
		HTML::icon( empty( $data['dual_layer'] ) ? 'unchecked' : 'checked', ( empty( $data['dual_layer'] ) ? '-' : '+' ) ) . 'Dual-layer',
		HTML::icon( empty( $data['dual_sided'] ) ? 'unchecked' : 'checked', ( empty( $data['dual_sided'] ) ? '-' : '+' ) ) . 'Dual-sided',
		'<p>' . $d['description'] . '</p>'
	);
	$list[] = array(
		'dt' => $d['label'],
		'dd' => implode( "\n", $description ),
		'class' => 'disc'
	);
	$location = array();
	if( ! empty( $d['location'] ) ) $location['Location'] = $d['location'];
	if( ! empty( $d['slot'] ) ) $location['Slot'] = $d['slot'];
	if( $location ) $list[] = array(
		'dt' => implode( '/', array_keys( $location ) ),
		'dd' => implode( '/', $location ),
		'class' => 'location'
	);

}
if( empty( $list ) ) return '';

if( empty( $nolabel ) ) : ?>
<label class="label-disc"><?= count( $data ) == 1 ? 'Disc' : 'Discs' ?>:</label>
<?php endif;

print HTML::dl( $list, array( 'html' => true, 'class' => 'dl-disc' ) );
