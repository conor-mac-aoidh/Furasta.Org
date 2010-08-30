<?php

/**
 * Activate Template, Furasta.Org
 *
 * Activates a template. This page is accessed via AJAX.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

$name=@$_GET['name'];
if($name=='')
        exit;

if(!file_exists(HOME.'_www/'.$name.'/index.html'))
        error('Incomplete template files.','Template Error');

settings_rewrite($SETTINGS,$DB,$PLUGINS,HOME.'_www/'.$name.'/');

die('ok');

?>