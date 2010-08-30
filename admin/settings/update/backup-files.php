<?php

/**
 * File Backup, Furasta.Org
 *
 * Backs up all the files in the CMS to backup/file-backup.zip,
 * so that in the event of an upgrade failure the system can be
 * restored from the backup. 
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

$zip=new zipfile();

$files=scandir(HOME);

function zip_files($files,$dir=''){
	global $zip;
	foreach($files as $file){
		if($file=='.'||$file=='..'||$file=='update.zip'||$file=='backup')
			continue;
		if(is_dir(HOME.$dir.$file)){
			$zip->addDir($dir.$file);
			zip_files(scandir(HOME.$dir.$file),$dir.$file.'/');
		}
		else
			$zip->addFile(file_get_contents(HOME.'/'.$dir.$file),$dir.$file);
	}
}

zip_files($files);

$zip->output(HOME.'backup/file-backup.zip');

die('ok');
?>