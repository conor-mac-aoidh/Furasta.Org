<?php

/**
 * Error Template, Furasta.Org
 *
 * This file contains the template for the all erros the CMS generates.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package	   admin_temnplate
 */

ob_start('ob_gzhandler');
header('Content-Type: text/html; charset=UTF-8');

$Template->loadCSS('_inc/css/admin.css');

echo'
<html>
<head>
        <noscript><meta http-equiv="refresh" content="0;url=/_inc/noscript.php"></noscript>
        <link rel="stylesheet" href="'.$Template->cssUrl().'"/>
	<title>'.$Template->display('title').'</title>
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
			<div id="main">';
			$error=$Template->display('systemError');
			if($error!='')
				echo '
                                        <div id="system-error">
						<img
						src="/_inc/img/alert-logo.png"
						style="float:left"/><p>'.$error.'</p>
						<br style="clear:both"/>
					</div>';
echo '
					<div id="right">
						'.$Template->display('content').'
					</div>
			</div>
		</div>
		<div id="footer">
			<img src="/_inc/img/footer-right.png" style="float:right"/>
			<img src="/_inc/img/footer-left.png"/>
		</div>
	</div>
</div>
</body>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js"></script>
	<script type="text/javascript" src="'.$Template->javascriptUrl().'"></script>
</html>
';

ob_end_flush();
?>
