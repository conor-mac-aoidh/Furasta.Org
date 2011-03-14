<?php

/**
 * Edit Pages, Furasta.Org
 *
 * Displays a factility through which the user
 * can edit pages.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_pages
 * @todo       load all select boxes on this page via ajax
 */

/**
 * get page id - if none present redirect to 'new page' 
 */
$id = (int) @$_GET[ 'id' ];
if( $id == 0 )
        header( 'location pages.php?action=new' );

/**
 * get the error if isset 
 */
$error = @$_GET[ 'error' ];

if( $error != '' )
	$Template->runtimeError( $error );

/**
 * set up javascript and php form validation
 */
$conds = array(
	'Name' => array(
		'required'	=>	true,
		'minlength'	=>	2,
		'pattern'	=>	array(
			'^[A-Za-z0-9 ]{2,40}$',
			'The name field must be between 2 and 40 characters in length. It must only contain alphabetical characters, numbers and spaces.'
		)
	)
);

//$conds = $Plugins->filter( 'admin', 'filter_pages_required_conds' );

$valid = validate( $conds, "#pages-edit", 'edit-save' );

/**
 * read post information and edit page if applicable
 */
if( isset( $_POST[ 'edit-save' ] ) && $valid == true ){

	/**
	 *  set up post variables
	 */
        $name = addslashes( $_POST[ 'Name' ] );
        $type = addslashes( $_POST[ 'Type' ] );
        $template = addslashes( $_POST[ 'Template' ] );
        $content = addslashes( $_POST[ 'PageContent' ] );
        $slug = addslashes( $_POST[ 'slug' ] );
        $home = ( int ) @$_POST[ 'Homepage' ];
        $navigation = ( @$_POST[ 'Navigation' ] == 1 ) ? 0 : 1;
        $parent = (int) $_POST[ 'Parent' ];
        $perm = addslashes( @$_POST[ 'perm' ] );

	/**
	 * get pages_array and remove current page
	 */
	$pages_array = pages_array( );
	unset( $pages_array[ $id ] );

        /**
         * check if pagename exists already or if page name
	 * clashes with system pagename
         */
        if( in_array( $name, $pages_array ) == false ){

		/**
		 * if page is set as home page remove home
		 * tag from previous home page
		 */
		if( $home == 1 )
			query( 'update ' . PAGES . ' set home=0 where home=1' );

		/**
		 * update database with edited page 
		 */
		query('update '.PAGES.' set
		name="'.$name.'",content="'.$content.'",slug="'.$slug.'",template="'.$template.'",type="'.$type.'",edited="'.date('Y-m-d
		G:i:s').'",user="'.$User->name.'",parent='.$parent.',perm="'.$perm.'",home='.@$home.',display='.$navigation.'
		where id='.$id,true);

		/**
		 * clear pages cache and set status as edited 
		 */
		cache_clear( 'PAGES' );

		$Template->runtimeError( '1' );
	}
	else
		$Template->runtimeError( '4', $name );
}

/**
 * get page rows in array and stripslashes 
 */
$Page = row( 'select * from fr_pages where id= ' . $id );
$Page = stripslashes_array( $Page );

/**
 * check user has permission to edit pages
 * in general, then check for this page
 */
$perm = explode( '|', $Page[ 'perm' ] );
if( !$User->hasPerm( 'e' ) && !$User->pagePerm( $perm[ 1 ] ) )
	error( 'You have insufficient privelages to edit this page. Please contact one of the administrators.', 'Permissions Error' );

/**
 * @todo below: finish page delete! 
 */
$javascript='
$(document).ready(function(){

	/**
	 * load page type
	 */
	var type=$("#pages-type-content").attr("type");

        var id=$("#pages-type-content").attr("page-id");

        $("#pages-type-content").html("Loading... <img src=\"/_inc/img/loading.gif\"/>");

	loadPageType(type,id);

        /**
         * initial url load, also loads url from querystring parent 
         */
	var pagename = $( "#page-name" ).attr( "value" );

        getUrl( pagename );

        /**
         * display options when clicked 
         */
        $("#options-link").click(displayOptions);

        /**
         * reload page type when select box is changed 
         */
        $("#edit-type").change(function(){

                $("#pages-type-content").html("Loading... <img src=\"/_inc/img/loading.gif\"/>");

                var type=$(this).attr("value");

		loadPageType(type,id);

        });

        /**
         * set up page url 
         */
        $( "#select-parent" ).change( function(){

                var pagename = $( "#page-name" ).attr( "value" );

		getUrl( pagename );

        });

	/**
	 * re-set up page url when typing occurs
	 */
	$("#page-name").keyup(function(){

		var pagename = slugCheck( $("#page-name").val() );

		if( pagename == false )

			$("#page-name").addClass("error");

		else

			getUrl( pagename );
		
	});

        /**
         * function to run when page-permissions-dialog
         * is saved 
         */
        var saveFunction = function( ){
                var see = $( "input[name=\'who-can-see\']:checked" ).val( );
                var edit = $( "input[name=\'who-can-edit\']:checked" ).val( );

                var see_users = [];
                var edit_users = [];
                var see_groups = [];
                var edit_groups = [];
                var see_perm = "";
                var edit_perm = "";

                if( see == "selected" ){

                        var n = 0;

                        $( "input[name=\'see-users\']:checked" ).each( function( ){

                                if( !$( this ).attr( "disabled" ) ){
                                        see_users[ n ] = $( this ).val( );
                                        n++;
                                }


                        });

                        $( "input[name=\'see-groups\']:checked" ).each( function( i ){

                                see_groups[ i ] = $( this ).val( );

                        });

                        if( see_groups.length == 0 )
                                see_perm = see_users.join( "," );
                        else
                                see_perm = see_users.join( "," ) + "#" + see_groups.join( "," );


                }

                if( edit == "selected" ){

                        var n = 0;

                        $( "input[name=\'edit-users\']:checked" ).each( function( ){

                                if( !$( this ).attr( "disabled" ) ){
                                        edit_users[ n ] = $( this ).val( );
                                        n++;
                                }

                        });

                        $( "input[name=\'edit-groups\']:checked" ).each( function( i ){

                                edit_groups[ i ] = $( this ).val( );

                        });

                        if( edit_groups.length == 0 )
                                edit_perm = edit_users.join( "," );
                        else
                                edit_perm = edit_users.join( "," ) + "#" + edit_groups.join( "," );

                }

	

		var users = see_perm + "|" + edit_perm;

                $( "input[name=\'perm\']" ).attr( "value", users );

                $( this ).dialog( "close" );

        };

	$( "#page-permissions" ).click( function( ){

                /**
                 * load content if not already loaded
                 */
                if( !$( "#page-permissions-content" ).hasClass( "loaded" ) ){

                        $( "#page-permissions-content" ).load( "' . SITEURL . '_inc/ajax.php?file=admin/pages/permissions.php&id=" + id, function( ){

                                $( this ).addClass( "loaded" );

                        });

                }

                $( "#page-permissions-dialog" ).dialog({

                        modal:  true,

                        width:  "55%",

                        buttons: {
        
                                "Close": function( ) { $( this ).dialog( "close" ); },

                                "Save": saveFunction,

                        },

                        hide:   "fade",

                        show:   "fade",

                        resizeable:     false

                });

                $( "#page-permissions-dialog" ).dialog( "open" );

	});

});
';

/**
 * load javascript files to Template class for output later 
 */
$Template->loadJavascript( '_inc/js/jquery/tinymce.min.js' );
$Template->loadJavascript( '_inc/js/tiny_mce.js' );
$Template->loadJavascript( 'FURASTA_ADMIN_PAGES_EDIT', $javascript );

$content='
<div id="page-permissions-dialog" title="Page Permissions" style="display:none">
        <div id="complete-message" style="display:none"></div>
        <form id="page-permissions-content">
		<p>Loading.. <img src="' . SITEURL . '_inc/img/loading.gif"/></p>
        </form>
</div>

<span style="float:right"><a href="pages.php?page=new&parent='.$Page['id'].'" id="new-subpage"><span class="header-img" id="header-New-Page">&nbsp;</span><h1 class="image-left">New Subpage</h1></a></span>
<span><span class="header-img" id="header-Edit-Pages">&nbsp;</span><h1 class="image-left">Edit Pages</h1></span>
<br/>
<form method="post" id="pages-edit">
	<table id="page-details">
		<tr>
                        <td class="small">Name:</td>
			<td><input type="text" name="Name" id="page-name" value="'.$Page['name'].'" autocomplete="off"/></td>
			<td class="options"><a id="options-link">Show Options</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="' . SITEURL . $Page[ 'slug' ] . '" id="slug-url">' . SITEURL . $Page[ 'slug' ] . '</a></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<input type="hidden" name="slug" id="slug-put" value="' . $Page[ 'slug' ] . '"/>
	<div id="options">
		<h2>Options</h2>
		<table id="page-details">
			<tr>
				<td class="small">Type:</td>
				<td><select name="Type" id="edit-type">
					<option value="Normal">Normal</option>
';

$options=$Plugins->adminPageTypes();
foreach($options as $option){
	if($option==$Page['type'])
		$content.='<option selected="selected" value="'.$option.'">'.$option.'</option>';
	else
		$content.='<option value="'.$option.'">'.$option.'</option>';
}

$homepage = ( @$Page[ 'home' ] == 1 ) ? '<input type="checkbox" checked="checked" disabled="disabled"/><input type="hidden" name="Homepage" value="1"/>' : '<input type="checkbox" value="1" name="Homepage" />';

$content.='
				</select></td>
				<td class="small">Is Home Page:</td>
				<td>' . $homepage . '</td>
			</tr>
			<tr>
				<td class="small">Template:</td>
				<td><select name="Template">
					<option value="Default">Default</option>
';

$files = scandir( TEMPLATE_DIR );

foreach($files as $file){
	if(substr(strrchr($file,'.'),1)=='html'){
		$file_n=basename($file,'.html');
		if($file_n=='index')
			continue;
		if($file_n==$Page['template'])
			$selected=' selected="selected"';
		$content.='<option value="'.$file_n.'"'.@$selected.'>'.$file_n.'</option>';
	}
}

$navigation=($Page['display']==1)?'':' checked="checked"';

$content.='
				</select></td>
				<td class="small">Hide In Navigation</td>
				<td><input type="checkbox" value="1" name="Navigation"'.$navigation.'/></td>
			</tr>
			<tr>
				<td class="small">Parent:</td>
				<td><select id="select-parent" name="Parent">
';

$pages=array();
$query=query('select id,name,parent from '.PAGES.' order by position,name desc');
while($row=mysql_fetch_assoc($query)){
	if( $row[ 'id' ] != $Page['id'] )
        	$pages[$row['parent']][]=$row;
}

$parent=$Page['parent'];
if($parent==0){
        $content.='<option selected="selected" value="0">---</option>';
        $content.=list_parents(0,$pages,0,0);
}
else{
        $content.='<option value="0">---</option>';
        $content.=list_parents(0,$pages,0,$parent);
}

$content.='
				<td class="small"><a class="link" id="page-permissions">Permissions</a><input type="hidden" name="perm" value="' . $Page[ 'perm' ] . '"/></td>
			</tr>
		</table>
	</div>

	<div id="pages-type-content" type="'.$Page['type'].'" page-id="'.$Page['id'].'">&nbsp;</div>
<input type="submit" name="edit-save" value="Save" class="submit" id="edit-save"/>
</form>
';

$Template->add('content',$content);
?>
