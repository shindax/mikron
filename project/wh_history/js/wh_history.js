let globalTimeout = null; 

$( function()
{
      $( '.datepicker' ).datepicker(
        {
            closeText: '\u041F\u0440\u0438\u043D\u044F\u0442\u044C', // Принять
            prevText: '&#x3c;\u041F\u0440\u0435\u0434', //
            nextText: '\u0421\u043B\u0435\u0434&#x3e;',
            currentText: '\u0422\u0435\u043A. \u043C\u0435\u0441\u044F\u0446',// тек. месяц
            showButtonPanel: false,
            monthNames: monthNames,
            monthNamesShort : monthNamesShort,
            dayNames : dayNames,
            dayNamesShort : dayNamesShort,
            dayNamesMin : dayNamesMin,
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            changeMonth : true,
            changeYear : true,
            closeOnEscape: true,
            isRTL: false,
            beforeShow : function(input, inst) {},
            onSelect: function ()
            {
              get_data()
            }
        })

  
    init_datepickers()
    get_data()
});

function init_datepickers()
{
      let today = new Date();
      let day = today.getDate(); 
      let month = today.getMonth() + 1; //January is 0!
      let year = today.getFullYear();

      $('#from_date').datepicker( "setDate", "01." + month + "." + year );
      $('#to_date').datepicker( "setDate", day + "." + month + "." + year );
}

function adjust_ui()
{
  $( '#find' ).unbind('keyup').bind( 'keyup', get_data )
  $( '#wh_select' ).unbind('change').bind( 'change', get_data )
  $( '#op_select' ).unbind('change').bind( 'change', get_data )  
  $( '#clear_filter' ).unbind('click').bind( 'click', clear_filter_click )  

  $('#find').keyup(function() {
    if (globalTimeout != null) {
      clearTimeout(globalTimeout);
    }
    globalTimeout = setTimeout(function() 
    {
      globalTimeout = null;  
      get_data()
    }, 1000);  
  });

}

function cons( arg )
{
  console.log( arg )
}

function convert_date( date )
{
    let day = date.getDate(); 
    let month = date.getMonth() + 1
    let year = date.getFullYear();
    day = day < 10 ? "0" + day : day ;
    month = month < 10 ? "0" + month : month ;

    return year + '-' + month  + '-' + day
}

function get_data()
{
  let from_date = convert_date( $( '#from_date').datepicker( "getDate" ) );
  let to_date = convert_date( $( '#to_date').datepicker( "getDate" ) );
  let pattern = $( '#find').val();
  let wh = $( '#wh_select option:selected' ).val();
  let op = $( '#op_select option:selected' ).val();  

           $.post(
            "project/wh_history/ajax.getData.php",
            {
              from_date : from_date,
              to_date : to_date,
              pattern : pattern,
              wh : wh,
              op : op
            },
            function( data )
            {
              // cons( data )
              $('#table_div').html( data )
              adjust_ui()
            }
         );
}

function clear_filter_click()
{
    init_datepickers()
    $( '#wh_select option[value=0]' ).prop('selected', 'true');
    $( '#op_select option[value=0]' ).prop('selected', 'true');
    $( '#find' ).val('');
    get_data()
}
