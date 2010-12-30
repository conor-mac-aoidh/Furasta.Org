<?php

/**
 * Multiple Actions on Plugins, Furasta.Org
 *
 * Performs multiple actions on plugins, such as
 * Activate, Deactivate, Delete etc. This page is 
 * accessed via AJAX.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_settings
 */

$action= @$_GET[ 'act' ];
$boxes = @$_GET[ 'boxes' ];
$boxes = explode( ',', $boxes );

/**
 * switch between possible actions 
 */
switch( $action ){
	case 'Activate':
		foreach( $boxes as $box ){
                        if( !in_array( $box, $PLUGINS ) ){
				/**
				 * add plugin to plugin array
				 */
				array_push( $PLUGINS, $box );

				/**
				 * run plugin install file
				 */
				$file = HOME . '_plugins/' . $box . '/install.php';
				if( file_exists( $file ) )
				        require $file;
			}
		}
	break;
	case 'Deactivate':
		foreach( $boxes as $box ){
                        if( in_array( $box, $PLUGINS ) ){
                                /**
                                 * remove plugin from plugin array 
                                 */
                                $PLUGINS = array_flip( $PLUGINS );
				unset( $PLUGINS[ $box ] );
				$PLUGINS = array_flip( $PLUGINS );

				/**
				 * run plugin uninstall script
				 */
				$file = HOME . '_plugins/' . $box . '/uninstall.php';
				if( file_exists( $file ) )
				        require $file;
			}
		}
	break;
	case 'Delete':
		foreach( $boxes as $box ){
			$dir = HOME . '_plugins/' . $box;

			/**
			 * run uninstall script
			 */
			$file = HOME . '_plugins/' . $box . '/uninstall.php';
			if( file_exists( $file ) )
			        require $file;

			/**
			 * remove plugin files
			 */
			if( is_dir( $dir ) )
			        remove_dir( $dir );

			/**
			 * remove plugin from plugins array
			 */
			if( in_array( $box, $PLUGINS ) ){
                                $PLUGINS = array_flip( $PLUGINS );
                                unset( $PLUGINS[ $box ] );
                                $PLUGINS = array_flip( $PLUGINS );
			}
		}
	break;
	default:
		error( 'Please contact bugs@macaoidh.name', 'Unknown Error' );
}

/**
 * rewrite settings file and clear cache 
 */
settings_rewrite( $SETTINGS, $DB, $PLUGINS );
cache_clear( );

header( 'location: settings.php?page=plugins' );
?>
