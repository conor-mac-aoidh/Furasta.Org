<?php

/**
 * Edit User, Furasta.Org
 *
 * A facility to edit the users details.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_users
 */

$id=@$_GET['id'];
if($id==0)
	header('location: users.php');

$user=row('select name,user_group,email,password from '.USERS.' where id='.addslashes($id).' limit 1',true);

$head='
<script type="text/javascript">
$(document).ready(function(){
	$("#User").click(function(){
                var errors=required(["Name","Email"]);
                if(errors==0){
                        errors=emailFormat("Email");
                }
                if(errors!=0)
                        return false;
	});
</script>
';

$Template->add('head',$head);

$content='
<span><img src="/_inc/img/users.png" style="float:left"/> <h1 class="image-left">Edit User</h1></span>
<br/>
	<form method="post">
		<table id="config-table" class="row-color">
			<col width="50%"/>
			<col width="50%"/>
			<tr>
				<th colspan="2">User Options</th>
			</tr>
			<tr>
				<td>Name:</td>
				<td><input type="text" name="Name" value="'.$user['name'].'"/></td>
			</tr>
                	<tr>
				<td>Email:</td>
				<td><input type="text" name="Email" value="'.$user['email'].'"/></td>
			</tr>
			<tr>
				<td>Status:</td>
				<td><select name="Status" id="status">';
$groups=array('Admin','User');
foreach($groups as $group){
	if($group==$user['user_group'])
		$content.='<option selected="selected" value="'.$group.'">'.$group.'</option>';
	else
		$content.='<option value="'.$group.'">'.$group.'</option>';
}

$content.='
				</select></td>
			</tr>
		</table>
<input type="submit" name="Edit-User" id="User" class="submit right" value="Save" style="margin-right:10%"/>
</form>
<br style="clear:both"/>
';

$Template->add('content',$content);


?>
