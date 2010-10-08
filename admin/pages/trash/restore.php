<?php

/**
 * Trash Restore Page, Furasta.Org
 *
 * Restores a trash page.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_pages
 */

$id=@$_GET['id'];

if($id==0)
	exit;

mysql_query('insert into '.PAGES.' select NULL,name,content,slug,"Default","Normal",edited,user,position,"0",perm,"0",display from '.TRASH.' where id='.$id) or die('1');

mysql_query('delete from '.TRASH.' where id='.$id);

cache_clear('PAGES');
?>
