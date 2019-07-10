// Actions after full page loading
$( function()
{
  "use strict"
  adjust_ui();


function adjust_ui()
{
// Возможность изменения незакрытых этапов


    // Если Главный инженер, или ОВК имеют чекбокс "ознакомлен"
    // то последующие этапы не должны блокироваться.
    //  Дополнительно выбираются их чекбоксы с классом acquainted_checkbox_input

    let inputs = $('#table_div').find('.datepicker, .checkbox_input').get().reverse()
//    let inputs = $('#table_div').find('.datepicker, .checkbox_input, .acquainted_checkbox_input').get().reverse()

    $.each( inputs , function( key, item )
    {
      let row = $( item ).parent().parent().data( 'row' )

      if( row == 7 && $( item ).prop('disabled') == true )
          return false;

      if( $( item ).prop('disabled') == false )
        $( '#freeze_button').prop('disabled', false);

      if( parseInt( $( item ).val() ) && ( user_id == $( item ).data('coordinator_id') ) )
      {
        $( 'tr[data-row=' + row + ']').find('.datepicker').prop('disabled', false);
        return false;
      }
    });

// Заморозка для Рудых
    if( $('#coordinated_input').prop('disabled') == false && user_id == 4 )
      $( '#freeze_button').prop('disabled', false);

    if( user_id == $( '#unfreeze_button').data('frozen_by_id') )
        $( '#unfreeze_button').prop('disabled', false);

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
            changeMonth : true,
            changeYear : true,
            closeOnEscape: true,
            isRTL: false,
            beforeShow : function(input, inst) {},
            onSelect: function ()
            {
              var tr = $( this ).closest('tr') ;
              var id = $( tr ).data('id');
              var comment = $( tr ).find('.comment').val();
              var task_id = $( this ).data('task');

              var day = 0;
              var month = 0 ;
              var year = 0 ;
              var field = 'date';
              var date = $( this ).datepicker( 'getDate' );

              day = date.getDate();
              day = day > 9 ? day : "0" + day ;
              month = date.getMonth() + 1 ;
              month = month > 9 ? month : "0" + month ;
              year = date.getFullYear() ;

              if( $( this ).attr('id') == 'coordinated_input' )
              {
                var page_id = $( this ).data('id');
                var coord_date = year + '-' + month + '-' + day ;

                  $.post(
                            '/project/coordination_page/ajax.coordinate_page.php',
                            {
                                page_id  : page_id,
                                coord_date  : coord_date,
                            },
                            function( data )
                            {
                            }
                          );
              }
                else
                  update_page_and_save( id, day, month, year, comment, tr, task_id )
            }
        });

    $('.comment').unbind('keyup').bind('keyup', comment_input_keyup );
    $('.agreed_flag').unbind('change').bind('change', agreed_flag_change );
    $('.hide_checkbox').unbind('click').bind('click', hide_checkbox_click );
    $('#print_button').unbind('click').bind('click', print_button_click );
    
    $('.acquainted_checkbox_input').unbind('click').bind('click', acquainted_checkbox_input_click );

    $('#freeze_button').unbind('click').bind('click', freeze_button_button_click );
    $('#unfreeze_button').unbind('click').bind('click', unfreeze_button_button_click );

    $('#doc_path_copy').unbind('click').bind('click', doc_path_copy_button_click );
    $('#doc_path_input').unbind('paste').bind('paste', function(e) 
    {
     var self = this;
          setTimeout(
            function(e) 
              {
                var path = $(self).val()
                var id = $(self).data('id');

                $.post(
                            '/project/coordination_page/ajax.save_path.php',
                            {
                                id : id,
                                path  : path
                            },
                            function( data )
                            {
                            }
                          );
              }
          , 0);
    });

    if( frozen )
      $( 'input' ).prop('disabled', true )

}


function doc_path_copy_button_click()
{
    event.preventDefault();
    var str = $('#doc_path_input').val();
    CopyToClipboard( str )
}

function CopyToClipboard(str)
{
  let tmp   = document.createElement('INPUT'), // Создаём новый текстовой input
      focus = document.activeElement; // Получаем ссылку на элемент в фокусе (чтобы не терять фокус)

  tmp.value = str; // Временному input вставляем текст для копирования

  document.body.appendChild(tmp); // Вставляем input в DOM
  tmp.select(); // Выделяем весь текст в input
  document.execCommand('copy'); // Магия! Копирует в буфер выделенный текст (см. команду выше)
  document.body.removeChild(tmp); // Удаляем временный input
  focus.focus(); // Возвращаем фокус туда, где был
}

function update_page_and_save( id, day, month, year, comment, tr, task_id )
{
  var date = year + '-' + month + '-' + day ;
  var row_id = $( tr ).data('row');

$.post(
          '/project/coordination_page/ajax.save_all.php',
          {
              id  : id,
              date : date,
              comment : comment,
              user_id : user_id,
              row_id : row_id,
              task_id : task_id
          },
          function( data )
          {
            $( '#table_div' ).empty().append( data )
            adjust_ui()
          }
        );
}

function  comment_input_keyup()
{
  var comment = $( this ).val()
  var tr = $( this ).closest('tr') 
  var id = $( tr ).data('id')
  var el = this 

  $.post(
          '/project/coordination_page/ajax.save_comment.php',
          {
              id  : id,
              comment : comment
          },
          function( data )
          {
            adjust_ui()
            $( el ).focus();
          }
        );
}

function agreed_flag_change()
{
  var tr = $( this ).closest('tr') ;
  var id = $( tr ).data('id');
  var comment = $( tr ).find('.comment').val();

  var day = 0;
  var month = 0 ;
  var year = 0 ;
  var field = 'date';
  var date = new Date();

  day = date.getDate();
  day = day > 9 ? day : "0" + day ;
  month = date.getMonth() + 1 ;
  month = month > 9 ? month : "0" + month ;
  year = date.getFullYear() ;
  update_page_and_save( id, day, month, year, comment, tr ) 
}

function hide_checkbox_click()
{
  var page_id = $( this ).data('page_id');
  var task_id = $( this ).data('task_id');

 $.post(
          '/project/coordination_page/ajax.hide_rows.php',
          {
              page_id  : page_id,
              task_id  : task_id,
              user_id  : user_id
          },
          function( data )
          {
            $( '#table_div' ).empty().append( data )
            adjust_ui()
          }
        );
}

function print_button_click( event )
{
      event.preventDefault();
      let id = $( this ).data('id');
      let url = "print.php?do=show&formid=272&p0=" + id;
      window.open( url, "_blank" );
}

function acquainted_checkbox_input_click()
{
  let tr = $( this ).closest('tr');
  let id = $( tr ).data('id');
  let row_id = $( tr ).data('row');
  let page_id = $( this ).data('page_id');
  let that = this 

  $.post(
        '/project/coordination_page/ajax.save_acquainted_no_coop.php',
        {
            id  : id,
            page_id : page_id,
            row_id  : row_id,
            user_id : user_id
        },
        function( data )
        {
          $( tr ).find('.ins_time').text( data );
          $( that ).prop('disabled', true );
        }
      );
}


function freeze_button_button_click()
{
  event.preventDefault();
    let inputs = $('#table_div').find('.datepicker, .checkbox_input').get().reverse()
    let row = 0 

    $.each( inputs , function( key, item )
    {
      let loc_row = $( item ).data('task') //$( item ).parent().parent().data( 'row' )
      if( $( item ).prop('disabled') == false )
         row = loc_row
    });

  $.post(
            '/project/coordination_page/ajax.freeze_unfreeze.php',
            {
                id  : id,
                user_id  : user_id,
                state : 1,
                stage : row
            },
            function( data )
            {
              let caption = $( data ).filter('.frozen_caption').text()
              $( 'h2').addClass('frozen').find('span').text( caption )
              $('#table_div').html( data )
              // Разморозить
              $('#freeze_button').prop( 'disabled', false ).html('\u{420}\u{430}\u{437}\u{43C}\u{43E}\u{440}\u{43E}\u{437}\u{438}\u{442}\u{44C}').attr('id','unfreeze_button')
              frozen = 1
              adjust_ui();
            }
          );
}

function unfreeze_button_button_click()
{
  event.preventDefault();
  let id = $( this ).data('id')
  $.post(
            '/project/coordination_page/ajax.freeze_unfreeze.php',
            {
                id  : id,
                user_id  : user_id,
                state : 0
            },
            function( data )
            {
              $( 'h2').removeClass('frozen').find('span').text('')
              $('#table_div').html( data )
              // Заморозить
              $('#unfreeze_button').prop( 'disabled', false ).html('\u{417}\u{430}\u{43C}\u{43E}\u{440}\u{43E}\u{437}\u{438}\u{442}\u{44C}').attr('id','freeze_button')
              frozen = 0

              // Разморозка для Рудых
                  if( user_id == 4 )
                    $( '#coordinated_input').prop('disabled', false);

              adjust_ui();              
            }
          );

}


function cons( arg ) 
{
  console.log( arg )
}

});
