$( function() 
{
    var date = new Date();
    var firstDay = new Date(date.getFullYear(), date.getMonth(), 1)
    var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

    let date_from = firstDay.getFullYear() + '-' + ( firstDay.getMonth() + 1 ) + '-' + firstDay.getDate();

    let date_to = lastDay.getFullYear() + '-' + ( lastDay.getMonth() + 1 ) + '-' + lastDay.getDate();

    var from = $( "#from" )
        .datepicker({
          changeMonth: true,
          numberOfMonths: 1
        })
        .on( "change", function() 
        {
          to.datepicker( "option", "minDate", this.value );
          date_from = this.value;
          getChart()
        }),
      to = $( "#to" ).datepicker({
        changeMonth: true,
        numberOfMonths: 1
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", this.value );
        date_to = this.value;
        getChart()
      });
 
$.datepicker.regional['ru'] = {
                    closeText: 'Закрыть',
                    prevText: 'Пред',
                    nextText: 'След',
                    currentText: 'Сегодня',
                    monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
                    'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
                    monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
                    'Июл','Авг','Сен','Окт','Ноя','Дек'],
                    dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
                    dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
                    dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
                    weekHeader: 'Нед',
                    dateFormat: 'dd.mm.yy',
                    firstDay: 1,
                    isRTL: false,
                    showMonthAfterYear: false,
                    yearSuffix: ''};
            $.datepicker.setDefaults($.datepicker.regional['ru']);  


  $( "#from" ).datepicker( "setDate", new Date( date_from ) )
  $( "#to" ).datepicker( "setDate", new Date( date_to ) )

 getChart()

function getChart()
{

if( date_from && date_to )
    $.ajax({    
                url : '/project/heavycut_scoreboard/ajax.GetData.php',
                type : 'POST',
                data : {
                          date_from  : date_from,
                          date_to  : date_to
                        },
                dataType: 'json',
                success: function( respond, textStatus, jqXHR )
                  {
                    if( typeof respond.error === 'undefined' )
                    {

                        let machine_on = []
                        let tool_on = []
                        let dates = []

                        respond.forEach(function( element )
                        {
                          dates.push( element['highchart_date'] )
                          machine_on.push( element['machine_on_time'] )
                          tool_on.push( element['tool_on_time'] )
                        });


////////////////////////////////////////////////////////////////////////

            var title = {
               text: 'Режим работы станка Heavycut'   
            };

            var subtitle = {
               text: 'Интервал : с '+ convert_date( date_from ) + ' по ' + convert_date( date_to )
            };
            var xAxis = {
               categories: dates
            };
            var yAxis = {
               title: {
                  text: 'Часов'
               },
               plotLines: [{
                  value: 0,
                  width: 1,
                  color: '#808080'
               }]
            };   
            var tooltip = {
               valueSuffix: 'ч.'
            }
            var legend = {
               layout: 'vertical',
               align: 'right',
               verticalAlign: 'middle',
               borderWidth: 0
            };
            var series =  [{
                  name: 'Станок включен',
                  data: machine_on
               }, 
               {
                  name: 'Инструмент включен',
                  data: tool_on
               }
            ];

            var json = {};
            json.title = title;
            json.subtitle = subtitle;
            json.xAxis = xAxis;
            json.yAxis = yAxis;
            json.tooltip = tooltip;
            json.legend = legend;
            json.series = series;
            
            $('#container').highcharts(json);

////////////////////////////////////////////////////////////////////////

                    }
                    else
                    {
                        console.log('AJAX request errors detected. Server said : ' + respond.error );
                    }
                  },
                  error: function( jqXHR, textStatus, errorThrown )
                {
                    console.log('AJAX request errors in coop_orders.js detected : ' + textStatus + errorThrown );
                }
                });

}

function convert_date( str ) 
{
    if( str.indexOf('-') != -1 )
    {
     let arr =  str.split('-')
     str = ( arr[2] < 10 ? '0' + arr[2] : arr[2] ) + '.' + ( arr[1] < 10 ? '0' + arr[1] : arr[1]) + '.' + arr[0]
    }
    return str
}

});
