$( function()
{
  // cons('master evaluation')

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

function cons( arg1='', arg2='', arg3='', arg4='', arg5='')
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
function adjust_ui()
{
  // $('.print_button').unbind('click').bind('click', print_button_click )
  // $('.print_total_button').unbind('click').bind('click', print_total_button_click )
  // $('input[type=radio]').unbind('click').bind('click', input_radio_click )  

  $('.editable').mouseenter( editableIn ).mouseleave( editableOut );
  $('.score').unbind('keyup').bind('keyup', score_keyup)

 
  // $( ".score" ).removeClass('hidden').spinner({
  //     spin: function( event, ui ) 
  //     {
  //       var td = $( this ).closest('td')
  //       var tr = $( td ).parent()

  //       var day = $( td ).data('id')
  //       var month = $( tr ).data('month')
  //       var year = $( tr ).data('year')
  //       var type = $( tr ).data('eval-type')
  //       var master_id = $( tr ).parents('table').attr("id")

  //       $( this ).closest('td').find('span.val').text( ui.value )

  //       $.post(
  //       "project/master_evaluation/ajax.save_update.php",
  //       {
  //           res_id : res_id,          
  //           master_id   : master_id,
  //           day : day,
  //           month : month,
  //           year : year,
  //           type : type,
  //           value : ui.value
  //       },
  //       function( data )
  //       {
  //         var tds = $( tr ).find('span.val')
          
  //         var total = 0 ;
  //         var count = 0 ;
  //         var average = 0 ;

  //         $.each( tds , function( key, item )
  //         {
  //           if( $( item ).text().length )
  //           {
  //             count ++
  //             total += 1 * $( item ).text()
  //           }
  //         });

  //         $( tr ).find('span.sum').text( total )
  //         average = isNaN( Number( total / count ).toFixed(2) ) ? 0 : Number( total / count ).toFixed(2)
  //         $( tr ).find('span.average').text( average )
  //       }
  //     );
  //     }
  //   });

//  $( '.score' ).parent().hide()
}

function editableIn()
{
  // $( this ).find( '.score' ).parent().show()
  $( this ).find( '.score' ).removeClass('hidden')  
  $( this ).closest('td').find('span.val').addClass('hidden')
}

function editableOut()
{
    let input = $( this ).find( '.score' )
    let val = $( input ).val()

    if( val.length )
      val = parseFloat( val ).toFixed( 1 )

    if( val == '0.0' || isNaN( val ) )
      val = ''
    $( input ).val( val ).addClass('hidden')
    
    if( val == '0.0' || isNaN( val ))
      val = '-'
    $( this ).closest('td').find('span.val').text( val ).removeClass('hidden') 
}

function getCalendar( month, year )
{
  startLoadingAnimation()
  $.post(
        "project/master_evaluation/ajax.get_calendar.php",
        {
            month : month,
            year : year,
            user_id : user_id,
            res_id : res_id
        },
        function( data )
        {
          $( '.table_div' ).empty().html( data )
          adjust_ui()
          stopLoadingAnimation()
          // cons('here')
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

function score_keyup()
{
  var val = $( this ).val()

  // if( val == '' || ( val == 0 && val.indexOf(".") !== -1 ) || Number( val ) > 5 )
  // {
  //     val = 0 ;
  //     $( this ).val('');
  // }
 
  var td = $( this ).closest('td')
  var tr = $( td ).parent()

  var day = $( td ).data('id')
  var month = $( tr ).data('month')
  var year = $( tr ).data('year')
  var type = $( tr ).data('eval-type')
  var master_id = $( tr ).parents('table').attr("id")
  $( td ).find('span.val').text( val ? val : '-')

   $.post(
    "project/master_evaluation/ajax.save_update.php",
    {
        res_id : res_id,          
        master_id   : master_id,
        day : day,
        month : month,
        year : year,
        type : type,
        value : val
    },
    function( data )
    {
      // cons( data )
      var tds = $( tr ).parent().find('td.editable[data-id=' + day + ']')
      var sum = 0 ;

      $.each( tds , function( key, item )
          {
            sum += Number( $( item ).find('input').val() )
              count ++             
          });

      $( tr ).parent().find('tr.final').find('td[data-id=' + day + ']').find('input').val( sum )

      if( sum == 0 || sum == 0.00 )
        sum = '-';
          else
            sum = sum.toFixed(1)

      $( tr ).parent().find('.final').find('td[data-id=' + day + ']').find('span').text( sum )
//
      tds = $( tr ).find('td[data-id]')
      sum = 0 
      count = 0 ;

      $.each( tds , function( key, item )
          {
              sum += Number( $( item ).find('input').val() )
              count ++             
          });      

      $( tr ).find( 'span.sum').text( sum.toFixed(1) )
      $( tr ).find( 'span.average').text( ( sum / count ).toFixed(1) )
      
//
      tds = $( tr ).parent().find('tr[data-year]').find('td[data-id]')

      var total = 0 ;
      var count = 0 ;

      $.each( tds , function( key, item )
          {
            var val = $( item ).find('.val').text()
            if( !isNaN( val ) )
            {
              total += Number( val )
              count ++;
            }
          });

      var average = Number( count ) ? Number( Number( total ) / Number( count )).toFixed(1) : "0.0"

      $( tr ).parent().find('tr.final').find('.final_val').text( total.toFixed(1) )
      $( tr ).parent().find('tr.final').find('.final_avg').text( average )
    }
  );
}

