<?php

/**
 * Sitemap Generator, Furasta.Org
 *
 * Generates sitemaps when the /sitemap.xml file is accessed.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

require 'define.php';

header('Content-type: text/xml');

$pages=rows('select id,slug,edited,parent from '.PAGES);

$_url='http://'.$_SERVER["SERVER_NAME"].'/';
$sitemap="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset>\n";
foreach($pages as $page){                
	$url=($page['parent']==0)?$_url.$page['slug']:$_url.implode('/',array_reverse(explode(',',get_page_url($pages,$page['parent'])))).'/'.$page['slug'];
	$sitemap.="	<url>\n".
		"		<loc>".$url."</loc>\n".
		"		<lastmod>".$page['edited']."</lastmod>\n".
		"	</url>\n";
}
$sitemap.="</urlset>";

$sitemap=$Plugins->about('sitemap',$sitemap);

echo $sitemap;
?>