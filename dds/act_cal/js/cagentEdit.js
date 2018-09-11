function insertOneRowServerResponse( data )
{
  $( '#tbl' ).append( data );
  $('.inp_name').unbind( 'blur' ).bind( 'blur', dataChanged ).last().focus();
}

function addNewRecord()
{
  if( $( '.new_record' ).length )
    return ;
 
  $('.alink').css( 'color', 'gray' );
 
  var user_id = res_user_id ;    
    
  $.post( "project/act_cal/ajaxAddCagent.php", { user_id : user_id } ,insertOneRowServerResponse );
}

function dataChanged()
{
    if( $( '.new_record' ).length )
    {
      if( $( '.new_record' ).val() == '' )
        $( '.new_record' ).focus();
         else
         {
           $( '.new_record' ).removeClass( 'new_record' );
           $('.alink').css( 'color', 'rgb( 35, 96, 158 )' );
         }
    }
 
    var id = $( this ).data('id') ;
    var name = $( '#inp_' + id ).val();
    var user_id = res_user_id ;
    
    var addr = "project/act_cal/ajaxUpdateCagent.php?id=" + id + "&name=" + name;
    
//    alert( addr );
    $.post( addr, { id : id , name : name, user_id : user_id } , responseFunc );
}

function responseFunc( data )
{
//  alert( data );
}

// Действия после загрузки страницы : 
$( function()
{
  $('.alink').click( addNewRecord );
  $('.inp_name').blur( dataChanged );  
});
