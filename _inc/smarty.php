<?php

/**
 * Smarty Configuration File, Furasta.Org
 *
 * Loads the Smarty Template Engine and asigns
 * values to all of the template variables.
 * Assigns functions, then plugin functions and
 * displays the correct template file.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

header('Content-Type: text/html; charset=UTF-8');

/**
 * @todo move to _user dir 
 */
$smarty_dir=HOME.'_inc/smarty/';

require $smarty_dir.'libs/smarty.class.php';
require $function_dir.'frontend.php';

$Smarty=new Smarty();

$Smarty->template_dir=$smarty_dir.'templates';
$Smarty->compile_dir=$smarty_dir.'templates_c';

/**
 * register plugin functions 
 */
$plugin_functions=$Plugins->frontendTemplateFunctions();
foreach($plugin_functions as $function)
	$Smarty->register_function($function->frontendTemplateFunction,array($function,'frontendTemplateFunction'));

/**
 * assign values to Smarty variables 
 */
$keywords = meta_keywords( $Page[ 'content' ] );
$description = substr( strip_tags( $Page[ 'content' ] ), 0, 250 ) . '...';

$meta='
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="' . SITEURL . '_inc/js/jquery/multi-ddm.min.js"></script>
<script type="text/javascript" src="' . SITEURL . '_inc/js/frontend.js"></script>
<link rel="stylesheet" type="text/css" href="' . SITEURL . '_inc/css/frontend.css"/>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="generator" content="Furasta.Org ' . VERSION . '" />
<meta name="description" content="' . $description . '" />
<meta name="keywords" content="' . $keywords . '" />
<link rel="shortcut icon" href="' . SITEURL . '_inc/img/favicon.ico" />
';
$Smarty->assign( 'metadata', $meta );

/**
 * plugins - filter page content 
 */
$content = $Plugins->filter( 'frontend', 'filter_page_content', $Page[ 'content' ] );

/**
 * assign content according to page type 
 */
$type = $Page[ 'type' ];

if( $type == 'Normal' )
	$Smarty->assign( 'page_content', $content );
else
	$Smarty->assign( 'page_content', $Plugins->frontendPageType( $type, $Page ) );

/**
 * assign other page vars 
 */
$Smarty->assign( 'page_name', $Page[ 'name' ] );
$Smarty->assign( 'page_id', $Page[ 'id' ] );
$Smarty->assign( 'page_slug', $Page[ 'slug' ] );
$Smarty->assign( 'page_edited', $Page[ 'edited' ] );
$Smarty->assign( 'page_user', $Page[ 'user' ] );
$Smarty->assign( 'page_parent_id', $Page[ 'parent' ] );

$Smarty->assign( 'site_url', SITEURL );

$time=microtime(true)-START_TIME;
$Smarty->assign('page_load_time',$time);

$Smarty->assign('site_title',$SETTINGS['site_title']);
$Smarty->assign('site_subtitle',$SETTINGS['site_subtitle']);

/**
 * register default template functions 
 */
$Smarty->register_function( 'menu', 'frontend_menu' );
$Smarty->register_function( 'page_tree', 'frontend_page_tree' );
$Smarty->register_function( 'css_load', 'frontend_css_load' );
$Smarty->register_function( 'javascript_load', 'frontend_javascript_load' );
$Smarty->register_function( 'page_tree', 'frontend_page_tree' );

$file = ( $Page[ 'template' ] == 'Default' ) ? TEMPLATE_DIR . 'index.html' : TEMPLATE_DIR . $Page[ 'template' ] . '.html';

if( !file_exists( $file ) )
        error( 'Template files could not be found.', 'Template Error' );

$Smarty->display( $file );
?>
