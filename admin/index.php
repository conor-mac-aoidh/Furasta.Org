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
 */

require 'header.php';

$jquery='
        $(".sort-container").sortable({
		placeholder:"ui-state-highlight",connectWith:
		".sort-container",stop:function(){
			$(".sort-container").each(function(){
				alert($(this).sortable("serialize"));
			});
		} });
	$(".sort-container").disableSelection();
	$(".collapse-button").click(function(){
		if($(this).html()=="+"){
			var el=$(this).parent().parent();
			var status=el.attr("id").split(":");
			el.attr("id",status[0]+":open")
			$(this).html("-");
		}
		else{
			var el=$(this).parent().parent();
                        var status=el.attr("id").split(":");
			el.attr("id",status[0]+":closed")
			$(this).html("+");
		}
		$(this).parent().siblings(".collapse-content").slideToggle("fast");
	});
';

$Template->add('jquery',$jquery);

$overview_items=array();

/**
 * START OVERVIEW ITEMS
 *
 * Website Overview Item
 */

$template=parse_template_file(TEMPLATE_DIR.'style.css');

$overview_items[1]['name']='Website Overview';
$overview_items[1]['id']='website-overview';
$overview_items[1]['content']='
<table class="row-color">
	<tr><td>Pages:</td><td>'.count(rows('select id from '.PAGES)).'</td></tr>
	<tr><td>Trash:</td><td>'.count(rows('select id from '.TRASH)).'</td></tr>
	<tr><td>Users:</td><td>'.count(rows('select id from '.USERS)).'</td></tr>
	<tr><td>Theme:</td><td>'.$template['Name'].'</td></tr>
	<tr><td>Furasta.Org Version:</td><td>'.VERSION.'</td></tr>
</table>';

/**
 * Recently Trashed Item
 */

$overview_items[2]['name']='Recently Trashed';
$overview_items[2]['id']='recently-trashed';

$item_content='<table class="row-color">';

$pages=rows('select id,name,content,edited from '.TRASH.' order by edited desc limit 5');
foreach($pages as $page)
	$item_content.='<tr><td><span>'.date("F j, Y",strtotime($page['edited'])).'
	</span><a href="pages.php?page=trash"><h3>'.$page['name'].'</h3></a>
	<p>'.strip_tags(substr($page['content'],0,65)).' [...]</p></td></tr>';

$item_content.='</table>';

$overview_items[2]['content']=$item_content;

/**
 * Recently Edited Item
 */

$overview_items[3]['name']='Recently Edited';
$overview_items[3]['id']='recently-edited';

$item_content='<table class="row-color">';

$pages=rows('select id,name,content,edited from '.PAGES.' order by edited desc limit 5');
foreach($pages as $page)
	$item_content.='<tr><td><span>'.date("F j,Y",strtotime($page['edited'])).'</span><a
	href="pages.php?page=edit&id='.$page['id'].'"><h3>'.$page['name'].'</h3></a>
	<p>'.strip_tags(substr($page['content'],0,65)).'[...]</p></td></tr>';

$item_content.='</table>';

$overview_items[3]['content']=$item_content;

/**
 * Furasta Development Blog
 */

$overview_items[4]['name']='Furasta Development Blog';
$overview_items[4]['id']='furasta-devblog';

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

$overview_items[4]['content']=$item_content;

/**
 * Plugin Overview Items
 */

$items=$Plugins->adminOverviewItems();
$num=4;

foreach($items as $item){
	$num++;
	$overview_item[$num]['name']=$item['name'];
	$overview_item[$num]['id']=$item['class_name'];
	$overview_item[$num]['content']=$item['content'];
}

/**
 * END OVERVIEW ITEMS
 */

$cache_file='FURASTA_OVERVIEW_ITEMS_'.$_SESSION['user']['id'];

if(cache_exists($cache_file,'OVERVIEW_ITEMS'))
	$order=json_decode(cache_get($cache_file,'OVERVIEW_ITEMS'));
/*else{
	$order=array(
		'1'=>array(
			'website-overview',
			'open'
		),
		'2'=>array(
			'recently-trashed',
			'open'
		),
		'3'=>array(
			'recently-edited',
			'open'
		)
		'4'=>
	);
//	cache($cache_file,json_encode($order),'OVERVIEW_ITEMS')
}**/

//$Template->add('content',$content);
$Template->add('title','- Overview');

require 'footer.php';
?>
