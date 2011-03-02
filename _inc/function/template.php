<?php

/**
 * Template Functions, Furasta.Org
 *
 * Alows quick, shorthand access to the template class,
 * to be expanded on!
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_template
 */

/**
 * _e
 *
 * Echos using the template class, a shorthand
 * version of $Template->add( 'content', $content );
 * This function also echos using language files
 * which is a feature still being worked on
 * 
 * @param string $message
 * @access public
 * @return void
 */
function _e( $content ){

	$Template = Template::getInstance( );

	// todo add language file processing

	$Template->add( 'content', $content );

}

?>
