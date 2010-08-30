<?php



$query=mysql_query('select id from '.USERS.' where email="'.$_SESSION['email'].'" and password="'.$pass.'" and hash="activated"');
	$num=mysql_num_rows($query);
	if($num!=1){
        	$result=mysql_query('select id from '.USERS.' where email="'.$_SESSION['email'].'" and password="'.$pass.'"');
		$num_res=mysql_num_rows($result);
		if($num_res==1)
			$error='User not activated';
		else
			$error='Login details are incorrect.';
	}

if(!isset($error)){
	$array=mysql_fetch_array($query);
	$_SESSION['user_id']=$array['id'];
	if($remember==1){
	        setcookie('furasta[email]',$_SESSION['email'],time()+3600*24*7);
        	setcookie('furasta[password]',$pass,time()+3600*24*7);
	}
        unset($_SESSION['email']);
	header('location: index.php');
}

?>
