<?php

if( empty( $data ) ) return '';
$list = array(); $i = 0;
foreach( $data as $d )
{
	if( empty( $d['person_id'] ) ) continue;
	if ( $link = Role::link( $d['person_id'] ) )
	{
		$k = isset( $d['sortorder'] ) ? $d['sortorder']: $i++;
		$a = array( $link );
		if( ! empty( $d['creditedas'] ) ) $a[] = 'Credited As: ' . $d['creditedas'];
		if( $voice = empty( $d['voice'] ) ? '' : Role::link( $d['voice'] ) ) $a[]  = 'Voice: ' . $voice;
		$d['uncredited'] = empty( $d['uncredited'] ) ? '' : Role::link( $d['uncredited'] );
		if( ! empty( $d['uncredited'] ) ) $a[]  = 'Not Credited: ' . $d['uncredited'];

		$list[$k] = array(
			'dt' =>  empty( $d['role'] ) ? '' : $d['role'],
			'dd' => '<span>' . implode( '</span> <span>', $a ) . '</span>'
		);
	}
}

if( empty( $list ) ) return '';
sort( $list );

if( empty( $nolabel ) ) : ?>
<label class="label-role"><?= count( $list ) == 1 ? 'Role' : 'Roles' ?>:</label>
<?php endif;

print HTML::dl( $list, array( 'html' => true, 'class' => 'dl-role' ) );
