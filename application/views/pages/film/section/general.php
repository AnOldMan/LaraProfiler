<?php
if( empty( $film ) ) return '';
foreach( array(
	'audio',
	'subtitle',
	'credit',
	'disc',
	'detail',
	'feature',
	'format',
	'genre',
	'manufacturer',
	'rating',
	'role',
	'studio'
	) as $k ) if( empty( $film[$k] ) ) $film[$k] = array();

foreach( array(
	'title',
	'prodyear',
	'origin',
	'released',
	'casetype',
	'srpid',
	'srp',
	'purchcurrencyid',
	'purchprice',
	'purchdate',
	'locality',
	'media_custom',
	'disttrait',
	'upc',
	'collectionnumber',
	'collectiontype'
	) as $k ) if( empty( $film['detail'][$k] ) ) $film['detail'][$k] = '-';

$runhour = '';
if( $runmin = empty( $film['detail']['runningtime'] ) ? 0 : $film['detail']['runningtime'] )
{
	$hour = floor( $runmin / 60 );
	$runhour = $hour . ':' . ( $runmin - ( $hour * 60 ) );
}
?>
<label>General Info</label>
<h2 class="film-title">
	<span class="film-title-label">Title: </span>
	<span class="film-title-text"><?= $film['detail']['title'] ?></span>
<?php if( ! empty( $film['detail']['originaltitle'] ) ) : ?>
	<span class="film-title-label">Original Title: </span>
	<span class="film-title-text"><?= $film['detail']['originaltitle'] ?></span>
<?php endif; ?>
</h2>
<dl>
	<dt>Produced:</dt>
	<dd><?= $film['detail']['prodyear'] ?> in <?= $film['detail']['origin'] ?></dd>
	<dt>Studios:</dt>
	<dd></dd>
	<dt>Media Companies:</dt>
	<dd></dd>
	<dt>Runtime</dt>
	<dd><?= $runhour ?> (<?= $runmin ?> min)</dd>
	<dt>Case Type</dt>
	<dd><?= $film['detail']['casetype'] ?></dd>
	<dt>Released</dt>
	<dd><?= $film['detail']['released'] ?></dd>
	<dt>Purchased</dt>
	<dd><?= $film['detail']['purchdate'] ?></dd>
	<dt>SRP</dt>
	<dd><?= $film['detail']['srp'] ?> <?= $film['detail']['srpid'] ?></dd>
	<dt>Price Paid</dt>
	<dd><?= $film['detail']['purchprice'] ?> <?= $film['detail']['purchcurrencyid'] ?></dd>
	<dt>locality</dt>
	<dd><?= $film['detail']['locality'] ?></dd>
	<dt>media_custom</dt>
	<dd><?= $film['detail']['media_custom'] ?></dd>
	<dt>disttrait</dt>
	<dd><?= $film['detail']['disttrait'] ?></dd>
	<dt>upc</dt>
	<dd><?= $film['detail']['upc'] ?></dd>
	<dt>collectionnumber</dt>
	<dd><?= $film['detail']['collectionnumber'] ?></dd>
	<dt>collectiontype</dt>
	<dd><?= $film['detail']['collectiontype'] ?></dd>
</dl>