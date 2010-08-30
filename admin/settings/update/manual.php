<?php

/**
 * Manual Update, Furasta.Org
 *
 * A facility to update the CMS manually.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */



echo '
<script type="text/javascript">
	$(document).ready(function(){
                $("#help-manual").click(function(){
                        fHelp("To perform a manual update go to <a href=\"http://Furasta.Org\">http://Furasta.Org</a> and download the latest .zip package. Using the form below, select the package and wait a few minutes as the updater backs up your files and database, and then updates the CMS. Please be patient as any interuption or cancelation may cause your installation to break.");
                });
		$("#uploader").change(function(){
			var ext=$("#uploader").val().split(".");
			ext=ext[ext.length-1];
			if(ext!="zip"){
				$("#uploader").attr("value","");
				fAlert("You may only upload packages with the .zip extention.");
			}
		});
	});
</script>

<div id="tabs-content"><h2>Manual Update <a href="#" id="help-manual"><img src="/_inc/img/help.png"/></a></h2>

	<form method="post" enctype="multipart/form-data" action="settings.php?page=update&action=verify-manual">
		<table>
			<tr><td>Update Package:</td><td><input id="uploader" type="file" name="Update-Package"></td><td rowspan="2" style="width:45%">The package must be in the .zip format. Download latest packages from <a href="http://Furasta.Org">Furasta.Org</a>. Database and all core files are backed up before installation.</td></tr>
			<tr><td></td><td><input type="submit" value="Upload" name="Upload" style="width:80px"/></td><td></td></tr>
		</table>
	</form>

</div>';

?>