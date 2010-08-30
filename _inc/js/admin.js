/**
 * General JS, Furasta.Org
 *
 * Not a very developed file as yet, but it is
 * planned to contain more general functions to
 * the admin area etc.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @todo	   expand on this file and remove js from individula files
 */

$(document).ready(function(){
	if($('.row-color'))
		rowColor();
	$('#menu ul').dropDownMenu({timer:1500,parentMO:'parent-hover'});
	var path=location.pathname;
	if(path=='/admin'||path=='/admin/')
		$('#Overview').addClass('current');
	else
		$('#menu li a[href=\''+path+'\']').addClass('current');
});

