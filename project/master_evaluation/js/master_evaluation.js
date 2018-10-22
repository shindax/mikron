$( function()
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
          getCalendar( month + 1, year )
      }).bind('monthpicker-change-year', function (e, year) { $('#monthpicker').val(''); });

  adjust_ui();
});

function adjust_ui()
{
  // $('.print_button').unbind('click').bind('click', print_button_click )
  // $('.print_total_button').unbind('click').bind('click', print_total_button_click )
  // $('input[type=radio]').unbind('click').bind('click', input_radio_click )  

  $('.editable').mouseenter( editableIn ).mouseleave( editableOut );

  $( ".score" ).removeClass('hidden').spinner({
      spin: function( event, ui ) 
      {
        var td = $( this ).closest('td')
        var tr = $( td ).parent()

        var day = $( td ).data('id')
        var month = $( tr ).data('month')
        var year = $( tr ).data('year')
        var type = $( tr ).data('eval-type')
        var master_id = $( tr ).parents('table').attr("id")

        $( this ).closest('td').find('span.val').text( ui.value )

        $.post(
        "project/master_evaluation/ajax.save_update.php",
        {
            res_id : res_id,          
            master_id   : master_id,
            day : day,
            month : month,
            year : year,
            type : type,
            value : ui.value
        },
        function( data )
        {
          var tds = $( tr ).find('span.val')
          
          var total = 0 ;
          var count = 0 ;
          var average = 0 ;

          $.each( tds , function( key, item )
          {
            if( $( item ).text().length )
            {
              count ++
              total += 1 * $( item ).text()
            }
          });

          $( tr ).find('span.sum').text( total )
          average = isNaN( Number( total / count ).toFixed(2) ) ? 0 : Number( total / count ).toFixed(2)
          $( tr ).find('span.average').text( average )
        }
      );
      }
    });

  $( '.score' ).parent().hide()
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


function editableIn()
{
  $( this ).find( '.score' ).parent().show()
  $( this ).closest('td').find('span.val').hide()
}

function editableOut()
{
    $( this ).find( '.score' ).parent().hide()
    $( this ).closest('td').find('span.val').show()    
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
          //console.log( data )
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
