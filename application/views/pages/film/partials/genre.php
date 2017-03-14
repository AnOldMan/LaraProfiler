<?php

if( empty( $data ) ) return '';

$list = array();
foreach( $data as $k => $d )
{
	$d = empty( $d['phrase'] ) ? array() : $d['phrase'];
	if( ! empty( $d['stub'] ) && ! empty( $d['phrase'] ) ) $list[] = Genre::format_link( $d['stub'], $d['phrase'] );
}	

if( empty( $list ) ) return '';

if( empty( $nolabel ) ) : ?>
<label class="label-genre"><?= count( $data ) == 1 ? 'Genre' : 'Genres' ?>:</label>
<?php endif;

print HTML::ul( $list, array( 'html' => true, 'class' => 'list-unstyled film-genre' ) );
