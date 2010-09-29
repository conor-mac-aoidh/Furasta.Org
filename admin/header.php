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
require $function_dir.'admin.php';

$admin_dir=HOME.'admin/';

$Template=Template::getInstance();

if(!is_logged_in($_SESSION['user']['id']))
        require 'login.php';
else
        $User=new User($_SESSION['user']['id']);

$cache_file='FURASTA_ADMIN_MENU';

if(cache_exists($cache_file,'PAGES'))
	$menu=json_decode(cache_get($cache_file,'PAGES'));
else{
	$menu_items=array(
		'Overview'=>array(
			'url'=>'/admin/index.php',
		),
		'Pages'=>array(
			'url'=>'/admin/pages.php',
			'submenu'=>array(
				'Edit Pages'=>array(
						'url'=>'/admin/pages.php?page=list',
				),
				'New Page'=>array(
						'url'=>'/admin/pages.php?page=new',
				),
				'Trash'=>array(
						'url'=>'/admin/pages.php?page=trash',
				),
			),
		),
		'Users'=>array(
			'url'=>'/admin/users.php',
			'submenu'=>array(
				'Edit Users'=>array(
					'url'=>'/admin/users.php?page=users',
				),
				'Edit Groups'=>array(
					'url'=>'/admin/users.php?page=groups',
				),
			),
		),
		'Settings'=>array(
			'url'=>'/admin/settings.php',
			'submenu'=>array(
				'Configuration'=>array(
					'url'=>'/admin/settings.php?page=configuration',
				),
				'Template'=>array(
						'url'=>'/admin/settings.php?page=template',
				),
				'Plugins'=>array(
						'url'=>'/admin/settings.php?page=plugins',
				),
				'Update'=>array(
						'url'=>'/admin/settings.php?page=update',
				),
			),
		),
	);

	$menu_items=$Plugins->adminMenu($menu_items);

	$menu=display_menu($menu_items);

        $menu_items_cache=json_encode($menu);
        cache($cache_file,$menu_items_cache,'PAGES');
}

$Template->add('menu',$menu);

$javascript='
$(document).ready(function(){
        rowColor();
        $("#menu ul").dropDownMenu({timer:1500,parentMO:"parent-hover"});
        var path=location.pathname;
        if(path=="/admin"||path=="/admin/"){
                $("#Overview").addClass("current");
        }
        else{
                $("#menu li a[href=\'"+path+"\']").addClass("current");
        }
});
';

$Template->loadJavascript( '_inc/js/admin.js' );
$Template->loadJavascript( 'FURASTA_ADMIN_HEADER', $javascript );

?>
