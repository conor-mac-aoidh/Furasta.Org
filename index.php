<?php

/**
 * Index File, Furasta.Org
 *
 * This is the first file loaded in the forntend of the CMS. It
 * figures out what page has been accessed and then includes
 * a file to deal with the Smarty Template Engine.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_overview
 */

require '_inc/define.php';

if($SETTINGS['maintenance']==1&&!verify_user($_SESSION['user_id']))
	error($SETTINGS['site_title'].' is undergoing maintenance. It should be back to normal shortly.','Maintenance');

$page=@$_GET['page'];

if($page=='')
	$id=0;
else{
        $array=explode('/',$page);
        if(end($array)=='')
                array_pop($array);
        $slug=addslashes(end($array));
	$id=single('select id from '.PAGES.' where slug="'.$slug.'"','id');
	if($id==false)
		error('Page not found.<br/><a href="/">Return to Website.</a>','404 - Page Not Found');
}

$Page=new Page($id);

require HOME.'_inc/smarty.php';

?>
