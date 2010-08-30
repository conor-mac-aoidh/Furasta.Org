<?php

/**
 * Admin Template, Furasta.Org
 *
 * This file contains the template for the admin area. Info is stored
 * in the $Template class during execution and then displayed here.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_temnplate
 */

header('Content-Type: text/html; charset=UTF-8');

echo'
<html>
<head>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js"></script>
	<noscript><meta http-equiv="refresh" content="0;url=/_inc/noscript.php"></noscript>
	<script type="text/javascript" src="/_inc/js/multi-ddm.jquery.min.js"></script>
	<script type="text/javascript" src="/_inc/js/treeTable.jquery.min.js"></script>
        <script type="text/javascript" src="/_inc/js/system.js"/></script>
	<script	type="text/javascript" src="/_inc/js/admin.js"/></script>
';//<script type="text/javascript" src="/_inc/js/all.php?files='.$Template->about('javascript').')"></script>
echo $Template->display('head');

$jquery=$Template->display('jquery');
if($jquery!=false)
	echo '<script type="text/javascript"> $(document).ready(function(){ '.$jquery.' }); </script>';

echo '
	<link rel="stylesheet" href="/_inc/css/admin.css"/>
	<title>Furasta.Org '.$Template->display('title').'</title>
</head>
<body>
<div id="dialog">&nbsp;</div>
	<div id="wrapper">
		<div id="top" class="system">
			<div id="top-wrapper">
	                        <div id="title"><a href="settings.php?page=general">'.$SETTINGS['site_title'].'</a><br/><span id="subtitle">'.$SETTINGS['site_subtitle'].'</span></div>
				<div id="right">&nbsp;</div>
			</div>
		</div>
		<div id="header">
			<div id="menu">
				<ul id="full-menu">
					<li><a href="../" id="title">&nbsp;</a></li>
					'.$Template->display('menu').'
					<li id="right-item"><a href="logout.php" id="logout">&nbsp;</a></li>
				</ul>
			</div>
		</div>
		<div id="container">
			<div id="container-right">
				<div id="main">';
				$error=$Template->display('system_error');
				if($error!='')
					echo '
					<div id="system_error">
                                                <img src="/_inc/img/"'.$error.'
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
		<div id="bottom">
                        <p class="right"><a href="settings.php?page=users&id='.$_SESSION['user']['id'].'">'.$_SESSION['user']['name'].'</a> - Status: '.$_SESSION['user']['user_group'].'</p>
			<p class="left">&copy; <a href="http://furasta.org">Furasta.Org</a> | <a href="http://furasta.org/Support">Support</a> | <a href="http://furasta.org/Feature-Requests">Feature Requests</a></p>
			<br style="clear:both"/>
		</div>
	</div>
</body>
</html>
';
?>
