<?php

/**
 * Activate Template, Furasta.Org
 *
 * Activates a template.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

require '../../../_inc/define.php';

$User = new User ( );

/**
 * if user not logged in die 
 */
if( !$User->verify( ) )
	die( );

/**
 * check if user has permission to activate template
 */
if( !$User->hasPerm( 's' ) )
        error( 'You have insufficient privelages to view this page. Please contact one of the administrators.', 'Permissions Error' );

$name=@$_GET['name'];
if($name=='')
        exit;

if(!file_exists(HOME.'_www/'.$name.'/index.html'))
        error('Incomplete template files.','Template Error');

settings_rewrite($SETTINGS,$DB,$PLUGINS,HOME.'_www/'.$name.'/');

header( 'location: ' . SITEURL . 'admin/settings.php?page=template' );
?>
