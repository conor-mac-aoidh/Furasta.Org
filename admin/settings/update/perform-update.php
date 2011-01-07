<?php

/**
 * Perform Update, Furasta.Org
 *
 * Performs the update process. Displays a visual loading bar
 * and performs the update in phases.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

$javascript='
$(document).ready(function(){
	$("#progress").progressbar({value:2});	
	$("#percentage").html("2%");
	ajax_get(1);
});

function ajax_get(stage){
	var messages=new Array();
	messages[1]="Backing up database...";
	messages[2]="Backing up files...";
	messages[3]="Unzipping package...";
	messages[4]="Running package scripts...";
	messages[5]="Finished";
	$("#status").html(messages[stage]);

	if(stage==5){
		$("#progress").progressbar({value:100});  
		$("#percentage").html("100%");
		$("#loading-gif").hide();
		$("#update-message").html("Update Completed Successfully. Please enjoy your new version of Furasta.Org");
		return;
	}

	var file=new Array();
	file[1]="backup-database";
	file[2]="backup-files";
	file[3]="unzip-package";
	file[4]="run-scripts";

        $.ajax({
                url:"/_inc/ajax.php?file=admin/settings/update/"+file[stage]+".php",
                error:function(){
                        fAlert("There has been an unknown error during the update process. Please check your internet connection and refresh the page.");
                },
                success:function(html){
                        if(html=="ok"){
                                $("#progress").progressbar({value:20*stage});  
                                $("#percentage").html(20*stage+"%");
				ajax_get(stage+1);
                        }
                        else
                                fAlert("There has been an unknown error during the update process. Please refresh the page and try again.");
                }
        }); 
}
';

$Template->loadJavascript('FURASTA_ADMIN_SETTINGS_PERFORM_UPDATE',$javascript);

$Template->add('content','<h2 style="float:right;color:red" id="update-message"></h2><h1>Core Updater</h1><div id="update-content"><p class="right" id="percentage"></p><p id="status">Begining update..</p><img src="/_inc/img/loading.gif" id="loading-gif" class="right" style="margin-top:20px"/><div id="progress"></div><br style="clear:both"/></div>');

?>
