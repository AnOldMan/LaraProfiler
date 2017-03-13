<?php

if( empty( $data ) ) return '';

?>
<label><?= count( $data ) == 1 ? 'Disc' : 'Discs' ?>:</label>
<dl class="dd-disc">
<?php foreach( $data as $d ) :
		if( empty( $d['label'] ) || empty( $d['description'] ) ) continue;
		$location = array();
		if( ! empty( $d['location'] ) ) $location['Location'] = $d['location'];
		if( ! empty( $d['slot'] ) ) $location['Slot'] = $d['slot'];
		?>
	<dt><?= $d['label'] ?>:</dt>
	<dd>
		<?= HTML::icon( empty( $data['dual_layer'] ) ? 'unchecked' : 'checked', ( empty( $data['dual_layer'] ) ? '-' : '+' ) ) . 'Dual-layer' ?>

		<?= HTML::icon( empty( $data['dual_sided'] ) ? 'unchecked' : 'checked', ( empty( $data['dual_sided'] ) ? '-' : '+' ) ) . 'Dual-sided' ?>

		<p><?= $d['description'] ?></p>
	</dd>
<?php   if( ! empty( $d['disc_id'] ) ) : ?>
	<dt>Disc ID:</dt>
	<dd><?= $d['disc_id'] ?></dd>
<?php   endif;
		if( $location ) : ?>
	<dt><?= implode( '/', array_keys( $location ) ) ?>:</dt>
	<dd><?= implode( '/', $location ) ?></dd>
<?php   endif;
      endforeach; ?>
</dl>