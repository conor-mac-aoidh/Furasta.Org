<?php

/**
 * Overview Page, Furasta.Org
 *
 * Displays an overview of the CMS with various
 * overview items. Allows for plugin access to
 * create overview items.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_overview
 */

require 'header.php';

$jquery='
        $(".sort-container").sortable({
		placeholder:"ui-state-highlight",
		connectWith:".sort-container",
		stop:function(){
			var order="";
			$(".column_one").children(".overview-preview").each(function(){
				order+=$(this).attr("id")+",";
			});
                        order=order.substring(0,order.length-1)+"|";
                        $(".column_two").children(".overview-preview").each(function(){
                                order+=$(this).attr("id")+",";
                        });
			order=order.substring(0,order.length-1);
			fetch("/_inc/ajax.php?file=admin/overview/item_order.php&order="+order);
		}
	});
	$(".sort-container").disableSelection();
	$(".collapse-button").click(function(){
		if($(this).html()=="+"){
			var el=$(this).parent().parent();
			el.removeClass("closed");
			el.addClass("open");
                        $(this).html("-");
			var id=el.attr("id");
			fetch("/_inc/ajax.php?file=admin/overview/item_status.php&id="+id+"&status=open");
		}
		else{
			var el=$(this).parent().parent();
                        el.removeClass("open");
                        el.addClass("closed");
			$(this).html("+");
                        var id=el.attr("id");
                        fetch("/_inc/ajax.php?file=admin/overview/item_status.php&id="+id+"&status=closed");
		}
		$(this).parent().siblings(".collapse-content").slideToggle("fast");
	});
';

$Template->add('jquery',$jquery);

/**
 * Require and proccess order of overview items
 */

$OverviewItems=new OverviewItems();

require HOME.'admin/overview/items.php';

$OverviewItems->order();
$content=$OverviewItems->displayItems();

$content.='<br style="clear:both"/>';

$Template->add('content',$content);
$Template->add('title','- Overview');

require 'footer.php';
?>
