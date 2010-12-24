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

/**
 * check if user can view page 
 */
if( $_SESSION[ 'user' ][ 'perm' ][ 0 ] == '0' )
	error( 'You have insufficient privelages to view this page. Please contact one of the administrators.', 'Permissions Error' ); 

$page = addslashes( @$_GET[ 'page' ] );

switch( $page ){
	case 'groups':
                $Template->add( 'title', 'Groups' );
		require HOME . 'admin/users/groups.php';
	break;
	case 'edit-group':
                $Template->add( 'title', 'Edit Group' );
		require HOME . 'admin/users/edit-group.php';
	break;
	case 'edit-users':
                $Template->add('title','Edit User');
		require HOME . 'admin/users/edit-users.php';
	break;
	default:
                $Template->add( 'title', 'Users' );
		require HOME . 'admin/users/users.php';
}

require 'footer.php';

?>
