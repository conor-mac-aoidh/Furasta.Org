<?php

/**
 * New User, Furasta.Org
 *
 * A facility to create a new user.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_users
 */

$head='
<script type="text/javascript">
$(document).ready(function(){
	$("#User").click(function(){
                var errors=required(["Name","Email","Password","Repeat-Password"]);
                if(errors==0){
                        errors=emailFormat("Email");
                        if(errors==0){
                                errors=match("Password","Repeat-Password");
                                if(errors==0)
                                        errors=minlength("Password",6);
                        }
                }
                if(errors!=0)
                        return false;
	});
});
</script>
';

$Template->add('head',$head);

$content='
<span><img src="/_inc/img/new-user.png" style="float:left"/> <h1 class="image-left">New User</h1></span>
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
				<td><input type="text" name="Name" value=""/></td>
			</tr>
                	<tr>
				<td>Email:</td>
				<td><input type="text" name="Email" value=""/></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type="password" name="Password" value=""/></td>
			</tr>
                        </tr>
                        <tr>
                                <td>Repeat Password:</td>
                                <td><input type="password" name="Repeat-Password" value=""/></td>
                        </tr>
			<tr>
				<td>Status:</td>
				<td><select name="Status" id="status">
					<option default="default">Admin</option>
					<option>User</option>
				</select></td>
			</tr>
		</table>
<input type="submit" name="New-User" id="User" class="submit right" value="Add" style="margin-right:10%"/>
</form>
<br style="clear:both"/>
';

$Template->add('content',$content);


?>
