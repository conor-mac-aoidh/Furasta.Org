<?php

/**
 * Check Connection, Furasta.Org
 *
 * Checks if database details are valid. Accessible
 * via AJAX.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package	   installer
 */

$hostname=addslashes(@$_POST['hostname']);
$username=addslashes(@$_POST['username']);
$database=addslashes(@$_POST['database']);
$password=addslashes(@$_POST['password']);

$connect=mysql_connect($hostname,$username,$password);
if(!$connect)
	die('host');
else{
	$select=mysql_select_db($database,$connect);
	if(!$select)
		die('database');
}

die('ok');

?>
