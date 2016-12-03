<?php

class Player
{
    public $host = '192.168.1.70';
    public $port = 4998;
    public $module = 2;
    public $zeros = false;
    public $players = 3;
    public $discs = 300;
    public $ccf = array();

    public $irPort = 1;

    private $hertz = 0;
    private $sequence = array();
    private $uid = '';
    private $sendir = array();

    function __construct( $port = 0 )
    {
        $config = Config::get( 'player', array() );
        foreach( $config as $k => $v )
        {
            if( property_exists( $this, $k ) ) $this->$k = $v;
        }
        if( $port ) $this->irPort = $port;
    }

    public static function sendCommand( $cmd, $port )
    {
        // todo: verify NEC command
        $player = new Player( $port );
        return $player->verifySonyCommand( $cmd );
    }

    public function createMacro( $nId )
    {
        // $nId at this point should be disc number, not player id
        $nId = (int)$nId;
        if( ! $nId || $nId > ($this->discs * $this->players)) return array( 'msg' => 'Disc # out of range' );
        if( $nId > ( $this->discs * 2 ) ) // player # is three
             { $nDisc = $nId - ( $this->discs * 2 ); $this->irPort = 3; }
        elseif( $nId > $this->discs ) // player # is two
             { $nDisc = $nId - $this->discs; $this->irPort = 2; }
        else { $nDisc = $nId; $this->irPort = 1; } // player # is one
        // add zero keypresses to front
        $sDisc = $this->zeros ? str_pad( (string)$nDisc, 3, '0', STR_PAD_LEFT) : (string)$nDisc;

        /*	SONY MACRO:	
            PowerOn 	Pause:60
            Folder		Pause:15
            NUMBER:1	Pause:1
            NUMBER:2	Pause:1
            NUMBER:3	Pause:1
            Enter		Pause:4
            Enter
        */
        // build macro inside-out
        $aMacro = $this->addCommand('enter');
        $aMacro = $this->addCommand('enter', 4, $aMacro);
        foreach( array_reverse( str_split( $sDisc ) ) as $a ) $aMacro = $this->addCommand('number' . $a, 2, $aMacro);
        $aMacro = $this->addCommand('folder', 15, $aMacro);

        return array(
            'msg' => array(
                'PowerOn',
                $this->verifySonyCommand( 'poweron' )
            ),
            'sec' => 60,
            'nxt' => $aMacro
        );
    }
    
    private function verifySonyCommand( $cmd )
    {
        $sCommand = empty( $this->ccf[$cmd] ) ? '' : $this->ccf[$cmd];
        if( ! $sCommand ) return 'Command not found';
        // CHECK CCF CODE FOR ERRORS
        $aSplit = str_split( $sCommand );
        foreach( $aSplit as $k => $a ) if ( !preg_match( "/[A-Fa-f0-9 ]/", $a ) ) return 'Invalid CCF: non hexadecimal';
        unset( $aSplit );
        if( substr( $sCommand, 0, 4 ) != '0000' ) return 'Invalid CCF: RAW starts with 0000';        
        // break command string into pieces
        $aExplode = explode( ' ', $sCommand );
        $nCount = count( $aExplode );
        if( $nCount < 8 ) return 'Invalid CCF: less than 8 elements OR missing spaces';
        if( $nCount > 260 ) return 'Invalid CCF: more than 260 elements';
        // check count indicators
        $sPairs1 = 2*( hexdec( $aExplode[2] ) );
        $sPairs2 = 2*( hexdec( $aExplode[3] ) );
        if( $aExplode[2] == '0000' ) { if( $sPairs2 != $nCount - 4 ) return 'Invalid CCF: bad pair count: single'; }
        elseif( $aExplode[3] == '0000' ) { if( $sPairs1 != $nCount - 4 ) return 'Invalid CCF: bad pair count: repeating'; }
        elseif( $sPairs1 + $sPairs2 != $nCount - 4 ) return 'Invalid CCF: bad pair count: mulitple';
        // get frequency
        $nHertz = (int)( ( 41450 / hexdec( $aExplode[1] ) + 5 ) / 10 ) * 1000;
        if( $nHertz < 20000 || $nHertz > 500000 ) return 'Invalid CCF: frequency out of range';
        // PASSED ERROR CHECK
        $this->hertz = $nHertz;
        $this->sequence = array_slice( $aExplode, 4 );// discard first four
        return $this->sendIR();
    }

    private function sendIR()
    {
        if( !$this->hertz || !$this->sequence ) return 'Invalid CCF';
        $this->uid = rand( 1000, 9999 ); // unique id to check response
        // sendir,<mod-addr>:<conn-addr>,<uniqueID>,<frequency>,<repeatcount>,<offset>,<command_string>
        $this->sendir = array(
            'sendir',
            $this->module . ':' . $this->irPort,// <mod-addr>:<conn-addr>
            $this->uid,// <uniqueID>
            $this->hertz,// <frequency>
            '3',// <repeatcount>
            '1'// <offset>
        );
        foreach ( $this->sequence as $k => $a ) $this->aSend[] = hexdec( $a );// convert to hex
        $this->sendir[] = "\r";// add return to end of string

        if( empty( $GLOBALS['environment'] ) || $GLOBALS['environment'] == 'local' ) return 'Command Processed';
        $handle = @fsockopen( $this->host, $this->port );
        if( $handle === false ) return 'Socket Failed';
        stream_set_timeout( $handle, 2 ); // timeout = 2 seconds
        fwrite( $handle, implode( ',', $this->sendir ) ); // yep, PHP treats this like a file read/write !
        while( $c < 100 && ! feof( $handle ) && ! stristr( $sResponse, $this->uid ) )
        {
            $sResponse .= fread( $handle, 32 );
            $c++;
        }
        // above WILL FAIL if repeating command lasts more than 2 seconds...

        $sInfo = stream_get_meta_data( $handle );
        fclose( $handle );

        if( stristr( $sResponse, 'completeir' ) ) return 'Command Processed'; // makes response pretty

        if( ! empty( $sInfo['timed_out'] ) ) $sResponse .= '<br/>Connection timed out!<br/>';
        // if this comes back too much, try increasing timeout period
        // or set something up to send new command within timeout period
        // (that stops gc100's current queue and resets it

        return $sResponse;
    }

    private function addCommand($cmd, $sec = 0, $nxt = false)
    {
        $step = array(
            'cmd' => $cmd
        );
        if( $sec ) $step['sec'] = $sec;
        if( $nxt ) $step['nxt'] = $nxt;
        return $step;
    }
}