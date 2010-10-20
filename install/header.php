<?php

/**
 * Install Header, Furasta.Org
 *
 * Header which is loaded on all the install pages.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package	   installer
 */

define('HOME',substr(dirname(__FILE__),0,-7));

$function_dir=HOME.'_inc/function/';
require $function_dir.'system.php';
require $function_dir.'db.php';

if(file_exists(HOME.'.settings.php'))
	error('You can\'t install Furasta CMS because it is already installed. If you would like to re-install then simply remove the <i>../.settings.php</i> file and reload this page.','Already Installed!');

$Template = Template::getInstance( );

$head='
<script type="text/javascript" src="/_inc/js/system.js"></script>
<script type="text/javascript" src="/_inc/js/validate.js"></script>
<script type="text/javascript">
$(document).ready(function(){
        if($(".row-color")){
                $(".row-color tr:even").addClass("even");
                $(".row-color tr:odd").addClass("odd");
        }
});
</script>
';

$Template->add('head',$head);
$Template->add('title','Furasta.Org Installation');

session_start();

/**
 * calculate_url
 *
 * Accurately calculates the URL where the CMS
 * is being installed. Removes the install
 * dir and the file name from the URL, it also
 * has support for https and different ports.
 * 
 * @access public
 * @return string
 */
function calculate_url(){
	$url='http';

	if($_SERVER['HTTPS']=='on')
		$url.='s';

	$url.='://'.$_SERVER['SERVER_NAME'];

	if($_SERVER['SERVER_PORT']!='80')
  		$url.=':'.$_SERVER['SERVER_PORT'];

	$end=explode('/',$_SERVER['REQUEST_URI']);
	array_pop($end);
	$url.=implode('/',$end);

	$url=substr($url,0,-7);	

	return $url;
}
?>
