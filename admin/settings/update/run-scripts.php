<?php

/**
 * Run Update Scripts, Furasta.Org
 *
 * Runs whatever script is contained in the update package.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

$script=HOME.'update.php';

if(!file_exists($script))
	die();

require $script;

cache_clear();

remove_dir(HOME.'update');
unlink(HOME.'update.php');
unlink(HOME.'update.zip');

die('ok');
?>