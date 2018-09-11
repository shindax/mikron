function insertOneRowServerResponse( data )
{
  $( '#tbl' ).append( data );
  $('.inp_type').unbind( 'change' ).bind( 'change', dataChanged );
  $('.inp_name').unbind( 'blur' ).bind( 'blur', dataChanged ).last().focus();
}

function addNewRecord()
{
  if( $( '.new_record' ).length )
    return ;
  
  var user_id = res_user_id ;    
    
  $.post( "project/act_cal/ajaxAddRecordEventName.php", { user_id : user_id } ,insertOneRowServerResponse );    
}

function dataChanged()
{
    if( $( '.new_record' ).length )
    {
      if( $( '.new_record' ).val() == '' )
        $( '.new_record' ).focus();
         else
           $( '.new_record' ).removeClass( 'new_record' );
    }
 
    var id = $( this ).data('id') ;
    var name = $( '#inp_' + id ).val();
    var type = $( '#sel_' + id ).val();
    var user_id = res_user_id ;
    
    var addr = "project/act_cal/ajaxUpdateRecordEventName.php?id=" + id + "&name=" + name + "&type=" + type ;
    
//    alert( addr );
    $.post( addr, { id : id , name : name, type : type, user_id : user_id } , responseFunc );
}

function responseFunc( data )
{
//  alert( data );
}

// Действия после загрузки страницы : 
$( function()
{
  $('.alink').click( addNewRecord );
  $('.inp_type').change( dataChanged );
  $('.inp_name').blur( dataChanged );  
});

