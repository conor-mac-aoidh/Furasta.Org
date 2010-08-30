<?php

/**
 * Install Completion, Furasta.Org
 *
 * Completes the install process. Installs the database,
 * creates the settings.php file and emails the user
 * with a validation link.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    installer
 * @todo       create contents of _user dir here, and let the user choose it's location, which should be inside the web root by default
 */

require 'header.php';

if(@$_SESSION['complete']!=1)
	header('location: stage3.php');

$connect=mysql_connect($_SESSION['db']['host'],$_SESSION['db']['user'],$_SESSION['db']['pass']);
$select=mysql_select_db($_SESSION['db']['name'],$connect);

$prefix=$_SESSION['db']['prefix'];
$pages=$prefix.'pages';
$users=$prefix.'users';
$trash=$prefix.'trash';
$groups=$prefix.'groups';
$hash=md5(mt_rand());
$site_url=$_SESSION['settings']['site_url'];
$user_files=$_SESSION['settings']['user_files'];

$pagecontent='
<h1>Welcome to Furasta CMS</h1>
<p>This is your new installation of Furasta CMS.</p>
<p>To log in to your CP <a href=\'/admin\'>Click Here</a></p>
';

query('drop table if exists '.$pages);
query('create table '.$pages.' (id int auto_increment primary key,name text,content text,slug text,template text,type text,edited date,user text,position int,parent int,perm int,home int,display int)');
query('insert into '.$pages.' values(0,"Home","'.$pagecontent.'","Home","Default","Normal","'.date('Y-m-d').'","Installer",1,0,0,1,1)');

query('drop table if exists '.$users);
query('create table '.$users.' (id int auto_increment primary key,name text,email text,password text,homepage text,user_group text,overview text,hash text,reminder text)');
query('insert into '.$users.' values(0,"'.$_SESSION['user']['name'].'","'.$_SESSION['user']['email'].'","'.$_SESSION['user']['pass'].'","","Admin","'.$hash.'","")');

query('drop table if exists '.$trash);
query('create table '.$trash.' (id int auto_increment primary key,name text,content text,slug text,template text,type text,edited date,user text,position int,parent int,perm text,home int,display int)');
query('insert into '.$trash.' values(0,"Example Page","Sample page content.","Example-Page","Default","Normal","'.date('Y-m-d').'","Installer",1,"","Public",1,1)');

$filecontents='<?php
define(\'PAGES\',\''.$pages.'\');
define(\'USERS\',\''.$users.'\');
define(\'TRASH\',\''.$trash.'\');
define(\'GROUPS\',\''.$groups.'\');
define(\'TEMPLATE_DIR\',\''.HOME.'_www/.default/\');
define(\'PREFIX\',\''.$prefix.'\');
define(\'VERSION\',\'0.9\');
define(\'SITEURL\',\''.$site_url.'\');
define(\'USERFILES\',\''.$user_files.'\');

$PLUGINS=array();

$SETTINGS=array(
	\'site_title\'=>\''.$_SESSION['settings']['title'].'\',
        \'site_subtitle\'=>\''.$_SESSION['settings']['sub_title'].'\',
        \'index\'=>\''.$_SESSION['settings']['index'].'\',
	\'maintenance\'=>\''.$_SESSION['settings']['maintenance'].'\'
);

$DB=array(
        \'name\'=>\''.$_SESSION['db']['name'].'\',
        \'host\'=>\''.$_SESSION['db']['host'].'\',
        \'user\'=>\''.$_SESSION['db']['user'].'\',
        \'pass\'=>\''.$_SESSION['db']['pass'].'\'
);

?>
';

file_put_contents(HOME.'.settings.php',$filecontents) or error('Please grant <i>0777</i> write access to the <i>'.HOME.'</i> directory then reload this page to complete installation.');

$htaccess=
	"# .htaccess - Furasta.Org\n".
	"<IfModule mod_deflate.c>\n".
	"       SetOutputFilter DEFLATE\n".
	"       Header append Vary User-Agent env=!dont-vary\n".
	"</IfModule>\n\n".

	"php_flag magic_quotes_gpc off\n\n".

	"RewriteEngine on\n".
	"RewriteCond %{SCRIPT_NAME} !\.php\n".
	"RewriteRule ^admin$ /admin/ [L]\n".
	"RewriteRule ^files/(.*)$ /_user/files.php?name=$1 [L]\n".
	"RewriteRule ^([^./]{3}[^.]*)$ /index.php?page=$1 [QSA,L]\n\n".

	"AddCharset utf-8 .js\n".
	"AddCharset utf-8 .xml\n".
	"AddCharset utf-8 .css\n".
	"AddCharset utf-8 .php";

file_put_contents(HOME.'.htaccess',$htaccess);

if($_SESSION['settings']['index']==0)
	$robots=
	"# robots.txt - Furasta.Org\n".
	"User-agent: *\n".
	"Disallow: /admin\n".
	"Disallow: /install\n".
	"Disallow: /_user";
else
	$robots=
	"# robots.txt - Furasta.Org\n".
	"User-agent: *\n".
	"Disallow: /\n";

file_put_contents(HOME.'robots.txt',$robots);

$url='http://'.$_SERVER["SERVER_NAME"];
$subject='User Activation | Furasta.Org';
$message=$_SESSION['user']['name'].',

	Please activate your new user by clicking on the link below:

	'.$url.'/admin/users/activate.php?hash='.$hash.'

	If you are not the person stated above please ignore this email.

	Thanks
	---
	Furasta.Org
	http://furasta.org
	support@furasta.org
';
$headers='From: support@furasta.org'."\r\n".'Reply-To: support@furasta.org'."\r\n".'X-Mailer: PHP/' .phpversion();
mail($_SESSION['user']['email'],$subject,$message,$headers);

$content='
<h1>Installation Complete</h1>
<p>Your Furasta CMS installation has been performed successfully. Please now finish and configure your website.</p>
<br/>
<a href="../admin/index.php" class="grey-submit right">Finish</a>
</form>
<br style="clear:both"/>
';

require 'footer.php';
?>
