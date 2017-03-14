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
	) as $k ) if( empty( $film['detail'][$k] ) ) $film['detail'][$k] = '';

$runhour = '';
if( $runmin = empty( $film['detail']['runningtime'] ) ? 0 : $film['detail']['runningtime'] )
{
	$hour = floor( $runmin / 60 );
	$runhour = $hour . ':' . ( $runmin - ( $hour * 60 ) );
}
if( $film['detail']['prodyear'] ) $film['detail']['prodyear'] = $film['detail']['prodyear'] . ' ';
if( $film['detail']['origin'] ) $film['detail']['prodyear'] = $film['detail']['prodyear'] . 'in ' . $film['detail']['origin'];

?>
<label class="label-general">General Info</label>
<div class="film-general">
	<h2 class="film-title">
		<span class="film-title-label">Title: </span>
		<span class="film-title-text"><?= $film['detail']['title'] ?></span>
<?php if( ! empty( $film['detail']['originaltitle'] ) ) : ?>
		<span class="film-title-label">Original Title: </span>
		<span class="film-title-text"><?= $film['detail']['originaltitle'] ?></span>
<?php endif; ?>
	</h2>
<?php

$list = array(
	array(
		'dt' => count( $film['genre'] ) == 1 ? 'Genre' : 'Genres',
		'dd' => View::make( 'pages.film.partials.genre' )
			->with( 'data', $film['genre'] )
			->with( 'nolabel', true )
			->render(),
		'class' => 'genre'
	),
	array(
		'dt' => 'Produced',
		'dd' => $film['detail']['prodyear'],
		'class' => 'produced'
	),
	array(
		'dt' => count( $film['studio'] ) == 1 ? 'Studio' : 'Studios',
		'dd' => View::make( 'pages.film.partials.studio' )
			->with( 'data', $film['studio'] )
			->with( 'nolabel', true )
			->render(),
		'class' => 'studio'
	),
	array(
		'dt' => count( $film['manufacturer'] ) == 1 ? 'Company' : 'Companies',
		'dd' => View::make( 'pages.film.partials.manufacturer' )
			->with( 'data', $film['manufacturer'] )
			->with( 'nolabel', true )
			->render(),
		'class' => 'manufacturer'
	),
	array(
		'dt' => 'Runtime',
		'dd' => $runhour . ' (' . $runmin .' min)',
		'class' => 'runtime'
	),
	array(
		'dt' => 'Case Type',
		'dd' => $film['detail']['casetype'],
		'class' => 'casetype'
	),
	array(
		'dt' => 'Released',
		'dd' => $film['detail']['released'],
		'class' => 'released'
	),
	array(
		'dt' => 'Purchased',
		'dd' => $film['detail']['purchdate'],
		'class' => 'purchdate'
	),
	array(
		'dt' => 'SRP',
		'dd' => $film['detail']['srp'] . ' ' . $film['detail']['srpid'],
		'class' => 'srp'
	),
	array(
		'dt' => 'Price Paid',
		'dd' => $film['detail']['purchprice']. ' ' . $film['detail']['purchcurrencyid'],
		'class' => 'purchprice'
	)
);

print HTML::indent( HTML::dl( $list, array( 'html' => true, 'class' => 'dl-general' ) ), 1 );

$list = array();
foreach( array( 'upc', 'locality', 'media_custom', 'disttrait' ) as $k ) if( ! empty( $film['detail'][$k] ) ) $list[] = $film['detail'][$k];
if( ! empty( $film['detail']['collectionnumber'] ) ) $list[] = '#' . $film['detail']['collectionnumber'];

print HTML::indent( HTML::ul( $list, array( 'html' => true, 'class' => 'list-unstyled ul-general' ) ), 1 );

?>
</div>