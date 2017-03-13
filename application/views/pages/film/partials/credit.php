<?php

if( empty( $data ) ) return '';
$credits = array();
foreach( $data as $k => $d )
{
	if( empty( $d['person'] ) ) continue;
	$d['sub_type_phrase'] = empty( $d['sub_type_phrase'] ) ? '' : $d['sub_type_phrase'] . ': ';
	$credits[$d['type_phrase']][] = $d['sub_type_phrase'] . Person::format_link( $d['person']['stub'], $d['person']['fullname'], $d['person']['birthyear'] );
}
if( empty( $credits ) ) return '';

?>
<label>Credits:</label>
<dl class="dd-credit">
<?php foreach( $credits as $t => $d ) : ?>
	<dt><?= $t ?>:</dt>
	<dd><?= implode( '<br/>', $d ) ?></dd>
<?php endforeach; ?>
</dl>