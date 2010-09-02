/**
 * System JS Functions, Furasta.Org
 *
 * Contains javascript functions which can be expected
 * available on all admin and frontend pages.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

function rowColor(){
	$(".row-color tr").each(function(){
		if($(this).hasClass('even'))
                        $(this).removeClass('even');
		else if($(this).hasClass('odd'))
			$(this).removeClass('odd');
			
	});
	$(".row-color tr:even").addClass("even");
	$(".row-color tr:odd").addClass("odd");
}

function fAlert(message){
        $('#dialog').html('<div id="dialog-content">'+message+'</div><div id="dialog-alert-logo">&nbsp;</div>');
	$('#dialog').attr('title','Warning!');
        $('#dialog').dialog({ modal: true,buttons:{ Close:function(){ $(this).dialog('close'); }},hide:'fade',show:'fade',resizeable:false });
        $('#dialog').dialog("open");
}


function fConfirm(message,callback,param){
        $('#dialog').html('<div id="dialog-content">'+message+'</div><div id="dialog-alert-logo">&nbsp;</div>');
        $('#dialog').attr('title','Are You Sure?');
        $('#dialog').dialog({ modal: true,buttons:{ Cancel:function(){ $(this).dialog('close'); },Yes:function(){ callback(param);$(this).dialog('close'); } },hide:'fade',show:'fade',resizeable:false });
        $('#dialog').dialog("open");
}

function fHelp(message){
        $('#dialog').html('<div id="dialog-content">'+message+'</div><div id="dialog-help-logo">&nbsp;</div>');
        $('#dialog').attr('title','Help');
        $('#dialog').dialog({ modal: true,buttons:{ Close:function(){ $(this).dialog('close'); }},hide:'fade',show:'fade',resizeable:false });
        $('#dialog').dialog("open");
}

function checkConnection(details){
	var hostname=$("input[name="+details[0]+"]").val();
	var username=$("input[name="+details[1]+"]").val();
        var database=$("input[name="+details[2]+"]").val();
        var password=$("input[name="+details[3]+"]").val();

        var hash=Math.floor(Math.random()*1001);
	var dataString='hostname='+hostname+'&username='+username+'&database='+database+'&password='+password;

	$.ajax({
		type: "POST",
		data: dataString,
		url:'/install/connection.php?hash='+hash,
		async: false,
                success:function(html){
			window.html=html;
                }
	});			
	return window.html;
}

function fetch(url){
	$.ajax({
		url:url,
		success:function(html){
			if(html=='1')
				fAlert('There has been an unknown error. Please refresh the page and try again.');
		},
		error:function(){
			fAlert('There has been an error processing your request. Please check your internet connection and refresh the page.');
		}
	});
}
