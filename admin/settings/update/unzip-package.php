<?php

/**
 * Unzip Package, Furasta.Org
 *
 * Unzips the update package to the HOME directory,
 * using the dUnzip2 class.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

$zip_file=USERFILES.'update.zip';
if(!file_exists($zip_file))
	exit;

$unzip=new dUnzip2($zip_file);

$unzip->unzipAll(HOME);

die('ok');
?>
