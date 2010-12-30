<?php

/**
 * Page Type, Furasta.Org
 *
 * Accessed via AJAX, this page returns a page type which
 * is requested by the user, or suitable for this page. It
 * allows for page types to be created by plugins.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_pages
 */

/**
 * make sure ajax script was loaded and user is
 * logged in 
 */
if( !defined( 'AJAX_LOADED' ) || !defined( 'AJAX_VERIFIED' ) )
        die( );

/**
 * set up $_GET variables 
 */
$type = @$_GET[ 'type' ];
$id = (int) @$_GET[ 'id' ];

if( $type == 'Normal' ){
	if( $id != 0 )
		$content = stripslashes( single( 'select content from ' . PAGES . ' where id= ' . $id, 'content' ) );

		echo '
			<script type="text/javascript" src="/_inc/js/tiny_mce.js"></script>
			<textarea id="page-content" name="PageContent" class="tinymce" style="width:100%">' . @$content . '</textarea>
		';
}
else
        echo $Plugins->adminPageType( $type, $id );

?>
