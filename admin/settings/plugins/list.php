<?php

/**
 * List Plugin, Furasta.Org
 *
 * Lists available plugins, activated and de-activated.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

$javascript = '
$(document).ready(function(){

        $(".checkbox-all").click(function(){

                if($(".checkbox-all").attr("all")=="checked"){

                        $("input[type=checkbox]").attr("checked","");

                        $(".checkbox-all").attr("all","");

                }

                else{

                        $("input[type=checkbox]").attr("checked","checked");

                        $(".checkbox-all").attr("all","checked");

                }

         });

	$(".p-submit").click(function(){

		var action=$(".select-"+$(this).attr("id")).val();

		if(action=="---")

			return false;

		var boxes=[];

		$("#users input[name=trash-box]:checked").each(function() {

			boxes.push($(this).val());

		});

		var boxes=boxes.join(",");

		if(boxes=="")

			return false;

		fConfirm("Are you sure you want to perform a multiple "+action+"?",function(){ window.location="settings.php?page=plugins&action=multiple&act="+action+"&boxes="+boxes; });

	});

});
';

$Template->loadJavascript( 'FURASTA_ADMIN_SETTINGS_PLUGINS', $javascript );

$content='
<span class="right"><select name="action" class="trash-select select-p_1"><option default="default">---</option><option>Activate</option><option>Deactivate</option><option>Delete</option></select> <input id="p_1" class="p-submit submit" type="submit" value="Go"/></span>

<span class="header-img" id="header-Plugins">&nbsp;</span><h1 class="image-left">Plugins</h1></span>
<br/>
<table id="users" class="row-color">
        <tr class="top_bar"><th><input type="checkbox" class="checkbox-all" all=""/></th><th>Name</th><th>Description</th><th>Status</th><th>Delete</th></tr>
';

$num=0;

/**
 * scan plugin dir for plugins 
 */
$p_inactive=scan_dir(HOME.'_plugins');

/**
 * load active plugins 
 */
$plugins = $Plugins->registeredPlugins( );

/**
 * load inactive plugins and merge $plugin array to registerd plugins array 
 */
foreach( $p_inactive as $plugin_file ){
	if( in_array( $plugin_file, $PLUGINS ) || !is_dir( HOME . '_plugins/' . $plugin_file ) )
		continue;

	require HOME . '_plugins/' . $plugin_file . '/plugin.php';

	/**
	 * merge plugin array with plugins array
	 */
	$plugins[] = $plugin;

}

foreach( $plugins as $plugin){
        $num++;
	$p_name = str_replace( ' ', '-', $plugin[ 'name' ] );
	$status=(in_array($p_name,$PLUGINS))?'<a href="settings.php?page=plugins&action=deactivate&p_name='.$p_name.'">De-activate</a>':'<a href="settings.php?page=plugins&action=activate&p_name='.$p_name.'">Activate</a>';
        $content.='<tr>
                        <td class="small"><input type="checkbox" class="p-box" value="'.$plugin[ 'name' ].'" name="trash-box"/></td>
                        <td class="first">' . $plugin[ 'name' ] . '</a></td>
                        <td>' . @$plugin[ 'description' ] . '</a></td>
			<td>' . $status . '</td>
                        <td><a href="settings.php?page=plugins&action=delete&p_name='. str_replace( ' ', '-', $p_name ) .'"><span class="admin-menu-img" id="delete-img" title="Delete User" alt="Delete User">&nbsp;</span></a></td>
                </tr>';
}

$content.='<tr><th><input type="checkbox" class="checkbox-all" all=""/></th><th colspan="6"></th></tr></table><br/>
<span class="right"><select name="action" class="trash-select select-p_2"><option default="default">---</option><option>Activate</option><option>Deactivate</option><option>Delete</option></select> <input type="submit" id="p_2" class="p-submit submit" value="Go"/></span>
<br style="clear:both"/>
';

$Template->add('content',$content);

?>
