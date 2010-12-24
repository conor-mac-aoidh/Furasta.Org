<?php

/**
 * Order Overview Items, Furasta.Org
 *
 * This file is accessed by AJAX and it
 * changes the order of overview items.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_overview
 */

$items=@$_GET['order'];

if($items=='')
	die('1');

$columns=explode('|',$items);

$ordered=array();
$ordered['1']=explode(',',$columns[0]);
$ordered['2']=explode(',',$columns[1]);

$cache_file='FURASTA_OVERVIEW_ITEMS_'.$_SESSION['user']['id'];

cache($cache_file,json_encode($ordered),'USERS');

die('0');
?>
