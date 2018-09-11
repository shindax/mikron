$( function() 
{
  adjust_behavior();
} );


function adjust_behavior() 
{
        $( "#exec_list" ).selectmenu( { change: exec_list_change } ).selectmenu( "menuWidget" ).addClass('overflow');
        $('.overflow').css( 'height', '250px' );
        $( '#exec_list-button').css('margin-top','5px').css('margin-bottom','5px').css('height','25px');      
        $('.executor_span').unbind( 'click').bind( 'click', executor_click ).hover( executor_mouseover ).mouseout( executor_mouseout );
        $("[id^='del_executor_img_']").css( 'display', 'none' );        
}

function get_executor_span_id( el )
{
  var full_id = $( el ).attr('id');
  var arr = full_id.split('_') ;
  return arr[1];
}

function executor_mouseover()
{
  var executor_id = get_executor_span_id( this );
  $( '#del_executor_img_' + executor_id ).css( 'display', 'block' );
}

function executor_mouseout()
{
  var executor_id = get_executor_span_id( this );
  $( '#del_executor_img_' + executor_id ).css( 'display', 'none' );
}

function executor_click()
{
  var executor_id = executor_id = get_executor_span_id( this );
  var order_id = $('#title').data('id');  
  $( this ).remove();

$.post(
  "project/MyJobs/OrderExecutorDeleteAjax.php",
  {
    order_id     : order_id ,
    executor_id  : executor_id 
  },
  execListChangeResponse
);    

}

function exec_list_change( event, ui )
{
  var executor_id = ui.item.value;
  var order_id = $('#title').data('id');

$.post(
  "project/MyJobs/OrderExecutorAddingAjax.php",
  {
    order_id     : order_id ,
    executor_id  : executor_id 
  },
  execListChangeResponse
);    

}

function execListChangeResponse( response )
{
  if( response.length )
   {
      if( $('.executor_span').length ) 
        $('.executor_span').last().after( response );
          else
            $('#exec_list-button').after( response );
   }
  
  adjust_behavior();
}
