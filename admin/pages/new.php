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

if( isset( $_POST[ 'new-save' ] ) ){

        $name = addslashes( $_POST[ 'Name' ] );
        $type = addslashes( $_POST[ 'Type' ] );
        $template = addslashes( $_POST[ 'Template' ] );
        $content = addslashes( $_POST[ 'PageContent' ] );
        $slug = addslashes( $_POST[ 'slug' ] );
        $home = (int) @$_POST[ 'Homepage' ];
        $navigation = ( @$_POST[ 'Navigation' ] == 1) ? 0 : 1;
        $parent = (int) $_POST[ 'Parent' ];
        $perm = (int) $_POST[ 'perm' ];

        query('insert into '.PAGES.' values ("","'.$name.'","'.$content.'","'.$slug.'","'.$template.'","'.$type.'","'.date("Y-m-d
	").'","'.$_SESSION['user']['name'].'","","'.$parent.'","'.$perm.'","'.$home.'","'.$navigation.'")');

        cache_clear( 'PAGES' );
        header( 'location: pages.php?page=edit&status=new&id=' . mysql_insert_id( ) );
}

$javascript = '
$(document).ready(function(){

        var type=$("#pages-type-content").attr("type");

        $("#pages-type-content").html("Loading... <img src=\"/_inc/img/loading.gif\"/>");

        loadPageType(type,0);

        var parent=$("#select-parent :selected").text();

        if(parent!="---"){

                var url=$("#slug-put").val();

                url=url+"/"+parent.replace(/\s/g,"-")+"/";

                $("#slug-url").text(url);

                $("#slug-put").attr("value",url);

                $("#slug-url").attr("href",url);

        }

        $("#options-link").click(displayOptions);

        $("#edit-type").change(function(){

                $("#pages-type-content").html("Loading... <img src=\"/_inc/img/loading.gif\"/>");

                var type=$(this).attr("value");

                loadPageType(type,0);

        });

        $("#pages-permissions").click(function(){  });

        $("#page-name").keyup(function(){

                var result=slugCheck($("#page-name").attr("value"));

                if(result==false)

                        $("#page-name").addClass("error");

                else{

                        var parent=$("#select-parent :selected").text();

                        var host="http://"+window.location.hostname+"/";

                        $("#page-name").removeClass("error");

                        var fullUrl=(parent=="---")?host+result:host+parent.replace(/\s/g,"-")+"/"+result;

                        $("#slug-url").html(fullUrl);

                        $("#slug-url").attr("href",fullUrl);

                        $("#slug-put").attr("value",result);

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
 * begin setup of the page 
 */

$url = 'http://' . $_SERVER[ 'SERVER_NAME' ];

$content = '
<img src="/_inc/img/new-page.png" style="float:left"/>
<h1 class="image-left">
	New Page
</h1>

<br/>

<form method="post" id="pages-edit">
	<table id="page-details">
		<tr>
                        <td class="small">Name:</td>
			<td><input type="text" name="Name" id="page-name" value=""/></td>
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
if( $parent == ''){
        $content .= '<option selected="selected" value="0">---</option>';
        $content .= list_parents( 0, $pages, 0, 0 );
}
else{
        $content .= '<option value="0">---</option>';
        $content .= list_parents( 0, $pages, 0, $parent );
}


$type = ( @$_GET[ 'type' ] == '' ) ? 'Normal' : @$_GET[ 'type '];

$content .= '
				<td class="small"><a href="#" id="pages-permissions">Permissions</a><input type="hidden" name="perm" value=""/></td>
			</tr>
		</table>
	</div>

	<div id="pages-type-content" type="' . $type . '">&nbsp;</div>

<input type="submit" name="new-save" value="Save" class="submit" id="edit-save"/>
</form>
';

/**
 * add $content string to the $Template class for output later 
 */

$Template->add( 'content', $content );
?>
