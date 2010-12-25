<?php

/**
 * Logout, Furasta.Org
 *
 * Destorys session, and cookies if applicable.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

require '../_inc/class/User.php';

$User = User::getInstance( );

$User->logout( );
$User->destroyCookie( );

header('location: index.php');

?>
