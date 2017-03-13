<?php

// what route files to include_once in this admin instance

// menu pages
include_once( $base . 'menu-pages.php' );

// user
include_once( $base . 'adminauth.php' );
include_once( $base . 'permissiontype.php' );
include_once( $base . 'permission.php' );
include_once( $base . 'group.php' );
include_once( $base . 'user.php' );

// special
include_once( $base . 'tourism.php' );
include_once( $base . 'industry.php' );

// media
include_once( $base . 'slideshow.php' );
include_once( $base . 'photo.php' );
include_once( $base . 'files.php' );
include_once( $base . 'imagealias.php' );
include_once( $base . 'video.php' );
include_once( $base . 'filemanager.php' );

// utility
include_once( $base . 'meta.php' );
include_once( $base . 'pathalias.php' );
include_once( $base . 'link.php' );
include_once( $base . 'ga.php' );
include_once( $base . 'revision.php' );

// content
include_once( $base . 'document.php' );
include_once( $base . 'article.php' );
include_once( $base . 'menu.php' );
include_once( $base . 'info.php' );
include_once( $base . 'area.php' );
include_once( $base . 'interest.php' );
include_once( $base . 'photoslist.php' );
include_once( $base . 'press.php' );

// tagging
include_once( $base . 'tag.php' );

// static
include_once( $base . 'static.php' );
