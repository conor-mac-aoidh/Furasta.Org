<?php

/**
 * Plugins Admin Page, Furasta.Org
 *
 * Allows plugins to create admin pages.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    plugin_architecture
 */

require 'header.php';

$p_name=str_replace('-',' ',@$_GET['p_name']);

if($p_name=='')
	error('Undefined plugin error, please de-activate recently activated plugins to resolve the problem.','Plugin Error');

$Template->add('content',$Plugins->adminPage($p_name));

require 'footer.php';
?>