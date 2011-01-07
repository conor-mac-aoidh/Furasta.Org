<?php

/**
 * Change Template, Furasta.Org
 *
 * Allows the user to edit which template is used in
 * the frontend of the website.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

$javascript='
$(document).ready(function(){

        $(".delete-link").click(function(){

                fConfirm("Are you sure you want to delete this template?",function(element){

                        fetch( "/_inc/ajax.php?file=admin/settings/template/delete.php&name="+element.attr("id"), function( element, html ){

                                if( html == "perm" )
                                        fAlert( "You have insufficient privelages to delete this page." );

                                else{

                                        element.parent().parent().parent( ).fadeOut( "slow", function( ){

                                                $( this ).remove( );

                                        });

                                }

                        }, element );

                },$(this));

        });

});
';

$Template->loadJavascript( 'FURASTA_ADMIN_SETTINGS_TEMPLATE', $javascript );

$content='
<span class="header-img" id="header-Template">&nbsp;</span><h1 class="image-left">Template</h1></span>
<br/>
<ul id="template">
';

$templates=scan_dir(HOME.'_www');
$validated = array( );

foreach($templates as $template){
	$loc=HOME.'_www/'.$template.'/';
	if(!file_exists($loc.'index.html')||!file_exists($loc.'style.css'))
		continue;
	$array=parse_template_file($loc.'style.css');
	if($array==false)
		continue;

	if( !isset( $array[ 'Name' ] ) )
		continue;

	if( $template == basename( TEMPLATE_DIR ) ){
		array_unshift( $validated, $array );
		continue;
	}

	$array[ 'slug' ] = $template;
	array_push( $validated, $array );
}

$i=0;
foreach( $validated as $array ){
	$screenshot=(file_exists(HOME.'_www/'.$slug.'/screenshot.png'))?'<img src="' . SITEURL . '_www/'.$slug.'/screenshot.png" width="248px"/>': '<p>No Screenshot</p>';
	if( $i == 0 ){
		$class = ' activated';
		$status = 'Activated';
	}
	else{
		$activate = '<a href="' . SITEURL . 'admin/settings/template/activate.php?name=' . $slug . '" class="activate-link">Activate</a> | <a class="delete-link link" id="'.$slug.'">Delete</a>';
		$class = '';
		$status = '';
	}

	$content.='
	<li class="template-selectable' . $class . '">
		<p class="th"><span>'.htmlspecialchars($array['Name']).'</span><span class="status">' . $status . '</span></p>
		<div class="temp-cont">
	       		<div id="temp-screenshot">'.$screenshot.'</div>
			<br/>
			<p>'.htmlspecialchars(@$array['Description']).'</p>
                        <p class="right">by <a href="'.htmlspecialchars(@$array['Author URL']).'">'.htmlspecialchars(@$array['Author']).'</a></p>
			<br/>
			<p>' . @$activate . '</p>
		</div>
	</li>';

	$i++;
}

$content.='</ul><br style="clear:both"/>';

$Template->add('content',$content);

?>
