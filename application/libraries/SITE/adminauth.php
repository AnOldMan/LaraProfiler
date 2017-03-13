<?php namespace MMG;

use Laravel\Auth\Drivers\Eloquent as ParentClass,
	\Hash,
	\Config,
	\DB,
	\DataEngine,
	\Session,
	\Variable,
	\URL,
	\View,
	\Bundle,
	\Message,
	\Profiler;

class Adminauth extends ParentClass
{
	public function attempt( $arguments = array() )
	{
		Profiler::tick('Adminauth::attempt');
		$arguments['username'] = empty( $arguments['username'] ) ? '' : trim( $arguments['username'] );
		$arguments['password'] = empty( $arguments['password'] ) ? '' : trim( $arguments['password'] );
		if( ! $arguments['username'] || ! $arguments['password'] ) return false;

		$attempts = Variable::get( 'failed_auth', array() );
		$k = $_SERVER['REMOTE_ADDR'];
		$c = empty( $attempts[$k] ) ? 0 : count( $attempts[$k] );

		if( $c > 9 )
		{
			Log::Adminauth( 'Excessive login attempts from ip ' . $k );
			if( $c == 25 ) $this->notify( $attempts[$k], $k );
			return $this->failed( $attempts, $arguments, 'excessive' );
		}

		if( ! $config = $this->get_config() )
		{
			Log::Adminauth( 'Invalid auth config' );
			return false;
		}

		// Use the username and password from the arguments to log in
		$user = $this->model()
			->where( $config['username'], '=', $arguments['username'] )
			->first();

		// double-check for listing user
		if( is_null( $user ) ) $user = $this->model()
			->where( $config['username'], '=', '-' . $arguments['username'] . '-' )
			->first();

		if( is_null( $user ) )
		{
			if( arg(0) == 'user' )
			{
				// attempt DE login / create
				if( $arguments['info'] = $this->de_login( $arguments ) )
				{
					return $this->de_create( $config, $arguments );
				}
			}
			return $this->failed( $attempts, $arguments, 'no user' );
		}

		// check credentials
		if( Hash::check( $arguments['password'], $user->{$config['password']} ) )
		{
			if( $user->{$config['type']} == $config['special']['listing']['type'] )
			{
				if( ! $this->de_login( $arguments ) )
				{
					$user->{$config['enabled']} = 0;
					$user->save();
				}
			}
			if( $user->{$config['reset']} )
			{
				$user->{$config['reset']} = '';
				$user->save();
			}
			if( ! $user->{$config['enabled']} )
			{
				return $this->failed( $attempts, $arguments, 'disabled' );
			}
			Profiler::tick('Adminauth::attempt');
			return $this->login( $user->id );
		}
		elseif( $user->{$config['type']} == $config['special']['listing']['type'] )
		{
			if( $this->de_login( $arguments ) )
			{
				$user->{$config['enabled']} = 1;
				$user->save();
				Profiler::tick('Adminauth::attempt');
				return $this->login( $user->id );
			}
		}

		// if a password reset request exists
		if( $user->{$config['reset']} )
		{
			// reset request only valid for 24 hrs
			if( ( time() - strtotime( $user->updated_at ) ) > ( 60*60*24 ) )
			{
				$user->{$config['reset']} = '';
				$user->save();

				Profiler::tick('Adminauth::attempt');
				return false;
			}

			// if valid, move reset password to normal password and blank reset
			if( Hash::check( $arguments['password'], $user->{$config['reset']} ) )
			{
				DB::table( $config['table'] )
					->where( 'id', '=', $user->id )
					->update( array(
						$config['password'] => $user->{$config['reset']},
						$config['reset'] => ''
					) );

				Profiler::tick('Adminauth::attempt');
				return $this->login( $user->id );
			}
		}

		return $this->failed( $attempts, $arguments, 'invalid password' );
	}

	private function notify( $attempts, $ip )
	{
		//  $email, $subject, $view, $from = ''
		$email = Config::get( 'email.error', '' );
		$to = $title = $cc = $bcc = '';
		if( is_array( $email ) )
		{
			if( ! empty( $email['email'] ) ) $to = $email['email'];
			if( ! empty( $email['title'] ) ) $title = $email['title'];
			if( ! empty( $email['cc'] ) ) $cc = $email['cc'];
			if( ! empty( $email['bcc'] ) ) $bcc = $email['bcc'];
		}
		else $to = (string)$email;

		if( ! $to )
		{
			Log::Adminauth( 'No error email address. File: ' . __FILE__ . ' , Line: ' . __LINE__ );
			return;
		}

		$domain = preg_replace( '/^http(s|):\/\//', '', URL::base() );

		$from = Config::get( 'email.webmaster', 'admin@' . $domain );

		$subject = '-NOTICE- EXCESSIVE LOGIN ATTEMPTS FROM IP ' . $ip;

		$body = View::make( 'email.excessive-login' )
			->with( 'attempts', $attempts[$ip] )
			->with( 'domain', $domain )
			->render();

		Bundle::start( 'messages' );

		$message = Message::to( $to, $title );
		if( $cc ) $message->cc( $cc );
		$message->from( $from )
			->subject( $subject )
			->body( $body )
			->html( true )
			->send();

		if( ! $bcc ) return;

		$message = Message::to( $bcc )
			->from( $from )
			->subject( $subject )
			->body( $body )
			->html( true )
			->send();
	}

	private function de_login( $arguments )
	{
		$de = DataEngine::with( array(
			'username' => $arguments['username'],
			'password' => $arguments['password']
		) )->credentials( true );

		if( ! empty( $de['valid_login'] )
			&& $de['valid_login'] == 'yes' ) return empty( $de['user_information'] ) ? true : $de['user_information'];

		return false;
	}

	private function de_create( $config, $arguments )
	{
		$email = empty( $arguments['info']['organization'] ) ? '' : $arguments['info']['organization'];
		$lastname = '';

		if( ! $email
			&& ! empty( $arguments['info']['user_listings'] )
			&& is_array( $arguments['info']['user_listings'] ) )
		{
			foreach( $arguments['info']['user_listings'] as $first )
			{
				if( ! empty( $first['name'] ) )
				{
					if( ! empty( $first['listing_id'] ) ) $lastname = '-' . $first['listing_id'] . '-';
					$email = $first['name'];
					break;
				}
			}
		}

		$email = strip_tags( $email );
		$email = preg_replace( '/[^a-zA-Z0-9\-\s]/', ' ', $email );
		$email = preg_replace( '/\s+/', ' ', $email );
		$email = trim( $email );
		if( strlen( $email ) > 250 ) $email = substr( $email, 0, 250 );
		$email = $email ? '-' . $email . '-' : '[' . $arguments['username'] . ']';

		if( $user = $this->model()->where( $config['email'], '=', $email ) ->first() )
		{
			Log::Adminauth( 'Duplicate name entry for DE user ' . $email . ' with user name ' . $arguments['username'] );
			return false;
		}

		$user = $this->model();
		$user->{$config['type']} = $config['special']['listing']['type'];
		$user->{$config['enabled']} = 1;
		$user->{$config['email']} = $email;
		$user->{$config['username']} = $arguments['username'];
		$user->{$config['password']} = $arguments['password'];
		$user->{$config['firstname']} = '-listing-';
		$user->{$config['lastname']} = $lastname;
		$user->{$config['group']} = $config['special']['listing']['group'];

		$user->save();

		return $this->login( $user->id );
	}

	private function failed( $attempts, $arguments, $reason )
	{
		$k = $_SERVER['REMOTE_ADDR'];

		if( empty( $attempts[$k] ) ) $attempts[$k] = array();

		$t = time();
		$e = $t - 60*60*24;// one day

		foreach( $attempts[$k] as $d ) if( $d['t'] < $e ) unset( $attempts[$k] );
		$attempts[$k][] = array( 't' => $t, 'u' => $arguments['username'], 'r' => $reason );

		Variable::set( 'failed_auth', $attempts );

		Profiler::tick('Adminauth::failed');
		return false;
	}

	public function get_config()
	{
		$required = array(
			'table'		=> 'users',
			'username'	=> 'username',
			'password'	=> 'password',
			'reset'		=> 'reset',
			'type'		=> 'type',
			'enabled'	=> 'enabled',
			'email'		=> 'email',
			'firstname'	=> 'firstname',
			'lastname'	=> 'lastname',
			'group'		=> 'group_id',
			'special' => array( 'listing' => array( 'type' => 3, 'group' => 6 ) )
		);
		$config = Config::get( 'auth', array() );
		foreach( $required as $k => $v ) if( empty( $config[$k] ) ) return false;
		if( empty( $config['special']['listing']['type'] )
			|| empty( $config['special']['listing']['group'] ) ) return false;
		return $config;
	}
}
