<?php

/**
 * Pages Switch, Furasta.Org
 *
 * Switches between possible pages.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_pages
 */

require 'header.php';

$page=addslashes(@$_GET['page']);

switch($page){
	case 'new':
		/**
		 * check if user has permission to create new page
		 */
		if( !$User->hasPerm( 'c' ) )
		        error( 'You have insufficient privelages to edit this page. Please contact one of the administrators.', 'Permissions Error' );

		$Template->add('title','New Page');
		require 'pages/new.php';
	break;
	case 'edit':
		/**
		 * permissions aren't delt with here, but in edit.php
		 * because while a user's group may not have permission
		 * to edit pages, the user still may have access to edit
		 * specific pages 
		 */

		$Template->add('title','Edit Page');
		require 'pages/edit.php';
	break;
	case 'trash':
		/**
		 * check if user has permission to access trash
		 */
                if( !$User->hasPerm( 't' ) )
                        error( 'You have insufficient privelages to edit this page. Please contact one of the administrators.', 'Permissions Error' );
		
		$Template->add('title','Trash');
		require 'pages/trash.php';
	break;
	default:
		if( @$_GET[ 'action' ] == 'multiple' )
			require 'pages/multiple.php';
		else{
			$Template->add('title','List Pages');
			require 'pages/list.php';
		}
}

require 'footer.php';
?>
