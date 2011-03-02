<?php

/**
 * Configuration, Furasta.Org
 *
 * Allows for changes to the $SETTINGS variable.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

/**
 * set up form validation
 */
$conds = array(
        'Title' => array(
                'required'      =>      true
        ),
	'SubTitle' => array(
		'required'	=>	true
	),
	'URL' => array(
		'required'	=> true,
		'url'		=> true
	)
);

$valid = validate( $conds, "#config-settings", 'settings_general' );

/**
 * executed if form is submitted 
 */
if( isset( $_POST[ 'settings_general' ] ) && $valid == true ){

        $SETTINGS['site_title']=addslashes($_POST['Title']);
        $SETTINGS['site_subtitle']=addslashes($_POST['SubTitle']);
        $SETTINGS['maintenance']=(int) @$_POST['Maintenance'];
        $SETTINGS['index']=(int) @$_POST['Index'];
	$url = @$_POST[ 'URL' ];
	$constants = array( );

	/**
	 * set the diagnostic mode setting if
	 * diagnostic mode is to be enabled
	 */
	if( @$_POST[ 'DiagnosticMode' ] == 1 ){
		$SETTINGS[ 'diagnostic_mode' ] = 1;
		$SETTINGS[ 'recache' ] = 1;
		$Template->diagnosticMode = 1;
	}
	else{
		$SETTINGS[ 'diagnostic_mode' ] = 0;
		$Template->diagnosticMode = 0;
	}

	if( $url != SITEURL )
		$constants = array_merge( $constants, array( 'SITEURL' => $url ) );

        /**
         * rewrite the settings file 
         */
        settings_rewrite( $SETTINGS, $DB, $PLUGINS, $constants );
	cache_clear( );

	/**
	 * stripslashes from the settings array
	 */
	$SETTINGS = stripslashes_array( $SETTINGS );
	$Template->runtimeError( '13' );
}


$javascript = '
$(document).ready(function(){

        $("#help-index").click(function(){

                fHelp("Ticking this box will force search engines such as Google, Yahoo etc not to index this website. This option should be enabled to make the website more private, though people will still be able to access it through direct URLs.");

        });

        $("#help-maintenance").click(function(){

                fHelp("Ticking this box will enable Maintenance Mode; a feature which should be enabled while making sitewide changes. For example, if you are performing a re-structure of your website and you enable Maintenance Mode people who view the website will see a maintenance notice and will not be able to access any content. More information is available <a href=\"http://furasta.org/Help/Maintenance-Mode\">here</a>.");

        });

	$( "#help-diagnostic" ).click( function( ){

		fHelp( "<b>Note: For developers only</b><br/>The CMS carrys out all kinds of caching to make page loads quicker, including that of CSS and JavaScript. If you want to temporarily disable this feature for development reasons then you can enable Diagnostic Mode. During this time there will be no caching of CSS or JavaScript, so it is recommended to enable it again when finished testing." );

	});

	$( "#help-url" ).click( function( ){

		fHelp( "The default URL for the website. All links will use this URL. Some may want to add remove the \"www.\"" );

	});

});
';

$Template->add( 'javascript', $javascript );

$maintenance = ( $SETTINGS[ 'maintenance' ] == 1 ) ? 'checked="checked"' : '';
$index = ( $SETTINGS[ 'index' ] == 1 ) ? 'checked="checked"' : '';
$diagnostic = ( $Template->diagnosticMode == 1 ) ? 'checked="checked"' : '';
$url = ( isset( $url ) ) ? $url : SITEURL;

$content='
<span class="header-img" id="header-Configuration">&nbsp;</span><h1 class="image-left">Configuration</h1></span>
<br/>

<form method="post" id="config-settings">
<table id="config-table" class="row-color">
	<col width="50%"/>
        <col width="50%"/>
	<tr>
		<th colspan="2">Website Options</th>
	</tr>
	<tr>
		<td>Title:</td>
		<td><input type="text" name="Title" value="' . $SETTINGS[ 'site_title' ] . '" class="input" /></td>
	</tr>
	<tr>
		<td>Sub Title:</td>
		<td><input type="text" name="SubTitle" value="' . $SETTINGS[ 'site_subtitle' ] . '" class="input" /></td>
	</tr>
	<tr>
		<td>Website URL: <a class="help link" id="help-url">&nbsp;</a></td>
		<td><input type="text" name="URL" value="' . SITEURL . '" class="input" /></td>
	</tr>
	<tr>
		<td>Maintenance Mode: <a class="help link" id="help-maintenance">&nbsp;</a></td>
		<td><input type="checkbox" name="Maintenance" class="checkbox" value="1" '.$maintenance.'/></td>
	</tr>
	<tr>
		<td>Don\'t Index Website: <a class="help link" id="help-index">&nbsp;</a></td>
		<td><input type="checkbox" name="Index" value="1" class="checkbox" '.$index.'/></td>
	</tr>
        <tr>
                <td>Diagnostic Mode: <a class="help link" id="help-diagnostic">&nbsp;</a></td>
                <td><input type="checkbox" name="DiagnosticMode" value="1" class="checkbox" '. $diagnostic .'/></td>
        </tr>
</table>
<input type="submit" id="config-save" name="settings_general" class="submit right" style="margin-right:10%" value="Save"/>
</form></br style="clear:both"/>
';

$Template->add('content',$content);

?>
