<?php

Route::get( 'remote', array(
	'as' => 'remote',
	function()
	{
        Config::set( 'application.profiler', false );
        Config::set( 'database.profile', false );

		$irport = 1;
		$macro = false;
		if( $id = (int)Input::get('getdisc',0) )
		{
			$player = new Player();
			$macro = $player->createMacro( $id );
			$irport = $player->irPort;
		}
		return View::make('remote.index')
			->with('irport', $irport)
			->with('macro', $macro);
	}
));

Route::post( 'remote/macro', array(
	'as' => 'macro',
	function()
	{
        Config::set( 'application.profiler', false );
        Config::set( 'database.profile', false );

		$data = Input::all();
		if( ! empty( $data['cmd'] ) )
		{
			$data['msg'] = array(
				ucfirst( $data['cmd'] ),
				Player::sendCommand( $data['cmd'], $data['player'] )
			);
		}
		else $data['msg'] = 'No Command Sent';
		return Response::json($data);
	}
));

Route::get('remote/old', function()
{
	Config::set( 'application.profiler', false );
	Config::set( 'database.profile', false );

	return View::make('remote.old');
});
