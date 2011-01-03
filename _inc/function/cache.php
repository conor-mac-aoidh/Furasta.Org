<?php

/**
 * Cache Functions, Furasta.Org
 *
 * This file contains all of the functions used in the caching process.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    cache_system
 */

/**
 * cache 
 * 
 * Used to make a cache of data that could be
 * hardware intensive, for re-usage later
 *
 * @param mixed $name of cache file 
 * @param mixed $content to be cached
 * @param string $dir of containing cache file, if applicable
 * @access public
 * @return bool
 */
function cache($name,$content,$dir=''){
        if($dir!=''){
		if(substr($dir,-1)!='/')
	                $dir=$dir.'/';
		if(!is_dir(USERFILES.'cache/'.$dir))
			mkdir(USERFILES.'cache/'.$dir);
	}

	$file=USERFILES.'cache/'.$dir.md5($name);
	return file_put_contents($file,$content);
}

/**
 * cache_get 
 *
 * Used to retrieve cached information
 *  
 * @param mixed $name of cache file
 * @param string $dir of containing cache file, if applicable
 * @access public
 * @return bool
 */
function cache_get($name,$dir=''){
        if($dir!=''&&substr($dir,-1)!='/')
                $dir=$dir.'/';

	$file=USERFILES.'cache/'.$dir.md5($name);
	return file_get_contents($file);
}

/**
 * cache_is_good 
 * 
 * Used to check validity of a cache file
 *
 * @param mixed $name of cache file
 * @param mixed $length of time elapsed before file should be regenerated
 * @param string $dir of containing cache file, if applicable
 * @access public
 * @return bool
 */
function cache_is_good($name,$length,$dir=''){
        if($dir!=''&&substr($dir,-1)!='/')
                $dir=$dir.'/';
	
	$file=USERFILES.'cache/'.$dir.md5($name);

	if(!file_exists($file))
		return false;

	if( ( time( ) - $length ) < filemtime( $file ) )
		return true;

	return false;
}

/**
 * cache_clear
 *
 * Used to clear the cache. If no $dir is specified all
 * cache will be cleared.
 * 
 * @param string $dir to be removed, if applicable
 * @access public
 * @return bool
 */
function cache_clear($dir=''){
	if($dir!=''&&substr($dir,-1)!='/')
		$dir=$dir.'/';

	$dir=USERFILES.'cache/'.$dir;

	if(!is_dir($dir))
		return false;

	$handle=opendir($dir);

	while(false!==($read=readdir($handle))){
		if($read=='.'||$read=='..')
			continue;
		if(!is_dir($dir.$read))
			unlink($dir.$read);
		else
			remove_dir($dir.$read);
	}
	
	closedir($handle);
	return true;
}

/**
 * cache_exists
 *
 * If you are entirely confident that your cache file
 * should never expire you can use this file to see if
 * the cache exists, otherwise you should use the
 * cache_is_good function, which allows a time period
 * before the file becomes invalid.
 *
 * @param string $file to check if exists
 * @param string $dir optional dir containing file
 * @access public
 * @return bool
 */

function cache_exists($file,$dir=''){
	if($dir!=''&&substr($dir,-1)!='/')
		$dir=$dir.'/';

	return (file_exists(USERFILES.'cache/'.$dir.md5($file)));
}

?>
