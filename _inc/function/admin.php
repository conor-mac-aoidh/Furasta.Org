<?php 

/**
 * Admin Functions, Furasta.Org
 *
 * Contains admin functions which are available
 * in the admin area only.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

/**
 * parse_template_file 
 * 
 * Parses the template file indicated, and returns
 * an array of details about the template, depending
 * on what keys are present in the template's style.css
 *
 * Possible array keys:
 * 
 * Name
 * Description
 * Author
 * URL
 * Author URL
 *
 * @param mixed $file to be parsed
 * @access public
 * @return array
 */
function parse_template_file($file){
	$contents=file_get_contents($file);
	if(!preg_match("/\/\*(.*?)\*\//s",$contents,$match))
		return false;
	$vals=preg_split("/[\s]*[\n][\s]*/",$match[0]);
	array_pop($vals);
	array_shift($vals);

	$array=array();
	foreach($vals as $val){
		$val=preg_split("/[\s]*[:] [\s]*/",$val);
		$array[$val[0]]=$val[1];
	}

	return $array;
}

/**
 * verify_user 
 * 
 * Checks if user $id is present in the database
 *
 * @param mixed $id of user
 * @access public
 * @return string
 */
function verify_user($id){
	$query=mysql_query('select name from '.USERS.' where id='.addslashes($id));
	return $query;
}

/**
 * list_parents 
 * 
 * orderes the parent select box in order of parents
 * with indentation
 *
 * @param int $id 
 * @param array $pages 
 * @param int $level 
 * @param mixed $default 
 * @access public
 * @return void
 */
function list_parents($id,$pages,$level,$default){
        $num=0;
        if(!isset($pages[$id]))
                return;
        $list='';
	$margin=$level*10;
        foreach($pages[$id] as $page){
		$def=($default!=0&&$page['id']==$default)?' selected="selected"':'';
		$list.='<option value="'.$page['id'].'"'.$def.' style="margin-left:'.$margin.'px;" class="' . $page['parent'] . '">'.$page['name'].'</option>';;
                $list.=list_parents($page['id'],$pages,$level+1,$default);
        }
        return $list;
}

function get_page_url($pages,$id){
	$url='';
	foreach($pages as $page){
		if($page['id']==$id){
			$url.=$page['slug'];
			if($page['parent']!=0)
				$url.=','.get_page_url($pages,$page['parent']);
			break;
		}
	}
	return $url;
}

function list_pages( $id, $pages, $level=0 ){

        $num = 0;

        if( !isset( $pages[ $id ] ) )
                return;

        $list='';
        foreach( $pages[ $id ] as $page ){
                $num++;
                $href = '<a href="pages.php?page=edit&id='.$page['id'].'" class="list-link">';
                $class = ( $level == 0 ) ? ' ':' class="child-of-node-'.$page['parent'].'"';
                $list .= '<tr id="node-'.$page['id'].'"'.$class.'>
                        	<td class="pages-table-left"><input type="checkbox" value="' . $page[ 'id' ] . '" name="trash-box"/></td>
                                <td class="first">'.$href.$page['name'].'</a></td>
                                <td>'.$href.$page['user'].'</a></td>
                                <td>'.$href.$page['type'].'</a></td>
                                <td>' . $href. date( "d/m/y", strtotime( $page[ 'edited' ] ) ) . '</a></td>
                                <td><a href="pages.php?page=new&parent='.$page['id'].'"><img src="/_inc/img/new-page-small.png" title="New Sub Page" alt="New Sub Page"/></a></td>
	                        <td><a href="#" id="' . $page[ 'id' ] . '" class="delete"><img src="/_inc/img/trash-small.png" title="Delete Page" alt="Delete Page"/></a></td>
                        </tr>';

                $list.=list_pages($page['id'],$pages,$level + 1);
        }

        return $list;
}

function display_menu($menu_items){
	$list='';
	foreach($menu_items as $item){
		$name=reset(array_keys($menu_items,$item));
		$r_item=str_replace(' ','-',$name);
		$list.='<li><a href="'.$item['url'].'" id="'.$r_item.'">'.$name.'</a>';
		if(isset($item['submenu'])){
			$list.='<ul>';
			foreach($item['submenu'] as $ite){
				$name=reset(array_keys($item['submenu'],$ite));
		                $r_item=str_replace(' ','-',$name);
                		$list.='<li><a href="'.$ite['url'].'" id="'.$r_item.'"><span id="'.$r_item.'-img" class="admin-menu-img">&nbsp;</span><span class="admin-menu-link">'.$name.'</span></a></li>';

			}
			$list.='</ul>';
		}
		$list.='</li>';

	}
	return $list;
}

?>
