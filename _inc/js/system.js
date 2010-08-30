/**
 * Admin JS Functions, Furasta.Org
 *
 * Contains javascript functions which can be expected
 * available on all admin pages.
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

function loadModule(mod,put){
        var hash=Math.floor(Math.random()*1001);
        $.ajax({
		url:'/_inc/ajax.php?file='+mod+'&hash='+hash,
		success: function(html){
			if(put){
				$(put).html(html);
				$(put).fadeIn(800);
			}
		}
	});
	return;
}

function loadPageType(type,id){
        var hash=Math.floor(Math.random()*1001);
        $.ajax({
                url:'/admin/pages/type.php?type='+type+'&id='+id+'&hash='+hash,
                success: function(html){
			$("#pages-type-content").html(html);
                }
        });
        return;
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

function displayOptions(){
	var options=$("#options");
	if(options.is(":hidden")){
		$("#options-link").html("Hide Options");
		$(options).slideDown('slow');
	}
	else{
		$("#options-link").html("Show Options");
		$(options).slideUp('slow');
	}

	return;
}

function pagePermissions(id){
	var html='<script type="text/javascript">$(document).ready(function(){var hash=Math.floor(Math.random()*1001);$.ajax({url:"/admin/pages/permissions.php?id='+id+'",success: function(html){$("#permissions-content").html(html);}});});</script><div id="permissions-content">Loading... <img src="/_inc/img/loading.gif"/></div><br style="clear:both"/>';
        $('#dialog').html(html);
        $('#dialog').attr('title','Permissions');
        $('#dialog').dialog({ modal: true,buttons:{ Cancel: function(){ $(this).dialog('close'); }, Save:function(){ $(this).dialog('close'); }},hide:'fade',show:'fade',resizeable:false });
        $('#dialog').dialog("open");

}

function passwordReminder(){
	var html='<script type="text/javascript">$(document).ready(function(){ $("#jqi_state0_buttonSend").click(function(){ $("#loading-icon").html("<img src=\'/_inc/img/loading.gif\'/>");var email=$("#reminder-email").val();$.ajax({url:"/_inc/ajax.php?file=admin/settings/users/verify_email.php&email="+email,success: function(html){ if(html==1) $("#loading-icon").html("error"); else{ $("#loading-icon").html("success"); setTimeout(function(){ $("#jqibox").fadeOut("slow"); },300); } }}); return false; }); });</script><p style="margin-left:0;font-style:italic">Enter your email address below and a new password will be sent to you.</p><table><td class="small">Email:</td><td><input type="text" id="reminder-email" name="Email"/></td><td class="small" id="loading-icon"></td></tr></table><br/>';
        $('#dialog').html(html);
        $('#dialog').attr('title','Password Reminder');
        $('#dialog').dialog({ modal: true,buttons:{ Cancel: function(){ $(this).dialog('close'); }, Send:function(){ $(this).dialog('close'); }},hide:'fade',show:'fade',resizeable:false });
        $('#dialog').dialog("open");
}

function newPage(){
	var html='<table><tr><td class="small">Name:</td><td><input type="text" name="PageName"/></td><td class="medium" id="name-value"></td></tr><tr><td class="small">Type:</td><td><select name="type"></select></td><td class="medium" id="type-value"></td></tr><tr><td class="small"></td><td></td><td class="medium"></td></tr></table>';
        $('#dialog').html(html);
        $('#dialog').attr('title','New Page');
        $('#dialog').dialog({ modal: true,buttons:{ Cancel: function(){ $(this).dialog('close'); }, Create:function(){ $(this).dialog('close'); }},hide:'fade',show:'fade',resizeable:false });
        $('#dialog').dialog("open");
}

function slugCheck(url){
	if(!/^[A-Za-z0-9 ]{2,40}$/.test(url))
		return false;
	if(url==1)
		return false;
	url=url.replace(/\s/g,'-');
	return url;
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
