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
		$Template->add('title','- New Page');
		require 'pages/new.php';
	break;
	case 'edit':
		$Template->add('title','- Edit Page');
		require 'pages/edit.php';
	break;
	case 'trash':
		$Template->add('title','- Trash');
		require 'pages/trash.php';
	break;
	default:
		if( @$_GET[ 'action' ] == 'multiple' )
			require 'pages/multiple.php';
		else{
			$Template->add('title','- List Pages');
			require 'pages/list.php';
		}
}

require 'footer.php';
?>
