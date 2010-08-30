<?php

/**
 * Change Template, Furasta.Org
 *
 * Allows the user to edit which template is used in
 * the frontend of the website.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

$head='
<script type="text/javascript">
$(document).ready(function(){
        $(".delete-link").click(function(){
                fConfirm("Are you sure you want to delete this template?",function(element){
                        element.parent().parent().parent().fadeOut("slow");
                        fetch("/_inc/ajax.php?file=admin/settings/template/delete.php&name="+element.attr("id"));
                },$(this));
        });
        $(".activate-link").click(function(){
		$(".template-selectable").each(function(){
			if($(this).hasClass("activated"))
				$(this).removeClass("activated");
		});
		var element=$(this).parent().parent().parent();
		element.stop().css("background-color","#FFFF9C").animate({backgroundColor:"#ffffff"},1500);
                element.addClass("activated");

		fetch("/_inc/ajax.php?file=admin/settings/template/activate.php&name="+$(this).attr("id"));
        });

});
</script>
';

$Template->add('head',$head);

$content='
<img src="/_inc/img/template.png" style="float:left"/> <h1 class="image-left">Template</h1></span>
<br/>
<ul id="template">
';

$templates=scan_dir(HOME.'_www');
$num=0;
foreach($templates as $template){
	$loc=HOME.'_www/'.$template.'/';
	if(!file_exists($loc.'index.html')||!file_exists($loc.'style.css'))
		continue;
	$array=parse_template_file($loc.'style.css');
	if($array==false)
		continue;
	$screenshot=(file_exists($loc.'screenshot.png'))?'/_www/'.$template.'/screenshot.png':'/_inc/img/screenshot.png';
	$content.='
	<li class="template-selectable">
		<p class="th"><span>'.htmlspecialchars($array['Name']).' <span class="status"></span><span></p>
		<div class="temp-cont">
	       		<img src="'.$screenshot.'" max-height="200px" max-width="250px"/>
			<br/>
			<p>'.htmlspecialchars($array['Description']).'</p>
                        <p class="right">by <a href="'.htmlspecialchars($array['Author URL']).'">'.htmlspecialchars($array['Author']).'</a></p>
			<br/>
			<p><a href="#" class="activate-link" id="'.$template.'">Activate</a> | <a href="#" class="delete-link" id="'.$template.'">Delete</a></p>
		</div>
	</li>';
}

$content.='</ul><br style="clear:both"/>';

$Template->add('content',$content);

?>
