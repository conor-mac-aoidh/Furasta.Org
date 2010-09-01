<?php

/**
 * Install Stage 3, Furasta.Org
 *
 * Requests website settings from the user, which
 * are stored in the $_SESSION variable.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package	   installer
 */

require 'header.php';

if(@$_SESSION['begin']!=3)
        header('location: stage2.php');

/**
 * Conditions for validation
 */

$conds=array(
        'Title'=>array(
                'required'=>true,
		'patern'=>'^[A-Za-z0-9., ]{2,40}$'
        ),
        'SubTitle'=>array(
                'required'=>true,
		'patern'=>'^[A-Za-z0-9., ]{2,40}$'
	),
	'Website-URL'=>array(
		'required'=>true,
		'pattern'=>'^[A-Za-z]+://[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$'
	),
	'User-Files'=>array(
		'required'=>true
	)
);

/**
 * Validate the conditions
 */

$valid=validate($conds,'#install','form-submit');

if(isset($_POST['submit'])&&$valid==true){	
	$_SESSION['settings']['title']=$_POST['Title'];
	$_SESSION['settings']['sub_title']=$_POST['SubTitle'];
	$_SESSION['settings']['index']=(@$_POST['Index']==1)?'1':'0';
        $_SESSION['settings']['maintenance']=(@$_POST['Maintenance']==1)?'1':'0';
        $_SESSION['settings']['user_files']=$_POST['User-Files'];
        $_SESSION['settings']['site_url']=$_POST['Website-URL'];

	if(substr($_SESSION['settings']['user_files'],-1)!='/')
		$_SESSION['settings']['user_files'].='/';
	
	if(!mkdir($_SESSION['settings']['user_files'])&&!is_dir($_SESSION['settings']['user_files']))
                $Template->add('system_error','User files directory must be writable.');
	else{
	        $_SESSION['complete']=1;
	        header('location: complete.php');
	}
}

$index_checked=(@$_SESSION['settings']['index']==1)?'CHECKED':'';
$maintenance_checked=(@$_SESSION['settings']['maintenance']==1)?'CHECKED':'';

$head='
<script type="text/javascript">
$(document).ready(function(){
	$("#help-index").click(function(){
		fHelp("Ticking this box will force search engines such as Google, Yahoo etc not to index this website. This option should be enabled to make the website more private, though people will still be able to access it through direct URLs.");
	});
        $("#help-maintenance").click(function(){
		fHelp("Maintenance Mode is a feature which should be enabled while making sitewide changes. For example, if you are performing a re-structure of your website and you enable Maintenance Mode people who view the website will see a maintenance notice and will not be able to access any content. More information is available <a href=\"http://furasta.org/Help/Maintenance-Mode\">here</a>.");
        });
        $("#help-url").click(function(){
                fHelp("The website URL. The default URL is displayed, but in some cases people prefer to change it, for example to add \"www.\" before the website URL, or to remove it."); 
	});
        $("#help-files").click(function(){
                fHelp("The user files folder is where all of the users personal files are stored. The default location is inside the web root, thought it is reccomended to change this location to somewhere outside the web root if you wish to keep your files totally secure. If your files are all going to be made available publically then the default location is ok.");
	});
});
</script>
';

$url=(isset($_SESSION['settings']['website_url']))?$_SESSION['settings']['site_url']:calculate_url();
$user_files=(isset($_SESSION['settings']['user_files']))?$_SESSION['settings']['user_files']:HOME.'_user/';

$Template->add('head',$head);

$content='
<div id="install-center">
	<h1 style="text-align:left">Stage 3 / 4</h1>
	<form id="install" method="post">
		<table class="row-color">
			<tr><th colspan="2">Website Settings</th></tr>
			<tr><td>Title:</td><td><input type="text" name="Title" value="'.@$_SESSION['settings']['title'].'" class="input right" /></td></tr>
                        <tr><td>Sub Title:</td><td><input type="text" name="SubTitle" value="'.@$_SESSION['settings']['sub_title'].'" class="input right" /></td></tr>
			<tr><td>Website URL <a href="#" id="help-url"><img src="/_inc/img/help.png"/></a>:</td><td><input type="text" name="Website-URL" value="'.$url.'" class="input right" /></td></tr>
                        <tr><td>User Files Location <a href="#" id="help-files"><img src="/_inc/img/help.png"/></a>:</td><td><input type="text" name="User-Files" value="'.$user_files.'" class="input right" /></td></tr>
			<tr><td>Don\'t Index Website <a href="#" id="help-index"><img src="/_inc/img/help.png"/></a>:</td><td><input type="checkbox" name="Index" value="1" class="checkbox" style="margin:0 10px 0 28%" '.$index_checked.'/></td></tr>
		        <tr><td>Enable Maintenance Mode <a href="#" id="help-maintenance"><img src="/_inc/img/help.png"/></a>:</td><td><input type="checkbox" name="Maintenance" value="1" class="checkbox" style="margin:0 10px 0 28%"/ '.$maintenance_checked.'></td></tr>
		</table>
	        <br/>
        	<input type="submit" name="submit" id="form-submit" class="submit right" value="Next"/>
	        </form>
        	<br style="clear:both"/>
</div>
';

require 'footer.php';
?>
