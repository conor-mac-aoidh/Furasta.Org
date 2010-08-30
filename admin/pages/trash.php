<?php

/**
 * Trash Page, Furasta.Org
 *
 * Switches between pages that can be accessed in the Trash section.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_pages
 */

$action=@$_GET['action'];

switch($action){
        case 'multiple':
                require HOME.'admin/pages/trash/multiple.php';
        break;
	default:
                require HOME.'admin/pages/trash/list.php';
}

?>
