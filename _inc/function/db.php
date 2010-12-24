<?php

/**
 * Database Functions, Furasta.Org
 *
 * Contains functions which simplify the process of
 * obtaining database information.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    database
 */

function query($query,$alert=false){
	$result=mysql_query($query);
	if(!$result&&$alert==true)
		error('There has been a problem executing this MySQL query:<br/><br/>'.htmlspecialchars($query).'<br/><br/><b>MySQL Error:</b> '.mysql_error(),'MySQL Error');
	return $result;
}

function num($query,$alert=false){
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
        if(!$num&&$alert==true)
		error('There has been a problem executing this MySQL num query:<br/><br/>'.htmlspecialchars($query).'<br/><br/><b>MySQL Error:</b> '.mysql_error(),'MySQL Error');
	return $num;
}

function row($query,$alert=false){
	$result=mysql_query($query);
	$array=mysql_fetch_array($result,MYSQL_ASSOC);
	if((!$result||!$array)&&$alert==true)
		error('There has been a problem executing this MySQL rows query:<br/><br/>'.htmlspecialchars($query).'<br/><br/><b>MySQL Error:</b> '.mysql_error(),'MySQL Error');
	return $array;
}

function rows($query,$alert=false){
	$result=mysql_query($query);
	$array=array();
	while($row=mysql_fetch_array($result,MYSQL_ASSOC))
        	array_push($array, $row);
	if((!$result||!$row)&&$alert==true)
                error('There has been a problem executing this MySQL rows query:<br/><br/>'.htmlspecialchars($query).'<br/><br/><b>MySQL Error:</b> '.mysql_error(),'MySQL Error');
	return $array;
}

function single($query,$r,$alert=false){
	$result=query($query);
	$array=mysql_fetch_array($result,MYSQL_ASSOC);
        if((!$result||!$array)&&$alert==true)
		error('There has been a problem executing this MySQL single query:<br/><br/>'.htmlspecialchars($query).'<br/><br/><b>MySQL Error:</b> '.mysql_error(),'MySQL Error');
	return $array[$r];
}
?>
