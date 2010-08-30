<?php  ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'menu', '/Users/conormacaoidh/hosts/furasta/_www/.default/index.html', 14, false),)), $this); ?>
<!-- TODO: add sections for GUI editing purposes -->

<html>
<head>
<?php echo $this->_tpl_vars['metadata']; ?>

<link rel="stylesheet" href="/_www/.default/style.css"/>
<title><?php echo $this->_tpl_vars['page_name']; ?>
</title>
</head>
<body>
<div id="wrapper">
	<div id="header">
                <div id="title"><h1><?php echo $this->_tpl_vars['site_title']; ?>
 - <?php echo $this->_tpl_vars['site_subtitle']; ?>
</h1></div>
        	<div id="menu">
			<?php echo frontend_menu(array(), $this);?>

        	</div>
		<br style="clear:both"/>
	</div>
        <div id="content"><?php echo $this->_tpl_vars['page_content']; ?>
</div>
        <div id="footer">Loaded in <?php echo $this->_tpl_vars['page_load_time']; ?>
 seconds.</div>
</div>
</body>
</html>