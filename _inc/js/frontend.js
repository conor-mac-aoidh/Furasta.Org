/**
 * Frontend JS, Furasta.Org
 *
 * Javascript functions available in the frontend, and
 * scripts to be executed in the frontend,
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 */

$(document).ready(function(){
	$('.furasta-menu-0').dropDownMenu({timer:1500,parentMO:'parent-hover',childMO:'child-hover1'});
	var base=parent.location.protocol+'//'+window.location.hostname;
        var path=document.URL;
        if(path==base||path==base+'/')
                $('.homepage').addClass('current');
        else{
		if(path.substring(path.length-1)!='/')
			path=path+'/';
                $('.furasta-menu-0 li a[href=\''+path+'\']').addClass('current');
	}
});
