<?php

/**
 * AJAX Access, Furasta.Org
 *
 * Designed as a method through which logged in users
 * can make AJAX requests through the CMS. This file
 * provides developers access to all main libraries that
 * the CMS provides, through AJAX, and then includes the
 * developers own script. It essentially provides a
 * working enviornment for AJAX calls.
 * 
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

if(isset($_GET['no_config'])){
	define('START_TIME',microtime(true));
	define('HOME',substr(dirname(__FILE__),0,-4));
}
else
	include 'define.php';

if(!isset($_SESSION['user_id']))
	die('Please login to access this content.');

$file=HOME.@$_GET['file'];

if(!file_exists($file))
	die('The file at <i>'.$file.'</i> does not exist.');

require $file;

exit;
?>
