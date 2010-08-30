<?php



if(isset($_POST['settings_general'])){
	$SETTINGS['site_title']=$_POST['Title'];
        $SETTINGS['site_subtitle']=$_POST['SubTitle'];
        $SETTINGS['maintenance']=addslashes($_POST['Maintenance']);
        $SETTINGS['index']=addslashes($_POST['Index']);
	settings_rewrite($SETTINGS,$DB,$PLUGINS);
}

?>
