<?php

/**
 * New Page, Furasta.Org
 *
 * A facility through which the user can create a new
 * page in the PAGES table.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_pages
 */

/**
 * set up form validation
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
 * set up post form validation 
 */
if( isset( $_POST[ 'new-save' ] ) && $valid == true ){

	/**
	 * read $_POST variables 
	 */
        $name = addslashes( $_POST[ 'Name' ] );
        $type = addslashes( $_POST[ 'Type' ] );
        $template = addslashes( $_POST[ 'Template' ] );
        $content = addslashes( $_POST[ 'PageContent' ] );
        $slug = addslashes( $_POST[ 'slug' ] );
        $home = (int) @$_POST[ 'Homepage' ];
        $navigation = ( @$_POST[ 'Navigation' ] == 1) ? 0 : 1;
        $parent = (int) $_POST[ 'Parent' ];
        $perm = addslashes( @$_POST[ 'perm' ] );

	/**
	 * make sure a duplicate pagename isn't being created,
	 * or a pagename already used by the system 
	 */
	if( in_array( $name, pages_array( ) ) == false ){

		/**
		 * if page should be new home page then remove
		 * home tag from previous home page
		 */
		if( $home == 1 )
			query( 'update ' . PAGES . ' set home=0 where home=1' );

		/**
		 * save page to database 
		 */
        	query('insert into '.PAGES.' values ("","'.$name.'","'.$content.'","'.$slug.'","'.$template.'","'.$type.'","'.date("Y-m-d
		").'","'.$User->name.'","","'.$parent.'","'.$perm.'","'.$home.'","'.$navigation.'")');

		/**
		 * clear pages cache and redirect to edit pages
		 */	
        	cache_clear( 'PAGES' );

	        header( 'location: pages.php?page=edit&error=3&id=' . mysql_insert_id( ) );
	}
	else
		$Template->runtimeError( '4', $name );
}


/**
 * page specific javascript 
 */
$javascript = '
$(document).ready(function(){

        /**
         * prepare and begin loading page type 
         */
//        var type=$("#pages-type-content").attr("type");

//        $("#pages-type-content").html("Loading... <img src=\"/_inc/img/loading.gif\"/>");

//        loadPageType(type,0);

	/**
	 * initial url load, also loads url from querystring parent 
	 */
	var parent = queryString( "parent" );

	if( parent != "" )

		getUrl( "" );

	/**
	 * set up page url when parent select box is changed
	 */
        $( "#select-parent" ).change( function(){

		var pagename = $( "#page-name" ).attr( "value" );

		getUrl( pagename );

	});

	/**
	 * allow page options to be displayed 
	 */
        $("#options-link").click(displayOptions);

	/**
	 * load a new page type if page type select box is changed 
	 *
        $("#edit-type").change(function(){

                $("#pages-type-content").html("Loading... <img src=\"/_inc/img/loading.gif\"/>");

                var type=$(this).attr("value");

                loadPageType(type,0);

        });*/


        /**
         * load url when key is typed 
         */
        $("#page-name").keyup(function(){

                var pagename = slugCheck( $( "#page-name" ).attr( "value" ) );

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

		/**
		 * load content if not already loaded
		 */
		if( !$( "#page-permissions-content" ).hasClass( "loaded" ) ){

			$( "#page-permissions-content" ).load( "' . SITEURL . '_inc/ajax.php?file=admin/pages/permissions.php", function( ){

				$( this ).addClass( "loaded" );

			});

		}

        });


});
';

/**
 * Load javascript files 
 */
$Template->loadJavascript( '_inc/js/jquery/tinymce.min.js' );
$Template->loadJavascript( '_inc/js/tiny_mce.js' );
$Template->loadJavascript( 'FURASTA_ADMIN_PAGES_NEW', $javascript );

/**
 * create base url 
 */
$url = substr( SITEURL, 0, -1 );

$content = '
<div id="page-permissions-dialog" title="Page Permissions" style="display:none">
        <div id="complete-message" style="display:none"></div>
        <form id="page-permissions-content">
		<p>Loading.. <img src="' . SITEURL . '_inc/img/loading.gif"/></p>
	</form>
</div>


<span class="header-img" id="header-New-Page">&nbsp;</span>
<h1 class="image-left">
	New Page
</h1>

<br/>

<form method="post" id="pages-edit">
	<table id="page-details">
		<tr>
                        <td class="small">Name:</td>
			<td><input type="text" name="Name" id="page-name" value="" autocomplete="off"/></td>
			<td class="options"><a id="options-link">Show Options</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="' . $url . '" id="slug-url">' . $url . '/</a></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<input type="hidden" name="slug" id="slug-put" value="' . $url . '"/>
	<div id="options">
		<h2>Options</h2>
		<table id="page-details">
			<tr>
				<td class="small">Type:</td>
				<td><select name="Type" id="edit-type">
					<option default="default">Normal</option>
';


/**
 * load page type options from installed plugins via the $Plugin class 
 */
$options = $Plugins->adminPageTypes( );
foreach( $options as $option )
	$content .= '<option>' . $option . '</option>';

$content .= '
				</select></td>
				<td class="small">Is Home Page:</td>
				<td><input type="checkbox" value="1" name="Homepage"/></td>
			</tr>
			<tr>
				<td class="small">Template:</td>
				<td><select name="Template">
					<option>Default</option>
				</select></td>
				<td class="small">Hide In Navigation</td>
				<td><input type="checkbox" value="1" name="Navigation"/></td>
			</tr>
			<tr>
				<td class="small">Parent:</td>
				<td><select name="Parent" id="select-parent">
';

/**
 * load possible page parents and display in an indented list 
 */
$pages = array( );
$query = query( 'select id,name,parent from ' . PAGES . ' order by position,name desc' );
while( $row = mysql_fetch_assoc( $query ) )
        $pages[ $row[ 'parent' ] ][ ] = $row;

$parent = addslashes( @$_GET[ 'parent' ] );
if( $parent == '' ){
        $content .= '<option selected="selected" parent="0" value="0">---</option>';
        $content .= list_parents( 0, $pages, 0, 0 );
}
else{
        $content .= '<option value="0" parent="0">---</option>';
        $content .= list_parents( 0, $pages, 0, $parent );
}

$content .= '
				<td class="small">
					<a class="link" id="page-permissions">Permissions</a>
					<input type="hidden" name="perm" value="|"/>
				</td>
			</tr>
		</table>
	</div>

	<div id="pages-type-content">';

	$Template->add( 'content', $content );

	if( isset( $_GET[ 'type' ] ) )
		$Plugins->adminPageType( str_replace( '-', ' ' , $_GET[ 'type' ] ), 0 );
	else
                $Template->add( 'content', '<textarea id="page-content" name="PageContent" class="tinymce" style="width:100%">&nbsp;</textarea>' );

$content = '
</div>

<input type="submit" name="new-save" value="Save" class="submit" id="edit-save"/>
</form>
';

/**
 * add $content string to the $Template class for output later 
 */

$Template->add( 'content', $content );
?>
