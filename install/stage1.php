<?php

/**
 * Install Stage 1, Furasta.Org
 *
 * Requests database information from the user and stores
 * it in a $_SESSION variable.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package	   installer
 */

require 'header.php';


/**
 * Conditions for validation
 */

$conds=array(
	'DatabaseName'=>array(
		'required'=>true
	),
	'Hostname'=>array(
		'required'=>true
	),
	'Username'=>array(
		'required'=>true
	)
);

/**
 * Validate the conditions
 */

$valid=validate($conds,'#install','form-submit');

if(isset($_POST['submit'])&&$valid==true){
	$_SESSION['db']['name']=$_POST['DatabaseName'];
	$_SESSION['db']['host']=$_POST['Hostname'];
	$_SESSION['db']['user']=$_POST['Username'];
	$_SESSION['db']['prefix']=$_POST['Prefix'];
	$_SESSION['db']['pass']=$_POST['Password'];
	$_SESSION['begin']=2;
	header('location: stage2.php');
}

$head='
<script type="text/javascript">
	function validateCallback(){
		$("#check_connection").html("<img src=\"/_inc/img/loading.gif\"/> Checking Details...");
		var connection=checkConnection(["Hostname","Username","DatabaseName","Password"]);
		$("input[name=DatabaseName]").removeClass("error");
		$("input[name=Hostname]").removeClass("error");
		$("input[name=Username]").removeClass("error");
		$("input[name=Password]").removeClass("error");
		if(connection!="ok"){
			if(connection=="database")
				$("input[name=DatabaseName]").addClass("error");
			else{
				$("input[name=Hostname]").addClass("error");
				$("input[name=Username]").addClass("error");
				$("input[name=Password]").addClass("error");
			}
			fAlert("The details that you have supplied are invalid. Please correct them to continue.");
			$("#check_connection").html("&nbsp;");
		}
		else
			return true;

		return false;
	}
</script>
';

$Template->add('head',$head);

$prefix=(isset($_SESSION['db']['prefix']))?$_SESSION['db']['prefix']:'fr_';

$content='
<div id="install-center">
	<p id="check_connection" class="right">&nbsp;</p>
	<h1 style="text-align:left">Stage 1 / 4</h1>
	<form id="install" method="post" >
		<table class="row-color">
			<tr><th colspan="2">Database Details</th></tr>
			<tr><td>Database Name:</td><td><input type="text" name="DatabaseName" value="'.@$_SESSION['db']['name'].'" class="input right" /></td></tr>
			<tr><td>Host Name:</td><td><input type="text" name="Hostname" value="'.@$_SESSION['db']['host'].'" class="input right" /></td></tr>
			<tr><td>User Name:</td><td><input type="text" name="Username" value="'.@$_SESSION['db']['user'].'" class="input right" /></td></tr>
			<tr><td>Password:</td><td><input type="password" name="Password" value="" class="input right" /></td></tr>
			<tr><td>Optional Table Prefix:</td><td><input type="text" name="Prefix" value="'.$prefix.'" class="input right"/></td></tr>
		</table>
	<br/>
	<input type="submit" name="submit" id="form-submit" class="submit right" value="Next"/>
	</form>
	<br style="clear:both"/>
</div>
';

require 'footer.php';
?>
