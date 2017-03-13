<?php

//          to pass to wrap
global $section_icon, $section_title;

if( Auth::guest() ) :
	$section_icon = 'users';
?>
		<div id="header-box">
			<div id="module-status">
				<span class="viewsite"><a href="/">View Site</a></span>
			</div>
			<div class="clr"></div>
		</div>

<?php
	return;
endif;

$aMenu = empty( $aMenu ) ? User::menu() : $aMenu;

// personal profile
$profile = '/admin/user/edit/' . Auth::user()->id;

$first = arg(1);
$second = arg(2);
$third = arg(3);

// for setting active menu component
if( $profile == $_SERVER['REQUEST_URI'] )
{
	$current_menu = $profile;
	$section_icon = 'profile';
}
elseif( '/admin/users/online' == $_SERVER['REQUEST_URI'] )
{
	$current_menu = '/admin/users/online';
	$section_icon = 'users';
}
// for some single-item pages
elseif( key_exists( $first, $aMenu ) )
{
	$current_menu = $section_icon = $first;
	$section_title = ucfirst( str_replace( '_', ' ', $first ) );
}
else
{
	$current_menu = array( '', 'admin' );
	if( $first && ! is_numeric( $first ) ) $current_menu[] = $first;
	if( $second && ! is_numeric( $second ) ) $current_menu[] = $second;
	if( $third && ! is_numeric( $third ) ) $current_menu[] = $third;
	$current_menu = implode( '/', $current_menu );
}

$current_url = '/' . implode( '/', arg() );

$tabindex = 1;

$aDrops = $aSubs = array();

$permissiontypes = PermissionType::order_by( 'order' )->lists( 'name', 'slug' );

?>
		<div id="header-box">
			<div id="module-status">
<?php if( empty( $site ) ) : ?>
				<span class="loggedin-users"><a href="<?= route( 'user_online', array(), true ) ?>"><?= User::where( 'session', '>', time() )->count() ?> Admin</a></span>
				<span class="viewsite"><a href="/">View Site</a></span>
<?php elseif( $link ) : ?>
				<span class="edit-content"><?= $link ?></span>
<?php endif; ?>
				<span class="logout"><a href="<?= route( 'admin_logout', array(), true ) ?>">Log out</a></span>
			</div>
			<div id="module-menu">
				<ul id="menu">
<?php

foreach( $aMenu as $title => $group )
{
	if( is_array( $group ) )
	{
		if( ! $group ) continue;
		// valid sub-menus
		if( ! key_exists( $title, $permissiontypes ) ) continue;
		$group_title = $title;
		$aSubs = array();
		$active = false;
		if( count( $group ) < 1 ) continue;
		foreach( $group as $category )
		{
			if( in_array( $current_menu, $category['alias'] ) || in_array( $current_url, $category['alias'] ) )
			{
				$active = true;
				// these are for top of admin content area
				// icon class
				if( empty( $section_icon ) ) $section_icon = $category['id'];
				// headline
				if( empty( $section_title ) ) $section_title = $category['subtitle'];
				if( empty( $section_title ) ) $section_title = $permissiontypes[$title];
			}
			// build subs first in case sub is active
			$class = $active ? ' class="sfActive"' : '';
			$list  = "							<li{$class} id=\"menu-{$category['id']}\">\n";
			$list .= "								<span class=\"menu-icon\"></span>\n";
			if( $category['url'] == '/admin/clearcache' ) $category['url'] .= '?src=' . urlencode( $current_url );
			$list .= "								<a href=\"{$category['url']}\" tabindex=\"$tabindex\" title=\"{$category['subtitle']}\">{$category['title']}</a>\n";
			$list .= "							</li>\n";

			$tabindex ++;
			$aSubs[] = $list;
		}
	}
	elseif( $aSubs )
	{
		$aDrops[] = array(
			'list' => $aSubs,
			'title' => $group_title,
		);
		$aSubs = array();
	}
}

foreach( $aDrops as $sub ) : $title = ucfirst( str_replace( '_', ' ', $sub['title'] ) ) ?>
					<li class="node <?= $sub['title'] ?>">
						<span class="menu-icon"></span>
						<a href="/admin/<?= $sub['title'] ?>" title="<?= $title ?>"><?= $title ?></a>
						<ul class="sfMenu">
<?php foreach( $sub['list'] as $list ) print $list ?>
						</ul>
					</li>
					<li class="separator"><span></span></li>
<?php endforeach;

if( empty( $section_icon ) ) $section_icon = 'error';

?>
				</ul>
			</div>
			<div class="clr"></div>
		</div>
