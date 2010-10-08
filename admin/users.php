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

require 'header.php';

$page=addslashes(@$_GET['page']);

switch($page){
	case 'groups':
		require HOME.'admin/users/groups.php';
	break;
	case 'edit-group':
		require HOME.'admin/users/edit-group.php';
	break;
	case 'edit-users':
		require HOME.'admin/users/edit-users.php';
	break;
	default:
		require HOME.'admin/users/users.php';
}

require 'footer.php';

?>
