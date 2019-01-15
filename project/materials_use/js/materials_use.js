$( function()
{
	"use strict";

function cons( arg )
{
	console.log( arg )
}

$('#mat_sel').unbind('change').bind('change', get_data )
$('#sort_sel').unbind('change').bind('change', get_data )

function get_data()
{
  let mat = + $( '#mat_sel option:selected' ).val();
  $( '#mat_sel' ).attr('data-sel', mat );

  let sort = + $( '#sort_sel option:selected' ).val();
  $( '#sort_sel' ).attr('data-sel', sort );

  $.post(
      '/project/materials_use/ajax.get_data.php',
      {
          mat  : mat,
          sort : sort
      },
      function( data )
      {
      	$('#main_div').html( data )
      	// cons( data )
      }
    );
}

});
