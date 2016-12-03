<pre><?php

//ffmpeg -rtsp_transport tcp -y -i rtsp://192.168.1.248:9000/ch0_0.h264 -r 10 -f image2 /var/www/camera.jpg
var_dump( DVRconnect() );


function DVRconnect( $channel = 1, $host = '192.168.1.248', $port = 7050, $userName = 'Admin', $password = '111111' )
{
	$fSocket = fsockopen( $host, $port ) or die( "Cannot connect to: $host<br/>" );
	stream_set_timeout( $fSocket, 2 );

	$command = '00000001000000030a00000000000000000000680000000100000054000000';
	$command .= str_pad( $channel, 2, '0', STR_PAD_LEFT );
	$command = str_pad( $command, (40*2), '0' );

	$t = '';
	foreach( str_split( $userName ) as $char ) $t .= dechex( ord( $char ) );
	print $t . "\n";
	$command = str_pad( $command . $t, (72*2), '0' );

	$t = '';
	foreach( str_split( $password ) as $char ) $t .= dechex( ord( $char ) );
	print $t . "\n";
	$command = str_pad( $command . $t, (104*2), '0' );

	$command = str_pad( $command, 500, '0' );

	hexprint( $command );

	fwrite( $fSocket, $command );

	$response = fread( $fSocket, 2000 );// 2000 is 2 seconds

	$sInfo = stream_get_meta_data( $fSocket );
	fclose( $fSocket );
	
	if ( $sInfo['timed_out'] ) $response = 'Connection timed out!';

	return $response;
}

function hexprint( $str )
{
	$a = str_split( $str, 2 );
	$c = 0;
	foreach( $a as $i => $v )
	{
		if( ! ( $i % 8 ) )
		{
			print "\n";
			print str_pad( $i, 4, '0', STR_PAD_LEFT );
		}
		print "\t";
		print $v;
	}
	print "\n";
}