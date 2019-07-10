$( function()
{

"use strict"

  let today = new Date();
  let month = today.getMonth() + 1; //January is 0!
  let year = today.getFullYear();

  getCalendar( month, year )

  var options =
  {
      selectedYear: 2019,
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
          getCalendar( month + 1, year )
      }).bind('monthpicker-change-year', function (e, year) { $('#monthpicker').val(''); }).val( monthNames[month - 1 ] + ' ' + year ).data( 'date', { month : month, year : year } );

  adjust_ui();
});

function adjust_ui()
{
  $('.day_plan_input').unbind('keyup').bind('keyup', day_plan_input_keyup)
}

function getCalendar( month, year )
{
  startLoadingAnimation()
  $.post(
        "project/master_plan_scoring/ajax.get_calendar.php",
        {
            month : month,
            year : year,
            user_id : user_id,
            res_id : res_id
        },
        function( data )
        {
          // console.log( data )
          $( '.table_div' ).empty().html( data )
          adjust_ui()
          stopLoadingAnimation()          
        }
      );
}

function startLoadingAnimation() // - функция запуска анимации
{
  // найдем элемент с изображением загрузки и уберем невидимость:
  var imgObj = $("#loadImg");
  imgObj.show();
 
  var centerY = $(window).height() / 2  - imgObj.height()/2 ;
  var centerX = $(window).width()  / 2  - imgObj.width()/2;
 
  // поменяем координаты изображения на нужные:
  imgObj.offset( { top: centerY, left: centerX } );
}
 
function stopLoadingAnimation() // - функция останавливающая анимацию
{
  $("#loadImg").hide();
}

function day_plan_input_keyup() 
{
  let date = $( "#monthpicker" ).data( 'date' );
  let month = date['month'];
  let year = date['year'];
  let day = $( this ).data('day');
  let viol = $( this ).data('viol');
  let id = $( this ).parents('table').prop('id')
  let val = $( this ).val()

  if( isNaN( val ) || val.length == 0 )
     $( this ).addClass('error')
        else
        {
          $( this ).removeClass('error')
            $.ajax({    
            url : '/project/master_plan_scoring/ajax.updateNormPlan.php',
            type : 'POST',
            data : {
                      year  : year,
                      month  : month ,
                      day : day,
                      res_id : id,
                      val : val,
                      viol : viol
                    },
            dataType: 'json',
            success: function( respond, textStatus, jqXHR )
              {
                if( typeof respond.error === 'undefined' )
                {
                  // console.log( respond )
                  let plan_minus_viol = respond[0];
                  let plan_minus_viol_str = min_to_hour( respond[1] );
                  let score = respond[2];
                  $( '#' + id ).find('span.norm_minus_viol_span[data-day=' + day + ']').text( plan_minus_viol_str ).data('viol', plan_minus_viol ).attr('data-viol', plan_minus_viol )
                  $( '#' + id + ' span.score_span[data-day=' + day + ']').text( score )

                    let arr = $( '#' + id ).find('.day_plan_input')
                    let len = arr.length
                    let sum = 0
                   $.each( arr , function( key, item )
                    {
                      sum += + $( item ).val();
                    });
                    $( '#' + id ).find('.plan_mid').text( ( sum / len ).toFixed(1) )


                    arr = $( '#' + id ).find('.norm_minus_viol_span')
                    len = arr.length
                    sum = 0
                   
                   $.each( arr , function( key, item )
                    {
                          sum += + $( item ).data('viol');
                    });

                    $( '#' + id ).find('.mid_norm_minus_viol_span').text( min_to_hour( ( sum / len ).toFixed(0)) )

                    arr = $( '#' + id ).find('.score_span')
                    len = arr.length
                    sum = 0
                   $.each( arr , function( key, item )
                    {
                      sum += + $( item ).text();
                    });
                    
                   score = ( sum / len ).toFixed(1)
                   $.post(
                        "project/master_plan_scoring/ajax.updateScore.php",
                        {
                            res_id   : id,
                            year : year,
                            month : month,
                            score : score
                        },
                        function( data )
                        {
                        }
                    );

                   $( '#' + id ).find('.mid_score_span').text( score )

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
}

function min_to_hour( min )
{
    sign = min < 0 ? "-" : "" ;
    min = Math.abs( min );
    hours = parseInt( min / 60 );
    minutes= min - hours * 60;
    result = hours ? hours + ":" + ( minutes < 10 ? "0" + minutes : minutes ) : minutes + '\u{43C}';
    return sign + result;
}
