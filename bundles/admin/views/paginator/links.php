<?php

if( empty( $paginator ) || ! is_object( $paginator ) || $paginator->last < 2 ) return;

// appends - an array of additional query parameters
// window  - number of links to show
//
$params     = empty( $appends )		? array()		: $appends;
// this template did not actually include existing params unless passed,
// since it doesn't use the actual paginator class.
if( ! $params )
{
	foreach( Input::get() as $k => $v )
	{
		// excludes
		if( in_array( $k, array( 'reset', 'page', 'param' ) ) ) continue;
		// no value
		if( ! strlen( $v ) ) continue;
		// include
		$params[$k] = $v;
	}
}
$window     = isset( $window )		? $window		: 5;
$show_first = isset( $show_first )	? $show_first	: true;
$show_prev  = isset( $show_prev )	? $show_prev	: true;
$show_next  = isset( $show_next )	? $show_next	: true;
$show_last  = isset( $show_last )	? $show_last	: true;

// window values <= 0 will indicate an unlimited window. If the value is
// greater than 0, ensure it is not larger then the total number of pages
//
if( $window <= 0 ) $window = $paginator->last;
else $window = min( $window, $paginator->last );

// Get starting location. Make sure it is not less then 1 and not greater
// then the total number of pages - (window - 1). The last page is included
// which accounts for the -1.
//
$window_start = $paginator->page - floor( $window / 2 );
$window_start = max( $window_start, 1 );
$window_start = min( $window_start, ( $paginator->last - ( $window - 1 ) ) );

$current_url = URL::current();

?><div class="pagination"><ul><?php

if( $paginator->page == 1 )
{
	if( $show_first ) print '<li class="first_page disabled">' . HTML::link( '#', '« First' ) . '</li>';
    if( $show_prev ) print '<li class="previous_page disabled">' . HTML::link( '#', '« Previous' ) . '</li>';
}
else
{
	if( $show_first )
	{
		$params['page'] = 1;
		print '<li class="first_page">' . HTML::link( $current_url . '?' . http_build_query( $params ), '« First' ) . '</li>';
	}
	if( $show_prev )
	{
		$params['page'] = $paginator->page - 1;
		print '<li class="first_page">' . HTML::link( $current_url . '?' . http_build_query( $params ), '« Previous' ) . '</li>';
	}
}

for( $i = $window_start; $i < $window_start + $window; $i++ )
{
	$params['page'] = $i;
	print '<li class="' . ( $i == $paginator->page ? 'active' : '' ) . '">' . HTML::link( $current_url . '?' . http_build_query( $params ), $i ) . '</li>';
}

if( $paginator->page == $paginator->last )
{
	if( $show_next ) print '<li class="next_page disabled">' . HTML::link( '#', 'Next »' ) . '</li>';
    if( $show_last ) print '<li class="previous_last disabled">' . HTML::link( '#', 'Last »' ) . '</li>';
}
else
{
	if( $show_next )
	{
		$params['page'] = $paginator->page + 1;
		print '<li class="next_page">' . HTML::link( $current_url . '?' . http_build_query( $params ), 'Next »' ) . '</li>';
	}
    if( $show_last)
    {
	    $params['page'] = $paginator->last;
		print '<li class="previous_last">' . HTML::link( $current_url . '?' . http_build_query( $params ), 'Last »' ) . '</li>';
	}
}
?></ul></div>