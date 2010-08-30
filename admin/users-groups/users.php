<?php

/**
 * Users Switch, Furasta.Org
 *
 * Switches between possible users pages.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_users
 */

$action=addslashes(@$_GET['action']);

if(isset($_POST))
        require 'post/users.php';

switch($action){
	case 'edit':
		require HOME.'admin/users-groups/users/edit.php';
	break;
	default:
		require HOME.'admin/users-groups/users/list.php';
}
?>
