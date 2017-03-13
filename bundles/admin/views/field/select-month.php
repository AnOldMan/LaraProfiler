<?php

$none = empty( $none ) ? ' <none> ' : $none;

$options = array( 0 => $none );
if( $months = Occurence::months_array(false) )
{
	foreach( $months as $r ) if( $r['active'] )
	{
		$k = strtolower( $r['month'] );
		$options[$k] = $r['month'] . ' ' . $r['year'];
	}
}

print render(
	'admin::field.select',
	array(
		'name'     => 'month',
		'options'  => $options,
		'value'    => empty( $value ) ? 0 : $value,
		'help'     => empty( $help ) ? '' : $help
	)
);
