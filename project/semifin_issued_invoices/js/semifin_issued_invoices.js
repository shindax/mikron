$( function()
{
  adjust_ui()
  invNumSelectChange()
});

function adjust_ui()
{
  $('#year-select').unbind('change').bind('change', yearSelectChange );
  $('#month-select').unbind('change').bind('change', monthSelectChange );      
  $('#inv-num-select').unbind('change').bind('change', invNumSelectChange );
  $('.print_button').unbind('click').bind('click', print_button_click );
}

function invNumSelectChange()
{
  let year = $( "#year-select option:selected" ).val();  
  let month = $( "#month-select option:selected" ).val();
  let num = $( "#inv-num-select option:selected" ).val();
  viewChange( year, month, num )
}

function yearSelectChange()
{
  let year = $( "#year-select option:selected" ).val();  
  let month = $( "#month-select option:selected" ).val();  
  $( "#month-select option:selected" ).prop('selected', false )
  viewChange( year, month )
}

function monthSelectChange()
{
  let year = $( "#year-select option:selected" ).val();  
  let month = $( "#month-select option:selected" ).val();
  let num = $('#inv-num-select option:selected').val();  
  viewChange( year, month, num )
}

function viewChange( year, month = 1, batch = 0 )
{
  $.post(
    "project/semifin_issued_invoices/ajax.getInvoicesNums.php",
    {
      year : year,
      month : month
    },
    function( data ){

      // cons( data )

      $( "#inv-num-select" ).html( data )

      if( selected_in && ! batch )
        batch = selected_in

      $.post(
        "project/semifin_issued_invoices/ajax.getInvoices.php",
        {
          year : year,
          month : month,
          batch : batch,
        },
        function( data )
        {
          $('.table-responsive').html( data )
          $( "#inv-num-select option[value='" + batch + "']" ).prop('selected', true )
          adjust_ui()
        }
        );
    }
    );
}

function print_button_click()
{
  let batch = $( this ).data('batch')
  let transaction = $( this ).data('transaction')
  url = "print.php?do=show&formid=271&p0=" + batch + '&p1=' + transaction
  window.open( url, "_blank" );
}

function cons( arg1='', arg2='', arg3='', arg4='', arg5='' )
{
  let str = arg1 ;
  if( String(arg2).length )
    str += ' : ' + arg2
  if( String(arg3).length )
    str += ' : ' + arg3
  if( String(arg4).length )
    str += ' : ' + arg4
  if( String(arg5).length )
    str += ' : ' + arg5

  console.log( str )
}