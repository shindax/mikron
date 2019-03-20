$( function()
{
  "use strict"

  let today = new Date();
  let today_month = today.getMonth() + 1; //January is 0!
  let today_year = today.getFullYear();

  var options =
    {
        selectedYear: today_year,
        selectedMonth : today_month,
        startYear: 2010,
        finalYear: 2020,
        monthNames: monthNamesShort
    };

  $('#monthpicker').monthpicker(options).bind('monthpicker-click-month', monthpicker_click_month).bind('monthpicker-change-year', function (e, year) { $('#monthpicker').val(''); }).val( monthNames[ today_month - 1 ] + ' ' + today_year )

  monthpicker_click_month()

  if( can_edit )
    $('#add_note').prop( 'disabled', false )
      else
        $('#add_note').prop( 'disabled', true )

  adjust_textarea_height()
  adjust_ui();

  $( "#receivers_dialog" ).dialog({
      resizable: false,
      height: "auto",
      width: 500,
      height : 300,
      modal: true,
      autoOpen : false,
      buttons: 
      [
        {
        id : "apply",
        // Применить
        text: "\u041f\u0440\u0438\u043c\u0435\u043d\u0438\u0442\u044c",
        disabled : can_edit ? false : true,
        click : function() 
        {

          id = $( this ).data('id')
          let options =   $( "#receivers_select_to option" );
          let ids_arr = []
          let new_receivers_spans = ''

          $.each( options , function( key, item )
            {
              let val = $( item ).val()
              ids_arr.push( val )
              // cons( item )
              new_receivers_spans += "<span class='receiver_span' data-id='" + val + "'>" +  $( item ).text() + "</span>"
            });

          // cons( new_receivers_spans )

          let ids_list = "[" + ids_arr.join(',') + "]"
          
          $.post(
          '/project/service_note/ajax.update_note.php',
          {
              id  : id, 
              field  : 'receivers_res_id',
              val : ids_list,
          },
          function( data )
          {
            $('tr[data-id="' + id + '"]').find( '.receivers_div' ).html( new_receivers_spans )
            adjust_textarea_height();
            adjust_ui()
          }
        );
                $( this ).dialog( "close" );
          }
        },
        // закрыть
        {
          id : "close",
          text : "\u0417\u0430\u043a\u0440\u044b\u0442\u044c",
          click : function() 
          {
            $( this ).dialog( "close" );
          }
        }
      ],
      open : function()
      {
      },
      classes:
      {
        "ui-dialog-titlebar" : "receivers_dialog_title"
      }
    });// .dialog({ classes : { "ui-dialog-titlebar" : "user_job_dialog_title" }});



function monthpicker_click_month()
{
  let year = $('#monthpicker').monthpicker('getDate').getFullYear();
  let month = $('#monthpicker').monthpicker('getDate').getMonth();

  if( ( today_year != year || today_month  !=  ( 1 + month ) ) )
    $('#add_note').prop( 'disabled', true )
      else
        if( can_edit )
            $('#add_note').prop( 'disabled', false )

  $('#monthpicker').data('date', { 'month': month + 1 , 'year' : year });
  $('#monthpicker').val( monthNames[ month ] + ' ' + year );
  getMonthPages( year, month + 1 )
}

function adjust_ui()
{
    // $('textarea').height( $('.textarea').height() ).unbind('keyup').bind('keyup', textarea_keyup )

    $('textarea').unbind('keyup').bind('keyup', textarea_keyup )
    $('input[type="checkbox"]').unbind('change').bind('change', checkbox_change )
    $('#add_note').unbind('click').bind('click', add_note_button_click )
    $('.view_pict_img').unbind('click').bind('click', view_pict_img_click )
    $('.add_pict_img').unbind('click').bind('click', add_pict_img_click )
    $('.del_img').unbind('click').bind('click', del_img_click )
    $('.receivers').unbind('click').bind('click', receivers_click )
    $('#upload_file_input').unbind('change').bind('change', upload_file_input_change );

    $('#receivers_select_from option, #receivers_select_to option').unbind('dblclick').bind('dblclick', option_dblclick );
    $('#add_to_receivers').unbind('click').bind('click', add_to_receivers_click )
    $('#remove_from_receivers').unbind('click').bind('click', remove_from_receivers_click )

    $('#find_input').unbind('keyup').bind('keyup', find_input_keyup )
    $('#find_button').unbind('click').bind('click', find_button_click )    

}

function cons( arg )
{
  console.log( arg )
}

function textarea_keyup()
{
    let val = $( this ).val()
    let id = $( this ).closest('tr').data('id')
    update_note( id, 'description', val )
}

function update_note( id, field, val )
{
          $.post(
          '/project/service_note/ajax.update_note.php',
          {
              id  : id, 
              field  : field,
              val : val,
          },
          function( data )
          {
            // cons( data )
            adjust_ui()
          }
        );
}

function checkbox_change()
{
  let state = $( this ).prop('checked') ? 1 : 0
  let id = $( this ).closest('tr').data('id')  
  update_note( id, 'executed', state ) 
  if( state )
      $( 'tr[data-id=' + id + ']').addClass( 'executed' )
       else
        $( 'tr[data-id=' + id + ']').removeClass( 'executed' )
}

function add_note_button_click()
{
      $.post(
      '/project/service_note/ajax.add_note.php',
      {
          res_id  : res_id, 
          can_edit : can_edit
      },
      function( data )
      {
        // cons( data )

        let trs = $( '#main_div tr.data-class' ).length;

        if( trs )
        {
          let tr = $( data ).find('tr.data-class')
          let first_class = $( 'table.tbl tr.data-class' ).first().attr('class')
          
          if( first_class.indexOf('even') == -1 )
              $( tr ).addClass('even')
              else
                $( tr ).addClass('odd')


          $( 'table.tbl .first' ).after( tr )
        }
        else
        {
          $('#main_div').append( "<div class='row'><div class='col-sm-12'>" + data + "</div></div>")
        }
        $('span.found').text('\u{41D}\u{430}\u{439}\u{434}\u{435}\u{43D}\u{43E} \u{437}\u{430}\u{43F}\u{438}\u{441}\u{43E}\u{43A} : ' +  trs )
        adjust_ui()
      }
    );
}


function view_pict_img_click()
{
  var url = images_path + $( this ).data('img');
  window.open( url );

}
function add_pict_img_click()
{
    let id = $( this ).closest('tr').data('id');
    $('#upload_file_input').data('id', id ).click()
}

 function upload_file_input_change()
{
    let id = $( this ).data('id');
    let files = this.files;

    // Создадим данные формы и добавим в них данные файлов из files
    let data = new FormData();
    
    $.each( files, function( key, value )
    {
        data.append( key, value );
    });

    data.append( 'id', id );
    data.append( 'res_id', res_id );    

    // Отправляем запрос
    $.ajax({
        url: '/project/service_note/ajax.upload_file.php?uploadfiles=0',
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
              // cons( respond )
              let tr = $( 'tr[data-id="' + id + '"]')
              $( tr ).find('.add_pict_img').removeClass('add_pict_img').addClass('view_pict_img').data('img',respond).attr( "src", "uses/film.png").attr( "title", "\u{41F}\u{43E}\u{441}\u{43C}\u{43E}\u{442}\u{440}\u{435}\u{442}\u{44C} \u{434}\u{43E}\u{43A}\u{443}\u{43C}\u{435}\u{43D}\u{442}").data('img',respond); // Посмотреть документ
              $( tr ).find('.del_img').removeClass('hidden');
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


function del_img_click()
{
    let id = $( this ).closest('tr').data('id')
    $.post(
    '/project/service_note/ajax.delete_image.php',
    {
        id  : id, 
    },
    function( data )
    {
        let tr = $( 'tr[data-id="' + id + '"]')
        $( tr ).find('.view_pict_img').removeClass('view_pict_img').addClass('add_pict_img').attr( "src", "uses/addf_img.png").attr( "title", "\u{417}\u{430}\u{433}\u{440}\u{443}\u{437}\u{438}\u{442}\u{44C} \u{434}\u{43E}\u{43A}\u{443}\u{43C}\u{435}\u{43D}\u{442}"); // Загрузить документ
        $( tr ).find('.del_img').addClass('hidden');

        $( '#upload_file_input' ).val( null )
        adjust_ui();        
    }
  );
}

function receivers_click()
{
  let id = $( this ).parents('tr').data('id')
  let recievers = $( this ).parent().find('div').find('.receiver_span')

// Переместить все option в select from
  $('#receivers_select_to option').appendTo( '#receivers_select_from' )

  let recievers_arr = []

// выделить все id получателей в отдельный массив
  $.each( recievers , function( key, item )
  {
    recievers_arr.push( + $( item ).data('id') );
  });

  let res_arr = $( '#receivers_select_from option' )

// Переместить option из общего списка в список получателей
  $.each( res_arr , function( key, item )
  {
    let id = + $( item ).val()

    if( recievers_arr.indexOf( id ) != -1 )
      $( item ).appendTo( '#receivers_select_to' )
  });

  sort_all_select()
  adjust_ui()

  $( "#receivers_dialog" ).data('id', id ).dialog('open');
}

function option_dblclick()
{
  let id = $( this ).parent().attr('id')

  if( id == 'receivers_select_from' )
    $( this ).appendTo( '#receivers_select_to' )
      else
        $( this ).appendTo( '#receivers_select_from' )

    $('#receivers_select_from option, #receivers_select_to option').attr('selected', false)
    sort_all_select()
}

function add_to_receivers_click()
{
    $('#receivers_select_from option:selected').appendTo('#receivers_select_to')
    sort_all_select()    
}

function remove_from_receivers_click()
{
    $('#receivers_select_to option:selected').appendTo('#receivers_select_from')
    sort_all_select()    
}


function sort_all_select()
{
    sort_select( '#receivers_select_from' )
    sort_select( '#receivers_select_to' )
}

function sort_select ( select )
{
    var options = jQuery.makeArray( $( select ).find('option') );
    var sorted = options.sort(function(a, b) 
    {
        return (jQuery(a).text() > jQuery(b).text()) ? 1 : -1;
    });
    $( select ).append(jQuery( sorted )).attr('selectedIndex', 0);
};


function   getMonthPages( year, month )
{

          $.post(
          '/project/service_note/ajax.get_notes.php',
          {
            year : year,
            month : month,
            res_id : res_id,
            can_edit : can_edit 
          },
          function( data )
          {
              // cons( data )

              $('#main_div').html( data )
              adjust_ui()
              adjust_textarea_height()              
              $('span.found').text('\u{41D}\u{430}\u{439}\u{434}\u{435}\u{43D}\u{43E} \u{437}\u{430}\u{43F}\u{438}\u{441}\u{43E}\u{43A} : ' + $( '#main_div table' ).length )
          }
        );

}


function find_button_click()
{
  let find_text = $( '#find_input' ).val()
  
  if( !find_text.length )
  {
    let rows = $( '#main_div tr.data-class' )
    $( rows ).removeClass('hidden')
    $('span.found').text('\u{41D}\u{430}\u{439}\u{434}\u{435}\u{43D}\u{43E} \u{437}\u{430}\u{43F}\u{438}\u{441}\u{43E}\u{43A} : ' + rows.length )
  }

  let descrs = $( '#main_div textarea' )
  let found = 0

  $.each( descrs , function( key, item )
    {
      let val = $( item ).val()
      let regexp = new RegExp( find_text , "i" )
      if( val.search(regexp) == -1 )
        $( item ).closest('tr.data-class').addClass('hidden')
        else
        {
          $( item ).closest('tr.data-class').removeClass('hidden')
          found ++
        }
    });

    $('span.found').text('\u{41D}\u{430}\u{439}\u{434}\u{435}\u{43D}\u{43E} \u{437}\u{430}\u{43F}\u{438}\u{441}\u{43E}\u{43A} : ' + found )

}

function find_input_keyup()
{
    let find_text = $( this ).val()
    if( find_text.length )
      $( '#find_button').html( '\u{41D}\u{430}\u{439}\u{442}\u{438}' )
      else
        $( '#find_button').html( '\u{41F}\u{43E}\u{43A}\u{430}\u{437}\u{430}\u{442}\u{44C} \u{432}\u{441}\u{435}' )
}

function adjust_textarea_height()
{
  let textareas = $('textarea')
  $.each( textareas , function( key, item )
            {
              let td = $( item ).parent()
              $( item ).height( $( td ).height() )            
            });
}

});