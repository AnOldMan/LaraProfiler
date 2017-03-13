<?php

if( empty( $data ) || empty( $data['company'] ) ) return '';

?>
<dl class="film-manufacturer">
	<dt>Manufacturer:</dt>
	<dd><?= Company::format_link( $data['company']['stub'], $data['company']['name'] ) ?></dd>
</dl>