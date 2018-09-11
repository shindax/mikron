function Sort( a , b ) 
{
  var val1 = 1 * $( a ).data('ord') ;
  var val2 = 1 * $( b ).data('ord') ;
  
  if( val1 == val2 )
      return 0 ;
      
  return val1 < val2 ? -1 : 1 ;
}

$( function()
{
  $('img.edit_project_img').bind('click',img_click )
  $('tr.project_head').bind( 'click', project_head_click );

  var row_arr = $('tr.ord_row');
  
  $( row_arr ).each( function( index, element )
                {   
                  var ord = $( element ).data('ord');
                  var arr = $('tr[data-ord="'+ ord + '"]');
                  
                  if( arr.length >= 2 )
                    $( arr ).eq(1).remove();
                });
                
  $('#total').html('&nbsp;&nbsp;&nbsp;&nbsp;(' + $('tr.ord_row').length + ')');

  var head_arr = $('tr.project_head');

  $( head_arr ).each( function( index, element )
                {   
                  var proj_id = $( element ).data('proj-id');
                  var ord_row_arr = $('tr[data-proj="'+ proj_id + '"]');
                  ord_row_arr.sort( Sort );
                  $('tr[data-proj="'+ proj_id + '"]').remove();
                  $( element ).after( ord_row_arr );
                });

                 
});

function img_click()
{
  event.stopPropagation();
  var edit_project_page = $( this ).data('path');
  var id = $( this ).attr('id');
  var loc = location.origin + '/index.php?do=show&formid=' + edit_project_page + '&id=' + id ;
  location.href = loc;

//  alert( path );
}

function project_head_click()
{
  var project_id = $( this ).data('proj-id');
  var opened = $( this ).attr('data-opened');
    
  if( opened == 0 )
  {
    $( this ).attr('data-opened','1');
    $('[data-proj="' + project_id + '"]').removeClass('hidden');
    $( this ).find('td').find('span').html('&#9660;');
  }
  else
  {
    $( this ).attr('data-opened','0');
    $('[data-proj="' + project_id + '"]').addClass('hidden');    
    $( this ).find('td').find('span').html('&#9658;');    
  }
}
