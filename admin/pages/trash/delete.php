<?php

/**
 * Trash Delete Page, Furasta.Org
 *
 * Deletes a trash page
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_pages
 */

$id=@$_GET['id'];

if($id==0)
	exit;

mysql_query('delete from '.TRASH.' where id="'.$id.'"') or die('1');
?>