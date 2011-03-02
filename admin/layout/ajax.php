<?php

/**
 * AJAX Template, Furasta.Org
 *
 * This file contains displays pages being fetched by AJAX
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package	admin_template
 */

ob_start('ob_gzhandler');
header('Content-Type: text/html; charset=UTF-8');

foreach( $Template->cssUrls( ) as $url )
        echo '<link rel="stylesheet" href="' . $url . '"/>';

echo '
<div id="ajax-errors" style="display:none">' . $Template->displayErrors( ) . '</div>

' . $Template->display('content') . '</body>';

foreach( $Template->javascriptUrls( ) as $url )
        echo '<script type="text/javascript" src="' . $url . '"></script>';

$javascript = $Template->display( 'javascript' );

if( $javascript != '' )
	echo '<script type="text/javascript">' . $javascript . '</script>';

ob_end_flush();
?>
