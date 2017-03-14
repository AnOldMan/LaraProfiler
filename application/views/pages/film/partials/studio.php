<?php

if( empty( $data ) ) return '';
$list = array(); $i = 0;
foreach( $data as $k => $d )
{
	if( empty( $d['company_id'] ) ) continue;
	$k = isset( $d['sortorder'] ) ? $d['sortorder']: $i++;
	$list[$k] = Studio::link( $d['company_id'] );
}

if( empty( $list ) ) return '';
sort( $list );

if( empty( $nolabel ) ) : ?>
<label class="label-studio"><?= count( $list ) == 1 ? 'Studio' : 'Studios' ?>:</label>
<?php endif;

print HTML::ul( $list, array( 'html' => true, 'class' => 'list-unstyled film-studio' ) );
