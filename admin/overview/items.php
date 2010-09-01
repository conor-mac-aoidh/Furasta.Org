<?php

/**
 * Overview Items, Furasta.Org
 *
 * Collects the overview items into an array.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_overview
 */

/**
 * START OVERVIEW ITEMS
 *
 * Website Overview Item
 */

$template=parse_template_file(TEMPLATE_DIR.'style.css');

$name='Website Overview';
$id='website-overview';
$item_content='
<table class="row-color">
	<tr><td>Pages:</td><td>'.count(rows('select id from '.PAGES)).'</td></tr>
	<tr><td>Trash:</td><td>'.count(rows('select id from '.TRASH)).'</td></tr>
	<tr><td>Users:</td><td>'.count(rows('select id from '.USERS)).'</td></tr>
	<tr><td>Theme:</td><td>'.$template['Name'].'</td></tr>
	<tr><td>Furasta.Org Version:</td><td>'.VERSION.'</td></tr>
</table>';

$OverviewItems->addItem($name,$id,$item_content);

/**
 * Recently Trashed Item
 */

$name='Recently Trashed';
$id='recently-trashed';

$item_content='<table class="row-color">';

$pages=rows('select id,name,content,edited from '.TRASH.' order by edited desc limit 5');
foreach($pages as $page)
	$item_content.='<tr><td><span>'.date("F j, Y",strtotime($page['edited'])).'
	</span><a href="pages.php?page=trash"><h3>'.$page['name'].'</h3></a>
	<p>'.strip_tags(substr($page['content'],0,125)).' [...]</p></td></tr>';

$item_content.='</table>';

$OverviewItems->addItem($name,$id,$item_content);

/**
 * Recently Edited Item
 */

$name='Recently Edited';
$id='recently-edited';

$item_content='<table class="row-color">';

$pages=rows('select id,name,content,edited from '.PAGES.' order by edited desc limit 5');
foreach($pages as $page)
	$item_content.='<tr><td><span>'.date("F j,Y",strtotime($page['edited'])).'</span><a
	href="pages.php?page=edit&id='.$page['id'].'"><h3>'.$page['name'].'</h3></a>
	<p>'.strip_tags(substr($page['content'],0,125)).' [...]</p></td></tr>';

$item_content.='</table>';

$OverviewItems->addItem($name,$id,$item_content);

/**
 * Furasta Development Blog
 */

$name='Furasta Development Blog';
$id='furasta-devblog';

$cache_file='FURASTA_RSS_DEVBLOG';

if(cache_is_good($cache_file,'60*60*24','RSS'))
        $items=json_decode(cache_get($cache_file,'RSS'),true);
else{
	if(file_exists(USERFILES.'cache/RSS/'.md5($cache_file))){
		$Template->add('jquery','fetch("'.SITEURL.'admin/overview/dev_blog.php?ajax=on");');
        	$items=json_decode(cache_get($cache_file,'RSS'),true);
	}
	else
		require HOME.'admin/overview/dev_blog.php';
}

$item_content='<table class="row-color">';

foreach($items as $item)
	$item_content.='<tr><td><span>'.$item['pubDate'].'</span><a
	href="'.$item['link'].'"><h3>'.$item['title'].'</h3></a><p>'.$item['description'].'</p></td></tr>';

$item_content.='</table>';

$OverviewItems->addItem($name,$id,$item_content);

/**
 * END OVERVIEW ITEMS
 */
?>
