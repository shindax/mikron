$( function()
{
  adjust_ui();
});

function adjust_ui()
{
  var options =
  {
      selectedYear: 2018,
      startYear: 2010,
      finalYear: 2020,
      monthNames: monthNamesShort
  };

  $('#monthpicker').monthpicker(options).bind('monthpicker-click-month', function (e, month )
        {
            var year = $('#monthpicker').monthpicker('getDate').getFullYear();
            var month = $('#monthpicker').monthpicker('getDate').getMonth();

            $('#monthpicker').data('date', { 'month': month + 1 , 'year' : year });
            $('#monthpicker').val( monthNames[ month ] + ' ' + year );
            getViolationCalendar()
        }).bind('monthpicker-change-year', function (e, year) { $('#monthpicker').val(''); });

  $('.print_button').unbind('click').bind('click', print_button_click )
  $('.print_total_button').unbind('click').bind('click', print_total_button_click )
  $('input[type=radio]').unbind('click').bind('click', input_radio_click )  
}

function getViolationCalendar()
{
   var data = $( "#monthpicker" ).data( 'date' );

   $('.selected').removeClass('selected')
    var viol_radio = $("input[name='type']:checked");
    var viol_type = $( viol_radio ).val();
    $( viol_radio ).parent().addClass('selected')

    if( !data )
        return ;

    $('.table_div').empty();

    var month = data['month'];
    var year = data['year'];
  
  $('#curloadingpage1').show()
  
  if( viol_type < 3 ) // По сотрудниками
  $.post(
          '/project/labor_regulations_violation/ajax.getViolationCalendar.php',
          {
              year  : year,
              month  : month ,
              viol_type : viol_type
          },
          function( data )
          {
            $('.table_div').html( data );
            adjust_ui();
            $('#curloadingpage1').hide()
          }
        );
  else // По предприятию
  {
    $.post(
          '/project/labor_regulations_violation/ajax.getViolationCalendarByEnterprise.php',
          {
              year  : year,
              month  : month ,
          },
          function( data )
          {
            $('.table_div').html( data );
            adjust_ui();
            $('#curloadingpage1').hide()
          }
        );
  }
}

function print_button_click( event )
{
  event.preventDefault();  
  var id = $( this ).attr( 'id' );
  var data = $( "#monthpicker" ).data( 'date' );
  var viol_type = $("input[name='type']:checked"). val();

    if( !data )
        return ;

    var month = data['month'];
    var year = data['year'];

  url = "print.php?do=show&formid=277&p0=" + id + "&p1=" + year + '&p2=' + month + '&p3=' + viol_type; 
  window.open( url, "_blank" );

}

function input_radio_click()
{
  getViolationCalendar()
}

function print_total_button_click( event )
{
  event.preventDefault();  
  var id = $( this ).data('id')
  var data = $( "#monthpicker" ).data( 'date' );
  var month = data['month'];
  var year = data['year'];

  url = "print.php?do=show&formid=280&p0=" + id + "&p1=" + year + '&p2=' + month ; 
  window.open( url, "_blank" );
}