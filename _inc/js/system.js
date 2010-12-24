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

/**
 * rowColor 
 * 
 * adds odd and even classes to trs in all tables
 * with the row-color class
 *
 * @access public
 * @return void
 */
function rowColor( ){
	$( ".row-color tr:even" ).not( ":hidden" ).removeClass( "odd" ).addClass( "even" );
	$( ".row-color tr:odd" ).not( ":hidden" ).removeClass( "even" ).addClass( "odd" );
}

function fAlert(message){
        $('#dialog').html('<div id="dialog-content">'+message+'</div><div id="dialog-alert-logo">&nbsp;</div>');
	$('#dialog').attr('title','Warning!');
        $('#dialog').dialog({ modal: true,buttons:{ Close:function(){ $(this).dialog('close'); }},hide:'fade',show:'fade',resizeable:false });
        $('#dialog').dialog("open");
}


function fConfirm(message,callback,param){
        $('#dialog').html('<div id="dialog-content">'+message+'</div><div id="dialog-confirm-logo">&nbsp;</div>');
        $('#dialog').attr('title','Are You Sure?');
        $('#dialog').dialog({ modal: true,buttons:{ Cancel:function(){ $(this).dialog('close'); },Yes:function(){ callback(param);$(this).dialog('close'); } },hide:'fade',show:'fade',resizeable:false });
        $('#dialog').dialog("open");
}

function fHelp(message){
        $('#dialog').html('<div id="dialog-content">'+message+'</div><div id="dialog-help-logo">&nbsp;</div>');
        $('#dialog').attr('title','Information');
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

/**
 * fetch 
 * 
 * @param string url to be fetched
 * @param selector put - optional place to put loaded content
 * @access public
 * @return void
 */
function fetch( url, callback, param ){
	$.ajax({
		url	:	url,
		timeout	:	5000,
		success	:	function( html ){
					if( html == '1' )
						fAlert( 'There has been an unknown error. Please refresh the page and try again.' );

					else if( callback != null )
						callback( param, html );

				},
		error	:	function( ){
					fAlert( 'There has been an error processing your request. Please <a href="javascript:window.location.reload()">refresh the page</a> and try again. ' );
					if( callback != null )
						callback( param, "content not loaded" );

				}
	});
}

/**
 * queryString
 *
 * returns the value of the querystring requested
 * with the name var
 * 
 * @param string name of querystring section to return
 * @access public
 * @return string
 */
function queryString( name ){
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]"+name+"=([^&#]*)";
	var regex = new RegExp( regexS );
	var results = regex.exec( window.location.href );
	if( results == null )
		return "";
  	else
    		return decodeURIComponent(results[1].replace(/\+/g, " "));
}



