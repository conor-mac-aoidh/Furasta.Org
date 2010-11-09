<?php
$Template->diagnosticJavascript=1;
/**
 * List Pages, Furasta.Org
 *
 * Lists pages in the PAGES table.
 *
 * @author     Conor Mac Aoidh <conormacaoidh@gmail.com>
 * @license    http://furasta.org/licence.txt The BSD License
 * @version    1.0
 * @package    admin_pages
 */

/**
 * @todo finish the sortable below 
 */
$javascript = '
function toggleGroupedChildren( id ){

	$( "#" + id ).toggleClass( "grouped" );

	var splitID = String( id ).split( "-" );

	splitID = splitID[ splitID.length -1 ];

        var children = $( ".child-of-node-" + splitID );

	if( children ){

	        children.each(function( ){

			$( this ).toggleClass( "grouped" );

                	if( $( this ).hasClass( "parent" ) ){

                        	var parent = $( this ).attr( "id" );

	                        parent = String( parent ).split( "-" );
        
        	                parent = parent[ parent.length - 1 ];

                	        toggleGroupedChildren( parent );

	                }

        	});
	}
}

function reclassifyItem( item ){

                var prevID = $( item ).prev( ).attr( "id" );

                var prev = $( "#" + prevID );

                var prevParentID = prev.attr( "class" ).match( /child-of-node\-.+?\b/ );

		var parent = item.attr( "class" ).match( /child-of-node\-.+?\b/ );
		
                if( prev.hasClass( "parent" ) && prev.hasClass( "expanded" ) ){

                        prevID = String( prevID ).split( "-" );
        
                        prevID = prevID[ prevID.length - 1 ];

                        item.addClass( "child-of-node-" + prevID );

                        var padding = prev.children( "td" ).eq( "1" ).css( "padding-left" );
                        padding = parseInt( padding.substring( 0, padding.length - 2 ) ) + 19;

                        item.children( "td" ).eq( 1 ).css( "padding-left", padding );

                }
                else if( prevParentID != null ){

                        prevParentID = String( prevParentID ).split( "-" );
        
                        prevParentID = prevParentID[ prevParentID.length -1 ];

                        item.addClass( "child-of-node-" + prevParentID );

                        var padding = prev.children( "td" ).eq( "1" ).css( "padding-left" );
                        padding = parseInt( padding.substring( 0, padding.length - 2 ) );

                        item.children( "td" ).eq( 1 ).css( "padding-left", padding );

                }
                else{

                        var classes = item.attr( "class" ).split( /\s+/ );

                        for( var i = 0; i < classes.length; i++ ){

                                if( classes[ i ].match( /child-of-node\-.+?\b/ ) )

                                        item.removeClass( classes[ i ] );

                        }

			if( parent != null ){

                                        var parentID = String( parent ).split( "-" );
					parentID = parentID[ parentID.length - 1 ];

                                        if( $( ".child-of-node-" + parentID ).length == 0 ){

						$( "#node-" + parentID ).removeClass( "parent" );

						$( "#node-" +parentID + " td span.expander" ).remove( );

					}

			}

                        item.children( "td" ).eq( 1 ).css( "padding-left", "5px" );

                }

}

$(document).ready( function( ){
	/**
	 * set up the treetable for the pages table
	 */
	$( "#pages" ).treeTable({

		treeColumn: 1,

		initialState: "expanded" 

	});

	/**
	 * configure expand/collapse all button
	 */
	$( "#treeTable-toggle" ).click( function( ){

		if( $( "#pages" ).hasClass( "expanded" ) ){

			$( "#pages tr.parent" ).each( function( ){

	                        if( !$( this ).hasClass( "children" ) && $( this ).hasClass( "expanded" ) )
	
        	                        $( this ).removeClass( "expanded" ).collapse( );

			});

			$( "#pages" ).removeClass( "expanded" ).addClass( "collapsed" );

		}
		else{

                        $( "#pages tr.parent" ).each( function( ){

                                if( $( this ).hasClass( "collapsed" ) )
       
                                        $( this ).expand( );

                        });

                        $( "#pages" ).removeClass( "collapsed" ).addClass( "expanded" );

		}

	});

	/**
	 *  set helper for sortable objects
	 */
	var fixHelper = function( e, ui ) {

		ui.children( ).each( function( ){

			$( this ).width( $( this ).width( ) );

		});

		return ui;

	};

        /**
         *  set sorter for sortable objects
         */
        var fixSorter = function( e, ui ) {

		if ( $( "#list-pages-dropbox tr" ).length ){

			var offset = ui.item.offset( );

			$( "#list-pages-dropbox" ).css({
				left: ( offset.left ) + "px",
				top: ( offset.top + 30 ) + "px"
			});
		}
	};


        /**
         *  set starter for sortable objects
         */
        var fixStarter = function( e, ui ) {

		$(".holder").css("height", ui.item.height());

		if( $( ui.item ).hasClass( "parent" ) ){

			toggleGroupedChildren( $( ui.item ).attr( "id" ) );

			console.log( $( ".grouped" ).index( ui.item ) );

			ui.item.data( "i", $( ".grouped" ).index( ui.item ) );

			$( ".grouped:not(.holder)" ).not( ui.item ).each( function( ){

				var content = $( this );

				content.children( ).each( function( ){

					$( this ).width( $( this ).width( ) );

				});

				$( this ).data( "n", $( "tr:not(.holder)" ).index( this ) );

				content.appendTo( "#list-pages-dropbox" );

			});
								
			$( "#pages tbody" ).sortable( "refresh" );
							
			$( "#list-pages-dropbox" ).show( );
							
			$( ".holder" ).css( "height", ( ( $( "#list-pages-dropbox tr" ).length + 1 ) * ui.item.outerHeight( ) ) + "px" );

		}

	};

	/**
	 * set stoper for sortable objects
	 */
	var fixStoper = function( e, ui ){

		if ( $( "#list-pages-dropbox tr" ).length ){

			$( "#list-pages-sortable" ).hide( );

				ui.item.after( $( "#list-pages-dropbox tr" ) );
							
				var pos = ui.item.data( "i" );

				console.log( pos );

				if ( pos > 0 ) ui.item.insertAfter( $( "#pages tbody .grouped" ) [ pos ] );

//				console.log( $( "#pages tbody .grouped" ) [ pos ] );

				reclassifyItem( ui.item );

				$( ".grouped" ).each( function( ){

					reclassifyItem( $( this ) );

				});

				toggleGroupedChildren( $( ui.item ).attr( "id" ) );

				$( "#pages tbody" ).sortable( "refresh" );

				rowColor( );

//				return ui;
		}

		reclassifyItem( ui.item );

		return ui;

	};

	/**
	 *  make the pages table trs sortable
	 *  @todo finish this!
	 */
	$("#pages tbody").sortable({

		forcePlaceholderSize: true,

		placeholder: "holder",

		helper:	fixHelper,

		stop: fixStoper,

		start: fixStarter,

		sort: fixSorter,

		update:function(){

			rowColor();

		}

	}).disableSelection( );

	/**
	 *  delete page when delete button is pressed
	 */
	$(".delete").click(function(){

		fConfirm("Are you sure you want to trash this page?",function(element){

			element.parent().parent().fadeOut( function( ){

				$( this ).remove( );

				rowColor( );

			});

			fetch( "/_inc/ajax.php?file=admin/pages/delete.php&id="+element.attr("id") );

		},$(this));

	});

        /**
         *  make all checkboxes clicked / unclicked when clicked 
         */
        $(".checkbox-all").click(function(){

                if($(".checkbox-all").attr("all")=="checked"){

                        $("input[type=checkbox]").attr("checked","");

                        $(".checkbox-all").attr("all","");

                }

                else{

                        $("input[type=checkbox]").attr("checked","checked");

                        $(".checkbox-all").attr("all","checked");

                }       

         });

	/**
	 * handle the multiple submit button 
	 */
        $(".p-submit").click(function(){

                var action=$(".select-"+$(this).attr("id")).val();

                if(action=="---")

                        return false;

                var boxes=[];

                $("#pages input[name=trash-box]:checked").each(function() {

                        boxes.push($(this).val());

                });

                var boxes=boxes.join(",");

                if(boxes=="")

                        return false;

                fConfirm(
			"Are you sure you want to perform a multiple " + action + "?",
			function( ){
				window.location = "pages.php?page=list&action=multiple&act="+action+"&boxes="+boxes;
			}
		);
        });

});
';

/**
 * load the treeTable jquery plugin and some page specific javascript 
 */
$Template->loadJavascript( '_inc/js/jquery/treeTable.min.js' );
$Template->loadJavascript( 'FURASTA_ADMIN_PAGES_LIST', $javascript );

$content = '
<div id="list-pages-dropbox"></div>

<span style="float:right">
	<a href="pages.php?page=new">
		<img src="/_inc/img/new-page.png" style="float:left"/>
		<h1 class="image-left">
			New Page
		</h1>
	</a>
</span>

<span>
	<img src="/_inc/img/pages.png" style="float:left"/>
	<h1 class="image-left">
		Pages
	</h1>
</span>

<br/>

<div id="options-bar" style="margin-top:20px">
        <div id="options-bar-right" style="float:right">
		<select name="action" class="trash-select select-p_1">
			<option default="default">---</option>
			<option>Trash</option>
		</select> 
		<input id="p_1" class="p-submit submit" type="submit" value="Go"/>
        </div>
	<div id="options-bar-left">
		<a href="#" id="treeTable-toggle">
			<img src="/_inc/img/expand-collapse-all.jpg"/>
		</a>
	</div>
</div>

<table id="pages" class="row-color expanded">

	<tr class="top_bar">
		<th class="pages-table-left">
			<input type="checkbox" class="checkbox-all" all=""/>
		</th>
		<th>Name</th>
		<th>Author</th>
		<th>Type</th>
		<th>Edited</th>
		<th>New</th>
		<th>Trash</th>
	</tr>
';

/**
 * fetch the pages info from the db and list the pages 
 */

$pages=array();
$query=query('select id,name,type,edited,user,parent from '.PAGES.' order by position,name desc');
while($row=mysql_fetch_assoc($query)){
	$pages[$row['parent']][]=$row;
}

//$content .= list_pages( 0, $pages );

$content .= list_pages( 0, $pages );

$content .= '
	<tr>
		<th class="pages-table-left">
			<input type="checkbox" class="checkbox-all" all=""/>
		</th>
		<th colspan="6">&nbsp;</th>
	</tr>

</table>

<div style="float:right;margin-top:10px">
	<select name="action" class="trash-select select-p_2">
		<option default="default">---</option>
		<option>Trash</option>
	</select> 
	<input id="p_2" class="p-submit submit" type="submit" value="Go"/>
</div>

<br style="clear:both"/>
';


/**
 * add the $content variable to the template class for output later 
 */
$Template->add( 'content', $content );
?>
