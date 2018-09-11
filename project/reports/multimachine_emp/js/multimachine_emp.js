// Actions after full page loading
$( function()
{

var options =
{
    selectedYear: 2018,
    startYear: 2017,
    finalYear: 2020,
    monthNames: monthNamesShort
};

$('.ui-datepicker-header').css('background','navy');

$('#monthpicker').monthpicker(options).bind('monthpicker-click-month', function (e, month )
        {
            var year = $('#monthpicker').monthpicker('getDate').getFullYear();
            var month = $('#monthpicker').monthpicker('getDate').getMonth();
            $('#monthpicker').data('date', { 'month': month + 1 , 'year' : year });
            $('#monthpicker').val( monthNames[ month ] + ' ' + year );
            dataChanged();
        }).bind('monthpicker-change-year', function (e, year) { $('#monthpicker').val(''); });

});

function dataChanged()
{
    var data = $( "#monthpicker" ).data( 'date' );
    var month = 0;
    var year = 0;

    if( data )
            {
                var month = data['month'];
                var year = data['year'];

                $.post(
                "project/reports/multimachine_emp/ajax.GetCalendar.php",
                              {
                                month : month,
                                year : year
                              },
                                          function( data )
                                          {
                                              $('#wrap').html( data );
                                              $('#print').unbind('click').bind('click', printReport );
                                          }
                          );



            }

//    alert( month + " : " + year );
}


function printReport()
{
  var month = $( this ).attr('data-month');
  var year = $( this ).attr('data-year');
  var url = "print.php?do=show&formid=257&p0=" + month + "&p1="+ year ;
//  document.location.href = url ;
  window.open(url, '_blank');
}


