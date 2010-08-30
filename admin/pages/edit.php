<?php

/**
 * Edit Pages, Furasta.Org
 *
 * Displays a factility through which the user
 * can edit pages.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_pages
 */

$id=(int)@$_GET['id'];
if($id==0)
        header('location pages.php?action=new');

$conds=array(
	'Name'=>array(
		'required'=>true,
		'minlength'=>2,
		'pattern'=>"^[A-Z a-z0-9]{1,40}$"
	)
);

$valid=validate($conds,"#pages-edit",'edit-save');

if(isset($_POST['edit-save'])&&$valid==true){
        $name=addslashes($_POST['Name']);
        $type=addslashes($_POST['Type']);
        $template=addslashes($_POST['Template']);
        $content=addslashes($_POST['PageContent']);
        $slug=addslashes($_POST['slug']);
        $home=(int)@$_POST['Homepage'];
        $navigation=(@$_POST['Navigation']==1)?0:1;
        $parent=(int)$_POST['Parent'];
        $perm=(int)$_POST['perm'];
	query('update '.PAGES.' set
	name="'.$name.'",content="'.$content.'",slug="'.$slug.'",template="'.$template.'",type="'.$type.'",edited="'.date('Y-m-d
	G:i:s').'",user="'.$User->about('name').'",parent='.$parent.',perm="'.$perm.'",home='.$home.',display='.$navigation.'
	where id='.$id,true);
	cache_clear('PAGES');
}

$Page=row('select * from fr_pages where id='.$id);

$head='
	var type=$("#pages-type-content").attr("type");
        var id=$("#pages-type-content").attr("page-id");
        $("#pages-type-content").html("Loading... <img src=\"/_inc/img/loading.gif\"/>");
	loadPageType(type,id);

	$("#edit-delete").click(function(){
		fConfirm("Are you sure you want to delete this page?");
		return false;
	});
        $("#options-link").click(displayOptions);
        $("#edit-type").change(function(){
                $("#pages-type-content").html("Loading... <img src=\"/_inc/img/loading.gif\"/>");
                var type=$(this).attr("value");
		loadPageType(type,id);
        });
	$("#pages-permissions").click(function(){ pagePermissions(id); });
	$("#page-name").keyup(function(){
		var url=$("#page-name").val();
		var result=slugCheck(url);
		if(result==false)
			$("#page-name").addClass("error");
		else{
			$("#page-name").removeClass("error");
			var fullUrl="http://furasta.l/"+result;
			$("#slug-url").html(fullUrl);
                        $("#slug-url").attr("href",fullUrl);
			$("#slug-put").attr("value",result);
		}
		
	});
	$("#redirect-help").click(function(){
		fHelp("This feature allows you to redirect the user. Enter a URL to forward them to and then when they look at this page they will be redirected to that URL. For more information visit <a href=\"http://Furasta.Org/Help\">http://Furasta.Org/Help</a>");
	});
';

$Template->load_javascript('_inc/js/validate.js');
$Template->add('doc_ready',$jquery);

$url='http://'.$_SERVER["SERVER_NAME"];

$content='
<span style="float:right"><a href="pages.php?page=new&parent='.$Page['id'].'" id="new-subpage"><img src="/_inc/img/new-page.png" style="float:left"/> <h1 class="image-left">New Subpage</h1></a></span>
<span><img src="/_inc/img/pages.png" style="float:left"/> <h1 class="image-left">Edit Pages</h1></span>
<br/>
<form method="post" id="pages-edit">
	<table id="page-details">
		<tr>
                        <td class="small">Name:</td>
			<td><input type="text" name="Name" id="page-name" value="'.$Page['name'].'"/></td>
			<td class="options"><a href="#" id="options-link">Show Options</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="'.$url.'/'.$Page['slug'].'" id="slug-url">'.$url.'/'.$Page['slug'].'</a></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<input type="hidden" name="slug" id="slug-put" value="'.$Page['slug'].'"/>
	<div id="options">
		<h2>Options</h2>
		<table id="page-details">
			<tr>
				<td class="small">Type:</td>
				<td><select name="Type" id="edit-type">
					<option value="Normal">Normal</option>
';

$options=$Plugins->adminPageTypes();
foreach($options as $option){
	if($option==$Page['type'])
		$content.='<option selected="selected" value="'.$option.'">'.$option.'</option>';
	else
		$content.='<option value="'.$option.'">'.$option.'</option>';
}

$homepage=($Page['home']==1)?' checked="checked"':'';

$content.='
				</select></td>
				<td class="small">Is Home Page:</td>
				<td><input type="checkbox" value="1" name="Homepage"'.$homepage.'/></td>
			</tr>
			<tr>
				<td class="small">Template:</td>
				<td><select name="Template">
					<option value="Default">Default</option>
';

$files=scandir(TEMPLATE_DIR);

foreach($files as $file){
	if(substr(strrchr($file,'.'),1)=='html'){
		$file_n=basename($file,'.html');
		if($file_n=='default')
			continue;
		if($file_n==$Page['template'])
			$selected=' selected="selected"';
		$content.='<option value="'.$file_n.'"'.@$selected.'>'.$file_n.'</option>';
	}
}

$navigation=($Page['display']==1)?'':' checked="checked"';

$content.='
				</select></td>
				<td class="small">Hide In Navigation</td>
				<td><input type="checkbox" value="1" name="Navigation"'.$navigation.'/></td>
			</tr>
			<tr>
				<td class="small">Parent:</td>
				<td><select name="Parent">
';

$pages=array();
$query=query('select id,name,parent from '.PAGES.' order by position,name desc');
while($row=mysql_fetch_assoc($query))
        $pages[$row['parent']][]=$row;

$parent=$Page['parent'];
if($parent==0){
        $content.='<option selected="selected" value="0">---</option>';
        $content.=list_parents(0,$pages,0,0);
}
else{
        $content.='<option value="0">---</option>';
        $content.=list_parents(0,$pages,0,$parent);
}

$content.='
				<td class="small"><a href="#" id="pages-permissions">Permissions</a><input type="hidden" name="perm" value=""/></td>
			</tr>
		</table>
	</div>

	<div id="pages-type-content" type="'.$Page['type'].'" page-id="'.$Page['id'].'">&nbsp;</div>

<input type="submit" name="edit-delete" value="Delete" class="submit" id="edit-delete"/>
<input type="submit" name="edit-save" value="Save" class="submit" id="edit-save"/>
</form>
';

$Template->add('content',$content);
?>