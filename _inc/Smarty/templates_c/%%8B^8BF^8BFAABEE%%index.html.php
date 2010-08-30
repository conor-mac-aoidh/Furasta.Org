<?php  ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'frontend_editor', '/Users/conormacaoidh/hosts/furasta/_www/furasta/index.html', 10, false),array('function', 'menu', '/Users/conormacaoidh/hosts/furasta/_www/furasta/index.html', 20, false),)), $this); ?>
<html>
<head>
	<noscript><meta http-equiv="refresh" content="0;url=/_inc/noscript.php"></noscript>
	<?php echo $this->_tpl_vars['metadata']; ?>

	<link rel="stylesheet" href="/_inc/css/admin.css"/>
	<title><?php echo $this->_tpl_vars['site_title']; ?>
 - <?php echo $this->_tpl_vars['page_name']; ?>
</title>
</head>
<body>
<div id="dialog">&nbsp;</div>
<?php echo $this->_plugins['function']['frontend_editor'][0][0]->frontendTemplateFunction(array(), $this);?>

	<div id="wrapper">
		<div id="top">
			<div id="top-wrapper">
	                        <div id="title"><a href="http://Furasta.Org"><?php echo $this->_tpl_vars['site_title']; ?>
</a><br/><span id="subtitle"><?php echo $this->_tpl_vars['site_subtitle']; ?>
</span></div>
				<div id="right">&nbsp;</div>
			</div>
		</div>
		<div id="header">
			<div id="menu">
				<?php echo frontend_menu(array(), $this);?>

			</div>
		</div>
		<div id="container">
			<div id="container-right">
				<div id="main">
					<div id="right">
						<?php echo $this->_tpl_vars['page_content']; ?>

					</div>
				</div>
			</div>
			<div id="footer">
				<img src="/_inc/img/footer-right.png" style="float:right"/>
				<img src="/_inc/img/footer-left.png"/>
			</div>
		</div>
		<div id="bottom">
			<p class="left">&copy; <a href="http://furasta.org">Furasta.Org</a> | <a href="http://furasta.org/Support">Support</a> | <a href="http://furasta.org/Feature-Requests">Feature Requests</a></p>
			<br style="clear:both"/>
		</div>
	</div>
</body>
</html>