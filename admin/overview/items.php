<?php

/**
 * Overview Items, Furasta.Org
 *
 * Accessed via AJAX, this file loads the content
 * of overview items.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_overview
 */

$overview_item = @$_GET['overview_item'];

if( $overview_item == '' )
	die( 'There has been an error loading the page.' );

/**
 * switch
 *
 * switches between the possible values of
 * $overview_item and echos the content of the
 * associated item. the default switch checks
 * plugins and if it exists in a plugin it also
 * echos the content. 
 */
switch( $overview_item ){

	case 'website-overview':
		require_once HOME . '_inc/function/admin.php';

		$template = parse_template_file(TEMPLATE_DIR.'style.css');

		echo '
		<table class="row-color">
		        <tr><td>Pages:</td><td>' . count( rows( 'select id from ' . PAGES ) ) . '</td></tr>
		        <tr><td>Trash:</td><td>' . count( rows( 'select id from ' . TRASH ) ) . '</td></tr>
		        <tr><td>Users:</td><td>' . count( rows( 'select id from ' . USERS ) ) . '</td></tr>
		        <tr><td>Theme:</td><td>' . $template[ 'Name' ] . '</td></tr>
		        <tr><td>Furasta.Org Version:</td><td>' . VERSION . '</td></tr>
		</table>';
	break;
	case 'recently-edited':
		echo '<table class="row-color">';

		$pages = rows( 'select id,name,content,edited from ' . TRASH . ' order by edited desc limit 5' );
		foreach( $pages as $page )
        		echo '<tr><td><span>' . date( "F j, Y", strtotime( $page[ 'edited' ] ) ) . '
        		</span><a href="pages.php?page=trash"><h3>' . $page[ 'name' ] . '</h3></a>
        		<p>' . strip_tags( substr( $page[ 'content' ], 0, 125 ) ) . ' [...]</p></td></tr>';

		echo '</table>';
	break;
	case 'recently-trashed':
		echo '<table class="row-color">';

		$pages = rows( 'select id,name,content,edited from ' . PAGES . ' order by edited desc limit 5' );
		foreach( $pages as $page )
        		echo '<tr><td><span>' . date( "F j,Y", strtotime( $page[ 'edited' ] ) ) . '</span><a
		        href="pages.php?page=edit&id=' . $page[ 'id' ] . '"><h3>' . $page[ 'name' ] . '</h3></a>
		        <p>' . strip_tags( substr( $page[ 'content' ], 0, 125 ) ) . ' [...]</p></td></tr>';

		echo '</table>';
	break;
	case 'furasta-devblog':
		$cache_file = 'FURASTA_RSS_DEVBLOG';

		if( cache_is_good( $cache_file, '60*60*24', 'RSS' ) )
        		$items = json_decode( cache_get( $cache_file, 'RSS' ), true );
		else{
			$elements = rss_fetch( 'http://blog.macaoidh.name/tag/furasta-org/feed/' );
			$items = array( );

			for( $i=0; $i<=2; $i++ ){
			        foreach( $elements[ $i ] as $element=>$value ){
		  	        	switch( $element ){
                        			case 'pubDate':
                                			$items[ $i ][ 'pubDate' ] = date( "F j, Y", strtotime( $value ) );
                        			break;
                        			case 'title':
                                			$items[ $i ][ 'title' ] = iconv( "UTF-8", "UTF-8//IGNORE", $value );
                        			break;
                        			case 'link':
                                			$items[ $i ][ 'link' ] = $value;
                        			break;
                        			case 'description':
                                			$items[ $i ][ 'description' ] = substr( $value, 0, 125) . ' [...]';
                        			break;
                			}
        			}
			}

			cache( 'FURASTA_RSS_DEVBLOG ', json_encode( $items ), 'RSS' );
		}

		echo '<table class="row-color">';

		foreach( $items as $item )
		        echo '<tr><td><span>' . $item[ 'pubDate' ] . '</span><a
		        href="' .$item[ 'link' ] . '"><h3>' . $item[ 'title' ] . '</h3></a><p>' . $item[ 'description' ] . '</p></td></tr>';

		echo '</table>';
	break;
	default:
		echo $Plugins->adminOverviewItemContent( $overview_item );
}
?>
