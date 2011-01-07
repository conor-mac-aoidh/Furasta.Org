<?php

/**
 * OverviewItems Class, Furasta.Org
 *
 * Manages overview items.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_overview
 */

/**
 * OverviewItems
 *
 * This class manages the overview items.
 * 
 * @package admin_overview
 * @version 1.0
 * @author Conor Mac Aoidh <conormacaoidh@gmail.com> 
 * @license http://furasta.org/licence.txt The BSD License
 */
class OverviewItems{

	/**
	 * items
	 *
	 * Stores an array of overview items.
	 * 
	 * @var array
	 * @access private
	 */
	private $items = array( );

	/**
	 * order 
	 * 
	 * Stored the cached order of overview items.
	 *
	 * @var array
	 * @access private
	 */
	private $order = array( );

	/**
	 * status
	 * 
	 * Stores the cached status of overview
	 * items, ie open or closed.
	 * 
	 * @var array
	 * @access private
	 */
	private $status = array( );

	/**
	 * __construct 
	 * 
	 * Loads plugin overview items into the
	 * $items array.
	 *
	 * @access protected
	 * @return void
	 */
	function __construct( ){

                /**
                 * enable default overview items 
                 */
                $items = array(
                        array(
                                'name'  =>      'Website Overview',
                                'id'    =>      'website-overview',
                                'status'=>      'open'
                        ),
                        array(
                                'name'  =>      'Recently Edited',
                                'id'    =>      'recently-edited',
                                'status'=>      'open'
                        ),
                        array(
                                'name'  =>      'Recently Trashed',
                                'id'    =>      'recently-trashed',
                                'status'=>      'open'
                        ),
                        array(
                                'name'  =>      'Furasta Development Blog',
                                'id'    =>      'furasta-devblog',
                                'status'=>      'open'
                        )
                );

		/**
		 * add plugin overview items
		 */
		$Plugins = Plugins::getInstance( );
		$items = array_merge( $Plugins->adminOverviewItems( ) , $items );

		/**
		 * make sure user has permission to view trash item
		 */
		$User = User::getInstance( );
		if( !$User->hasPerm( 't' ) )
			unset( $items[ 3 ] );

		$this->items = $items;

	}

	/**
	 * order 
	 * 
	 * Re-orders the $items array according to
	 * a cached order file.
	 *
	 * @access public
	 * @return bool
	 */
	function order( ){
                $items = $this->items;

                $cache_file = 'FURASTA_OVERVIEW_ITEMS_STATUS_' . $_SESSION[ 'user' ][ 'id' ];

                if( cache_exists( $cache_file, 'USERS' ) ){
                        $items_status = json_decode( cache_get( $cache_file, 'USERS' ) );

			foreach( $items as $item => $value ){
				foreach( $items_status as $key => $status ){
					if( $value[ 'id' ] == $key ){
						$items[ $item ][ 'status' ] = $status;
						break;
					}
				}
			}
                }

		$cache_file = 'FURASTA_OVERVIEW_ITEMS_' . $_SESSION[ 'user' ][ 'id' ];

		if( cache_exists( $cache_file, 'USERS' ) )
			$order = json_decode( cache_get( $cache_file, 'USERS' ), true );
		else{
		        $order = array(
	                	'1' => array( 'website-overview', 'recently-trashed' ),
        		        '2' => array( 'recently-edited', 'furasta-devblog' )
		        );
			cache( $cache_file, json_encode( $order ), 'USERS' );
		}
		
		$ordered = array( );
		$num = 0;

		foreach( $order as $key => $value ){
			foreach( $value as $id ){
				$num++;
				foreach( $items as $item => $vals ){
					if( $vals[ 'id' ] == $id ){
						$ordered[ $key ][ $num ] = $vals;
						unset( $items[ $item ] );
						break;
					}
				}
			}
		}

		if( count( $items ) != 0 ){
			for( $i=0; $i<= ( count( $items ) -1 ); $i++ ){
				$num++;
				if( $i%2 )
					$ordered[ '2' ][ $num ]= $items[ $i ];
				else
					$ordered[ '1' ][ $num ] = $items[ $i ];
			}
		}

		return ( $this->items = $ordered );
	}

	/**
	 * displayItems
	 *
	 * Returns a formatted version of the
	 * $items array.
	 * 
	 * @access public
	 * @return string
	 */
	function displayItems( ){
		$items = $this->items;
		$content = '<div class="sort-container column_one" style="float:left">';

		foreach( $items[ '1' ] as $item ){
			$status = ( $item[ 'status' ] == 'open' ) ? '-' : '+';
                        $content .= '<div class="overview-preview" id="' . $item[ 'id' ] . '">
                                        <p class="th"><a class="collapse-button" class="right">' . $status . '</a><span>' . $item[ 'name' ] . '</span></p>'
                                        .'<div class="collapse-content '. $item[ 'status' ] . '"></div></div>';
		}

		$content .= '</div><div class="sort-container column_two" style="margin-left:50%">';

                foreach( $items[ '2' ] as $item ){
                        $status = ( $item[ 'status' ] == 'open' ) ? '-' : '+';
                        $content .= '<div class="overview-preview" id="' . $item[ 'id' ] . '">
                                        <p class="th"><a class="collapse-button" class="right">' . $status . '</a><span>' . $item[ 'name' ] .'</span></p>'
                                        .'<div class="collapse-content ' . $item[ 'status' ] . '"></div></div>';
                }

		$content .= '</div>';

		return $content;
	}
}

?>
