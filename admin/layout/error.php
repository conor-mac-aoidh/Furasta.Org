<?php

/**
 * Error Template, Furasta.Org
 *
 * This file contains the template for the all erros the CMS generates.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package	   admin_template
 */

ob_start('ob_gzhandler');
header('Content-Type: text/html; charset=UTF-8');

$Template->loadCSS('_inc/css/admin.css');

echo'
<html>
<head>
        <noscript><meta http-equiv="refresh" content="0;url=' . SITEURL . '_inc/noscript.php"></noscript>';

foreach( $Template->cssUrls( ) as $url )
        echo '<link rel="stylesheet" href="' . $url . '"/>';

echo '
        <title>' . $Template->display( 'title' ) . ' - Furasta.Org</title>

        ' . $Template->display( 'head' ) . '
</head>
<body>
<div id="dialog">&nbsp;</div>
<div id="wrapper">
	<div id="top">
		<div id="right-error">&nbsp;</div>
	</div>
	<div id="header">
		<div id="menu">
			<ul>
				<li><a href="../" id="title">&nbsp;</a></li>
			</ul>
		</div>
	</div>
	<div id="container">
		<div id="container-right">
			<div id="main">
					' . $Template->displayErrors( ) . '
					<div id="right">
						'.$Template->display('content').'
					</div>
			</div>
		</div>
		<div id="footer">
			<img src="' . SITEURL . '_inc/img/footer-right.png" style="float:right"/>
			<img src="' . SITEURL . '_inc/img/footer-left.png"/>
		</div>
	</div>
</div>
</body>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js"></script>
';

foreach( $Template->javascriptUrls( ) as $url )
        echo '<script type="text/javascript" src="' . $url . '"></script>';

echo '
<script type="text/javascript">
' . $Template->display( 'javascript' ) . '
</script>
</html>';

ob_end_flush();
?>
