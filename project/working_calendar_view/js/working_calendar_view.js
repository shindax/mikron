// Actions after full page loading
$( function()
{

var options =
{
    selectedYear: 2019,
    startYear: 2010,
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


    $('#department').unbind('change').bind('change', department_change );
    $('#employee').unbind('change').bind('change', dataChanged );

    $('img.coll_exp').unbind('click').bind('click', coll_exp_click );

    adjustLoadingAnimation()
});

function department_change()
{
    $('#pie_div').empty();
    $('#wrap').empty();

    var id = $( '#department option:selected').val();
    $('#employee').empty();

                        if( id )
                        {
                          startLoadingAnimation();
                          $.post(
                            "project/working_calendar_view/ajax.GetUserSelect.php",
                              {
                                id : id
                              },
                                          function( select_data )
                                          {
                                              $('#employee').html( select_data );
                                              // show first user data
                                              stopLoadingAnimation();
                                              dataChanged();
                                          }
                          );
                        }

}

function dataChanged( user_id )
{
            var user_id = $('#employee option:selected').val();
            var data = $( "#monthpicker" ).data( 'date' );
            var user_arr

    if( data )
            {
                var month = data['month'];
                var year = data['year'];
            }

            if( month != undefined && year != undefined )
                if( user_id != undefined && user_id != 0 )
                    user_arr = [ user_id ];
                    else
                        {
                            var users = $('#employee option');
                            user_arr = [];
                            $.each( users, function( key, value )
                            {
                                var val = $( value ).val() ;
                                if( val && val != "0" )
                                    user_arr.push( val );
                            });
                        }

            if( user_arr && user_arr.length )
                getWorkingCalendar( user_arr, month, year );
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


function getWorkingCalendar( user_arr, month, year )
{
    var pie_data = [];

    $('#pie_div').empty();
    $('#wrap').empty();

    startLoadingAnimation();

    $.post(
        "project/working_calendar_view/ajax.GetWorkingCalendar.php",
        {
            user_arr : user_arr,
            month : month,
            year : year
        },
        function( data )
        {
            $('#wrap').html( data );
            $('img.coll_exp').unbind('click').bind('click', coll_exp_click );

            var hours_raw = $('.total_month_hrs span');
            var hours = [];
            var total_hours = 0 ;

            // Calc total hours
            $.each( hours_raw, function( key, value )
            {
                var val = $( value ).text() ;
                if( val != '-' )
                {
                    total_hours += Number(  val );
                    hours.push( value );
                }
            });

            if( total_hours )
            {
                var i = 0 ;
                // Make diagram data
                $.each( hours, function( key, value )
                {
                    var title = "___";
                    var orders_data = [];
                    var tr = $( value ).parent().parent();
                    var row_id = $( tr ).attr('id');
                    var section_name = $( tr ).find('td.name span').eq(1).text();
                    var section_hour = $( value ).text() ;
                    var section_val = 1 * (( section_hour ) /  total_hours * 100 ).toFixed(1);

                    var orders = $( 'tr[data-parent_id="' + row_id + '"]' );

                    $.each( orders, function( key, value )
                    {
                        var ord_val = $( value ).find('span').eq(2).text();
                        if( ord_val != '-' )
                        {
                            var row_id = $( value ).attr('data-id');
                            var name = $( value ).find('span').eq(0).text();

                            var val = 1 * (( ord_val ) *  section_val / section_hour ).toFixed(1);

                            orders_data.push(
                                {
                                    name: name,
                                    row_id: row_id,
                                    y: val
                                });
                        }
                    });

                    if( section_hour != '-' )
                        pie_data[ i ++ ] =
                            {
                                id: row_id,
                                name : section_name ,
                                y : section_val ,
                                title: title,
                                orders_data : orders_data
                            };

                });

                var offset ;
                switch( pie_data.length )
                {
                    case 1: offset = '-120px' ; break ;
                    case 2:
                    case 3: offset = '-90px' ; break ;
                    case 4: offset = '-80px' ; break ;
                    case 5: offset = '-70px' ; break ;
                    case 8: offset = '-20px' ; break ;
                }

                offset = '0px'

                $('#ord_table').css('margin-top', offset );

                var colors = semidonut_pie_chart( pie_data );

                $('rect').eq(0).attr('x','400px') ;

                $.each( hours, function( key, value )
                {
                    var color = colors[ key ];
                    $( value ).parent().parent().find('td.legend div').css('background',color);
                });
            }
            else
                $('#ord_table').css('margin-top', '-200px');


            for( var i = 1 ; i <= 31 ; i ++ )
                $('td[data-day="' + i + '"]').text( calc_columns( $( 'td[data-key="' + i + '"]') ) );

            $('td[data-day="M"]').text( calc_columns( $( 'td[data-key="M"]') ) );
            $('td[data-day="T"]').text( calc_columns( $( 'td[data-key="T"]') ) );
            stopLoadingAnimation();
        }


    );

}

function calc_columns( arr )
{
    var total_value = 0 ;

        $.each( arr, function( key, value )
        {
            var val = $( value ).text();
            if( val != '-')
                total_value += Number( val );
        });
    return total_value ? total_value : '-';
}

function adjustLoadingAnimation()
{
    var imgObj = $("#loadImg").hide();
    var centerY = $(window).height() / 2  - imgObj.height()/2 ;
    var centerX = $(window).width()  / 2  - imgObj.width()/2;

    // установка координат изображения:
    imgObj.offset( { top: centerY, left: centerX } );
}

function startLoadingAnimation() // - функция запуска анимации
{
    var imgObj = $("#loadImg").show();
}

function stopLoadingAnimation() // - функция останавливающая анимацию
{
    $("#loadImg").hide();
}
