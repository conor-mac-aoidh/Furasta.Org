<?php

/**
 * Activate User, Furasta.Org
 *
 * The activation link that is sent to the user is directed here
 * where the hash is verified, and the database updated.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_users
 */

require '../../_inc/define.php';

$hash=addslashes(@$_GET['hash']);

if($hash=='')
	die();

$details=rows('select name from '.USERS.' where hash="'.$hash.'"');

if(count($details)!=1)
	error('The activation hash provied has not matched any users. Please contact bugs@macaoidh.name for further details.','Activation Failure');

query('update '.USERS.' set hash="activated" where hash="'.$hash.'"');

error($details[0]['name'].', your account has been activated successfully. Please continue to the <a href="/admin/index.php">login area.</a>','User Activated');
?>