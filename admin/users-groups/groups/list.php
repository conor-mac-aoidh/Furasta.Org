<?php

/**
 * List Groups, Furasta.Org
 *
 * Lists all the groups from the GROUPS table.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_users
 */

$head='
<script type="text/javascript">
$(document).ready(function(){
        $(".delete").click(function(){
                fConfirm("Are you sure you want to delete this user?",function(element){
                        element.parent().parent().fadeOut("slow");
                        rowColor();
                        fetch("/_inc/ajax.php?file=admin/users/delete.php&id="+element.attr("id"));
                },$(this));
        });
});
</script>
';

$Template->add('head',$head);

$content='
<span style="float:right"><a href="users.php?page=new" id="new-user"><img src="/_inc/img/new-user.png" style="float:left"/> <h1 class="image-left">New User</h1></a></span>
<span><img src="/_inc/img/users.png" style="float:left"/> <h1 class="image-left">Users</h1></span>
<br/>
<table id="users" class="row-color">
	<tr class="top_bar"><th>Name</th><th>Email</th><th>Status</th><th>Delete</th></tr>
';

$query=query('select id,name,email,user_group from '.USERS.' order by id');
while($row=mysql_fetch_array($query)){
	$id=$row['id'];
	$perm=$row['user_group'];
	$href='<a href="users.php?page=edit&id='.$id.'" class="list-link">';
	$content.='<tr>
			<td class="first">'.$href.$row['name'].'</a></td>
			<td>'.$href.$row['email'].'</a></td>
			<td>'.$href.$perm.'</a></td>
			<td><a href="#" id="'.$id.'" class="delete"><img src="/_inc/img/delete.png" title="Delete User" alt="Delete User"/></a></td>
		</tr>';
}

$content.='<tr><th colspan="6"></th></tr></table>';

$Template->add('content',$content);


?>
