<?php

/**
 * Groups Switch, Furasta.Org
 *
 * Switches between possible groups pages.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_users
 */

$action=addslashes(@$_GET['action']);

switch($action){
	case 'edit':
		require HOME.'admin/users-groups/groups/edit.php';
	break;
	default:
		require HOME.'admin/users-groups/groups/list.php';
}
?>
