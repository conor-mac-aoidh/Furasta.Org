<?php

/**
 * Admin Header, Furasta.Org
 *
 * The admin header which is loaded on every page in
 * the admin area.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

require '../_inc/define.php';
require $function_dir . 'admin.php';

$admin_dir = HOME . 'admin/';

$Template = Template::getInstance( );
$User = User::getInstance( );

if( !$User->verify( ) )
        require 'login.php';

/**
 * execute the onload plugin functions 
 */
$Plugins->hook( 'admin', 'on_load' );

$cache_file = 'FURASTA_ADMIN_MENU_' . $_SESSION[ 'user' ][ 'id' ];

if(cache_exists($cache_file,'USERS'))
	$menu=json_decode(cache_get($cache_file,'USERS'));
else{
	$url = SITEURL . 'admin/';

	$menu_items=array(
		'Overview'=>array(
			'url'=>$url . 'index.php',
		),
		'Pages'=>array(
			'url'=>$url . 'pages.php',
			'submenu'=>array(
				'Edit Pages'=>array(
						'url'=>$url . 'pages.php?page=list',
				),
				'New Page'=>array(
						'url'=>$url . 'pages.php?page=new',
				),
				'Trash'=>array(
						'url'=>$url . 'pages.php?page=trash',
				),
			),
		),
		'Users & Groups'=>array(
			'url'=>$url . 'users.php',
			'submenu'=>array(
				'Edit Users'=>array(
					'url'=>$url . 'users.php?page=users',
				),
				'Edit Groups'=>array(
					'url'=>$url . 'users.php?page=groups',
				),
			),
		),
		'Settings'=>array(
			'url'=>$url . 'settings.php',
			'submenu'=>array(
				'Configuration'=>array(
					'url'=>$url . 'settings.php?page=configuration',
				),
				'Template'=>array(
						'url'=>$url . 'settings.php?page=template',
				),
				'Plugins'=>array(
						'url'=>$url . 'settings.php?page=plugins',
				),
				'Update'=>array(
						'url'=>$url . 'settings.php?page=update',
				),
			),
		),
	);

	if( $_SESSION[ 'user' ][ 'perm' ][ 2 ] == 1 )
		$menu_items=$Plugins->adminMenu($menu_items);

	$menu=display_menu($menu_items);

        $menu_items_cache=json_encode($menu);
        cache($cache_file,$menu_items_cache,'USERS');
}

$Template->add('menu',$menu);

$javascript='
$(document).ready(function(){
        rowColor();
        $("#menu ul").dropDownMenu({timer:1500,parentMO:"parent-hover"});
        var link=window.location.href.split( "?" );
	var path = link[ 0 ];
        if(path=="' . SITEURL . 'admin"||path=="' . SITEURL . 'admin/"){
                $("#Overview").addClass("current");
        }
	else if( path == "' . SITEURL . 'admin/plugin.php" ){
		link = link[ 1 ].split(/[=&]+/);
		$( "#" + link[ 1 ] ).addClass( "current" );
	}
        else{
                $("#menu li a[href=\'"+path+"\']").addClass("current");
        }
});
';

$Template->loadJavascript( '_inc/js/admin.js' );
$Template->loadJavascript( 'FURASTA_ADMIN_HEADER', $javascript );

?>
