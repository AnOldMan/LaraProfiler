<html>
<head>
	<!--<title>DVD Control</title>-->
	<style>
		body{ background-color:#000000; }
		.greyback{ background-color:#999999; }
		.blueback{ background-color:#000099; }
		.outset{ border-style:solid; border-left-color:#FFFFFF; border-left-width:1px; border-top-color:#FFFFFF; border-top-width:1px; border-right-color:#333333; border-right-width:3px; border-bottom-color:#333333; border-bottom-width:3px; }
		.inset{ border-style:solid; border-left-color:#333333; border-left-width:3px; border-top-color:#333333; border-top-width:3px; border-right-color:#FFFFFF; border-right-width:1px; border-bottom-color:#FFFFFF; border-bottom-width:1px; }
		.redback{ background-color:#CC0000; }
		.greenback{ background-color:#33CC00; }
		.yellowback{ background-color:#FFFF00; }
		.button{ font-family:Arial; font-size:24px; text-decoration:none; color:#FFFFFF; font-weight:bold; }
		.button2{ font-family:Arial; font-size:32px; text-decoration:none; color:#000000; font-weight:bold; }
		.button3{ font-family:Arial; font-size:16px; text-decoration:none; color:#000000; font-weight:bold; }
		.button4{ font-family:Arial; font-size:32px; text-decoration:none; color:#000000; font-weight:bold; }
		.timer{ height: 96px; width: 96px; display:none; background-image:url(timer.gif); background-repeat:no-repeat; }
		.response{ font-size:18px; color:#000099; font-weight:bold; }
		.container{ font-family:Arial; font-size:32px; color:#000000; font-weight:bold; }
	</style>
	<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
	<script type="text/javascript">
		<!--


		function countdown( seconds, stringObject )
		{ 
			if ( seconds <= 1 )
			{ 
				seconds = 0;
				document.getElementById("myTimer").style.display = 'none';
				sendCommand( 'macro', stringObject );
			} 
			else
			{
				seconds -= 1;
				document.getElementById("counter").innerHTML = seconds;
				setTimeout( "countdown( "+seconds+", '"+stringObject+"' )",1000 );
			}
		} 
	</script>
</head>
<?php if( empty( $sHTML ) ) echo "<body>"; else echo "<body onload=\"startmacro('$sHTML');\">"; ?>
<div style="padding-left:32px;">
<table>
<!--
	<tr>
		<td align="center" colspan="9" class="button">DVD REMOTE</td>
	</tr>
	-->
	<tr>
		<td class="greenback button outset" onclick="sendCommand('poweron');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			ON
		</td>
		<td class="redback button outset" onclick="sendCommand('poweroff');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			OFF
		</td>
		<td width="64" height="64">&nbsp;</td>
		<td width="32" rowspan="7">&nbsp;</td>
		<td class="greyback button3 outset" onclick="sendCommand('display');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			Display
		</td>
		<td class="blueback button4 outset" onclick="sendCommand('up');"
			width="64" height="64" align="center" valign="center" title="Up"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			&uarr;
		</td>
		<td class="greyback button3 outset" onclick="sendCommand('menu');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			Menu
		</td>
		<td width="32" rowspan="5">&nbsp;</td>
		<td class="blueback button3 outset" onclick="sendCommand('easyplay');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			Easy<br/>Play
		</td>
	</tr>
	<tr>
		<td class="greyback button2 outset" onclick="sendCommand('number1');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			1
		</td>
		<td class="greyback button2 outset" onclick="sendCommand('number2');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			2
		</td>
		<td class="greyback button2 outset" onclick="sendCommand('number3');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			3
		</td>
		<td class="blueback button4 outset" onclick="sendCommand('left');"
			width="64" height="64" align="center" valign="center" title="Left"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			&larr;
		</td>
		<td class="greyback button3 outset" onclick="sendCommand('enter');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			Enter
		</td>
		<td class="blueback button4 outset" onclick="sendCommand('right');"
			width="64" height="64" align="center" valign="center" title="Right"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			&rarr;
		</td>
		<td class="blueback button3 outset" onclick="sendCommand('open');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			Open<br/>Close
		</td>
	</tr>
	<tr>
		<td class="greyback button2 outset" onclick="sendCommand('number4');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			4
		</td>
		<td class="greyback button2 outset" onclick="sendCommand('number5');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			5
		</td>
		<td class="greyback button2 outset" onclick="sendCommand('number6');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			6
		</td>
		<td class="greyback button3 outset" onclick="sendCommand('folder');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			Folder
		</td>
		<td class="blueback button4 outset" onclick="sendCommand('down');"
			width="64" height="64" align="center" valign="center" title="Down"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			&darr;
		</td>
		<td class="greyback button3 outset" onclick="sendCommand('clear');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			Clear
		</td>
		<td class="blueback button3 outset" onclick="sendCommand('flip');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			Flip
		</td>
	</tr>
	<tr>
		<td class="greyback button2 outset" onclick="sendCommand('number7');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			7
		</td>
		<td class="greyback button2 outset" onclick="sendCommand('number8');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			8
		</td>
		<td class="greyback button2 outset" onclick="sendCommand('number9');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			9
		</td>
		<td class="greyback button4 outset" onclick="sendCommand('pause');"
			width="64" height="64" align="center" valign="center" title="Pause"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			||
		</td>
		<td class="redback button3 outset" onclick="sendCommand('stop');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			STOP
		</td>
		<td class="greyback button3 outset" onclick="sendCommand('subtitle');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			Sub<br/>Title
		</td>
		<td width="64" height="64">&nbsp;</td>
	</tr>
	<tr>
		<td width="64" height="64">&nbsp;</td>
		<td class="greyback button2 outset" onclick="sendCommand('number0');"
			width="64" height="64" align="center" valign="center"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			0
		</td>
		<td width="64" height="64">&nbsp;</td>
		<td class="blueback button4 outset" onclick="sendCommand('rewind');"
			width="64" height="64" align="center" valign="center" title="Rewind"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			&lt;&lt;
		</td>
		<td class="greenback button4 outset" onclick="sendCommand('play');"
			width="64" height="64" align="center" valign="center" title="Play"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			&gt;
		</td>
		<td class="blueback button4 outset" onclick="sendCommand('fforward');"
			width="64" height="64" align="center" valign="center" title="FastForward"
			onmouseover="$(this).addClass('inset'); this.style.cursor='pointer'"
			onmouseout="$(this).removeClass('inset');" >
			&gt;&gt;
		</td>
		<td></td>
	</tr>
	<tr>
		<td colspan="3" width="64" height="32">&nbsp;</td>
		<td class="response" id="ajax" colspan="3" rowspan="2" height="64" align="center" valign="center">
			<?php if(!empty($sResponse)) print $sResponse; ?>
		</td>
		<td colspan="2" rowspan="2" align="center" width="96" height="96">
			<div class="timer" id="myTimer">
				<div style="height:30px"></div>
				<div class="container" id="counter">&nbsp;</div>
			</div>
		</td>
	</tr>
	<tr>
<?php

if( empty( $nId ) ) $nId = 1;
if( empty( $players ) ) $players = 3;
echo '		<td class="';
if( $nId == 1 ) echo "greenback inset"; else echo "redback outset";
echo " button3\" onclick=\"window.location.href='index.php?player=change&id=1';\"\n";
echo ' width="64" height="64" align="center" valign="center"';
if( $nId != 1 ) echo " onmouseover=\"$(this).addClass('inset'); this.style.cursor='pointer'\"
			onmouseout=\"$(this).removeClass('inset');\"";
else echo " style=\"background-color:#00FF00;\" onmouseover=\"this.style.cursor='default'\"";
echo ">
			Player<br/>1
		</td>";

if( $players > 1 )
{
	echo '		<td class="';
	if( $nId == 2 ) echo "greenback inset"; else echo "redback outset";
	echo " button3\" onclick=\"window.location.href='index.php?player=change&id=2';\"\n";
	echo ' width="64" height="64" align="center" valign="center"';
	if( $nId != 2 ) echo " onmouseover=\"$(this).addClass('inset'); this.style.cursor='pointer'\"
			onmouseout=\"$(this).removeClass('inset');\"";
	else echo " style=\"background-color:#00FF00;\" onmouseover=\"this.style.cursor='default'\"";
	echo ">
				Player<br/>2
			</td>";
}
else echo '		<td width="64" height="64">&nbsp;</td>';
if( $players > 2 )
{
	echo '		<td class="';
	if( $nId == 3 ) echo "greenback inset"; else echo "redback outset";
	echo " button3\" onclick=\"window.location.href='index.php?player=change&id=3';\"\n";
	echo ' width="64" height="64" align="center" valign="center"';
	if( $nId != 3 ) echo " onmouseover=\"$(this).addClass('inset'); this.style.cursor='pointer'\"
			onmouseout=\"$(this).removeClass('inset');\"";
	else echo " style=\"background-color:#00FF00;\" onmouseover=\"this.style.cursor='default'\"";
	echo ">
				Player<br/>3
			</td>";
}
else echo '		<td width="64" height="64">&nbsp;</td>';

echo "	</tr>\n</table>\n</div>\n</body>\n</html>\n";
