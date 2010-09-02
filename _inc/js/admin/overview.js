$(document).ready(function(){
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
		var sib=$(this).parent().siblings(".collapse-content");
		if($(this).html()=="+"){
			sib.removeClass("closed");
			sib.addClass("open");
			$(this).html("-");
			var id=$(this).parent().parent().attr("id");
			sib.slideDown("fast");
			fetch("/_inc/ajax.php?file=admin/overview/item_status.php&id="+id+"&status=open");
		}
		else{
			sib.removeClass("open");
			sib.addClass("closed");
			$(this).html("+");
			var id=$(this).parent().parent().attr("id");
			sib.slideUp("fast");
			fetch("/_inc/ajax.php?file=admin/overview/item_status.php&id="+id+"&status=closed");
		}
	});
});
