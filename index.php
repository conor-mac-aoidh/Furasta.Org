<?php

/**
 * Index File, Furasta.Org
 *
 * This is the first file loaded in the forntend of the CMS. It
 * figures out what page has been accessed and then includes
 * a file to deal with the Smarty Template Engine.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_overview
 */

require '_inc/define.php';

/**
 * if mantinence mode is enabled and user not
 * logged in then display mantinence error
 * @todo enable custom mantinence error and option to use
 * mantinence template 
 */
$User = new User( );
if( $SETTINGS[ 'maintenance' ] == 1 && $User->verify( ) == false )
	error($SETTINGS['site_title'].' is undergoing maintenance. It should be back to normal shortly.','Maintenance');

$page=@$_GET['page'];

if( $page == '' )
	$id = single( 'select id from ' . PAGES . ' where home=1 limit 1', 'id' );
else{
        $array=explode('/',$page);
        if(end($array)=='')
                array_pop($array);
        $slug=addslashes(end($array));
	$id=single('select id from '.PAGES.' where slug="'.$slug.'"','id' );
	if($id==false)
		require HOME . '_inc/404.php';
}

/**
 * get page rows in array and stripslashes 
 */
$Page = row( 'select * from ' . PAGES . ' where id=' . $id, true );
$Page = stripslashes_array( $Page );

//$perm = explode( '|', $Page[ 'perm' ] );
//if( !$User->pagePerm( $perm[ 0 ] ) )
//	die( );
//	header( 'location: ' . SITEURL . 'admin/index.php?redirect=' . $_SERVER[ 'REQUEST_URI' ] );

/**
 * execute onload plugin functions
 */
$Plugins->hook( 'frontend', 'on_load' );

require HOME.'_inc/smarty.php';
?>
