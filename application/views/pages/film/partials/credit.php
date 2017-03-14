<?php

if( empty( $data ) ) return '';
$credits = $list = array(); $i = 0;
foreach( $data as $i => $d )
{
	if( empty( $d['person_id'] ) ) continue;
	if ( $link = Credit::link( $d['person_id'] ) )
	{
		$t = empty( $d['type_phrase'] ) ? '' : $d['type_phrase'] . ':';
		$k = isset( $d['sortorder'] ) ? $d['sortorder']: $i++;

		$a = array();
		if ( ! empty( $d['sub_type_phrase'] ) ) $a[] = $d['sub_type_phrase'] . ':';
		$a[] = $link;
		if( ! empty( $d['creditedas'] ) ) $a[] = '(Credited As: ' . $d['creditedas'] . ')';

		$credits[$t][$k] = implode( ' ', $a );
	}
}
if( ! empty( $credits ) ) foreach( $credits as $t => $d )
{
	if( empty( $d ) ) continue;
	sort( $d );
	$list[] = array(
		'dt' => $t,
		'dd' => HTML::ul( $d, array( 'html' => true, 'class' => 'list-unstyled ul-credit' ) )
	);
}
if( empty( $list ) ) return '';

if( empty( $nolabel ) ) : ?>
<label class="label-credit"><?= count( $data ) == 1 ? 'Credit' : 'Credits' ?>:</label>
<?php endif;

print HTML::dl( $list, array( 'html' => true, 'class' => 'dl-credit' ) );
