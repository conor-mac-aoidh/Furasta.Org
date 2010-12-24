<?php

/**
 * Database Backup, Furasta.Org
 *
 * Dumps the content of the current database to /backup/db-backup.sql
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 * @todo	   change the location of the backup folder to inside the
 * 			   _user dir and create that dir during installation.
 */

if(!is_dir(USERFILES.'backup'))
	mkdir(USERFILES.'backup');

$dump=new MYSQL_DUMP($DB['host'],$DB['user'],$DB['pass']);

$sql=$dump->dumpDB($DB['name']);

$dump->save_sql($sql,USERFILES.'backup/db-backup.sql');

die('ok');
?>
