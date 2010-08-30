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

session_start();
session_destroy();

if(isset($_COOKIE['furasta']['email']) && isset($_COOKIE['furasta']['password'])){
	setcookie('furasta[email]','',time()-3600);
	setcookie('furasta[password]','',time()-3600);
}

header('location: index.php');

?>
