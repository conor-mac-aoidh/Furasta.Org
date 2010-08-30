<?php

/**
 * Dev Blog, Furasta.Org
 *
 * This page manages the retrieval of articals from the Furasta.Org
 * dev blog. In order to prevent a long wait while the RSS is fetched,
 * this page is only executed procedurally once, then it is accessed
 * via AJAX.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_overview
 */


$ajax=@$_GET['ajax'];

if($ajax=='on')
	require '../../_inc/define.php';

$elements=rss_fetch('http://blog.macaoidh.name/tag/furasta-org/feed/');
$items=array();

for($i=0;$i<=2;$i++){
	foreach($elements[$i] as $element=>$value){
		switch($element){
			case 'pubDate':
				$items[$i]['pubDate']=date("F j, Y",strtotime($value));
			break;
			case 'title':
				$items[$i]['title']=iconv("UTF-8","UTF-8//IGNORE",$value);
			break;
			case 'link':
				$items[$i]['link']=$value;
			break;
			case 'description':
				$items[$i]['description']=substr($value,0,125).' [...]';
			break;
		}
	}
}

cache('FURASTA_RSS_DEVBLOG',json_encode($items),'RSS');
?>