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

/**
 * check if user has permission to view page 
 */
if( $_SESSION[ 'user' ][ 'perm' ][ 2 ] == '0' )
        error( 'You have insufficient privelages to view this page. Please contact one of the administrators.', 'Permissions Error' );

$p_name=str_replace('-',' ',@$_GET['p_name']);

if($p_name=='')
	error('Undefined plugin error, please de-activate recently activated plugins to resolve the problem.','Plugin Error');

$Plugins->adminPage( $p_name );

require 'footer.php';
?>
