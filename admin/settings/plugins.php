<?php

/**
 * Plugins Switch, Furasta.Org
 *
 * Switches between plugin pages and determines which
 * one is to be loaded.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

$action=@$_GET['action'];

switch($action){
	case 'deactivate':
		require HOME.'admin/settings/plugins/deactivate.php';
	break;
        case 'activate':
                require HOME.'admin/settings/plugins/activate.php';
        break;
        case 'delete':
                require HOME.'admin/settings/plugins/delete.php';
        break;
	case 'multiple':
                require HOME.'admin/settings/plugins/multiple.php';
        break;
	default:
		require HOME.'admin/settings/plugins/list.php';
}

?>
