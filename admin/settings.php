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

/**
 * check if user can view page 
 */
if( $_SESSION[ 'user' ][ 'perm' ][ 1 ] == '0' )
        error( 'You have insufficient privelages to view this page. Please contact one of the administrators.', 'Permissions Error' );

switch($page){
	case 'template':
		$Template->add( 'title', 'Template' );
		require 'settings/template.php';
	break;
	case 'plugins':
		$Template->add( 'title', 'Plugins' );
		require 'settings/plugins.php';
	break;
	case 'update':
		$Template->add( 'title', 'Update' );
		require 'settings/update.php';
	break;
	default:
		$Template->add( 'title', 'Configuration' );
		require 'settings/configuration.php';
}

require 'footer.php';

?>
