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
        $cache_file=md5( 'FURASTA_FUNCTION_FRONTEND_MENU' );

        if(cache_is_good($cache_file,'120*60*12','PAGES'))
                $order=json_decode(cache_get($cache_file,'PAGES'));
        else{
		$pages=array();
		$query=query('select id,name,parent,slug,home from '.PAGES.' where display=1 order by position,name desc');

		while($row=mysql_fetch_assoc($query))
		        $pages[$row['parent']][]=$row;

		$order=frontend_list_pages(0,$pages,0,SITEURL);

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

/**
 * frontend_page_tree
 *
 * returns a page tree with default css formatting 
 * 
 * @access public
 * @return void
 */
function frontend_page_tree( $params ){

	global $SETTINGS;

	$page = @$_GET[ 'page' ];
        $array = explode( '/', $page );

        if( end( $array ) == '' )
                array_pop( $array );

	$seperator = isset( $params[ 'seperator' ] ) ? $params[ 'seperator' ] : ' > ';

	$content = '<ul id="page-tree" style="list-style-type:none">
			<li style="display:inline"><a href="' . SITEURL . '">' . $SETTINGS[ 'site_title' ] . '</a></li>';

	for( $i = 0; $i < count( $array ); $i++ ){

		$content .= '<li style="display:inline">' . $seperator . '<a href="' . SITEURL . $array[ $i ] . '">' . str_replace( '-', ' ', $array[ $i ] ) . '</a></li>';

	}

	$content .= '</ul>';

	return $content;

}

/**
 * frontend_css_load
 *
 * returns a url to the compressed css
 * passed in the file parameter 
 * 
 * smarty parameters:
 * bool refresh
 * string file
 *
 * example: {css_load file="path/to/file"}
 * 
 * @param array $params
 * @access public
 * @return url or bool
 */
function frontend_css_load( $params ){

	$cache_file = 'FURASTA_FRONTEND_CSS_' . $params[ 'file' ];

	/**
	 * if cache doesn't exist or refresh is enabled,
	 * create cache
	 */
	if( !cache_exists( md5( $cache_file ), 'CSS' ) || @$params[ 'refresh' ] == true ){

	        $file = HOME . $params[ 'file' ];

	        if( strpos( $file, '..' ) !== false )
        	        return false;

	        if( !file_exists( $file ) )
        	        return false;

        	$content = file_get_contents( $file );
		$Template = Template::getInstance( true );
		$Template->loadCSS( $cache_file, $content );
		$Template->cssUrl( );

	}

	$link = '<link rel="stylesheet" type="text/css" href="' . SITEURL . '_inc/css/css.php?' . md5( $cache_file ) . '" />';

	return $link;
}

/**
 * frontend_javascript_load
 * 
 * returns a url to the comprssed javascript
 * passed in the file parmeter 
 * 
 * smarty parameters:
 * bool refresh
 * string file
 *
 * example: {css_load file="path/to/file"}
 *
 * @param array $params 
 * @access public
 * @return bool or string
 */
function frontend_javascript_load( $params ){
        
        $cache_file = 'FURASTA_FRONTEND_JS_' . $params[ 'file' ];

        /**
         * if cache doesn't exist or refresh is enabled,
         * create cache
         */
        if( !cache_exists( md5( $cache_file ), 'JS' ) || @$params[ 'refresh' ] == true ){

                $file = HOME . $params[ 'file' ];

                if( strpos( $file, '..' ) !== false )
                        return false;
        
                if( !file_exists( $file ) )
                        return false;

                $content = file_get_contents( $file );
                $Template = Template::getInstance( true );
                $Template->loadJavascript( $cache_file, $content );
		$Template->javascriptUrls( );

        }

        $link = '<script type="text/javascript" src="' . SITEURL . '_inc/js/js.php?' . md5( $cache_file ) . '"></script>';

        return $link;
}

?>
