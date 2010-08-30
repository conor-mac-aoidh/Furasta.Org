<?php



if(isset($_POST['edit-save'])){
	$name=addslashes($_POST['Name']);
	$type=addslashes($_POST['Type']);
	$template=addslashes($_POST['Template']);
	$content=addslashes($_POST['PageContent']);
	$slug=addslashes($_POST['slug']);
	$home=(int)@$_POST['Homepage'];
	$navigation=(@$_POST['Navigation']==1)?0:1;
	$parent=(int)$_POST['Parent'];
	$perm=(int)$_POST['perm'];
	query('update '.PAGES.' set name="'.$name.'",content="'.$content.'",slug="'.$slug.'",template="'.$template.'",type="'.$type.'",edited="'.date('Y-m-d G:i:s').'",user="'.$User->about('name').'",parent="'.$parent.'",perm="'.$perm.'",home="'.$home.'",display="'.$navigation.'" where id="'.$Page->about('id').'"');
	$Page->reLoad();
	cache_clear('PAGES');
}

if(isset($_POST['new-save'])){
        $name=addslashes($_POST['Name']);
        $type=addslashes($_POST['Type']);
        $template=addslashes($_POST['Template']);
        $content=addslashes($_POST['PageContent']);
        $slug=addslashes($_POST['slug']);
        $home=(int)@$_POST['Homepage'];
        $navigation=(@$_POST['Navigation']==1)?0:1;
        $parent=(int)$_POST['Parent'];
        $perm=(int)$_POST['perm'];
	query('insert into '.PAGES.' values ("","'.$name.'","'.$content.'","'.$slug.'","'.$template.'","'.$type.'","'.date("Y-m-d").'","'.$User->about('name').'","","'.$parent.'","'.$perm.'","'.$home.'","'.$navigation.'")');
        cache_clear('PAGES');
	header('location: pages.php?page=edit&id='.mysql_insert_id());
}

?>
