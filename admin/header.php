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

$Template=new Template();

if(!is_logged_in($_SESSION['user']['id']))
        require 'login.php';
else
        $User=new User($_SESSION['user_id']);

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
		'Users & Groups'=>array(
			'url'=>'/admin/users-groups.php',
			'submenu'=>array(
				'Edit Users'=>array(
					'url'=>'/admin/users-groups.php?page=users',
				),
				'Edit Groups'=>array(
					'url'=>'/admin/users-groups.php?page=groups',
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

        $content=json_encode($menu);
        cache($cache_file,$content,'PAGES');
}

$Template->add('menu',$menu);
?>
