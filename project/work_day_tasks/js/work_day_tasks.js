 $( function()
{
    $( '#datepicker, #datepicker_second' ).datepicker(
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
              update_page();
            }
        });
    $('#department').unbind('change').bind('change', update_page);
    $('#print_btn').unbind('click').bind('click', print_ptn_click );
});


function update_page()
{
  var date = $( '#datepicker' ).datepicker( 'getDate' );
  var date_second = $( '#datepicker_second' ).datepicker( 'getDate' );
  var dep_id =  $( '#department option:selected' ).val();
  var dep_name = $( '#department option:selected' ).text();

  if( (date || date_second || date && date_second) && dep_id )
    {
        var day = '';
        var month = '';
        var year = '';
		var day_second = '';
        var month_second = '';
        var year_second = '';
		
		if(date)
		{
			var day = date.getDate();
			var month = date.getMonth() + 1 ;
			var year = date.getFullYear() ;
		}
		
		if(date_second)
		{
			day_second  = date_second.getDate();
			month_second = date_second.getMonth() + 1;
			year_second = date_second.getFullYear() ;
		}
		
        var src = 'print.php?do=show&formid=267&p0=' + year + '&p1=' + month + '&p2=' + day + '&p3=' + dep_id + '&p4=' + dep_name + '&p5=' + year_second + '&p6=' + month_second + '&p7=' + day_second;
        $('#print_btn').data('src', src );

        // alert( dep_id )

          $.post(
            "project/work_day_tasks/ajax.GetData.php",
              {
                dep_id : dep_id,
                year : year,
                month : month,
                day : day,
				        year_second : year_second,
				        month_second : month_second,
				        day_second : day_second
              },
                          function( data )
                          {
                            $('#table_div').html( data );
                            $('#print_btn').show();
                            if( $('.data_row').length )
                              $('#print_btn').prop('disabled', false );
                                else
                                  $('#print_btn').prop('disabled', true );
                          }
          );


    }
    else
    {
        $('#print_btn').hide();
        $('#table_div').html('');
    }

}

function print_ptn_click()
{
  var src = $( this ).data('src');
  window.open( src ,'_blank');
}