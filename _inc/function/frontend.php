<?php

/**
 * Frontend Functions, Furasta.Org
 *
 * Contains functions which are loaded in
 * the frontend of the CMS only.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    frontend
 */

function frontend_menu(){
        $cache_file='FURASTA_FUNCTION_FRONTEND_MENU';

        if(cache_is_good($cache_file,'120*60*12','PAGES'))
                $order=json_decode(cache_get($cache_file,'PAGES'));
        else{
		$pages=array();
		$query=query('select id,name,parent,slug,home from '.PAGES.' where display=1 order by position,name desc');

		while($row=mysql_fetch_assoc($query))
		        $pages[$row['parent']][]=$row;

		$url='http://'.$_SERVER["SERVER_NAME"].'/';
		$order=frontend_list_pages(0,$pages,0,$url);

                cache($cache_file,json_encode($order),'PAGES');
        }
        return $order;
}

function frontend_list_pages($id,$pages,$level,$url){
        $num=0;
        if(!isset($pages[$id]))
                return;
        $list='<ul class="furasta-menu-'.$level.'">';
        foreach($pages[$id] as $page){
		$_url=$url.$page['slug'].'/';
		$home=($page['home']==1)?' homepage':'';
		$list.='<li class="level_'.$level.'" id="li_'.$page['slug'].'"><a href="'.$_url.'" class="link_level_'.$level.' menu_link'.$home.'" id="link_'.$page['slug'].'">'.$page['name'].'</a>';
                $list.=frontend_list_pages($page['id'],$pages,$level+1,$_url);
		$list.='</li>';
        }
        return $list.='</ul>';
}

?>
