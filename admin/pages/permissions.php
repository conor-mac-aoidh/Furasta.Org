<?php

/**
 * Page Permissions, Furasta.Org
 *
 * A page accessed via AJAX, which allows the user to
 * customise who can access and edit the page.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_pages
 */

require '../../_inc/define.php';

$id=(int)@$_GET['id'];
if($id=='')
	die('There has been a fatal error. Please contact <a href="http://furasta.org/Help">Support</a> for more details.');
$Page=new Page($id);

$options='';
$result=mysql_query('select id,name from '.USERS.' where hash="activated"');
while($array=mysql_fetch_array($result))
        $options.='<option value="'.$array['id'].'">'.$array['name'].'</option>';

echo '
<table>
	<tr><th colspan="4">Who can see this page:</th></tr>
	<tr>
                <td>Everyone:</td>
                <td><input type="checkbox" name="perm-see-everyone" value="1"/></td>
		<td>Users:</td>
		<td><input type="checkbox" name="perm-see-users" value="1"/></td>
	</tr>
	<tr>
		<td>Selected Users:</td>
		<td colspan="3"><select multiple="multiple">
'.$options.'
		</select></td>
	</tr>
	<tr><th colspan="4">Who can edit this page:</th></tr>
        <tr>
                <td>Users:</td>
                <td><input type="checkbox" name="perm-edit-everyone" value="1"/></td>
                <td>Admins:</td>
                <td><input type="checkbox" name="perm-edit-users" value="1"/></td>
        </tr>
        <tr>
                <td>Selected Users:</td>
                <td colspan="3"><select multiple="multiple">
				'.$options.'
		</select></td>
        </tr>
</table>
';

exit;
?>