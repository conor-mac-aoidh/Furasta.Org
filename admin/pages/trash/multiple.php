<?php

/**
 * Trash Multiple Action, Furasta.Org
 *
 * Performs multiple actions on trash pages such as
 * restore and delete.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_pages
 */

$function=@$_GET['act'];
$boxes=@$_GET['boxes'];
$boxes=explode(',',$boxes);

switch($function){
	case 'Restore':
		foreach($boxes as $id){
			query('insert into '.PAGES.' select NULL,name,content,slug,"Default","Normal",edited,user,position,"0",perm,"0",display from '.TRASH.' where id='.$id,true);
			query('delete from '.TRASH.' where id='.$id,true);
		}
		cache_clear('PAGES');

		$error = '16';
	break;
	case 'Delete':
		foreach($boxes as $id)
                        query('delete from '.TRASH.' where id='.$id,true);

		$error = '17';
	break;
	default:
		error('Please contact bugs@macaoidh.name','Unknown Error');
}

header('location: pages.php?page=trash&error=' . $error);
?>
