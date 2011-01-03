<?php

/**
 * Overview Page, Furasta.Org
 *
 * Displays an overview of the CMS with various
 * overview items. Allows for plugin access to
 * create overview items.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_overview
 */

require 'header.php';

/**
 *  Page specific javascript below 
 */
$javascript='
$(document).ready(function(){

	/**
	 * set up the sortable overview items 
	 */
	$(".sort-container").sortable({

                placeholder:"ui-state-highlight",

                connectWith:".sort-container",

                stop:function(){

			/**
			 * save order of overview items when they are dropped 
			 */			
                        var order="";

                        $(".column_one").children(".overview-preview").each(function(){
                                order+=$(this).attr("id")+",";
                        });

                        order=order.substring(0,order.length-1)+"|";

                        $(".column_two").children(".overview-preview").each(function(){
                                order+=$(this).attr("id")+",";
                        });

                        order=order.substring(0,order.length-1);

                        fetch("/_inc/ajax.php?file=admin/overview/item_order.php&order="+order);
                }

        });

        $(".sort-container").disableSelection();

	/**
	 * change the status of the overview items ( ie. open or closed ).
	 * saves the status to a cache file
	 */
        $(".collapse-button").click(function(){

                var sib=$(this).parent().siblings(".collapse-content");

                if($(this).html()=="+"){

                        sib.removeClass("closed");

                        sib.addClass("open");

                        $(this).html("-");

                        var id=$(this).parent().parent().attr("id");

			sib.html( "<p><i>Loading ... <img src=\"/_inc/img/loading.gif\"/> </i></p>" );

                        sib.slideDown("fast");

                        fetch("/_inc/ajax.php?file=admin/overview/item_status.php&id="+id+"&status=open");

			// load content of overview item
                        var hash = Math.floor( Math.random( ) * 1001 );

                        var item = sib.parent( ).attr( "id" );

			fetch( 
				"/_inc/ajax.php?file=admin/overview/items.php&overview_item="+ item + "&hash=" + hash,
				function( sib, html ){
					$( sib ).html( html );
					rowColor( );
				},
				sib
			);

			rowColor();
                }
                else{

                        sib.removeClass("open");

                        sib.addClass("closed");

                        $(this).html("+");

                        var id=$(this).parent().parent().attr("id");

                        sib.slideUp("fast");

                        fetch( "/_inc/ajax.php?file=admin/overview/item_status.php&id="+id+"&status=closed" );

                }

        });

	/**
	 * load the content of the open overview items 
	 */
	$( ".collapse-content" ).each( function( ){

		if( $( this ).hasClass( "open" ) ){

			var thiscol = $( this );

			thiscol.html( "<p><i>Loading ... <img src=\"/_inc/img/loading.gif\"/> </i></p>" );

        		var hash = Math.floor( Math.random( ) * 1001 );

			var item = $( this ).parent( ).attr( "id" );

			fetch(
				"/_inc/ajax.php?file=admin/overview/items.php&overview_item="+ item + "&hash=" + hash,
				function( thiscol, html ){
					$( thiscol ).html( html );
					rowColor( );
				},
				thiscol
			);

		}	

	} );

});
';

/**
 * load javascript to the template class, which will compress it 
 */

$Template->loadJavascript( 'FURASTA_ADMIN_OVERVIEW' , $javascript );

/**
 * Require and proccess order of overview items
 */

$OverviewItems = new OverviewItems( );

$OverviewItems->order( );
$content = $OverviewItems->displayItems( );

$content .= '<br style="clear:both"/>';

/**
 * add page content to the template class 
 */

$Template->add( 'content', $content );
$Template->add( 'title', 'Overview' );

require 'footer.php';
?>
