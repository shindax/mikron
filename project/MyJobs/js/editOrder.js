var gl_el ;

$( function() 
{
    $( "#dialog-confirm" ).dialog(
      {
      autoOpen : false, 
      modal: true,
      buttons: {
        Ok: function() 
        {
          var id = $( gl_el ).data( 'id' );
          var field = $( gl_el ).data( 'field' );          
          
          var raw_date = $( gl_el).val() ;
          var year = raw_date.substr(0,4);
          var month = raw_date.substr(5,2);
          var day = raw_date.substr(8,2);
          var date_str = year + month + day ;
          var comment = $('#date_change_comment').val();

          $( gl_el ).data( 'old_value',  raw_date );
          $( gl_el ).val( raw_date );
          
          DateUpdateLog( id, date_str, field, gl_el, comment )

          $( this ).dialog( "close" );
        },
        'Отмена': function() 
        {
          var old_value = $( gl_el ).data( 'old_value' );
          $( gl_el ).val( old_value );
          $( this ).dialog( "close" );
        }        
        
      }
    });

  $('.one_row_data').unbind('change').bind('change', one_row_data_change );

} );
  
function  one_row_data_change()
{
   gl_el = this ;
   $('#date_change_comment').val('');
   $( "#dialog-confirm" ).dialog('open');
}
  
function DateUpdateLog( id, date_str, field, el, comment )
{

 $.post(
  "project/MyJobs/EditOrderAJAX.php",
  {
    id        : id       ,
    proj_id   : proj_id  ,
    user_id   : user_id  ,
    date_str  : date_str ,
    field     : field,
    comment   : comment 
  },
  DateUpdateLogAJAX, 'json' );
  vote( el ,'db_edit.php?db=db_itrzadan&field=' + field + '&id=' + id + '&value=' + TXT( date_str ));
}

function DateUpdateLogAJAX( data )
{
  var resp = data['key'];
  
//  alert( resp );
  $( resp ).insertAfter( $('#order_change_history_table tr:first') );
}
