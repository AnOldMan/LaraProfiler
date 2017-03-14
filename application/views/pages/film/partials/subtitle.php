<?php

if( empty( $data ) ) return '';
$list = array();
foreach( $data as $i => $d ) if( ! empty( $d['phrase'] ) ) $list[] = $d['phrase'];

if( empty( $list ) ) return;

if( empty( $nolabel ) ) : ?>
<label class="label-subtitle"><?= count( $data ) == 1 ? 'SubTitle' : 'SubTitles' ?>:</label>
<?php endif;

print HTML::ul( $list, array( 'html' => true, 'class' => 'list-unstyled film-subtitle' ) );
