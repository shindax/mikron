$( function()
{
  $('input').unbind("change").bind("change", checkbox_change );
});

function checkbox_change()
{
  var id = $( this ).attr('id');
  var state = $( this ).is(':checked') ;
//  alert( id + ' : ' + state );

    $.post(
        "project/presense_in_shift_orders/ajax.setState.php",
        {
          id : id,
          state : state
        },

        function( data )
        {
//            alert( data  );
        }
    );


}