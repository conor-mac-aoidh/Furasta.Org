<?php



if(isset($_POST['Edit-User'])){
        $name=addslashes($_POST['Name']);
        $email=addslashes($_POST['Email']);
        $group=addslashes($_POST['Status']);
        query('update '.USERS.' set name="'.$name.'",email="'.$email.'",user_group="'.$group.'" where id='.$id);
	cache_clear('USERS');
}

if(isset($_POST['New-User'])){
	$name=addslashes($_POST['Name']);
	$email=addslashes($_POST['Email']);
	$group=addslashes($_POST['Status']);
	$password=md5($_POST['Password']);
	$hash=md5(mt_rand());
        query('insert into '.USERS.' values ("","'.$name.'","'.$email.'","'.$password.'","","'.$group.'","'.$hash.'","")');

	$url='http://'.$_SERVER["SERVER_NAME"];
	$subject='User Activation | Furasta.Org';
	$message=$name.',

        	Please activate your new user by clicking on the link below:

	        '.$url.'/_inc/ajax.php?file=admin/users/activate.php&hash='.$hash.'

	        If you are not the person stated above please ignore this email.

	        Thanks
        	---
	        Furasta.Org
        	http://furasta.org
	        support@furasta.org
	';
	$headers='From: support@furasta.org'."\r\n".'Reply-To: support@furasta.org'."\r\n".'X-Mailer: PHP/' .phpversion();
	mail($email,$subject,$message,$headers);

        cache_clear('USERS');
        header('location: users.php?page=edit&id='.mysql_insert_id());
}

?>
