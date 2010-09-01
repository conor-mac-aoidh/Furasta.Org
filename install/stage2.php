<?php

/**
 * Install Stage 2, Furasta.Org
 *
 * Requests user details and stores them in the
 * $_SESSION variable.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package	   installer
 */

require 'header.php';

if(@$_SESSION['begin']!=2)
        header('location: stage1.php');

/**
 * Conditions for validation
 */

$conds=array(
        'Name'=>array(
                'required'=>true
        ),
        'Email'=>array(
                'required'=>true,
		'email'=>true,
        ),
        'Password'=>array(
                'required'=>true,
		'minlength'=>6,
		'match'=>'Repeat-Password'
        ),
	'Repeat-Password'=>array(
		'required'=>true
	)
);

/**
 * Validate the conditions
 */

$valid=validate($conds,'#install','form-submit');

if(isset($_POST['submit'])&&$valid==true){
	$_SESSION['user']['name']=addslashes($_POST['Name']);
	$_SESSION['user']['email']=addslashes($_POST['Email']);
	$pass=$_POST['Password'];
	$repeat=$_POST['Repeat-Password'];
	if($_SESSION['user']['name']==''||$_SESSION['user']['email']==''||$pass==''||$repeat=='')
		$error='Please do not leave blank fields.';
	elseif($pass!=$repeat)
		$error='Passwords do not match';
	if(!isset($error)){
		$_SESSION['user']['pass']=md5($pass);
		$_SESSION['begin']=3;
		header('location: stage3.php');
	}
}

$content='
<div id="install-center">
	<h1 style="text-align:left">Stage 2 / 4</h1>
	<form id="install" method="post">
		<table class="row-color">
			<tr><th colspan="2">Personal Details</th></tr>
			<tr><td>Name:</td><td><input type="text" name="Name" value="'.@$_SESSION['user']['name'].'" class="input right" /></td></tr>
			<tr><td>Email Address:</td><td><input type="text" name="Email" value="'.@$_SESSION['user']['email'].'" class="input right" /></td></tr>
			<tr><td>Password:</td><td><input type="password" name="Password" class="input right" /></td></tr>
		        <tr><td>Repeat Password:</td><td><input type="password" name="Repeat-Password" class="input right" /></td></tr>
		</table>
	        <br/>
        	<input type="submit" name="submit" id="form-submit" class="submit right" value="Next"/>
	        </form>
	        <br style="clear:both"/>
</div>
';

require 'footer.php';
?>
