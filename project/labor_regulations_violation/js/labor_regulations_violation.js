$( function()
{
      $( '.datepicker' ).datepicker(
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
            yearRange: '-2:+2',
            changeMonth : true,
            changeYear : true,
            closeOnEscape: true,
            isRTL: false,
            showOtherMonths: true,
            selectOtherMonths: true,
            beforeShow : function(input, inst) {},
            onSelect: function ()
            {
              update_page()
            }
        }).datepicker( "setDate", new Date());

adjust_ui();
update_page();
});

function adjust_ui()
{

 $('.value').unbind('keyup').bind('keyup', input_value_keyup )
 $('.interval').unbind('keyup').bind('keyup', input_interval_keyup )
 $('.value').unbind('blur').bind('blur', input_value_blur )
 $('.interval').unbind('blur').bind('blur', input_interval_blur )
 $('.shift_sel').unbind('change').bind('change', update_page )
 $('.cell').unbind('click').bind('click', cell_click )
 $('.print_button').unbind('click').bind('click', print_button_click )
 $('.collapse_image').unbind('click').bind('click', collapse_image_click )
 $('.expand_image').unbind('click').bind('click', expand_image_click )
 $('.ack_radio').unbind('change').bind('change', ack_input_readio_click )
}

function update_page()
{
  var date_and_shift = get_date_and_shift();
  if( date_and_shift.day == undefined || date_and_shift.shift == 0 )
    return ;

  startLoadingAnimation()

        $.post(
          '/project/labor_regulations_violation/ajax.getData.php',
          {
              year  : date_and_shift.year,
              month  : date_and_shift.month,
              day : date_and_shift.day,
              shift : date_and_shift.shift,
              can_edit : can_edit
          },
          function( data )
          {
            $('.table_div').html( data );
            adjust_ui();
            stopLoadingAnimation()
          }
        );
}

function input_value_blur()
{
  if( $( this ).hasClass('error'))
    $( this ).val('').removeClass('error')
  var val = $( this ).val();
  if( val == 0 )
    val = '';
  $( this ).siblings('span').text( val ).show();
  $( this ).hide();
}

function input_value_keyup( )
{
  var value = $( this ).val();
  var tr = $( this ).closest('tr')
  var id = $( tr ).attr('data_id');
  var field = $( this ).data('field')
  var res_id = $( tr ).data('res_id');
  var can_be_minus = 0 ;
  var row_id = $( tr ).data('row_id');

// Ряды:
// 50 - КНТ
// 60 - СЗ
// 70 - Уборка
// 80 - Прогул
// 90 - Отсутствует

  if( row_id == 50 || row_id == 60 || row_id == 70 ||row_id == 80 ||row_id == 90 )
    can_be_minus = 1 ;

  if( isNaN( 1 * value ) || ( value > 0 && can_be_minus ) )
    $( this ).addClass('error');
      else
      {
        $( this ).removeClass('error');
        $.post(
          '/project/labor_regulations_violation/ajax.save.php',
          {
              id  : id,
              field  : field,
              value : value
          },
          function( data )
          {
            var tr = $( "tr[data-res_id='" + res_id + "']");
            var total = 0 ;

            $.each( tr , function( key, item )
            {
              var row_total = 0 ;
              var unputs = $( item ).find('input.value');
              var row_id = $( item ).data( 'row_id');
                  $.each( unputs , function( key, item )
                  {
                    row_total += Number( $( item ).val() );
                  });

              $( item ).find('.by_shift').text( convert_time( row_total ) )

// Ряды:
// 80 - Прогул
// 90 - Отсутствует

              if( row_id == 80 || row_id == 90 )
                if( row_total )
                  $( item ).addClass('table-danger');
                    else
                      $( item ).removeClass('table-danger');

// Ряды:
// 1 - Опоздание
// 10 - Курение
// 20 - Простой
// 30 - Простой по вине мастера

              // if( row_id == 1 || row_id == 10 || row_id == 20 || row_id == 30 )
              if( row_id == 1 || row_id == 10 || row_id == 20 )
                total += row_total;
            });

            $( tr ).find('.total_violations').text( convert_time( total ) )
          }
        );
      }
}

function convert_time( total )
{
  let minutes = 0 

          if( total >= 60 )
          {
              let hours = Math.floor( total / 60 );
              minutes = total - hours * 60 ;
              total = hours + "\u0447\u002e"
              if( minutes )
                  total += minutes < 10 ? "0" + minutes + "\u043c\u0438\u043d\u002e" : minutes + "\u043c\u0438\u043d\u002e"
          }
          else
              total = total ? total + "\u043c\u0438\u043d\u002e" : "-";

  return total ;
}

function cell_click()
{
  $( this ).find('span').hide();
  $( this ).find('input').show().focus();
}

function input_interval_blur()
{
  var val = $( this ).val();
  $( this ).siblings('span').text( val ).show();
  $( this ).hide(); 
}

function  input_interval_keyup()
{
{
  var value = $( this ).val();
  var tr = $( this ).closest('tr')
  var id = $( tr ).attr('data_id');
  var field = $( this ).data('field')

          $.post(
          '/project/labor_regulations_violation/ajax.save.php',
          {
              id  : id,
              field  : field,
              value : value
          },
          function( data )
          {
//            alert( data )
          }
        );
}
  
}

function print_button_click( event )
{
  event.preventDefault();  
  var id = $( this ).attr( 'id' );
  var date_and_shift = get_date_and_shift()
 
  url = "print.php?do=show&formid=275&p0=" + id + "&p1=" + date_and_shift.day + '&p2=' + date_and_shift.month + '&p3=' + date_and_shift.year + '&p4=' + date_and_shift.shift; 

  window.open( url, "_blank" );
}

function collapse_image_click()
{
  var tr = $( this ).closest( 'tr');
  var res_id = $( tr ).data('res_id');
  collapse_expand( res_id, 1 )
}

function expand_image_click()
{
  var tr = $( this ).closest( 'tr');
  var res_id = $( tr ).data('res_id');
  collapse_expand( res_id, 0 )
}


function collapse_expand( res_id, value )
{
    var date = get_date_and_shift()

        $.post(
          '/project/labor_regulations_violation/ajax.coll_expand.php',
          {
              res_id  : res_id,
              value : value,
              year : date.year,
              month : date.month,
              day : date.day,
              shift : date.shift,
              can_edit : can_edit
          },
          function( data )
          {

            $( '#' + res_id ).html( $( data ).html() )
            adjust_ui();
          }
        );
}

function get_date_and_shift()
{
  var shift = Number( $('.shift_sel').val() );
  var day = 0;
  var month = 0 ;
  var year = 0 ;

  var date = $( '.datepicker' ).datepicker( 'getDate' );

  day = date.getDate();
  month = date.getMonth() + 1 ;
  year = date.getFullYear() ;

  return {'day' : day, 'month' : month, 'year' : year, 'shift' : shift };
}

function startLoadingAnimation() // - функция запуска анимации
{
    //$("#loadImg").show();
    $("#loadImg").removeClass('hidden-xs-up');
}

function stopLoadingAnimation() // - функция останавливающая анимацию
{
//    $("#loadImg").hide();
    $("#loadImg").addClass('hidden-xs-up');    
}

function ack_input_readio_click()
{
  let date = $('.datepicker').datepicker('getDate');
  let year = date.getFullYear();
  let month = 1 + date.getMonth();
  let day = date.getDate();
  let dep_id = $( this ).data('dep_id')
  let shift = $( ".shift_sel option:selected" ).val();
  
$.post(
          '/project/labor_regulations_violation/ajax.confirm.php',
          {
              year : year,
              month : month,
              day : day,
              dep_id  : dep_id,
              user_id  : user_id,
              shift : shift
          },
          function( data )
          {
            let tables = $('table[data-dep_id=' + dep_id + ']' )
            $.each( tables , function( key, item )
            {
              $( item ).find('input').remove();
              $( item ).find('.cell').unbind('click').removeClass('cell')
            });
            console.log()
          }
        );

}
