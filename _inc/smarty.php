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

$smarty_dir=HOME.'_inc/Smarty/';

require $smarty_dir.'libs/Smarty.class.php';
require $function_dir.'frontend.php';

$smarty=new Smarty();

$smarty->template_dir=$smarty_dir.'templates';
$smarty->compile_dir=$smarty_dir.'templates_c';

/**
 * register plugin functions 
 */
$plugin_functions=$Plugins->frontendTemplateFunctions();
foreach($plugin_functions as $function)
	$smarty->register_function($function->frontendTemplateFunction,array($function,'frontendTemplateFunction'));

/**
 * assign values to smarty variables 
 */

$meta='
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="' . SITEURL . '_inc/js/jquery/multi-ddm.min.js"></script>
<script type="text/javascript" src="' . SITEURL . '_inc/js/frontend.js"></script>
<link rel="stylesheet" type="text/css" href="' . SITEURL . '_inc/css/frontend.css"/>
';
$smarty->assign('metadata',$meta);

/**
 * plugins - filter page content 
 */
$content = $Plugins->filter( 'frontend', 'filter_page_content', stripslashes( $Page[ 'content' ] ) );

/**
 * assign content according to page type 
 */
$type = $Page[ 'type' ];

if( $type == 'Normal' )
	$smarty->assign( 'page_content', $content );
else
	$smarty->assign( 'page_content', $Plugins->frontendPageType( $type, $Page ) );

/**
 * assign other page vars 
 */
$smarty->assign( 'page_name', $Page[ 'name' ] );
$smarty->assign( 'page_id', $Page[ 'id' ] );
$smarty->assign( 'page_slug', $Page[ 'slug' ] );
$smarty->assign( 'page_edited', $Page[ 'edited' ] );
$smarty->assign( 'page_user', $Page[ 'user' ] );
$smarty->assign( 'page_parent_id', $Page[ 'parent' ] );

$smarty->assign( 'siteurl', SITEURL );

$time=microtime(true)-START_TIME;
$smarty->assign('page_load_time',$time);

$smarty->assign('site_title',$SETTINGS['site_title']);
$smarty->assign('site_subtitle',$SETTINGS['site_subtitle']);

/**
 * register default template functions 
 */
$smarty->register_function( 'menu', 'frontend_menu' );
$smarty->register_function( 'page_tree', 'frontend_page_tree' );
$smarty->register_function( 'css_url', 'frontend_css_url' );


$file=($Page[ 'template' ]=='Default')?TEMPLATE_DIR.'index.html':TEMPLATE_DIR.$Page[ 'template' ].'.html';

if(!file_exists($file))
        error( 'Template files could not be found.', 'Template Error' );

/**
 * execute onload plugin functions
 */
$Plugins->hook( 'frontend', 'on_load' );

$smarty->display($file);
?>
