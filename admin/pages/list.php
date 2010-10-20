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

		ui.children( ).each( function( ) {

			$( this ).width( $( this ).width( ) );

		});

		return ui;

	};

	/**
	 *  make the pages table trs sortable
	 *  @todo finish this!
	 */
	$("#pages tbody").sortable({

		containment: ".parent",

		helper:	fixHelper,

		update:function(){

			rowColor();

			//alert($("#pages tr").sortable("serialize"));

			alert( "parents test" );

		}

	});

	$( "#pages tbody tr.children" ).each( function(){


		var row = $( this ).attr( "class" ).match( /child-of-node\-.+?\b/);

		var parent = String( row ).split( "-" );

		parent = parent[ parent.length - 1 ];

		parent = "#node-" + parent;

		if( !$( parent ).hasClass( "furasta-sortable" ) ){

			console.log( parent + "   ." + row );

			$( "#pages tbody").sortable({

				containment: "." + row,

				helper: fixHelper,

				update: function( ){

					rowColor();

					alert( "childern test" );

				}

			});

			$( parent ).addClass( "furasta-sortable" );

		}

	});

                        $( "#pages tbody").sortable({

                                containment: ".child-of-node-61",

                                helper: fixHelper,

                                update: function( ){

                                        rowColor();

                                        alert( "childern test" );

                                }

                        });

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
