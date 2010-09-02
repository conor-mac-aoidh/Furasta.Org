<?php

/**
 * Overview Page, Furasta.Org
 *
 * Displays an overview of the CMS with various
 * overview items. Allows for plugin access to
 * create overview items.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_overview
 */

require 'header.php';

$Template->loadJavascript('_inc/js/admin/overview.js');

/**
 * Require and proccess order of overview items
 */

$OverviewItems=new OverviewItems();

require HOME.'admin/overview/items.php';

$OverviewItems->order();
$content=$OverviewItems->displayItems();

$content.='<br style="clear:both"/>';

$Template->add('content',$content);
$Template->add('title','- Overview');

require 'footer.php';
?>
