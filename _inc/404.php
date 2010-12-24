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

$Plugins->frontendOnLoad( );

$smarty_dir=HOME.'_inc/Smarty/';

require $smarty_dir.'libs/Smarty.class.php';
require $function_dir.'frontend.php';

$smarty=new Smarty();

$smarty->template_dir=$smarty_dir.'templates';
$smarty->compile_dir=$smarty_dir.'templates_c';

$meta='
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="' . SITEURL . '_inc/js/jquery/multi-ddm.min.js"></script>
<script type="text/javascript" src="' . SITEURL . '_inc/js/frontend.js"></script>
<link rel="stylesheet" type="text/css" href="' . SITEURL . '_inc/css/frontend.css"/>
';

$plugin_functions=$Plugins->frontendTemplateFunctions();
foreach($plugin_functions as $function)
	$smarty->register_function($function->frontendTemplateFunction,array($function,'frontendTemplateFunction'));

$smarty->assign('metadata',$meta);

$content = '
<h2>404 - Page Not Found</h2>
<p>The requested URL was not found.</p>
<p>The page could have been removed or the URL may be incorrect.</p>
<p><a href="' . SITEURL . '">Return to site index</a></p>
';

$smarty->assign('page_content',$content);

$smarty->assign('page_name', '404 - Page Not Found');
$smarty->assign('page_id','0');
$smarty->assign('page_slug','');
$smarty->assign('page_edited','');
$smarty->assign('page_user','');
$smarty->assign('page_parent_id','');

$smarty->assign( 'siteurl', SITEURL );

$time=microtime(true)-START_TIME;
$smarty->assign('page_load_time',$time);

$smarty->assign('site_title',$SETTINGS['site_title']);
$smarty->assign('site_subtitle',$SETTINGS['site_subtitle']);

$smarty->register_function('menu','frontend_menu');

if(!file_exists(TEMPLATE_DIR.'index.html'))
	error( 'Template files could not be found.', 'Template Error' );

if(file_exists(TEMPLATE_DIR.'404.html'))
	$smarty->display(TEMPLATE_DIR.'404.html');
else
	$smarty->display(TEMPLATE_DIR.'index.html');

exit;
?>
