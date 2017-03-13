<?php

class UserList extends BaseList
{
	public function __construct()
	{
		parent::__construct( 'User', array(
			'order' => 'firstname_asc',
			'type'  => User::setting_type( 'admin' )
		));
	}

	public function _items( $settings = false )
	{
		$model = $this->model;
		$query = $model::with( 'group' );

		// group
		if( ! empty( $settings['group'] ) )
		{
			$query = $query->where( 'users.group_id', '=', $settings['group'] );
		}

		// Type
		$type = empty( $settings['type'] ) ? $this->_get( 'type' ) : $settings['type'];
		if( $type )
		{
			$query = $query->where( 'users.type', '=', $type );
		}

		// Search
		if( $search = $this->_get( 'search' ) )
		{
			$query = $query->where( function( $query ) use( $search )
			{
				$query
					->where( 'users.firstname', 'like', '%'.$search.'%' )
					->or_where( 'users.lastname', 'like', '%'.$search.'%' )
					->or_where( 'users.email', 'like', '%'.$search.'%' );
			});
		}

		// Order
		if( $order = $this->_order_params() )
		{
			if( ! in_array( $order['column'], array( 'firstname', 'session', 'type', 'group', 'enabled' ) ) ) $order['column'] = 'firstname';
			if( $order['column'] == 'group' )
			{
				$query = $query->join( 'groups', 'groups.id', '=', 'users.group_id' )
					->order_by( 'groups.name', $order['dir'] );
			}
			else
			{
				$query = $query->order_by( 'users.'.$order['column'], $order['dir'] );
			}
		}

		$columns = array(
			'users.id',
			'users.type',
			'users.email',
			'users.firstname',
			'users.lastname',
			'users.session',
			'users.enabled',
			'users.group_id'
		);

		// The paginator relies solely on the input class to get a page
		Input::merge( array(
			'page' => $this->_get( 'page' )
		));

		// Pagination
		return $query->paginate( $this->_get( 'pagesize' ), $columns );
	}
}
