<?php

/**
 * Overview Items Status, Furasta.Org
 *
 * This file is accessed by AJAX and it
 * changes the status of an overview item,
 * ie open or closed.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_overview
 */

$id=@$_GET['id'];
$status=@$_GET['status'];

if($id==''||$status=='')
	die('1');

$array=array(
	$id=>$status
);

$cache_file='FURASTA_OVERVIEW_ITEMS_STATUS_'.$_SESSION['user']['id'];

if(cache_exists($cache_file,'USERS')){
	$items=json_decode(cache_get($cache_file,'USERS'),true);
	
	if(array_key_exists($id,$items)){
		$items[$id]=$status;
		$array=$items;
	}
	else
		$array=array_merge($array,$items);
}

cache($cache_file,json_encode($array),'USERS');

die('0');
?>
