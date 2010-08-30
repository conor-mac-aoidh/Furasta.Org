<?php

/**
 * Verify User Email, Furasta.Org
 *
 * Verifies the users email and sends an email with
 * password reset details to the user.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_users
 */

$email=addslashes(@$_GET['email']);
if($email=='')
	die('1');


$query=mysql_query('select * from '.USERS.' where email="'.$email.'"');
$num=mysql_num_rows($query);

if($num==1)
	die('0');
else{
	$hash=md5(mt_rand());
	query('update '.USERS.' set reminder="'.$hash.'" where email="'.$email.'"');
	$url='http://'.$_SERVER["SERVER_NAME"];
	$subject='Password Reminder | Furasta.Org';
	$message='
        	'.single('select name from '.USERS.' where email="'.$email.'"','name').',

		You have requested a new password. Please follow the link below to reset your password:

		'.$url.'/admin/settings/users/reset_password.php?reminder='.$hash.'

		If you have not requested a new password please ignore this email.

	        Thanks
	        ---
	        Furasta.Org
	        http://furasta.org
	        support@furasta.org
	';
	$headers='From: support@furasta.org'."\r\n".'Reply-To: support@furasta.org'."\r\n".'X-Mailer: PHP/' .phpversion();
	mail($email,$subject,$message,$headers);
	die('1');
}
?>