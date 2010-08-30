<?php

/**
 * Settings Switch, Furasta.Org
 *
 * Switches between possible settings pages.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

require 'header.php';

$page=addslashes(@$_GET['page']);

if(isset($_POST))
	require 'post/settings.php';

switch($page){
	case 'template':
		require 'settings/template.php';
	break;
	case 'plugins':
		require 'settings/plugins.php';
	break;
	case 'update':
		require 'settings/update.php';
	break;
	default:
		require 'settings/configuration.php';
}

require 'footer.php';

?>
