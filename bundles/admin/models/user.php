<?php

class User extends Eloquent {

	private $_permissions; // A list of actions the user can perform

	public static $accessible = array(
		'id',
		'type',
		'enabled',
		'email',
		'username',
		'password',
		'password_confirmation',
		'firstname',
		'lastname',
		'group_id',
		'menu',
		'theme',
		'session',
		'created_at',
		'updated_at'
	);

	public static function user_menu()
	{
		$aRoute = array(
			route( 'user_list', array(), true ),
			route( 'user_confirm_delete', array(), true ),
			route( 'user_edit', array(), true )
		);

		return array(
			'id' => 'users',
			'title' => 'Users',
			'subtitle' => 'Manage Users',
			'url' => $aRoute[0],
			'submenu' => array(),
			'alias' => $aRoute
		);
	}

	public static function menu()
	{
		$user = Auth::user();
		//DB::table( 'users' )->update( array( 'menu' => '' ) );
		if( $aMenu = $user->menu ) return unserialize( $aMenu );

		$aMenu = array();
		$s = 0;

		$aMenu['admin_home'] =
			array(// group
				array(// category
					'id' => 'home',
					'title' => 'Admin Home',
					'subtitle' => 'Administration Menu',
					'url' => '/admin',
					'submenu' => array(),
					'alias' => array( '/admin' )
				)// end category
			);// end group

		$aMenu[$s++] =	'';// makes separator

		// build content
		$temp = array();// group

		if( $user->can( 'edit_document' ) )
		{
			$aRoute = array(
				route( 'document_tree', array(), true )
			);
			$temp[] = array(
				'id' => 'sitetree',
				'title' => 'Site Tree',
				'subtitle' => 'Nested tree view of site content',
				'url' => $aRoute[0],
				'submenu' => array(),
				'alias' => $aRoute
			);

			$temp[] = Document::user_menu();
		}

		if( $user->can( 'edit_info' ) ) $temp[] = Info::user_menu();

		if( $user->can( 'edit_article' ) ) $temp[] = Article::user_menu();

		if( $user->can( 'edit_menu' ) ) $temp[] = Menu::user_menu();

		if( $user->can( 'edit_area' ) ) $temp[] = Area::user_menu();

		if( $user->can( 'edit_interest' ) ) $temp[] = Interest::user_menu();

		if( $user->can( 'edit_photoslist' ) ) $temp[] = Photoslist::user_menu();

		if( $user->can( 'edit_press' ) ) $temp[] = Press::user_menu();

		if( $temp )
		{
			$aMenu['content'] = $temp;
			$aMenu[$s++] = '';// makes separator
		}
		// end build content

		// build special
		$temp = array();// group

		if( $user->can( 'edit_tourism' ) ) $temp[] = TourismCategory::user_menu();

		if( $user->can( 'edit_industry' ) ) $temp[] = IndustryCoop::user_menu();

		if( $temp )
		{
			$aMenu['special'] = $temp;
			$aMenu[$s++] =	'';// makes separator
		}
		// end build special

		// build media
		$temp = array();// group

		if( $user->can( 'edit_photo' ) ) $temp[] = Photo::user_menu();

		if( $user->can( 'any/edit_files/edit_industry' ) ) $temp[] = Files::user_menu();

		if( $user->can( 'edit_slideshow' ) ) $temp[] = Slideshow::user_menu();

		if( $user->can( 'edit_imagealias' ) ) $temp[] = ImageAlias::user_menu();

		if( $user->can( 'edit_video' ) ) $temp[] = Video::user_menu();

		if( $temp )
		{
			$aMenu['media'] = $temp;
			$aMenu[$s++] =	'';// makes separator
		}
		// end build media

		// build tools
		$temp = array();// group

		if( $user->can( 'edit_meta' ) ) $temp[] = Meta::user_menu();

		if( $user->can( 'edit_pathalias' ) ) $temp[] = PathAlias::user_menu();

		if( $user->can( 'edit_link' ) ) $temp[] = Link::user_menu();

		if( $user->can( 'edit_analytics' ) )
		{
			$aRoute = array(
				route( 'googleanalytics', array(), true )
			);

			$temp[] = array(// category
					'id' => 'analytics',
					'title' => 'Google Analytics',
					'subtitle' => 'Configure tracking',
					'url' => $aRoute[0],
					'submenu' => array(),
					'alias' => $aRoute
				);// end category
		}

		if( $user->can( 'edit_revision' ) ) $temp[] = Revision::user_menu();

		if( $user->can( 'flush_cache' ) )
		{
			$aRoute = array(
				route( 'clear_cache', array(), true )
			);
			$temp[] = // category
				array(
					'id' => 'cache',
					'title' => 'Flush Cache',
					'subtitle' => 'Flush cached data and force browser to fetch new styles/javascript',
					'url' => $aRoute[0],
					'submenu' => array(),
					'alias' => $aRoute
				);// end category
		}

			// Content
		if( $user->can( 'any/edit_document/edit_info/edit_article/edit_menu/edit_area/edit_interest/edit_photoslist/edit_press' ) )
		{
			$aRoute = array(
				route( 'library', array(), true )
			);
			$temp[] = // category
				array(
					'id' => 'library',
					'title' => 'Library',
					'subtitle' => 'View / Upload files for WYSIWYG',
					'url' => $aRoute[0],
					'submenu' => array(),
					'alias' => $aRoute
				);// end category
		}

		if( $user->can( 'view_logs' ) )
		{
			$aRoute = array(
				route( 'view_logs', array(), true )
			);
			$temp[] = // category
			array(
				'id' => 'logs',
				'title' => 'View Logs',
				'subtitle' => 'View Laravel event logs',
				'url' => $aRoute[0],
				'submenu' => array(),
				'alias' => $aRoute
			);// end category
		}

		if( $user->can( 'import_content' ) )
		{
			$aRoute = array(
				route( 'import_content', array(), true )
			);
			$temp[] = // category
			array(
				'id' => 'import',
				'title' => 'Import Content',
				'subtitle' => 'Import content as new content from dev/live server',
				'url' => $aRoute[0],
				'submenu' => array(),
				'alias' => $aRoute
			);// end category
		}

		if( $temp )
		{
			$aMenu['tools'] = $temp;
			$aMenu[$s++] =	'';// makes separator
		}
		// end build tools

		// build dataengine
		$temp = array();// group
		if( $user->can( 'view_hp_events' ) )
		{
			$aRoute = array(
				route( 'grid_hp_events', array(), true )
			);
			$temp[] = // category
				array(
					'id' => 'hp_events',
					'title' => 'Homepage Events',
					'subtitle' => 'View Events tagged for homepage',
					'url' => $aRoute[0],
					'submenu' => array(),
					'alias' => $aRoute
				);// end category
		}

		if( $user->can( 'edit_grid' ) ) $temp[] = Grid::user_menu();

		if( $user->can( 'edit_profile' ) ) $temp[] = Profile::user_menu();

		if( $user->can( 'edit_availability' ) ) $temp[] = Availability::user_menu();

		if( $user->can( 'dataengine_api' ) )
		{
			$aRoute = array(
				route( 'api', array(), true ),
			);
			$temp[] = // category
				array(
					'id' => 'api',
					'title' => 'API',
					'subtitle' => 'Debug DataEngine API',
					'url' => $aRoute[0],
					'submenu' => array(),
					'alias' => $aRoute
				);// end category
		}

		if( $temp )
		{
			$aMenu['dataengine'] = $temp;
			$aMenu[$s++] = '';// makes separator
		}
		// end build dataengine

		// build user/group/perms
		$temp = array();// group
		if( $user->can( 'edit_user' ) ) $temp[] = self::user_menu();

		if( $user->can( 'edit_permission' ) ) $temp[] = Permission::user_menu();

		if( $user->can( 'edit_permissiontype' ) ) $temp[] = PermissionType::user_menu();

		if( $user->can( 'edit_group' ) ) $temp[] = Group::user_menu();

		if( $temp )
		{
			$aMenu['user-manager'] = $temp;
			$aMenu[$s++] = '';// makes separator
		}
		// end build user/group/perms

		// personal profile
		$profile = '/admin/user/edit/' . $user->id;
		$aMenu['personal'] =
			array(// group
				array(// category
					'id' => 'personal',
					'title' => 'Edit Profile',
					'subtitle' => 'Change Your Password and Contact Information',
					'url' => $profile,
					'submenu' => array(),
					'alias' => array( $profile )
				)// end category
			);

		$aMenu[$s++] =	'';// makes separator

		// logout
		$aMenu['log_out'] =
			array(// group
				array(// category
					'id' => 'logout',
					'title' => 'Log Out',
					'subtitle' => 'Log out of the Content Managent System',
					'url' => route( 'admin_logout', array(), true ),
					'submenu' => array(),
					'alias' => array()
				)// end category
			);

		$user->menu = serialize( $aMenu );
		$user->save( true );

		return $aMenu;
	}

	public static function type_settings( $type = '' )
	{
		static $types;
		if( ! $types ) $types = Config::get( 'auth.special', array() );
		if( ! empty( $types[$type] ) ) return $types[$type];
		return false;
	}

	public static function setting_type( $type = '' )
	{
		if( $settings = self::type_settings( $type ) )
		{
			if( ! empty( $settings['type'] ) ) return $settings['type'];
		}
		return 0;
	}

	public static function setting_group( $type = '' )
	{
		if( $settings = self::type_settings( $type ) )
		{
			if( ! empty( $settings['group'] ) ) return $settings['group'];
		}
		return 0;
	}

	public static function types()
	{
		static $types;
		if( $types ) return $types;
		if( $config = Config::get( 'auth.special', array() ) )
		{
			$types = array();
			foreach( (array)$config as $type => $a )
			{
				if( ! empty( $a['type'] ) ) $types[$a['type']] = ucfirst( $type );
			}
		}
		return $types;
	}

	public function group()
	{
		return $this->belongs_to( 'Group' );
	}

	public function user_name( $full = false )
	{
		if( $this->type == self::setting_type( 'listing' ) )
		{
			return trim( $this->email, '-' );
		}
		if( $full ) return $this->firstname . ' ' . $this->lastname;
		return $this->firstname;
	}

	public function type_name()
	{
		$types = self::types();
		return empty( $types[$this->type] ) ? 'None' : $types[$this->type];
	}

	public function validator()
	{
		if( $this->type == self::setting_type( 'listing' ) )
		{
			$this->email = trim( $this->email, '-' );
			$this->email = '-' . $this->email . '-';
			$rules = array(
				'email'		=> 'required|max:255|unique:users,email',
				'username'	=> 'required|max:64|unique:users,username'
			);

			if( $this->exists )
			{
				$rules['email'] .= ','.$this->id;
				$rules['username'] .= ','.$this->id;
			}

			$messages = array(
				'max'			=> array(
					'numeric'	=> 'The :attribute{email|orgainization} must be less than :max.',
					'file'		=> 'The :attribute{email|orgainization} must be less than :max kilobytes.',
					'string'	=> 'The :attribute{email|orgainization} must be less than :max characters.',
				),
				'required'		=> 'The :attribute{email|orgainization} field is required.',
				'unique'		=> 'The :attribute{email|orgainization} has already been taken.'
			);
		}
		else
		{
			$rules = array(
				'firstname'	=> 'required|max:32',
				'lastname'	=> 'required|max:32',
				'group_id'	=> 'exists:groups,id',
				'email'		=> 'required|max:255|email|unique:users,email',
				'username'	=> 'required|max:64|alpha_dash|unique:users,username',
				'password'	=> 'required'
			);

			if( $this->exists )
			{
				$rules['email'] .= ','.$this->id;
				$rules['username'] .= ','.$this->id;
			}

			// If we have a password confirmation value (form submit), then the password should
			// be confirmed. New entries are required to have a password.
			if( $this->password_confirmation !== null )
			{
				if( $this->exists ) $rules['password'] = 'confirmed|min:6|max:32|match:/[a-z]+/|match:/[A-Z]+/|match:/[0-9]+/';
				else $rules['password'] = 'required|confirmed|min:6|max:32|match:/[a-z]+/|match:/[A-Z]+/|match:/[0-9]+/';
			}

			$messages = array( 'match' => 'The :attribute must contain at least one lowercase letter (a-z), one uppercase letter (A-Z), and one number (0-9).' );
		}
		return Validator::make( $this->to_array(), $rules, $messages );
	}

	public function save( $menu = false )
	{
		$this->purge( 'password_confirmation' );

		// If the password is empty, remove it from the model. No new value was
		// provided. If it isn't empty, has changed or this is a new record; hash
		// the password before storing it.
		if( empty( $this->password ) )
		{
			$this->purge( 'password' );
		}
		elseif( $this->changed( 'password' ) || ! $this->exists )
		{
			$this->password = Hash::make( $this->password );
		}

		if( $menu !== true ) $this->purge( 'menu' );

		if( $this->id == 1 ) $this->enabled = 1;

		if( $this->type == self::setting_type( 'listing' ) )
		{
			$this->email = trim( $this->email, '-' );
			$this->email = '-' . $this->email . '-';
		}

		parent::save();
	}

	/**
	 * can
	 *
	 * @param $k slug from permissions table
	 *
	 * @return true if the user has the permission
	 */
	public function can( $k )
	{
		// Group 1 - Administrator - has all privileges
		if( $this->group_id == 1 ) return true;

		if( empty( $this->_permissions ) )
			$this->_permissions = $this->group->permissions()->lists( 'name', 'slug' );

		if( isset( $this->_permissions[$k] ) ) return true;

		if( ! strpos( $k, '/' ) ) return false;

		$perms = explode( '/', $k );
		if( count( $perms ) > 1 )
		{
			$type = $perms[0];
			unset( $perms[0] );
			if( $type == 'any' )
			{
				foreach( $perms as $k ) if( isset( $this->_permissions[$k] ) ) return true;
			}
			elseif( $type == 'all' )
			{
				foreach( $perms as $k ) if( ! isset( $this->_permissions[$k] ) ) return false;
				return true;
			}
		}

		return false;
	}

	/**
	 * can_edit_user - Can this user edit another?
	 *
	 * @param int $id - The id of the other user
	 *
	 * @return bool
	 */
	public function can_edit_user( $id )
	{
		// Group 1 - Administrator - has all privileges
		if( $this->group_id == 1 ) return true;
		// This user is authorized to edit all other users
		if( $this->can( 'edit_user' ) )
			return true;

		// This user is editing their own record
		if( $this->id == $id )
			return true;

		return false;
	}

	/**
	 * auth_can_edit_user - Can the currently authorized user edit a given user
	 *
	 * @param int $id - The id of the other user
	 *
	 * @return bool
	 */
	public static function auth_can_edit_user( $id )
	{
		// The user table is empty. This is a special case where we are adding the
		// very first user.
		if( User::count() === 0 )
			return true;

		// The auth user is logged in and permitted to edit this user
		if( Auth::user() && Auth::user()->can_edit_user( $id ) )
			return true;

		return false;
	}

	public function reset_password( $email = false, $template = 'admin::email.password-reset' )
	{
		$password = self::random_password();

		$this->reset = Hash::make( $password );

		$this->save();

		if( $email ) $this->email_password( $password, $template );

		return $password;
	}

	/**
	 * Generate a random password of a given length
	 *
	 * @param int length
	 *
	 * @returns string password
	 */
	public static function random_password( $length = 8 )
	{
		$alphabet = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789';

		$count = strlen( $alphabet ) - 1;

		// make up to 1000 attempts before accepting whatever fate gives us
		for ( $i = 0; $i < 1000; $i++ )
		{
			$password = array();

			for( $j = 0; $j < $length; $j++ )
				$password[] = $alphabet[ rand( 0, $count ) ];

			$password = implode( $password );

			if( self::password_strength( $password ) >= 3 )
				break;
		}

		return $password;
	}

	/**
	 * Measure the strength of a password. Gets one point for each: uppercase
	 * alpha, lowercase alpha, number and special character
	 *
	 * @param string password
	 *
	 * @returns int 0 - lowest, 4 - highest
	 */
	public static function password_strength( $password )
	{
		$strength = 0;

		if( preg_match( '/[a-z]{1}/', $password ) ) $strength++;

		if( preg_match( '/[A-Z]{1}/', $password ) ) $strength++;

		if( preg_match( '/[0-9]{1}/', $password ) ) $strength++;

		if( preg_match( '/[^a-zA-Z0-9]{1}/', $password ) ) $strength++;

		return $strength;
	}

	/**
	 * Generate an email with the new password and send it to this user
	 *
	 * @param string password
	 */
	protected function email_password( $password, $template = 'admin::email.password-reset' )
	{
		Bundle::start( 'messages' );

		$domain = preg_replace( '/^http(s|):\/\//', '', URL::base() );

		$body = View::make( $template )
			->with( 'domain', $domain )
			->with( 'user', $this )
			->with( 'password', $password )
			->render();

		Message::to( $this->email )
			->from( 'admin@' . $domain )
			->subject( 'Password Reset for ' . $domain )
			->body( $body )
			->html( true )
			->send();
	}

	/**
	 * Eloquent getter for model, ensures that no matter how 'value' is retrieved, it is retrieved properly
	 *
	 * @return $value
	 */
	public function last_session()
	{
		if( ! $this->session ) return '- No History -';
		if( strtotime( date( 'Y/m/d' ) ) - $this->session < 0 )
		{
			$exp = date( 'g:ia', $this->session );
			$class = time() > $this->session ? 'expired' : 'active';
			return "<span class=\"$class\">$exp</span>";
		}
		return date( 'M jS, Y g:ia', $this->session );
	}
}
