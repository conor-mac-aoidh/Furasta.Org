<?php

/**
 * Update Switch, Furasta.Org
 *
 * Switches between pages in the update section.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

$action=@$_GET['action'];

switch($action){
	case 'manual':
		require HOME.'admin/settings/update/manual.php';
	break;
	case 'verify-manual':
		require HOME.'admin/settings/update/verify-manual.php';
	break;
	case 'verify-auto':
		require HOME . 'admin/settings/update/verify-auto.php';
	break;
	default:
		require HOME.'admin/settings/update/auto.php';
}

?>
