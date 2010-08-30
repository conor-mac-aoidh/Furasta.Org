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

$meta='
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="/_inc/js/multi-ddm.jquery.min.js"></script>
<script type="text/javascript" src="/_inc/js/frontend.js"></script>
<link rel="stylesheet" type="text/css" href="/_inc/css/frontend.css"/>
';

$plugin_functions=$Plugins->frontendTemplateFunctions();
foreach($plugin_functions as $function)
	$smarty->register_function($function->frontendTemplateFunction,array($function,'frontendTemplateFunction'));

$smarty->assign('metadata',$meta);

$type=$Page->about('type');
if($type=='Normal')
	$smarty->assign('page_content',stripslashes($Page->about('content')));
else
	$smarty->assign('page_content',$Plugins->frontendPageType($type,$Page));

$smarty->assign('page_name',$Page->about('name'));
$smarty->assign('page_id',$Page->about('id'));
$smarty->assign('page_slug',$Page->about('slug'));
$smarty->assign('page_edited',$Page->about('edited'));
$smarty->assign('page_user',$Page->about('user'));
$smarty->assign('page_parent_id',$Page->about('parent'));


$time=microtime(true)-START_TIME;
$smarty->assign('page_load_time',$time);

$smarty->assign('site_title',$SETTINGS['site_title']);
$smarty->assign('site_subtitle',$SETTINGS['site_subtitle']);

$smarty->register_function('menu','frontend_menu');

$file=($Page->about('template')=='Default')?TEMPLATE_DIR.'index.html':TEMPLATE_DIR.$Page->about('template').'.html';
if(!file_exists($file))
	$file=HOME.'_www/.default/index.html';

$smarty->display($file);
?>
