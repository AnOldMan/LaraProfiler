<?php

if( empty( $data ) ) return '';

$formats = array(
	'dolby-digital-mono' => 10,
	'dolby-digital-stereo' => 20,
	'dolby-digital-surround' => 51,
	'dolby-digital-surround-ex' => 61,
	'dolby-digital plus' => 71,
	'dolby-digital-hd' => 71,
	'dts' => 51,
	'dts-es-discrete' => 61,
	'dts-es-matrixed' => 61,
	'dts-hd-hr' => 71,
	'dts-hd-master-audio' => 71,
	'pcm-stereo' => 20,
	'ac3' => 61
);

$list = array();
foreach( $data as $k => $d )
{
	$channels = '';
	if( empty( $d['content'] ) || empty( $d['format'] ) ) continue;
	else if( ! empty( $formats[$d['format']['stub']] ) )
	{
		$channels = HTML::icon( 'channels-' . $formats[$d['format']['stub']] / 10 );
	}
	else {
		$e = explode( '-', $d['format']['stub'] );
		$e = array_pop( $e );
		if( is_numeric( $e ) ) $channels = HTML::icon( 'channels-' .(int)$e / 10 );;
	}
	$list[] = array(
		'dt' => $d['content']['phrase'],
		'dd' => HTML::icon( $d['format']['stub'], $d['format']['phrase'] ). $channels,
		'class' => $d['format']['stub']
	);
}

if( empty( $list ) ) return;

if( empty( $nolabel ) ) : ?>
<label class="label-audio">Audio <?= count( $data ) == 1 ? 'Channel' : 'Channels' ?>:</label>
<?php endif;

print HTML::dl( $list, array( 'html' => true, 'class' => 'dl-audio' ) );
