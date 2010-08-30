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

if(isset($_POST))
        require 'post/users.php';

switch($page){
	case 'groups':
		require HOME.'admin/users-groups/groups.php';
	break;
	default:
		require HOME.'admin/users-groups/users.php';
}

require 'footer.php';

?>
