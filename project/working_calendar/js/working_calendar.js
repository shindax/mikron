// Actions after full page loading
  $( function()
{

// **************************************************************************************************

    $.datepicker.setDefaults($.datepicker.regional['ru']);
    $( "#datepicker" ).datepicker(
      {
        onSelect : function( input, inst )
        {
            datepickerOnSelect( inst, this );
        },
        showOtherMonths: true,
        selectOtherMonths: true

      } );

// **************************************************************************************************
    var sliders = $( "[id^=slider-" );
    $.each( sliders, function( key, value )
    {
            var perc = $( value ).attr('data-perc');
            $( value ).slider({
            range: "min",
            value: perc,
            min: 0,
            max: 100,
            step: 1,
            slide: function( event, ui )
              {
                $( "#amount-" + $( this ).parent('div').parent('td').parent('tr').attr('data-id') ).val( ui.value );
              },
            stop: function( event, ui )
              {
              var id = $( this ).parent('div').parent('td').parent('tr').attr('data-id');
              var val = $( this ).slider( 'value' );

                $.post(
                        "/project/working_calendar/ajax.UpdateCompletionPerc.php",
                        {
                            id  : id,
                            val : val
                          },
                                function( data )
                                {
                                }
                              );
              },


          });

          var id = $( this ).parent('div').parent('td').parent('tr').attr('data-id');
          $( "#amount-" + id ).val( $( "#slider-" + id ).slider( "value" ) );
    });

// **************************************************************************************************

$( "[id^=spinner-]" ).spinner(
    {
      min: 0,
      max: 24,
      step: 1,
      disabled : true,
      spin: function( event, ui )
      {
        var order_id = $( this ).parent('span').parent('td').parent('tr').attr('data-id') ;

        var val = ui.value ;
        var date = null ;
        var inputs = null;

          if(  $( this ).attr('id').indexOf('prev') == -1 )
        {
          // current date column
            inputs = $('input[ id ^= spinner-now]');
            date = $( "#datepicker" ).data('cur_date');
        }
        else
        {
              if(  $( this ).attr('id').indexOf('prev-prev') == -1 )
              {
                var new_inputs = [];

                // previous date column
                inputs = $('input[ id ^= spinner-prev]');
                date = $( "#datepicker" ).data('prev_date');

                $.each( inputs, function( key, value )
                {
                  var id = $( value ).attr( 'id' );

                  if(  id.indexOf('prev-prev') == -1 )
                    new_inputs.push( value );
                });

                inputs = new_inputs;
              }
                else
                {
                    // prevprevious date column
                    inputs = $('input[ id ^= spinner-prev-prev]');
                    date = $( "#datepicker" ).data('prev_prev_date');
              }
      }

       if( 1 * calcTotalHours( inputs ) == 24 && $( this ).val() < ui.value )
              return false;

                $.post(
                        "/project/working_calendar/ajax.UpdateHour.php",
                        {
                            order_id  : order_id,
                            user_id : user_id,
                            date : date,
                            val : val
                          },
                                function( data )
                                {
                                  var tr = $( 'tr[data-id='+ order_id + ']');
                                  var parent_id = $( tr ).data('parent_id');
                                  $( tr ).find('td').last().find('span').text( data );
                                  $('#msg_span').attr('data-show', 1 ).addClass('hidden');

                                    var hrs_span = $( 'tr[data-parent_id=' + parent_id + ']').find('.hours_span') ;
                                    var new_hours = 0 ;
                                    $.each( hrs_span, function( key, value )
                                    {
                                        new_hours += Number( $( value ).text());
                                    });
                                    $('tr[id=' + parent_id + ']').find('.total_hrs').text( new_hours );

                                    var date = $( "#datepicker" ).data('cur_date').split('-');
                                    MakeChart( user_id, date[1] , date[0] );

                                }
                              );

        if ( val == 0  )
              {
                  $( this ).spinner( "value", '' );
                  return false;
              }
       }

    }).focus( function () { $(this).blur();}).spinner( "value", '' );

    var cur_date = new Date() ;
    $("#datepicker").datepicker("setDate", cur_date ).datepicker( 'option','onSelect' );
    datepickerOnSelect(
            {
                'selectedYear' : cur_date.getFullYear(),
                'selectedMonth' : cur_date.getMonth() ,
                'selectedDay' : cur_date.getDate()
            } , $("#datepicker") ) ;

    $('img.coll_exp').unbind('click').bind('click', coll_exp_click );

  });

// **************************************************************************************************

 function datepickerOnSelect( inst, el )
{
            var cur_date = new Date( inst.selectedYear, inst.selectedMonth , inst.selectedDay );
            var prev_date = prevDay( cur_date );
            var prev_prev_date = prevDay( prev_date );

            $( '#cur_date_span' ).text( getDateStr( cur_date, '.' ) );
            $( '#prev_date_span' ).text( getDateStr( prev_date, '.' ) );
            $( '#prev_prev_date_span' ).text( getDateStr( prev_prev_date, '.' ) );

            cur_date = getDateStr( cur_date, '-' );
            prev_date = getDateStr( prev_date, '-' );
            prev_prev_date = getDateStr( prev_prev_date, '-' );

            $( el ).data( { 'cur_date' : cur_date ,'prev_date' : prev_date , 'prev_prev_date' : prev_prev_date } );

              var data = new FormData();

              data.append( 'cur_date', cur_date );
              data.append( 'prev_date', prev_date );
              data.append( 'prev_prev_date', prev_prev_date );
              data.append( 'user_id', user_id );

              var now = new Date( cur_date );
              MakeChart( user_id, now.getMonth() + 1 , now.getFullYear() );

    // Отправляем запрос
              $.ajax({
                  url: '/project/working_calendar/ajax.GetData.php',
                  type: 'POST',
                  data: data,
                  cache: false,
                  dataType: 'json',
                  processData: false, // Не обрабатываем файлы (Don't process the files)
                  contentType: false, // Так jQuery скажет серверу что это строковой запрос
                  success: function( respond, textStatus, jqXHR )
                  {
                      // if everything is OK
                      if( typeof respond.error === 'undefined' )
                        {

                          var rows = respond.rows;
                          $( 'input[id^=spinner-]').spinner( "enable" ).val( '' );
                          $('.month_hours_span').text('0');
                          $('.total_month_hours_span').text('0');

                          rows.forEach(function( item, index, rows )
                            {
                                var order_id = item.order_id;
                                var hour_count = item.hour_count;
                                var suffix = item.suffix;

                                 $( '#spinner-' + suffix + '-' + order_id ).val( hour_count == 0 ? '' : hour_count );
                            });

                          var today = new Date();
                          var refdate = new Date( cur_date );
                          var dayOfWeek = new Date( cur_date ).getDay();

                          var timeDiffAbs = Math.abs(today.getTime() - refdate.getTime());
                          var timeDiff = today.getTime() - refdate.getTime();
                          var diffDays = Math.ceil( timeDiffAbs / (1000 * 3600 * 24)) - 1 ;

                          var diffDaysAfter = Math.ceil(( ( today.getTime() - refdate.getTime() ) / (1000 * 3600 * 24) ) ) - 1  ;

//                          console.log( dayOfWeek + ' : ' + diffDays );

                          if( 
                              ( dayOfWeek == 0 && diffDays == 1 ) 
                                || 
                              ( dayOfWeek == 1 && diffDays >= 1 ) 
                                || 
                              ( dayOfWeek == 6 && diffDays == 2 ) 
                                || 
                              ( dayOfWeek == 5 && diffDays == 3 ) 
                            )
                            diffDays --;

// Увеличить число дней для простановки часов "задним числом"                          
                          diffDays -= 2

                          if( timeDiff && diffDays > 0 )
                            switch ( diffDays )
                          {
                            case  1 :
                                            $( "[id^=spinner-prev-prev]" ).spinner('disable');
                                            break;
                            case  2 :
                                            $( "[id^=spinner-prev]" ).spinner('disable');
                                            break ;

                            default :
                                            $( "[id^=spinner]" ).spinner('disable');
                                            break ;
                          }

                          if( timeDiff && diffDaysAfter < 0 )
                            switch ( diffDaysAfter )
                          {
                            case -1 :
                                            $( "[id^=spinner-now]" ).spinner('disable');
                                            $( "[id^=spinner-prev]" ).spinner('enable');
                                            break;
                            case -2 :
                                            $( "[id^=spinner-now]" ).spinner('disable');
                                            $( "[id^=spinner-prev]" ).spinner('disable');
                                            $( "[id^=spinner-prev-prev]" ).spinner('enable');
                                            break ;
                            default :
                                            $( "[id^=spinner]" ).spinner('disable');
                                            break ;
                          }


                            var data = new FormData();
                            data.append( 'date', cur_date );
                            data.append( 'user_id', user_id );

                          // Отправляем запрос
                                    $.ajax({
                                        url: '/project/working_calendar/ajax.GetMonthStatistics.php',
                                        type: 'POST',
                                        data: data,
                                        cache: false,
                                        dataType: 'json',
                                        processData: false, // Не обрабатываем файлы (Don't process the files)
                                        contentType: false, // Так jQuery скажет серверу что это строковой запрос
                                        success: function( respond, textStatus, jqXHR )
                                        {
                                            // if everything is OK
                                            if( typeof respond.error === 'undefined' )
                                              {
                                                        var rows = respond.rows;
                                                        $('.month_hours_span').text('0');
                                                        $('.total_month_hours_span').text('0');

                                                        if( rows )
                                                        rows.forEach(function( item, index, rows )
                                                          {
                                                              var order_id = item.order_id;
                                                              var month_hours = Number( item.month_hours );
                                                              var tr =$( 'tr[data-id=' + order_id + ']');
                                                              var parent_id = $( tr ).attr( 'data-parent_id');
                                                              var section_month_hours = Number( $('tr[id=' + parent_id + ']').find('.total_month_hours_span').text());
                                                              section_month_hours += month_hours ;
                                                              $( tr ).find( '.month_hours_span' ).text( month_hours );
                                                              $('tr[id=' + parent_id + ']').find('.total_month_hours_span').text( section_month_hours );
                                                          });

                                                            rows = $('tr.item_row');

                                                            $.each( rows, function( key, value )
                                                            {
                                                               if( Number( $( value ).find('.month_hours_span').text() ) == 0 )
                                                               {
                                                                   $( value ).find('.month_hours_span').text('-');
                                                                   $( value ).find('.legend div').css('background-color','#ffff');
                                                                }
                                                            });

                                                            rows = $('tr[id]');

                                                            $.each( rows, function( key, value )
                                                            {
                                                               if( Number( $( value ).find('.total_month_hours_span').text() ) == 0 )
                                                               {
                                                                   $( value ).find('.month_hours_span').text('');
                                                                   $( value ).find('.legend div').css('background-color','#98b8e2');
                                                                }
                                                            });



                                              }
                                        },
                                        error: function( jqXHR, textStatus, errorThrown )
                                        {
                                            console.log('AJAX request errors in ajax.GetMonthStatistics.php detected : ' + textStatus + errorThrown );
                                        }
                                    });
                      }
                      else
                          console.log('AJAX request errors detected. Server said : ' + respond.error );
                  },
                  error: function( jqXHR, textStatus, errorThrown )
                  {
                      console.log('AJAX request errors in uploadProjectFiles.js detected : ' + textStatus + errorThrown );
                  }
              });

}

// **************************************************************************************************

function calcTotalHours( inputs )
{
          var cnt = 0 ;
          $.each( inputs, function( key, value )
          {
            var num = $( value ).val();
                if( num == '' )
                    num = 0 ;
                cnt += 1 * num;
          });

//        console.log( cnt );
        return cnt;
}

function coll_exp_click()
{
    var id = $( this ).parents('tr').attr('id');

   if( $(this).attr('data-show') == 0 )
   {
       $(this).attr('data-show',1).attr( 'src' , '/uses/expand.png' );
       $('tr[data-parent_id="' + id + '"]').removeClass('hidden');
   }
         else
        {
          $(this).attr('data-show',0).attr( 'src' , '/uses/collapse.png' );
            $('tr[data-parent_id="' + id + '"]').addClass('hidden');
        }
}

function MakeChart( user_id, month, year )
{
    var total_hours = 0 ;
    var user_arr = [ user_id ];

    var hours_raw = $('.total_hrs span');
    var hours = [];
    var total_hours = 0 ;

    // Calc total hours
    $.each( hours_raw, function( key, value )
    {
        var val = $( value ).text() ;
        if( val && val != '0'  )
        {
            total_hours += Number( val );
            hours.push( value );
        }
    });

    if( total_hours )
    {
        $('#ord_table').css('margin-top', '0px' );
        $('#pie_div').empty();

        $.post(
            "project/working_calendar/ajax.GetChartData.php",
            {
                user_arr : user_arr,
                month : month,
                year : year
            }, function( ajax_data )
            {
                colors = semidonut_pie_chart( ajax_data['data'] );
                var data_len = ajax_data['data'].length ;

                if( data_len )
                    $.each( hours, function( key, value )
                    {
                        var color = colors[ key ];
                        $( value ).parent().parent().find('td.legend div').css('background',color );
                        $('rect').eq(0).attr('x','400px') ;
                    });
                  else
                      $('#ord_table').css('margin-top', '-250px' );

            }, 'json' );
    }
}


function semidonut_pie_chart( in_data )
{

    var colors = Highcharts.getOptions().colors;
    var categories = [];
    var data = []

    in_data.forEach(function(item, i, arr)
    {
        var name = item["name"];
        var val = item["y"];
        var orders_data = item["orders_data"];

        var order_categories = [];
        var order_data = []
        var order_row_id = []

        categories.push( name );

        orders_data.forEach(function(item, i, arr)
        {
            order_categories.push( item["name"] );
            order_data.push( item["y"] );
            order_row_id.push( item["row_id"] );
        });

        data.push(
            {
                y: val,
                color: colors[i],
                drilldown:
                    {
                        categories: order_categories,
                        data: order_data,
                        order_row_id : order_row_id,
                        color: colors[i],
                        dataLabels:{ enabled: false }
                    }
            }
        );

    });

    var chartData = [],
        innerData = [],
        i,
        j,
        dataLen = data.length,
        drillDataLen,
        brightness;

// Build the data arrays

    for (i = 0; i < dataLen; i += 1)
    {

        // add data
        chartData.push(
            {
                name: categories[i],
                y: data[i].y,
                color: data[i].color
            });

        // add inner data
        drillDataLen = data[i].drilldown.data.length;
        for ( j = 0; j < drillDataLen; j += 1 )
        {
            brightness = 0.2 - (j / drillDataLen) / 5;
            var color = Highcharts.Color( data[i].color ).brighten( brightness ).get();
            var row_id = data[i].drilldown.order_row_id[j];
            $('tr[data-id="' + row_id + '"]').find('td.legend div').css('background', color );

            innerData.push(
                {
                    name: data[i].drilldown.categories[j],
                    y: data[i].drilldown.data[j],
                    color: color
                });
        }
    }

// Create the chart
    var chart = Highcharts.chart('pie_div', {
        exporting:
            {
                enabled: false
            },
        chart: {
            type: 'pie',
            backgroundColor:'rgba(255, 255, 255, 0.0)'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        yAxis: {
            title: {
                text: 'Total percent market share'
            }
        },
        plotOptions:
            {
                pie: {
                    animation: false,
                    size: 80,
                    dataLabels:{ enabled: true }, // shindax
                    shadow: false,
                    center: ['50%', '50%'],
//            startAngle: -90, // shindax
//            endAngle: 90, // shindax
                    showInLegend: false // shindax
                }
            },
        tooltip: {
            valueSuffix: '%',
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        series: [{
            name: '\u0417\u0430\u0442\u0440\u0430\u0442\u044B \u0440\u0430\u0431\u043E\u0447\u0435\u0433\u043E \u0432\u0440\u0435\u043C\u0435\u043D\u0438',
            data: chartData,
            size: '100%',
            dataLabels: {
                formatter: function ()
                {
                    return this.y > 5 ? this.point.name : null;
                },
                color: '#ffffff',
                distance: -30
            }
        }, {
            name: '\u0417\u0430\u0442\u0440\u0430\u0442\u044B \u0440\u0430\u0431\u043E\u0447\u0435\u0433\u043E \u0432\u0440\u0435\u043C\u0435\u043D\u0438',
            data: innerData,
            size: '100%',
            innerSize: '60%',
            dataLabels: {
                formatter: function ()
                {
                    // display only if larger than 1
                    return this.y > 1 ? '<b>' + this.point.name + ':</b> ' +
                        this.y + '%' : null;
                }
            },
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 400
                },
                chartOptions: {
                    series: [{
                        dataLabels: {
                            enabled: false
                        }
                    }]
                }
            }]
        }
    });

    return chart.options.colors;
}

