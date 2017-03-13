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

foreach( $data as $k => $d )
{
	if( empty( $d['content'] ) || empty( $d['format'] ) ) unset( $data[$k] );
	else if( ! empty( $formats[$d['format']['stub']] ) )
	{
		$data[$k]['format']['channels'] = $formats[$d['format']['stub']];
	}
	else {
		$e = explode( '-', $d['format']['stub'] );
		$e = array_pop( $e );
		if( is_numeric( $e ) ) $data[$k]['format']['channels'] = (int)$e;
	}
}

if( empty( $data ) ) return;

?>
<label>Audio <?= count( $data ) == 1 ? 'Channel' : 'Channels' ?>:</label>
<dl class="dd-audio">
<?php foreach( $data as $d ) : ?>
	<dt><?= $d['content']['phrase'] ?>:</dt>
	<dd><?php
		print HTML::icon( $d['format']['stub'], $d['format']['phrase'] );
		if( ! empty( $d['format']['channels'] ) ) print HTML::icon( 'channels-' . $d['format']['channels'], $d['format']['channels'] / 10 );
		?></dd>
<?php endforeach; ?>
</dl>