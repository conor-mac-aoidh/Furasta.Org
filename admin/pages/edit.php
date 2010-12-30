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
		'pattern'	=>	"^[A-Z a-z0-9]{1,40}$"
	)
);

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
        $home = (int) @$_POST[ 'Homepage' ];
        $navigation = ( @$_POST[ 'Navigation' ] == 1 ) ? 0 : 1;
        $parent = (int) $_POST[ 'Parent' ];
        $perm = (int) $_POST[ 'perm' ];

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
		G:i:s').'",user="'.$User->name.'",parent='.$parent.',perm="'.$perm.'",home='.$home.',display='.$navigation.'
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

$Page = row( 'select * from fr_pages where id= ' . $id );

/**
 * check user has permission to edit pages
 * in general, then check for this page
 */
if( !$User->hasPerm( 'e' ) && !$User->pagePerm( $Page[ 'perm' ] ) )
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
	 * delete the page if delete button is clicked 
	 *  @todo finish!
	 */
	$("#edit-delete").click(function(){

		fConfirm("Are you sure you want to delete this page?");

		return false;

	});

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

	var saveFunction = function( ){

		alert( "test" );

	};

	$( "#page-permissions" ).click( function( ){

                $( "#page-permissions-dialog" ).dialog({

                        modal:  true,

                        width:  "500px",

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

$url = 'http://' . $_SERVER[ 'SERVER_NAME' ];

$content='
<div id="page-permissions-dialog" title="Page Permissions" style="display:none">
        <div id="complete-message" style="display:none"></div>
        <form id="page-permissions-content">
                <table style="margin-top:0">
		        <tr><td colspan="4"><h3>Who can view this page:</h3></td></tr>
			<tr>
				<td><input type="radio" class="checkbox" name="who-can-see" value="everyone"/> Everyone</td>
			</tr>
			<tr>
				<td><input type="radio" class="checkbox" name="who-can-see" value="selected"/> Selected Groups:</td>
			</tr>
			<tr>';

$groups = rows( 'select name from ' . GROUPS );

for( $i = 0; $i < count( $groups ); $i++ ){
	$content .= '<td><input disabled="disabled" type="checkbox" class="checkbox" name="see-groups" value="' . $groups[ $i ][ 'name' ] . '"/> ' . $groups[ $i ][ 'name' ] . '</td>';

	if( ( $i + 1 ) % 3 == 0 && ( $i + 1 ) < count( $groups ) )
		$content .= '</tr><tr>';
}

$content .='
			</tr>
			<tr><td colspan="4"><h3>Who can edit this page:</h3></td></tr>
                        <tr>
                                <td><input type="radio" class="checkbox" name="who-can-see" value="everyone"/>All Groups</td>
                        </tr>
                        <tr>
                                <td><input type="radio" class="checkbox" name="who-can-see" value="selected"/> Selected Groups:</td>
                        </tr>
                        <tr>';

$groups = rows( 'select name from ' . GROUPS );

for( $i = 0; $i < count( $groups ); $i++ ){
        $content .= '<td><input type="checkbox" class="checkbox" name="see-groups" value="' . $groups[ $i ][ 'name' ] . '"/> ' . $groups[ $i ][ 'name' ] . '</td>';

        if( ( $i + 1 ) % 3 == 0 && ( $i + 1 ) < count( $groups ) )
                $content .= '</tr><tr>';
}

$content .='
			</tr>
                </table>
        </form>
</div>

<span style="float:right"><a href="pages.php?page=new&parent='.$Page['id'].'" id="new-subpage"><span class="header-img" id="header-New-Page">&nbsp;</span><h1 class="image-left">New Subpage</h1></a></span>
<span><span class="header-img" id="header-Edit-Pages">&nbsp;</span><h1 class="image-left">Edit Pages</h1></span>
<br/>
<form method="post" id="pages-edit">
	<table id="page-details">
		<tr>
                        <td class="small">Name:</td>
			<td><input type="text" name="Name" id="page-name" value="'.$Page['name'].'"/></td>
			<td class="options"><a id="options-link">Show Options</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="'.$url.'/'.$Page['slug'].'" id="slug-url">'.$url.'/'.$Page['slug'].'</a></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<input type="hidden" name="slug" id="slug-put" value="'.$Page['slug'].'"/>
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

$homepage=($Page['home']==1)?'value="NA" checked="checked" disabled="disabled"':' value="1"';

$content.='
				</select></td>
				<td class="small">Is Home Page:</td>
				<td><input type="checkbox" name="Homepage"'.$homepage.'/></td>
			</tr>
			<tr>
				<td class="small">Template:</td>
				<td><select name="Template">
					<option value="Default">Default</option>
';

$files=scandir(TEMPLATE_DIR);

foreach($files as $file){
	if(substr(strrchr($file,'.'),1)=='html'){
		$file_n=basename($file,'.html');
		if($file_n=='default')
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
				<td class="small"><a class="link" id="page-permissions">Permissions</a><input type="hidden" name="perm" value=""/></td>
			</tr>
		</table>
	</div>

	<div id="pages-type-content" type="'.$Page['type'].'" page-id="'.$Page['id'].'">&nbsp;</div>

<input type="submit" name="edit-delete" value="Delete" class="submit" id="edit-delete"/>
<input type="submit" name="edit-save" value="Save" class="submit" id="edit-save"/>
</form>
';

$Template->add('content',$content);
?>
