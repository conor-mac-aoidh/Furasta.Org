<?php

/**
 * User Files Access, Furasta.Org
 *
 * This file is loaded whenever a file contained in the _user
 * directory is accessed. It loads the file, even if the _user
 * dir is placed outside the web root.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

require '../_inc/header.php';

$name=@$_GET['name'];

if($name=='')
	error('You must select an item to view.','No File Selected');

if(file_exists($name)){
	$content=file_get_contents($name);
	echo $content;
}
else
	error('File does not exist.','404 - Not Found');

exit;

?>
