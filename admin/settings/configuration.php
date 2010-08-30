<?php

/**
 * Configuration, Furasta.Org
 *
 * Allows for changes to the $SETTINGS variable.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

$head='
<script type="text/javascript">
$(document).ready(function(){
        $("#help-index").click(function(){
                fHelp("Ticking this box will force search engines such as Google, Yahoo etc not to index this website. This option should be enabled to make the website more private, though people will still be able to access it through direct URLs.");
        });
        $("#help-maintenance").click(function(){
                fHelp("Ticking this box will enable Maintenance Mode; a feature which should be enabled while making sitewide changes. For example, if you are performing a re-structure of your website and you enable Maintenance Mode people who view the website will see a maintenance notice and will not be able to access any content. More information is available <a href=\"http://furasta.org/Help/Maintenance-Mode\">here</a>.");
        });
        $("#config-save").click(function(){
                var errors=required(["Title","SubTitle"]);
                if(errors==0)
                        errors+=pattern(["Title","SubTitle"]);
                if(errors!=0)
                        return false;
        });
});
</script>';

$Template->add('head',$head);

$maintenance=($SETTINGS['maintenance']==1)?'CHECKED':'';
$index=($SETTINGS['index']==1)?'CHECKED':'';

$content='
<img src="/_inc/img/configuration.png" style="float:left"/> <h1 class="image-left">Configuration</h1></span>
<br/>

<form method="post">
<table id="config-table" class="row-color">
	<col width="50%"/>
        <col width="50%"/>
	<tr>
		<th colspan="2">Website Options</th>
	</tr>
	<tr>
		<td>Title:</td>
		<td><input type="text" name="Title" value="'.$SETTINGS['site_title'].'" class="input" /></td>
	</tr>
	<tr>
		<td>Sub Title:</td>
		<td><input type="text" name="SubTitle" value="'.$SETTINGS['site_subtitle'].'" class="input" /></td>
	</tr>
	<tr>
		<td>Enable Maintenance Mode: <a href="#" id="help-maintenance"><img src="/_inc/img/help.png"/></a></td>
		<td><input type="checkbox" name="Maintenance" class="checkbox" value="1" '.$maintenance.'/></td>
	</tr>
	<tr>
		<td>Don\'t Index Website: <a href="#" id="help-index"><img src="/_inc/img/help.png"/></a></td>
		<td><input type="checkbox" name="Index" value="1" class="checkbox" '.$index.'/></td>
	</tr>
</table>
<input type="submit" id="config-save" name="settings_general" class="submit right" style="margin-right:10%" value="Save"/>
</form></br style="clear:both"/>
';

$Template->add('content',$content);

?>
