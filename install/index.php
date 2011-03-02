<?php

/**
 * Install Overview, Furasta.Org
 *
 * An overview of whats required to install the CMS.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package	   installer
 */

require 'header.php';

$phpversion=phpversion();
$ver=split("[/ ]",$_SERVER['SERVER_SOFTWARE']);
$apacheversion=$ver[1].' '.$ver[2];

$access=(is_writable(HOME)) ? '<span class="green">Granted</span>':'<span id="notgranted" class="red">Not Granted</span>';
$php=($phpversion<<5) ? '<span class="green">'.$phpversion.'</span>':'<span class="red">'.$phpversion.'</span>';
$apache=($ver[1]<<2) ? '<span class="green">'.$apacheversion.'</span>':'<span class="red">'.$apacheversion.'</span>';

if(function_exists('apache_get_modules')){
	$modules=apache_get_modules();

	$mods=(in_array('mod_rewrite',$modules))?'<span class="green">Installed</span>':'<span id="notgranted" class="red">Not Installed</span>';
}
else
	$mods='Unknown';

$javascript = '
$(document).ready(function(){
        $("#help-access").click(function(){
		fHelp("Write Access must be enabled for Furasta.Org to perform properly. It is recommended not to install the CMS without granting write access. Read more <a href=\"http://furasta.Org\">here</a>.");
        });
	$("#help-rewrite").click(function(){
		fHelp("The Apache module Mod_rewrite is essential for the CMS to run properly. It must be installed. However there are also dependencies of the Mod_rewrite module itself, such as Mod_proxy. For more information on all of these modules, and on how to install them, <a href=\"http://Furasta.Org/\">click here.</a>");
	});
});
';

$Template->add( 'javascript', $javascript );

$content='
<h1>Welcome to the Furasta.Org Installation</h1>

<table class="row-color">
	<tr><th>Installation Requirements</th><th>Required</th><th>Current</th></tr>
	<tr><td>PHP Version:</td><td>5</td><td>'.$php.'</td></tr>
	<tr><td>MySQL Version:</td><td>5</td><td>Unknown</td></tr>
	<tr><td>Apache Version:</td><td>2</td><td>'.$apache.'</td></tr>
	<tr><td>Apache Rewrite Module: <a id="help-rewrite" class="help link">&nbsp;</a></td><td>&nbsp;</td><td>'.$mods.'</td></tr>
	<tr><td colspan="3">Write Access: <a id="help-access" class="help link">&nbsp;</a></td></tr>
	<tr><td colspan="2">'.HOME.'</td><td>'.$access.'</td></tr>
</table>
<br/>
<p><a href="stage1.php" class="grey-submit right">Begin</a></p>
<br style="clear:both"/>
';

require 'footer.php';
?>	
