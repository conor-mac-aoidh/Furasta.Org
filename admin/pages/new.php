<?php

/**
 * New Page, Furasta.Org
 *
 * A facility through which the user can create a new
 * page in the PAGES table.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_pages
 */

/**
 * Load javascript files 
 */
$Template->loadJavascript('_inc/js/jquery/tinymce.min.js');
$Template->loadJavascript('_inc/js/tiny_mce.js');
$Template->loadJavascript('_inc/js/admin/pages-new.js');

if(isset($error)) 
	$Template->add('content','<h2 class="php-error">'.$error.'</h2>');

$url='http://'.$_SERVER["SERVER_NAME"];

$content='
<img src="/_inc/img/new-page.png" style="float:left"/> <h1 class="image-left">New Page</h1>
<br/>
<form method="post" id="pages-edit">
	<table id="page-details">
		<tr>
                        <td class="small">Name:</td>
			<td><input type="text" name="Name" id="page-name" value=""/></td>
			<td class="options"><a id="options-link">Show Options</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="'.$url.'" id="slug-url">'.$url.'/</a></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<input type="hidden" name="slug" id="slug-put" value="'.$url.'"/>
	<div id="options">
		<h2>Options</h2>
		<table id="page-details">
			<tr>
				<td class="small">Type:</td>
				<td><select name="Type" id="edit-type">
					<option default="default">Normal</option>
';

$options=$Plugins->adminPageTypes();
foreach($options as $option)
	$content.='<option>'.$option.'</option>';

$content.='
				</select></td>
				<td class="small">Is Home Page:</td>
				<td><input type="checkbox" value="1" name="Homepage"/></td>
			</tr>
			<tr>
				<td class="small">Template:</td>
				<td><select name="Template">
					<option>Default</option>
				</select></td>
				<td class="small">Hide In Navigation</td>
				<td><input type="checkbox" value="1" name="Navigation"/></td>
			</tr>
			<tr>
				<td class="small">Parent:</td>
				<td><select name="Parent" id="select-parent">
';

$pages=array();
$query=query('select id,name,parent from '.PAGES.' order by position,name desc');
while($row=mysql_fetch_assoc($query))
        $pages[$row['parent']][]=$row;

$parent=addslashes(@$_GET['parent']);
if($parent==''){
        $content.='<option selected="selected" value="0">---</option>';
        $content.=list_parents(0,$pages,0,0);
}
else{
        $content.='<option value="0">---</option>';
        $content.=list_parents(0,$pages,0,$parent);
}


$type=(@$_GET['type']=='')?'Normal':@$_GET['type'];

$content.='
				<td class="small"><a href="#" id="pages-permissions">Permissions</a><input type="hidden" name="perm" value=""/></td>
			</tr>
		</table>
	</div>

	<div id="pages-type-content" type="'.$type.'">&nbsp;</div>

<input type="submit" name="new-save" value="Save" class="submit" id="edit-save"/>
</form>
';

$Template->add('content',$content);
?>
