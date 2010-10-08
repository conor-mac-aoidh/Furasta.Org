<?php

/**
 * Trash List Pages, Furasta.Org
 *
 * Lists trash pages.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_pages
 */

$javascript = '
$(document).ready(function(){

        $(".delete").click(function(){

                fConfirm("Are you sure you want to permenantly delete this page?",function(element){

                        element.parent().parent().fadeOut("slow");

                        rowColor();

                        fetch("/_inc/ajax.php?file=admin/pages/trash/delete.php&id="+element.attr("id"));

                },$(this));

        });

        $(".restore").click(function(){

                fConfirm("Are you sure you want to restore this page? Certain features have not been preserved, such as page parent, type, and template settings.",function(element){

                        element.parent().parent().fadeOut("slow");

                        rowColor();

                        fetch("/_inc/ajax.php?file=admin/pages/trash/restore.php&id="+element.attr("id"));

                },$(this));

        });

	$(".checkbox-all").click(function(){

		if($(".checkbox-all").attr("all")=="checked"){

			$("input[type=checkbox]").attr("checked","");

                        $(".checkbox-all").attr("all","");

		}

		else{

	                $("input[type=checkbox]").attr("checked","checked");

			$(".checkbox-all").attr("all","checked");

		}	

	 });

        $(".p-submit").click(function(){

                var action=$(".select-"+$(this).attr("id")).val();

                if(action=="---")

                        return false;

                var boxes=[];

                $("#trash input[name=trash-box]:checked").each(function() {

                        boxes.push($(this).val());

                });

                var boxes=boxes.join(",");

                if(boxes=="")

                        return false;

                fConfirm("Are you sure you want to perform a multiple "+action+"?",function(){ window.location="pages.php?page=trash&action=multiple&act="+action+"&boxes="+boxes; });

        });

});
';

$Template->loadJavascript( 'FURASTA_ADMIN_PAGES_TRASH_LIST', $javascript );

$content='
<span class="right"><select name="action" class="trash-select select-p_1"><option default="default">---</option><option>Restore</option><option>Delete</option></select> <input id="p_1" class="p-submit submit" type="submit" value="Go"/></span>
<span><img src="/_inc/img/trash.png" style="float:left"/> <h1 class="image-left">Trash</h1></span>
<br/>';

$rows=rows('select id,name,type,edited,user from '.TRASH);
if(count($rows)==0)
	$content.='<br/><h2>No Trash!<h2>';
else{
	$content.='<table id="trash" class="row-color">
	<tr class="top_bar"><th><input type="checkbox" class="checkbox-all" all=""/></th><th>Name</th><th>Author</th><th>Type</th><th>Edited</th><th>Restore</th><th>Delete</th></tr>';
	foreach($rows as $row){
	        $id=$row['id'];
        	$content.='<tr>
				<td class="small"><input type="checkbox" value="'.$row['id'].'" name="trash-box"/></td>
                        	<td class="first">'.$row['name'].'</td>
	                        <td>'.$row['user'].'</td>
        	                <td>'.$row['type'].'</td>
                	        <td>'.$row['edited'].'</td>
                        	<td><a href="#" id="'.$row['id'].'" class="restore"><img src="/_inc/img/new-page-small.png" title="Restore Page" alt="Restore Page"/></a></td>
	                        <td><a href="#" id="'.$row['id'].'" class="delete"><img src="/_inc/img/delete.png" title="Delete Page" alt="Delete Page"/></a></td>
        	        </tr>';
	}
	$content.='<tr><th><input type="checkbox" class="checkbox-all" all=""/><th></th><th colspan="6"></th></tr></table>';
}

$content.='<br/><span class="right"><select name="action" class="trash-select select-p_2"><option default="default">---</option><option>Restore</option><option>Delete</option></select> <input id="p_2" class="p-submit submit" type="submit" value="Go"/></span>
<br style="clear:both"/>
';
$Template->add('content',$content);

?>
