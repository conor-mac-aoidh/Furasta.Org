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

/**
 * query 
 * 
 * used instead of mysql_query, checks for failures
 * and allows for debugging
 *
 * @param string $query 
 * @param bool $alert 
 * @access public
 * @return mixed
 */
function query( $query, $alert = false ){

	$result = mysql_query( $query );

	/**
	 * if the query fails check if alert is true or that
	 * diagnostic_mode is enabled for debugging
	 */
	if( !$result && ( $alert == true || DIAGNOSTIC_MODE == true ) )
		error( 'There has been a problem executing this MySQL query:<br/><br/>' . htmlspecialchars( $query ) . '<br/><br/><b>MySQL Error:</b> ' . mysql_error( ) , 'MySQL Error' );

	return $result;
}

/**
 * num 
 *
 * used instead of performing mysql_query and mysql_num_rows,
 * this function returns the number of rows and allows for
 * debugging
 * 
 * @param string $query 
 * @param bool $alert 
 * @access public
 * @return mixed
 */
function num( $query, $alert = false ){

	$result = query( $query );

	$num = mysql_num_rows( $result );

        /**
         * if the query fails check if alert is true or that
	 * diagnostic mode is enabled for debugging 
         */
        if( !$num && ( $alert == true || DIAGNOSTIC_MODE == true ) )
		error( 'There has been a problem executing this MySQL num query:<br/><br/> ' .htmlspecialchars( $query ) . '<br/><br/><b>MySQL Error:</b> ' . mysql_error( ) , 'MySQL Error' );

	return $num;
}

/**
 * row 
 *
 * used instead of performing mysql_query and mysql_fetch_array
 * also allows for debugging. returns one matching row
 * 
 * @param string $query 
 * @param bool $alert 
 * @access public
 * @return mixed
 */
function row( $query, $alert = false ){

	$result = query( $query );

	$array = mysql_fetch_array( $result, MYSQL_ASSOC );

	/**
	 * if the query fails check if alert is true or that
	 * diagnostic mode is enabled for debugging
	 */
	if( !$array && ( $alert == true || DIAGNOSTIC_MODE == true ) )
		error( 'There has been a problem executing this MySQL rows query:<br/><br/>' . htmlspecialchars( $query ) . '<br/><br/><b>MySQL Error:</b> ' . mysql_error( ) , 'MySQL Error' );

	return $array;
}

/**
 * rows 
 * 
 * used instead of performing mysql_query and mysql_fetch_array
 * also allows for debugging. returns all rows
 * 
 * @param string $query 
 * @param bool $alert 
 * @access public
 * @return mixed
 */
function rows( $query, $alert = false ){

	$result = query( $query );

	/**
	 * add all rows to the array
	 */
	$array = array( );
	while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) )
        	array_push( $array, $row );

	/**
	 * if the query fails check if alert is true or that
	 * diagnostic mode is enabled for debugging
	 */
	if( !$row && ( $alert == true || DIAGNOSTIC_MODE == true ) )
                error( 'There has been a problem executing this MySQL rows query:<br/><br/>' . htmlspecialchars( $query ) . '<br/><br/><b>MySQL Error:</b> ' . mysql_error( ) , 'MySQL Error' );

	return $array;
}

/**
 * single 
 *
 * returns a single element of the row
 * 
 * ex. single( 'select name from students where id=12032', 'name' );
 * 
 * the above example will return the value of the 'name' element
 * 
 * @param string $query 
 * @param string $r 
 * @param bool $alert 
 * @access public
 * @return mixed
 */
function single( $query, $r, $alert = false ){

	$result = query( $query );

	$array = mysql_fetch_array( $result, MYSQL_ASSOC );

        /**
         * if the query fails check if alert is true or that
	 * diagnostic mode is enabled for debugging 
         */
        if( !$array && ( $alert == true || DIAGNOSTIC_MODE == true ) )
		error( 'There has been a problem executing this MySQL single query:<br/><br/>' . htmlspecialchars( $query ) . '<br/><br/><b>MySQL Error:</b> ' . mysql_error( ), 'MySQL Error' );

	return $array[ $r ];
}
?>
