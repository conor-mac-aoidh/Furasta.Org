/**
 * Admin JS, Furasta.Org
 *
 * Contains admin js functions and some
 * javascript to be executed on admin
 * pages.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

$(document).ready(function(){
	if($('.row-color'))
		rowColor();
	$('#menu ul').dropDownMenu({timer:1500,parentMO:'parent-hover'});
	var path=location.pathname;
	if(path=='/admin'||path=='/admin/')
		$('#Overview').addClass('current');
	else{
		$('#menu li a[href=\''+path+'\']').addClass('current');
	}
});

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
function slugCheck(url){
        if(!/^[A-Za-z0-9 ]{2,40}$/.test(url))
                return false;
        if(url==1)
                return false;
        url=url.replace(/\s/g,'-');
        return url;
}
