<?php

/**
 * Delete Template, Furasta.Org
 *
 * Deletes a template. This page is accessed via AJAX.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

$name=@$_GET['name'];

if($name=='')
	exit;

$dir=HOME.'_www/'.$name;
if(is_dir($dir))
	remove_dir($dir) or error('Please check that you have allocated the correct permissions to the '.HOME.' directory.','Permissions Error');

die('ok');
?>