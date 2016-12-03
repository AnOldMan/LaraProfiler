<?php

if( empty( $data ) ) return '';
foreach( $data as $k => $d ) if( empty( $d['phrase'] ) ) unset( $data[$k] );
if( empty( $data ) ) return '';

?>
                <label><?= count( $data ) == 1 ? 'Genre' : 'Genres' ?>:</label>
                <ul class="list-unstyled">
<?php foreach( $data as $d ) : ?>
                    <li><?= Genre::format_link( $d['phrase']['stub'], $d['phrase']['phrase'] ) ?></li>
<?php endforeach; ?>
                </ul>