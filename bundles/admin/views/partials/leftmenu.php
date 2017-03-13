<?php

//          to pass to wrap
global $section_icon, $section_title;

?>
				<ul id="admin-menu" class="ui-widget">
<?php
if( Auth::guest() ) : ?>
					<li id="menu-home" class="ui-widget-header ui-corner-all">
						<span class="menu-icon"></span>
						<a title="Administration Menu" tabindex="1" href="/admin/">Home</a>
					</li>
				</ul>
<?php
	$section_icon = 'users';
	return;
endif;

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

$aMenu = User::menu();

foreach( $aMenu as $group )
{
	if( is_array( $group ) )
	{
		$count = count( $group );
		$g = 1;
		foreach( $group as $category )
		{
			$listclass = array( 'ui-widget-header' );
			if( in_array( $current_menu, $category['alias'] ) )
			{
				$listclass[] = 'ui-state-active';
				$listclass[] = 'ui-state-highlight';
				// these are for top of admin content area
				// icon class
				if( empty( $section_icon ) ) $section_icon = $category['id'];
				// headline
				if( empty( $section_title ) ) $section_title = $category['subtitle'];
			}
			// build subs first in case sub is active
			$out = '';
			$subtab = $tabindex + 1;
			if( $category['submenu'] )
			{
				$out .= "\n						<ul class=\"ui-widget-content\">\n";
				foreach ( $category['submenu'] as $submenu )
				{
					if( in_array( $current_menu, $submenu['alias'] ) )
					{
						$listclass[] = 'ui-state-active';
						$subclass = ' class="ui-state-highlight"';
						if( empty( $section_icon ) ) $section_icon = $category['id'];
						if( empty( $section_title ) ) $section_title = $submenu['subtitle'];
					}
					else $subclass = '';
					$out .= "							<li{$subclass}><a href=\"{$submenu['url']}\" tabindex=\"{$subtab}\" title=\"{$submenu['subtitle']}\">{$submenu['title']}</a></li>\n";
					$subtab++;
				}
				$out .= "						</ul>\n";
			}
			else $listclass[] = 'ui-state-nosub';

			if( $count == 1 ) $listclass[] = 'ui-corner-all';
			elseif( $g == 1 ) $listclass[] = 'ui-corner-top';
			elseif( $g == $count )
			{
				$listclass[] = 'ui-corner-bottom';
				if( $out ) $out = str_replace( 'class="', 'class="ui-corner-bottom ', $out );
			}

			$class = implode( ' ', $listclass );
			print "					<li class=\"{$class}\" id=\"menu-{$category['id']}\">\n";
			print "						<span class=\"menu-icon\"></span>\n";
			if( $out ) print "						<span class=\"ui-widget-control ui-icon ui-icon-triangle-1-s\"></span>\n";
			if( $category['url'] == '/admin/clearcache' ) $category['url'] .= '?src=' . urlencode( $current_url );
			print "						<a href=\"{$category['url']}\" tabindex=\"$tabindex\" title=\"{$category['subtitle']}\">{$category['title']}</a>\n";
			print $out;
			print "					</li>\n";

			$g++;
			$tabindex = $subtab;
		}
	}
	elseif( $group ) print "					<li><h3>$group</h3></li>\n";
	else print "					<li>&nbsp;</li>\n";
}

if( empty( $section_icon ) ) $section_icon = 'error';

?>
				</ul>
				<script type="text/javascript">jQuery('#admin-menu span.ui-widget-control').click(function(){ if(jQuery(this).hasClass('active')){ jQuery(this).removeClass('active').parent().removeClass('ui-state-drop'); } else { jQuery(this).addClass('active').parent().addClass('ui-state-drop'); } }); </script>
