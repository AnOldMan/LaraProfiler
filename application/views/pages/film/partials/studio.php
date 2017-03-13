<?php

if( empty( $data ) ) return '';
$companies = array();
foreach( $data as $k => $d )
{
	if( ! empty( $d['company'] ) ) $companies[$d['company']['stub']] = $d['company']['name'];
}
if( empty( $companies ) ) return '';

?>
<label><?= count( $companies ) == 1 ? 'Studio' : 'Studios' ?>:</label>
<ul class="list-unstyled">
<?php foreach( $companies as $stub => $txt ) : ?>
	<li><?= Company::format_link( $stub, $txt ) ?></li>
<?php endforeach; ?>
</ul>