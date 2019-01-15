$( function()
{
	"use strict";

	$('#mat_cat_sel').unbind('change').bind('change', cat_sel_change )
	$('#submat_cat_sel').unbind('change').bind('change', submat_cat_sel_change )
	$('#mat_sel').unbind('change').bind('change', load_orders )

	$('#sort_cat_sel').unbind('change').bind('change', sort_sel_change )	
	$('#subsort_cat_sel').unbind('change').bind('change', subsort_cat_sel_change )
	$('#sort_sel').unbind('change').bind('change', load_orders )


function load_orders()
{
	let mat = + $( '#mat_sel option:selected' ).val();
	let sort = + $( '#sort_sel option:selected' ).val();

	// cons( mat_cat + ' : ' + submat_cat + ' : ' + mat + ' : ' + sort_cat + ' : ' + subsort_cat + ' : ' + sort )

	if( mat && sort )
	{
		alert( mat + ' : ' + sort )
	}
}

function cons( arg )
{
	console.log( arg )
}

function cat_sel_change()
{
	let mat_cat = + $( '#mat_cat_sel option:selected' ).val();
$.post(
      '/project/materials_use/ajax.get_subcat.php',
      {
          id  : mat_cat
      },
      function( data )
      {
      	$('#submat_cat_span').text( $( data + ' option').length - 1 )
      	$('#submat_cat_sel').html( data )
      }
    );

}


function submat_cat_sel_change()
{
	let mat_cat = + $( '#submat_cat_sel option:selected' ).val();
	$.post(
      '/project/materials_use/ajax.get_mat.php',
      {
          id  : mat_cat
      },
      function( data )
      {
      	$('#mat_span').text( $( data + ' option').length - 1 )
      	$('#mat_sel').html( data )
      }
    );
}


function sort_sel_change()
{
	let mat_cat = + $( '#sort_cat_sel option:selected' ).val();
$.post(
      '/project/materials_use/ajax.get_subsort.php',
      {
          id  : mat_cat
      },
      function( data )
      {
      	$('#subsort_cat_span').text( $( data + ' option').length - 1 )
      	$('#subsort_cat_sel').html( data )
      }
    );

}


function subsort_cat_sel_change()
{
	let mat_cat = + $( '#subsort_cat_sel option:selected' ).val();
	$.post(
      '/project/materials_use/ajax.get_sort.php',
      {
          id  : mat_cat
      },
      function( data )
      {
      	$('#sort_span').text( $( data + ' option').length - 1 )
      	$('#sort_sel').html( data )
      }
    );
}

});