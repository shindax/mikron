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

  $('input[type=radio]').unbind('click').bind('click', input_radio_click )
}

function getViolationCalendar()
{
  var radio = $("input[name='type']:checked").val();
  var year = $('#monthpicker').monthpicker('getDate').getFullYear();
  var month = 1 + $('#monthpicker').monthpicker('getDate').getMonth();

  $.post(
          '/project/3_month_year_report/ajax.getData.php',
          {
            radio : radio,
            year : year ,
            month : month
          },
          function( data )
          {
            $('.table_div').html( data );
          }
        );
}

function input_radio_click()
{
  getViolationCalendar()
}
