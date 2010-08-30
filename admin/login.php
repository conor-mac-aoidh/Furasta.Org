<?php

/**
 * Login, Furasta.Org
 *
 * Displays login prompt, creates session and cookies if applicable.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

/**
 * Set up basic validation
 */

$conds=array(
	'Email'=>array(
		'required'=>true
	),
	'Password'=>array(
		'required'=>true
	)
);

$valid=validate($conds,"#login",'login');

/**
 * Check if form submitted, or if cookie is present
 */

if(isset($_POST['login'])&&$valid==true){
	$_SESSION['user']['email']=addslashes($_POST['Email']);
	$pass=md5($_POST['Password']);
	$remember=addslashes($_POST['Remember']);
	$check=1;
}
elseif(isset($_COOKIE['furasta']['email']) && isset($_COOKIE['furasta']['password'])){
        $_SESSION['user']['email']=$_COOKIE['furasta']['email'];
        $pass=$_COOKIE['furasta']['password'];
        $remember=1;
        $check=1;
}

/**
 * Confirm cookie/post data
 */

if(@$check==1){
	$query=mysql_query('select id,name,user_group from '.USERS.' where email="'.$_SESSION['user']['email'].'" and password="'.$pass.'" and hash="activated"');
	$num=mysql_num_rows($query);
	if($num!=1){
        	$result=mysql_query('select id from '.USERS.' where email="'.$_SESSION['user']['email'].'" and password="'.$pass.'"');
		$num_res=mysql_num_rows($result);
		if($num_res==1)
			$error=$Template->add('system_error','User not activated');
		else
			$error=$Template->add('system_error','Login details are incorrect.');
	}

	if(!isset($error)){
		$array=mysql_fetch_array($query);
		$_SESSION['user']['id']=$array['id'];
		$_SESSION['user']['name']=$array['name'];
		$_SESSION['user']['user_group']=$array['user_group'];

		if($remember==1){
		        setcookie('furasta[email]',$_SESSION['user']['email'],time()+3600*24*7);
        		setcookie('furasta[password]',$pass,time()+3600*24*7);
		}
       
		header('location: index.php');
	}
}

/*
 * Display the login template and javascript
 */

$head='
<script type="text/javascript" src="/_inc/js/system.js"></script>
<script type="text/javascript" src="/_inc/js/validate.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#password-reminder").click(passwordReminder);
});
</script>
';


$Template->add('head',$head);


$content='
<div id="login-wrapper">
	<img src="/_inc/img/login.png" class="left"/> <h1 id="login-header">Login</h1>
	<br/>
	<form id="login" method="post">
		<table id="login-table">
			<tr><td class="medium">Email:</td><td><input type="text" name="Email" value="'.@$_SESSION['email'].'"/></td></tr>
                	<tr><td class="medium">Password:</td><td><input type="password" name="Password" /></td></tr>
			<tr><td class="medium">&nbsp;</td><td class="small">Remember Me: <input type="checkbox" value="1" name="Remember" class="checkbox" class="checkbox" CHECKED/> <a href="#" id="password-reminder">Forgot your password?</a></td></tr>
			<tr><td></td><td><input type="submit" name="login" class="input" width="60px" value="Login"/></tr>
		</table>
	</form>
</div>
';

$Template->add('content',$content);

require $admin_dir.'layout/error.php';
exit;

?>
