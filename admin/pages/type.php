<?php

/**
 * Page Type, Furasta.Org
 *
 * Accessed via AJAX, this page returns a page type which
 * is requested by the user, or suitable for this page. It
 * allows for page types to be created by plugins.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_pages
 */

include '../../_inc/define.php';

$type=@$_GET['type'];
$id=(int)@$_GET['id'];

if($type!='Normal'){
	echo $Plugins->adminPageType($type,$id);
	exit;
}
else{
	if($id!=0){
		$Page=new Page($id);
		$content=stripslashes($Page->about('content'));
	}

	echo '
                <script type="text/javascript" src="/_inc/js/tinymce.jquery.min.js"></script>
                <script type="text/javascript" src="/_inc/js/tiny_mce.js"></script>

                <textarea id="page-content" name="PageContent" class="tinymce" style="width:100%">'.@$content.'</textarea>
               ';
	exit;
}
?>