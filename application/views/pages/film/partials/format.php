<?php

if( empty( $data ) ) return '';

?>
                <label>Format:</label>
                <ul class="list-unstyled">
<?php if( ! empty( $data['video'] ) ) : ?>
                    <li class="format-video">Video: <?= $data['video'] ?></li>
<?php endif;
if( ! empty( $data['ratio'] ) ) : ?>
                    <li class="format-ratio">Ratio: <?= $data['ratio'] ?></li>
<?php endif;
foreach( array(
        'panandscan' => 'Pan-and-Scan',
        'fullframe' => 'Full-frame',
        'widescreen' => 'Widescreen',
        'anamorph' => 'Anamorphic',
        'dualside' => 'Dual-sided',
        'duallayer' => 'Dual-layer'
    ) as $k => $t ) : ?>
                    <li><?= HTML::icon( empty( $data[$k] ) ? 'unchecked' : 'checked', ( empty( $data[$k] ) ? '-' : '+' ) ) . $t ?></li>
<?php endforeach; ?>
                </ul>
