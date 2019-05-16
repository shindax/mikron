$( function()
{
  "use strict"

  let today = new Date();
  let month = today.getMonth() + 1; //January is 0!
  let year = today.getFullYear();

  var options =
    {
        selectedYear: year,
        selectedMonth:month,
        startYear: 2010,
        finalYear: 2020,
        monthNames: monthNamesShort
    };

  $('#monthpicker').monthpicker(options).bind('monthpicker-click-month', monthpicker_click_month).bind('monthpicker-change-year', function (e, year) { $('#monthpicker').val(''); }).val( monthNames[month - 1 ] + ' ' + year )

   adjust_ui();
   make_dialog( '','' );

function monthpicker_click_month( e, month )
{
  var year = $('#monthpicker').monthpicker('getDate').getFullYear();
  var month = $('#monthpicker').monthpicker('getDate').getMonth();

  $('#monthpicker').data('date', { 'month': month + 1 , 'year' : year });
  $('#monthpicker').val( monthNames[ month ] + ' ' + year );
  getMonthPages( year, month + 1 )
}

function adjust_ui()
{
  $('#find_input').unbind( 'keyup' ).bind( 'keyup', find_input_keyup );
  $('#find_button').unbind( 'click' ).bind( 'click', find_button_click );

  $('.add_row').unbind( 'click' ).bind( 'click', add_row_button_click );
  $('#add_page').unbind( 'click' ).bind( 'click', add_page_button_click );
  $('.print_img').unbind( 'click' ).bind( 'click', print_img_click );
  $('.print_check').unbind( 'change' ).bind( 'change', print_check_change );

  $('.add_img').unbind( 'click' ).bind( 'click', add_in_cur_operation_click );
  $('.add_pict_img').unbind( 'click' ).bind( 'click', add_pict_img_click );
  $('.view_pict_img').unbind( 'click' ).bind( 'click', view_pict_img_click );
  $('.inwork_state').unbind( 'click' ).bind( 'click', inwork_state_click );

  $('.del_oper_img').unbind( 'click' ).bind( 'click', del_oper_img_click );
  $('.del_order_img').unbind( 'click' ).bind( 'click', del_order_img_click );  

  $('button[data-key]').unbind( 'click' ).bind( 'click', button_click );

  $('.del_img').unbind( 'click' ).bind( 'click', del_img_click );
  $('#excel_export').unbind( 'click' ).bind( 'click', excel_export_click );


if( user_id == 165 || user_id == 154 || user_id == 5 || user_id == 1 || user_id == 228 )
{
  $('#add_page').prop('disabled', false );
  $('.add_row').prop('disabled', false );
}
  else
  {
    $('#add_page').prop('disabled', true );
    $('.add_row').prop('disabled', true );
    $('.del_order_img').hide();
    $('.del_order_img').hide();
    $('.del_oper_img').hide();
  }

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
              var id = $( this ).closest('tr').attr('id');
              var day = 0;
              var month = 0 ;
              var year = 0 ;
              var field = 'date';

              var date = $( this ).datepicker( 'getDate' );

              day = date.getDate();
              month = date.getMonth() + 1 ;
              year = date.getFullYear() ;
              var val = year + '-' + month + '-' +day ;
              update_page( id, field, val )
            }
        });

var label = 0 ;
var value = 0 ;
var el = 0;

//*********************************
// autocomplete для списка операций
// Отправляем запрос
//if( user_id == 165 || user_id == 154 || user_id == 5  )
  $.ajax({
        url: '/project/entrance_control/ajax.get_operations.php',
        type: 'POST',
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
          {
            // Если все ОК
            if( typeof respond.error === 'undefined' )
            {
                  $(".operation").autocomplete
                  ({
                    source: respond,
                    select: function( event, ui )
                    {
                        el = this ;
                        var name =  $( this ).val() ;
                        label = ui.item.label ;
                        value = ui.item.value ;
                  },
                  close: function( event, ui )
                  {
                    if( value == 0 )
                      value = '';
                    if( label == 0 )
                        label = '';
                    $( el ).closest('tr').find('.operation').val( label ).attr('data-id', value ).blur();
                    data_changed( $( el ).data('key') );
                    label = 0 ;
                    value = 0;
                  }
                });
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


//*********************************
// autocomplete для списка поставщиков
// Отправляем запрос

//if( user_id == 165 || user_id == 154 || user_id == 5  )
  $.ajax({
        url: '/project/entrance_control/ajax.get_suppliers.php',
        type: 'POST',
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
          {
            // Если все ОК
            if( typeof respond.error === 'undefined' )
            {
                  $(".supplier").autocomplete
                  ({
                    source: respond,
                    select: function( event, ui )
                    {
                        el = this ;
                        var name =  $( this ).val() ;
                        label = ui.item.label ;
                        value = ui.item.value ;

                        var id = $( this ).parents('tr').attr('id');
                        var field = "client_id";
                        update_page( id, field, value );
                  },
                  close: function( event, ui )
                  {
                    $( el ).closest('tr').find('.supplier').val( label ).attr('data-id', value ).blur();
                    data_changed( $( el ).data('key') );
                    label = 0 ;
                    value = 0;
                  }
                });
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

// *************************************

// autocomplete для списка заказов
// Отправляем запрос

//if( user_id == 165 || user_id == 154 || user_id == 5  )
  $.ajax({
        url: '/project/entrance_control/ajax.get_orders.php',
        type: 'POST',
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
          {
            // Если все ОК
            if( typeof respond.error === 'undefined' )
            {
                  $(".order").autocomplete({
                    source: respond,
                    select: function( event, ui )
                    {
                        var name =  $( this ).val() ;
                        var el = this ;
                        var label = ui.item.label ;
                        var value = ui.item.value ;

                        // cons( value )

                        $.post(
                          '/project/entrance_control/ajax.get_order_name.php',
                          {
                              id  : value
                          },
                          function( data )
                          {
                            $( el ).closest('tr').find('.order_name').val( data )
                            $( el ).closest('tr').find('.order').val( label ).attr('data-id', value ).blur() ;
                            var key = $( el ).data('key') ;
                            get_dse_name( value,  key );
                            data_changed( key );
                            $(".dse_name").prop('disabled', false );
                          }
                        );
                     }
                  });
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

  $('.type_sel').unbind( 'change' ).bind( 'change', type_sel_changed );
  $('.page_num_input').unbind( 'keyup' ).bind( 'keyup', page_num_input_changed );
  $('.manual_edit').unbind( 'keyup' ).bind( 'keyup', manual_edit_keyup );
  $('.count_input').unbind( 'keyup' ).bind( 'keyup', count_changed );

  $('.supplier').unbind( 'blur' ).bind( 'blur', on_blur );
  $('.operation').unbind( 'blur' ).bind( 'blur', on_blur );
  $('.order').unbind( 'blur' ).bind( 'blur', on_blur );
  $('.dse_name').unbind( 'blur' ).bind( 'blur', on_blur );

  $('#upload_file_input').unbind('change').bind('change', upload_file_input_change );

  $( '.type_sel' ).prop( 'disabled', true );


  switch( user_id )
    {
    case 1    :  
                   $('#upload_file_input, .count_input, .manual_edit, #add_page, .add_row, .order, .datepicker, .supplier, .type_sel, .operation').prop('disabled', false );
                   $(' .inwork_state, #dialog_count, #dialog_comment').prop('disabled', true );

                        if( $('#add_row').data('id') )
                            $( '#add_row' ).prop('disabled', false );
                              else
                                $( '#add_row' ).prop('disabled', true );

                          $(' #dialog_count, #dialog_comment, .inwork_state').prop('disabled', false );
                          $('.add_img').css('cursor','default').unbind();
                          break ;


    case 228  :
    case 165  :
    case 154  :
    case 5    :  // Сотрудники отдела кооперации
                          $('.inwork_state, #upload_file_input, .count_input, .manual_edit, #add_page, .add_row, .order, .datepicker, .supplier, .type_sel, .operation').prop('disabled', false );
                          $(' .inwork_state, #dialog_count, #dialog_comment').prop('disabled', true );

                        if( $('#add_row').data('id') )
                            $( '#add_row' ).prop('disabled', false );
                              else
                                $( '#add_row' ).prop('disabled', true );
                        $('#upload_file_input').prop('disabled', true );
                        $('.add_pict_img').css('cursor','default').unbind();                        
                        break ;

    case 224 : // Михальчук
    case 130 : // Соловова
                          $( 'input' ).prop( 'disabled', true );
                          $('.count_input, #add_page, .order, .datepicker, .supplier, .type_sel, .operation').prop('disabled', true );
                          $(' #dialog_count, #dialog_comment, .inwork_state').prop('disabled', false );
                          $('.add_img').css('cursor','default').unbind();
                          $('#upload_file_input').prop('disabled', false );                          
                          break ;

  default :
                            $('#upload_file_input, .manual_edit, #add_page, .order, .datepicker, .supplier, .type_sel, .operation').prop('disabled', true );
                            $('.count_input, .inwork_state, #dialog_count, #dialog_comment').prop('disabled', true );
                            $('.add_img, .add_pict_img').css('cursor','default').unbind();
                            break ;

  }

    $( '#monthpicker' ).prop( 'disabled', false );
    $( '#find_input' ).prop( 'disabled', false );
    $( '.print_check' ).prop( 'disabled', false );    
}

function add_row_button_click()
{
  var id = $( this ).data('id');

    $.post(
      '/project/entrance_control/ajax.add_row.php',
      {
          id  : id
      },
      function( data )
      {
            var table = $( data ).find('table');
            $('div.table-row[data-id="' + id + '"]').empty().html( table );
            adjust_ui();
      }
    );

}

function del_oper_img_click()
{
  var oper_id = $( this ).data('key');
  var table_id = $( this ).parents('table').data('id');

    $.post(
      '/project/entrance_control/ajax.oper_delete.php',
      {
          oper_id  : oper_id,
          table_id : table_id
      },
      function( data )
      {
        var table = $( data ).find('table');
        var parent_div = $('div[data-id='+ table_id + ']');
        $( parent_div ).html( table );

        adjust_ui();
      }
    );
}


function del_order_img_click()
{
  var order_id = $( this ).data('key');
  var table_id = $( this ).parents('table').data('id');


    $.post(
      '/project/entrance_control/ajax.order_delete.php',
      {
          order_id  : order_id,
          table_id : table_id
      },
      function( data )
      {
        var table = $( data ).find('table');
        var parent_div = $('div[data-id='+ table_id + ']');
        $( parent_div ).html( table );

        adjust_ui();
      }
    );
}



function save_img_click()
{
   save_all();
}

function save_all()
{
  var id = $( '#ent_control_table' ).data('id') ;

    $.post(
      '/project/entrance_control/ajax.save_all.php',
      {
          id  : id,
      },
      function( data )
      {
            var table = $( data ).find('#ent_control_table');
            $('div.table-row[data-id="' + id + '"]').empty().html( table );
            adjust_ui();
      }
    );
}

function data_changed( row_id = null )
{

  var oper_id = $(".operation[data-key='" + row_id + "']").attr('data-id');

  if( oper_id == undefined )
    oper_id = $(".order[data-key='" + row_id + "']").attr('data-operation-id');

  var order_id = $(".order[data-key='" + row_id + "']").attr('data-id');
  var dse_id = $(".dse_name[data-key='" + row_id + "']").attr('data-id');
  var count = $(".count_input[data-key='" + row_id + "']").val();

    $.post(
      '/project/entrance_control/ajax.update_data.php',
      {
          id : row_id,
          oper_id  : oper_id,
          order_id : order_id,
          dse_id : dse_id,
          count : count
      },
      function( data )
      {
         adjust_ui();
      }
    );
}

function get_dse_name( id, key )
{
    var data = { 'id' : id };

// autocomplete для перечня дсе в заказе
// Отправляем запрос
  $.ajax({
        url: '/project/entrance_control/ajax.get_dse_name.php',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
          {

            // Если все ОК
            if( typeof respond.error === 'undefined' )
            {
                  $(".dse_name[data-key='" + key + "']").prop( 'disabled', false ).autocomplete({
                    source: respond,
                    select: function( event, ui )
                    {
                        var el = this ;
                        var zakdet_id =  ui.item.value
                        $( el ).attr('data-id', zakdet_id ).blur();

                        var data = { 'id' : zakdet_id };

                        $.ajax({
                              url: '/project/entrance_control/ajax.get_zakdet_data.php',
                              type: 'POST',
                              data: data,
                              dataType: 'json',
                              success: function( respond, textStatus, jqXHR )
                                {
                                       if( typeof respond.error === 'undefined' )
                                        {
                                          var dse_name = respond.dse_name ;
                                          var dse_draw = respond.dse_draw ;
                                          var tr = $( el ).closest('tr');

                                          $( el ).val( dse_name );
                                          $( tr ).find( '.dse_draw' ).val( dse_draw );
                                          data_changed( $( el ).data('key') );

                                          var inp = $( 'input.dse_name[data-key="' + key + '"]').unbind().removeClass('ui-autocomplete-input').addClass("manual_edit").data('field','dse_name').bind( 'keyup', manual_edit_keyup );

                                          $( tr ).find( '.dse_draw' ).prop('disabled', false ).addClass("manual_edit").attr('data-key', key ).data('field','dse_draw').bind( 'keyup', manual_edit_keyup );

                                          update_item( key, 'dse_name', dse_name );
                                          update_item( key, 'dse_draw', dse_draw );

                                          $( 'button.reject_state[data-key="' + key + '"]').removeClass('hidden');
                                          $( 'button.rework_state[data-key="' + key + '"]').removeClass('hidden');
                                          $( 'button.pass_state[data-key="' + key + '"]').removeClass('hidden');
                                          $( 'input.print_check[data-key="' + key + '"]').removeClass('hidden');
                                          adjust_ui();
                                        }
                                        else
                                        {
                                            console.log('AJAX request errors detected. Server said : ' + respond.error );
                                        }

                                }
                                ,
                                    error: function( jqXHR, textStatus, errorThrown )
                                  {
                                      console.log('AJAX request errors in coop_orders.js detected : ' + textStatus + errorThrown );
                                  }
                           });
                     }
                  });
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

function on_blur()
{
  if( $( this ).attr('data-id') == undefined || $( this ).attr('data-id') == 0 )
      $( this ).val('');
}

function add_in_cur_operation_click()
{
  var id = $( this ).parents('table' ).data('id') ;
  var oper_id = $( this ).data('id');

    $.post(
      '/project/entrance_control/ajax.add_in_cur_operation.php',
      {
          id  : id,
          oper_id : oper_id
      },
      function( data )
      {
            var table = $( data ).find('table');
            $('div.table-row[data-id="' + id + '"]').empty().html( table );
            adjust_ui();
      }
    );
}

function count_changed()
{
  var row_id = $( this ).data('key') ;
  var count = $( this ).val() ;

    $.post(
      '/project/entrance_control/ajax.update_count.php',
      {
          id : row_id,
          count : count
      },
      function( data )
      {
      }
    );
}

function make_dialog( el, caption )
{
      $( "#comment_dialog" ).dialog({
        resizable: false,
        height: 190,
        width: 340,
        modal: true,
        closeOnEscape: true,
        autoOpen : false,

        position: { my: "left top", at: "left bottom", of: el },
        create : function()
          {
                $('div.ui-widget-header').css('background','#5F9EA0');
          },
        close : function()
          {
//                $('#dialog_count').css('background','white');
          },

        buttons:
        [
            {
             id : "change_date_dialog_add_button",
            // 'Добавить' в unicode url : https://r12a.github.io/apps/conversion/
            text: "OK",
            click : function ()
            {
                var row_id = $( this ).attr('data-key');
                var field = $( this ).attr('data-field');

                var count = $('#dialog_count').val();
                var comment = $('#dialog_comment').val();

              if( isNaN( 1 * count ) )
              {
                  $('#dialog_count').css('background','#FFA07A');
              }
              else
              {
//                    $('#dialog_count').css('background','white');

                    $.post(
                      '/project/entrance_control/ajax.update_comment.php',
                      {
                          id  : row_id,
                          field : field,
                          count : count,
                          comment : comment,
                          user_id : user_id
                      },
                      function( data )
                      {
                        var but = $('.'+field+'[data-key="' + row_id + '"]') ;
                        var field_class = field + '_field';
                        $( but ).text( 1 * count );
                        var td = $( but ).parent();
                        if( 1 * count )
                            $( td ).addClass( field_class );
                              else
                                   $( td ).removeClass( field_class );
                      }
                    );

                      $(this).dialog("close");
            }
          }
            },
            {
            // 'Отмена' в unicode
            text : "\u041E\u0442\u043C\u0435\u043D\u0430",
            click : function () {
                        $(this).dialog("close");
                    }
            }
        ]
    }).dialog('option', 'title', caption );
}

function button_click( event )
{
    var that = this
    event.preventDefault();
    var cls = $( this ).attr('class').trim() ;    
    var caption = '';

    // if( cls == 'inwork_state' )
    //   caption = '\u0412 \u0440\u0430\u0431\u043E\u0442\u0435';
    if( cls == 'reject_state' )
      caption = '\u0418\u0437\u043E\u043B\u044F\u0442\u043E\u0440 \u0431\u0440\u0430\u043A\u0430';
    if( cls == 'rework_state' )
      caption = '\u0414\u043E\u0440\u0430\u0431\u043E\u0442\u043A\u0430';
    if( cls == 'pass_state' )
      caption = '\u041F\u0440\u043E\u043F\u0443\u0449\u0435\u043D\u043E';

    var count = $( this ).text();

    var row_id = $( this ).data('key');

    $('#dialog_count').val( count );
    $('#comment_dialog').attr( 'data-key', row_id ).attr( 'data-field', cls );

        $.post(
          '/project/entrance_control/ajax.get_comment.php',
          {
              id  : row_id,
              field : cls + '_comment'
          },
          function( data )
          {
              $('#dialog_comment').val( data == 0 ? '' : data );
              make_dialog( that,  caption );
              $( "#comment_dialog" ).dialog('open');
          }
        );
}

function add_page_button_click()
{
           $.post(
          '/project/entrance_control/ajax.add_page.php',
          {
            user_id  : user_id
          },
          function( data )
          {
            $('#main_div').prepend( data );
            adjust_ui();
          }
        );
}

function type_sel_changed()
{
  var id = $( this ).parents('tr').attr('id');
  var val =  $(  this ).find("option:selected" ).val() ;
  var field = "proc_type_id";

  update_page( id, field, val );
 }

 function page_num_input_changed()
 {
  var id = $( this ).parents('tr').attr('id');
  var val =  $(  this ).val() ;
  var field = "page_num";
  $('#page_sel option[value="' + id + '"]').text( val );
  update_page( id, field, val );
  }

 function update_page( id, field, val )
 {
        $.post(
          '/project/entrance_control/ajax.update_page.php',
          {
              id  : id,
              field : field,
              val : val
          },
          function( data )
          {
             adjust_ui()
          }
        );
 }

function update_item( id, field, val )
 {

        $.post(
          '/project/entrance_control/ajax.update_item.php',
          {
              id  : id,
              field : field,
              val : val
          },
          function( data )
          {
            if( field == 'inwork_state' )
              if( + val )
                $('span.inwork_ch_state_date[data-key=' + id + ']').text( data )
                else
                  $('span.inwork_ch_state_date[data-key=' + id + ']').text('')
          }
        );
 }


 function upload_file_input_change()
{
    var id = $( this ).data('id');
    var files = this.files;

    // Создадим данные формы и добавим в них данные файлов из files
    var data = new FormData();
    $.each( files, function( key, value )
    {
        data.append( key, value );
    });

    // Добавить id и what
    data.append( 'id', id );

    // Отправляем запрос
    $.ajax({
        url: '/project/entrance_control/ajax.upload_file.php?uploadfiles',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'html',
        processData: false, // Не обрабатываем файлы (Don't process the files)
        contentType: false, // Так jQuery скажет серверу что это строковой запрос
        success: function( respond, textStatus, jqXHR )
        {
            if( typeof respond.error === 'undefined' )
           {
              $( $( 'tr[id="' + id + '"]') ).find('.add_pict_img').data('img',respond).attr('data-img',respond).removeClass('add_pict_img').addClass('view_pict_img').attr( "src", "uses/film.png");
              $( $( 'tr[id="' + id + '"]') ).find('.del_img').removeClass('hidden');              
              adjust_ui();
            }
            else
            {
                console.log('AJAX request errors detected. Server said : ' + respond.error );
            }

        },
        error: function( jqXHR, textStatus, errorThrown )
        {
            console.log('AJAX request errors in entrance_control.js detected : ' + textStatus + errorThrown );
        }
    });
}

function add_pict_img_click()
{
    var image = $( this ).data('img');
    var id = $( this ).closest('tr').attr('id');
    $('#upload_file_input').data('id',id);
    $('#upload_file_input').click();
}

function view_pict_img_click()
{
  var url = images_path + $( this ).data('img');
  window.open( url );
}

function inwork_state_click()
{
var id = $( this ).data('key');

if(this.checked)
  update_item( id, 'inwork_state', 1 );
    else
      update_item( id, 'inwork_state', 0 );
}

function manual_edit_keyup()
{
  var id = $( this ).data('key');
  var field = $( this ).data('field');
  var val = $( this ).val();
  update_item( id, field, val );
}


function print_img_click()
{
  var arr = [];
  var op_arr = [];
  var id = $( this ).data('id');
  
  var ops = $( this ).closest('table').find('.add_img');
  $.each( ops , function( key, item )
    {
      op_arr.push( $( item ).data('id') );
    });
  
  var op_list = op_arr.join(',');

  var checks = $('table[data-id=' + id + ']').find('input[type=checkbox]:checked');
  
  $.each( checks , function( key, item )
    {
      var id = $( item ).data('key');
      arr.push( id );
    });

  var list = arr.join(',');

  if( $(this).hasClass('printed'))
  {
      var name = $('input[data-key=' + id + ']').val();
  	  var url = "http://mic.ru/project/entrance_control/load_doc.php?list=" + list + '&id=' + id +'&name=' + name + '&user_id=' + user_id + '&op_list=' + op_list;
      window.location.href = url;
      $('#curloading').remove();
  }
}

function print_check_change()
{
  var table = $( this ).closest('table');
  var id = $( table ).data('id');
  var checked = $( table ).find('.print_check:checked');
  
  if( checked.length )
    $('img[data-id=' + id + ']').addClass('printed').attr('src','uses/word_16.png');
    else
      $('img[data-id=' + id + ']').removeClass('printed').attr('src','uses/word_16_dis.png');

}

function del_img_click()
{

  if( confirm( "\u0412\u044B \u0434\u0435\u0439\u0441\u0442\u0432\u0438\u0442\u0435\u043B\u044C\u043D\u043E \u0445\u043E\u0442\u0438\u0442\u0435 \u0443\u0434\u0430\u043B\u0438\u0442\u044C \u0438\u0437\u043E\u0431\u0440\u0430\u0436\u0435\u043D\u0438\u0435?") )
  {
        $( this ).addClass('hidden');
        var row = $( this ).parent().parent().parent();
        var id = $( row ).attr('id');
        var img = $( row ).find('.view_pict_img');

         $.post(
          '/project/entrance_control/ajax.delete_image.php',
          {
              id  : id
          },
          function( data )
          {
              $( img ).attr('src','uses/addf_img.png').removeClass('view_pict_img').addClass('add_pict_img').removeAttr('data-img').data('img','');
              adjust_ui();
          }
        );
  }

}

function find_input_keyup()
{
  $('.found').text('')
  if( $( this ).val().length >= 4 )
      $('#find_button').prop('disabled', false );
        else
          $('#find_button').prop('disabled', true );
}

function getMonthPages( year, month )
{
  startLoadingAnimation() 

  $.post(
      '/project/entrance_control/ajax.get_month_pages.php',
      {
        year  : year,
        month : month,
        user_id : user_id
      },
      function( data )
      {
        $('#main_div').html( data )
        adjust_ui();        
        disablePastMonthesEdit( year, month )
        let count = $( data ).find('table[data-id]').length;
        $('.found').text( "\u041d\u0430\u0439\u0434\u0435\u043d\u043e \u043b\u0438\u0441\u0442\u043e\u0432 : " + count )
        stopLoadingAnimation()          
     }
    );
}

function startLoadingAnimation() // - функция запуска анимации
{
    $("#loadImg").show();
}

function stopLoadingAnimation() // - функция останавливающая анимацию
{
    $("#loadImg").hide();
}

function find_button_click( event )
{
  event.preventDefault(); 

  let filter = $( '#find_input' ).val()
     
  startLoadingAnimation() 

  $('#monthpicker').val('');

      $.post(
      '/project/entrance_control/ajax.filtrate.php',
      {
        user_id  : user_id,
        filter : filter
      },
      function( data )
      {
        $('#main_div').empty().html( data );
        adjust_ui();
        disablePastMonthesEdit( 0 , 0, true )
        let count = $( data ).find('table[data-id]').length;        
        $('.found').text( "\u041d\u0430\u0439\u0434\u0435\u043d\u043e \u043b\u0438\u0441\u0442\u043e\u0432 : " + count )
        stopLoadingAnimation()         
      }
    );
}

function disablePastMonthesEdit( year, month, find = 0 )
{
  let today = new Date();
  let today_month = today.getMonth() + 1; //January is 0!
  let today_year = today.getFullYear();

//  alert( month + ' : ' + today_month + ' : ' + year + ' : ' + today_year )

  if( month < today_month || year < today_year )
  {
      $('#add_page').prop('disabled', true );
      $('.add_row').prop('disabled', true );
      $('.manual_edit').prop('disabled', true );
      $('.count_input').prop('disabled', true );
      $('.inwork_state').prop('disabled', true );
      $('#dialog_count').prop('disabled', true );
      $('#dialog_comment').prop('disabled', true );

      if( find )
        switch( user_id )
          {
            case 228  :
            case 165  :
            case 154  :
            case 5    :  // Сотрудники отдела кооперации
                    $('#add_page').prop('disabled', false );
          }
  }
}

function excel_export_click()
{
  event.preventDefault();  
  let trs = $('tr[id]')
  let tr_ids = [];

    $.each( trs , function( key, item )
    {
      var id = $( item ).attr('id');
      tr_ids.push( id )
    });

  $.post(
        '/project/entrance_control/ajax.generate_csv.php',
        {
            ids  : tr_ids
        },
        function( data )
        {
          // cons( data )
          document.getElementById('file_export_link').click();
        }
      );
}


function cons( arg )
{
  console.log( arg )
}

});