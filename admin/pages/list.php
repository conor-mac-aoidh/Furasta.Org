<?php

/**
 * List Pages, Furasta.Org
 *
 * Lists pages in the PAGES table.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_pages
 */

$head='
<script type="text/javascript">
$(document).ready(function(){
	$("#pages").treeTable();
//	$("#pages tbody").sortable({
//		update:function(){
//			alert($("#pages tr").sortable("serialize"));
//		}
//	});
	$(".delete").click(function(){
		fConfirm("Are you sure you want to trash this page?",function(element){
			element.parent().parent().fadeOut("slow");
			rowColor();
			fetch("/_inc/ajax.php?file=admin/pages/delete.php&id="+element.attr("id"));
		},$(this));
	});
});
</script>
';

$Template->add('head',$head);

$content='
<span style="float:right"><a href="pages.php?page=new"><img src="/_inc/img/new-page.png" style="float:left"/> <h1 class="image-left">New Page</h1></a></span>
<span><img src="/_inc/img/pages.png" style="float:left"/> <h1 class="image-left">Pages</h1></span>
<br/>
<table id="pages" class="row-color">
<tr class="top_bar"><th>Name</th><th>Author</th><th>Type</th><th>Edited</th><th>New</th><th>Trash</th></tr>
';

$query=query('select id,name,type,edited,user,parent from '.PAGES);
while($row=mysql_fetch_array($query)){
        $id=$row['id'];
        $href='<a href="pages.php?page=edit&id='.$id.'" class="list-link">';
	$class=($row['parent']==0)?'':' class="child-of-node-'.$row['parent'].'"';
        $content.='<tr id="node-'.$id.'"'.$class.'>
                        <td class="first">'.$href.$row['name'].'</a></td>
                        <td>'.$href.$row['user'].'</a></td>
                        <td>'.$href.$row['type'].'</a></td>
                        <td>'.$href.$row['edited'].'</a></td>
                        <td><a href="pages.php?page=new&parent='.$id.'"><img src="/_inc/img/new-page-small.png" title="New Sub Page" alt="New Sub Page"/></a></td>
                        <td><a href="#" id="'.$id.'" class="delete"><img src="/_inc/img/trash-small.png" title="Delete Page" alt="Delete Page"/></a></td>
                </tr>';
}

$content.='<tr><th colspan="6"></th></tr></table>';
$Template->add('content',$content);

?>
