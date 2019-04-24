const  PLAN_FACT_STATE_CHANGE = 2;
const  PLAN_FACT_DATE_CHANGE = 4;
const  PLAN_FACT_CONFIRMATION_REQUEST = 16;

var steps = []

steps[1] = "\u041A\u0414" ;
steps[2] = "\u041D\u043E\u0440\u043C\u044B \u0440\u0430\u0441\u0445\u043E\u0434\u0430" ; 
steps[3] = "\u041C\u0422\u041A" ; 

steps[4] = "\u041F\u0440\u043E\u0440\u0430\u0431\u043E\u0442\u043A\u0430" ; 
steps[7] = "\u041F\u043E\u0441\u0442\u0430\u0432\u043A\u0430" ; 

steps[12] = "\u0414\u0430\u0442\u0430 \u043D\u0430\u0447. \u043F\u0440\u043E\u0438\u0437\u0432\u043E\u0434\u0441\u0442\u0432\u0430" ; 
steps[8] = "\u0414\u0430\u0442\u0430 \u043E\u043A\u043E\u043D\u0447. \u043F\u0440\u043E\u0438\u0437\u0432\u043E\u0434\u0441\u0442\u0432\u0430" ; 
steps[13] = "\u0418\u043D\u0441\u0442\u0440\u0443\u043C\u0435\u043D\u0442 \u0438 \u043E\u0441\u0442\u043D\u0430\u0441\u0442\u043A\u0430" ; 

steps[9] = "\u041F\u0440\u0435\u0434\u043E\u043F\u043B\u0430\u0442\u0430" ; 
steps[10] = "\u041E\u043A\u043E\u043D\u0447.\u0440\u0430\u0441\u0447\u0435\u0442" ; 
steps[11] = "\u041F\u043E\u0441\u0442\u0430\u0432\u043A\u0430" ; 

// Actions after full page loading
$( function()
{
      // Замена старой верстки
      $('#vpdiv').append( $('#main_div') ).append( $("#loadImg") );
      $('.A4W').remove();
      $('table.view').hide();

      adjustDropDownSelect();

      adjust_calendars( '#from_date' );
      adjust_calendars( '#to_date' );

      FixAction('order_table',2,0,100,100);
      adjustLoadingAnimation();
      adjust_ui();

      $.extend($.expr[":"],
      {
        "containsNC": function(elem, i, match, array)
        {
          return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
        }
      });

      if( input_id && rec_id )
      {

        $.post(
          "project/plan_fact/ajax.makeNotificationAck.php",
          {
            id   : rec_id ,
          },
          function( data )
          {
            $('#new_notify_dialog').dialog('close');
          }
          );

//      $('tr[data-id=' + input_id + ']').eq(0).get(0).scrollIntoView(true);
$('#table_div > div > div').eq(2).animate({scrollTop:  $('tr[data-id=' + input_id + ']').offset().top - 275 });
}

check_empty_cells();

let penalties_list = getParams('penalties_list');
if( penalties_list !== undefined )
{
  let penalties_arr = penalties_list.split(',');
  penalties_arr.forEach(function( item, i )
  {
    let arr = item.split(':');
    let row_id = arr[0] ;
    let pd = arr[1].toLowerCase();

    $('tr[data-id=' + row_id + '][data-group_member="0"]').find('td[data-str-id=' + pd + ']').find('div:first').addClass('penalty') ;
  });

//        cons( penalties_arr );
}

let key = 1 * localStorage.getItem( 'filter_bar' )

if( key )
{
  $('#form_div *').hide()
  $('.show_img').removeClass('hidden').show()
}
else
{
  $('#form_div *').show()
  $('.mutliSelect ul').hide()
}

$('#form_div').removeClass('hidden')  

});

function getParams( par )
{
  let search = window.location.search.substr(1);
  let keys = {};

  search.split('&').forEach(function(item)
  {
    item = item.split('=');
    keys[item[0]] = item[1];
  });

  if( par == undefined )
    return keys ;
  else
    return keys[ par ] ;
}

function ord_link_click( event )
{
  event.stopPropagation();

  let id = $( this ).data('id');
  let url ='/index.php?do=show&formid=39&id=' + id ;
  window.open( url , '_blank');
}

function reset_filter_button_click()
{
  $('#from_date').val('');
  $('#to_date').val('');

  $('.radio_div').find('input').prop('checked', false ) ;
  $('.ord_type_div').find('input').prop('checked', false );

  resetDropdown( "#dropdownStage" );
  resetDropdown( "#dropdownStatus" );

  getFilteredData();
}

function reset_date_filter_button_click()
{
  $('#from_date').val('');
  $('#to_date').val('');
  $('.radio_div').find('input').prop('checked', false ) ;
  getFilteredData();
}

function renumberRow( found )
{
  let total = found.length;
  let num = 1 ;

  $.each( found , function( key, value )
  {
    let id = $( value ).attr('data-id');
    $( 'tr[data-id="' + id + '"]' ).removeClass('hidden').find('span.num').text( num + '/' + total );
    num ++;
  });

}

function find_keypress()
{
  if( $(this).val().length < 2 )
    return ;

  $('#order_table tr').addClass('hidden');
  $('#order_table tr.empty_row').removeClass('hidden').css('height','96px');

  let found = $('td.ord_head:containsNC("' + $( '#find' ).val() + '")').parent('tr').removeClass('hidden') ;
  renumberRow( found );
}

function adjust_ui()
{
  $('a.ch_date_link').unbind('click').bind( 'click', showChangeDateDialog );
  $('a.count_list_link').unbind('click').bind( 'click', showChangeListDialog );
  $('input.ch_state').unbind('click').bind( 'click', showChangeStatusDialog );
  $('a.ord_link').unbind('click').bind( 'click', ord_link_click );

  $('#print_filter_button').unbind('click').bind( 'click', print_filter_button_click );
  $('#reset_filter_button').unbind('click').bind( 'click', reset_filter_button_click );
  $('#reset_date_filter_button').unbind('click').bind( 'click', reset_date_filter_button_click );
  $('#change_date_dialog_textarea').unbind('keyup').bind( 'keyup', change_date_dialog_textarea_keypress );

  $("input[name='radio']").unbind('click').bind( 'click', getFilteredData );
  $("input[name='ord_type']").unbind('click').bind( 'click', getFilteredData );

  $('.pressable').unbind('click').bind( 'click', pressableTdClick );
  $('.sort_arrow').unbind('click').bind( 'click', sortArrowClick );
  $( ".confirm_checkbox" ).unbind('click').bind( 'click', confirmCheckboxClick );
  $('td.ord_head select').unbind('change').bind( 'change', ordHeadSelectClick );

  $('#change_date_dialog_select').unbind('change').bind( 'change', check_to_add_button_enable );

  $('#find').unbind('keyup').bind( 'keyup', find_keypress );

  $('.pressable').prop('data-state', 0 );
  $( '.hiddenly' ).hide();
  $('.production').attr( 'colspan',3 );
  $('.ord_head').attr( 'colspan',16 );
  $('.empty_row').attr( 'colspan',16 );
  $('.arr_div').html( '&#9668;&#9658;' );

  $( "input:disabled" ).css('cursor','default');
  $( "a.disabled" ).css('cursor','default');

  $('.task_carries').unbind('click').bind( 'click', taskCarriesImgClick );

  $('.hide_img').unbind('click').bind( 'click', hideImgClick );
  $('.show_img').unbind('click').bind( 'click', showImgClick );    
}

function confirmCheckboxClick()
{
  let id = $( this ).data('id');
  let field = $( this ).data('field');
  let state =  $( this ).prop('checked');

  let can_change = $('tr[data-id="' + id + '"][data-group_member]').find('td[data-str-id="'+ field + '"]').find('div').find('input').data('conf', state == true ? 1 : 0 ).data('can_change');
  let div = $('tr[data-id="' + id + '"][data-group_member]').find('td[data-str-id="'+ field.replace("_conf", "") + '"]').find("div");

  // Отправляем запрос
  $.post(
    "project/plan_fact/ajax.setStatusConfirmation.php",
    {
      id   : id ,
      field : field + '_conf',
      state : state
    },
    function( data )
    {
      $( 'tr[data-id="' + id + '"][data-group_member]').find('td[data-str-id="' + field.replace("_conf","") + '"]').find('input.ch_state').attr( 'data-conf', data );

      if (state)
      {
       div.removeClass("cell_state_not_conf");
       div.addClass("cell_state_good");
     }
     else
     {
       div.removeClass("cell_state_good");
       div.addClass("cell_state_not_conf");
     }
   }

   );
}

function ordHeadSelectClick()
{
  let status = $( this ).val();
  let id = $( this ).parents('tr').data('id' );


    // Отправляем запрос
    $.post(
      "project/plan_fact/ajax.setStatus.php",
      {
        id   : id ,
        status : status
      },
      function( data )
      {
                        if( status == 5 ) // if order sended to warehouse
                        {
                          $('tr[data-id=' + id + ']').remove();
                          renumberRow( $('#order_table tr[data-group_member]') );
                        }

                      }
                      );
  }

  function pressableTdClick()
  {
    let state = $( this ).prop('data-state' );

    if( state == undefined || state == 1 )
    {
      $( this ).prop('data-state', 0 );
      $( '.hiddenly' ).hide();
      $('.production').attr( 'colspan',3 );
      $('.ord_head').attr( 'colspan',16 );
      $('.empty_row').attr( 'colspan',16 );
      $('.arr_div').html( '&#9668;&#9658;' );
    }

    if( state == 0 )
    {
      $( this ).prop('data-state', 1 );
      $( '.hiddenly' ).show();
      $('.production').attr( 'colspan',6 );
      $('.ord_head').attr( 'colspan', 19 );
      $('.empty_row').attr( 'colspan',19 );
      $('.arr_div').html( '&#9658;&#9668;' );
    }
  }

  function change_date_dialog_textarea_keypress()
  {
    // if ( $( this ).val().length )
    //   check_to_add_button_enable();
  }

  function showChangeDateDialog()
  {

    if( $( this ).hasClass('disabled') )
      return ;

    let el = this ;
    let field = $( el ).parents('td').data('str-id');
    let id = $( el ).parents('tr').data('id');

    $('#change_date_dialog_textarea').val('');
    $('#change_date_current_date_span').text('');

    $( '#change_date_dialog_calendar' ).datepicker(
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
            yearRange: '2015:2020',

            beforeShow : function(input, inst) {},

            onSelect: function ()
            {

              $('#change_date_current_date_span').text($(this).val());

              if ( $( this ).val().length  )
                check_to_add_button_enable();
            }
          });

    $( '#change_date_dialog_calendar' ).datepicker( "setDate", $( el ).text() );

    $('#change_date_dialog').removeClass('hidden');

    $( "#change_date_dialog" ).dialog({
      resizable: false,
      height: 450,
      width: 240,
      modal: true,
      closeOnEscape: true,

      position: { my: "left top", at: "left bottom", of: this },

      create : function()
      {
            $('div.ui-widget-header').css('background','#FF9933'); // Цвет заголовка диалога
          },

          open : function()
          {

            $.post(
              "project/plan_fact/ajax.getCauseSelect.php",
              {
                field   : field
              },
              function( data )
              {
                $( '#change_date_dialog_select' ).html( data );
              }
              );
          },

          buttons:
          [
          {
           id : "change_date_dialog_add_button",
            // 'Добавить' в unicode url : https://r12a.github.io/apps/conversion/
            text: "\u0414\u043E\u0431\u0430\u0432\u0438\u0442\u044C",
            click : function ()
            {
              let date = $('#change_date_current_date_span').text();
              let comment = $('#change_date_dialog_textarea').val();
              let cause_val = 1 * $( '#change_date_dialog_select option:selected' ).val() ;
              let cause_text = $( '#change_date_dialog_select option:selected' ).text() ;

//                alert( id + " : " +  field + " : " + date + " : " + user_id + " : " + cause_val + " : " + comment );

                // Отправляем запрос
                $.post(
                  "project/plan_fact/ajax.setDate.php",
                  {
                    id   : id ,
                    field : field,
                    date : date,
                    user_id : user_id,
                    cause : cause_val,
                    comment : comment
                  },
                  function( data )
                  {
                    stopLoadingAnimation();
                    $('tr[data-id=' + id + ']').find('td[data-str-id=' + field + ']').find('a.ch_date_link').text( date );
                    let ch_count = Number( $('tr[data-id=' + id + ']').find('td[data-str-id=' + field + ']').find('a.count_list_link').text().replace(/\s/g, '').slice(1, -1)) + 1;
                    $('tr[data-id=' + id + ']').find('td[data-str-id=' + field + ']').find('a.count_list_link').text( '[' + ch_count + ']' );

//                        adjustCellColor( el, data, date );

                        // Подготовка
                        // PD1 - КД
                        // PD2 - Нормы расхода
                        // PD3 - МТК

                        // Комплектация
                        // PD4 - Проработка
                        // PD7 - Поставка

                        // Производство
                        // PD12 - Дата нач.
                        // PD8  - Дата оконч.
                        // PD13 - Инструмент и остнастка

                        // Коммерция
                        // PD9  - Предоплата
                        // PD10 - Оконч.расчет
                        // PD11 - Поставка

                        let who = "\u042D\u0442\u0430\u043F \u0438\u0437\u043C\u0435\u043D\u0435\u043D\u0438\u044F : ";
                        switch( field )
                        {
                          case 'pd1' : who += steps[1] ; break ;
                          case 'pd2' : who += steps[2] ; break ;
                          case 'pd3' : who += steps[3] ; break ;
                          case 'pd13' : who += steps[13] ; break ;

                          case 'pd4' : who += steps[4] ; break ;
                          case 'pd7' : who += steps[7] ; break ;

                          case 'pd12' : who += steps[12] ; break ;
                          case 'pd8'  : who += steps[8] ; break ;

                          case 'pd9'  : who += steps[9] ; break ;
                          case 'pd10' : who += steps[10] ; break ;
                          case 'pd11' : who += steps[11] ; break ;
                        }

                        // why = 4 - изменение срока
                        make_notification( id, 0, PLAN_FACT_DATE_CHANGE, who + '. \u041F\u0440\u0438\u0447\u0438\u043D\u0430 \u0438\u0437\u043C\u0435\u043D\u0435\u043D\u0438\u044F : ' + cause_text + ". " + comment, field );
                      }
                      );

                $(this).dialog("close");
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
        });

$('#change_date_dialog_add_button').button('disable');
}

function showChangeStatusDialog()
{
  let field = $( this ).parents('td').data('str-id');
  let can_change = $( this ).data('can_change');

  let need_conf = 0 ;
  let confirmed = 0 ;

  let conf = $( this ).attr('data-conf');
  let groups = $( this ).parents('tr[data-group_member]').data('group_member');

  let prep  = ( groups & 16 ) ;
  let equip = ( groups & 8 )  ;
  let coop  = ( groups & 4 )  ;
  let prod  = ( groups & 2 )  ;
  let comm  = ( groups & 1 )  ;
  let id = $( this ).parents('tr').data('id');
  let el = this ;
  let initial_state =  $( el ).prop('checked');

  if( initial_state )
    $( el ).prop('checked','');
  else
    $( el ).prop('checked', 'checked' );

  $('div.ui-widget-header').css('background','#FF9933');
  $('#status_change_dialog').removeClass('hidden');

  $( "#status_change_dialog" ).dialog({
    resizable: false,
    height: 'auto',
    width: 400,
    modal: true,
    closeOnEscape: true,

    position: { my: "left top", at: "left bottom", of: this },
        create : function() { $('div.ui-widget-header').css('background','#FF9933'); }, // Цвет заголовка диалога
        buttons: [
        {
          id : 'status_change_dialog_ok_button',
          disabled : true,
            // 'Изменить' в unicode url : https://r12a.github.io/apps/conversion/
            text : "\u0418\u0437\u043C\u0435\u043D\u0438\u0442\u044C",
            click : function()
            {
              if( initial_state )
                $( el ).prop('checked', 'checked');
              else
                $( el ).prop('checked', '' );

                let checkbox = $( '#status_change_dialog .confirm_checkbox_div:not(.hidden) .confirm_checkbox')
                let dir = $( checkbox ).data('dir');
                make_notification( id, dir, PLAN_FACT_CONFIRMATION_REQUEST, "\u{437}\u{430}\u{43F}\u{440}\u{43E}\u{441} \u{43D}\u{430} \u{43F}\u{43E}\u{434}\u{442}\u{432}\u{435}\u{440}\u{436}\u{434}\u{435}\u{43D}\u{438}\u{435}", field )

                // Отправляем запрос
                $.post(
                  "project/plan_fact/ajax.setState.php",
                  {
                    id   : id ,
                    field : field,
                    state : initial_state,
                    user_id : user_id
                  },
                  function( data )
                  {
                    let span = $( '#data-stage_' + id );
                    let stage = '';
                    let new_stage = Number( data );

                    switch( new_stage )
                    {
                      case 0  : stage = '\u0417\u0430\u043F\u0443\u0441\u043A'; break ;
                      case 10 : stage = '\u041F\u043E\u0434\u0433\u043E\u0442\u043E\u0432\u043A\u0430'; break ;
                      case 20 : stage = '\u0417\u0430\u043A\u0443\u043F\u043A\u0430'; break ;
                      case 30 : stage = '\u0412 \u043F\u0440\u043E\u0438\u0437\u0432\u043E\u0434\u0441\u0442\u0432\u0435'; break ;

                      case 40 : stage = '\u0413\u043E\u0442\u043E\u0432 \u043A \u043E\u0442\u0433\u0440\u0443\u0437\u043A\u0435'; break ;
                      case 41 : stage = '\u041E\u0442\u0433\u0440\u0443\u0436\u0435\u043D \u0431\u0435\u0437 \u0440\u0430\u0441\u0447\u0435\u0442\u0430'; break ;

                      case 50 : stage = '\u0412\u044B\u043F\u043E\u043B\u043D\u0435\u043D'; break ;
                      default : stage = '\u041D\u0435\u0434\u043E\u043F\u0443\u0441\u0442\u0438\u043C\u043E stage id : ' + new_stage; break ;
                    }

                    let old_stage = $( span ).attr( 'data-stage');
                    $( span ).text( stage ).attr( 'data-stage', new_stage );

// Notification

if( old_stage != new_stage)
{
//                            cons( 'Notification. Zak id = ' + id + " new stage_id : " + new_stage + " stage : " + stage );
                              // why = 2 - изменение этапа
                              make_notification( id, new_stage, PLAN_FACT_STATE_CHANGE, "\u0418\u0437\u043C\u0435\u043D\u0435\u043D\u0438\u0435 \u044D\u0442\u0430\u043F\u0430. \u041D\u043E\u0432\u044B\u0439 \u044D\u0442\u0430\u043F : " + stage, field );
                            }


                            stopLoadingAnimation();
                            adjustCellColor( el, initial_state, 0 );
                          }
                          );

                $( this ).dialog( "close" );
              }
            },
            // {
            //   id : 'status_change_dialog_send_request_button',
            //   disabled : true,
            //   // 'Послать запрос' в unicode
            //   text : "\u{41F}\u{43E}\u{441}\u{43B}\u{430}\u{442}\u{44C} \u{437}\u{430}\u{43F}\u{440}\u{43E}\u{441}",

            //   click : function()
            //   {
            //     let checkbox = $( '#status_change_dialog .confirm_checkbox_div:not(.hidden) .confirm_checkbox')
            //     let dir = $( checkbox ).data('dir');
            //     let field = $( checkbox ).data('field');
            //     // console.log( id + ' : ' + user_id + ' : ' + list )

            //     make_notification( id, dir, 16, "\u{437}\u{430}\u{43F}\u{440}\u{43E}\u{441} \u{43D}\u{430} \u{43F}\u{43E}\u{434}\u{442}\u{432}\u{435}\u{440}\u{436}\u{434}\u{435}\u{43D}\u{438}\u{435}", field )
            //     $( this ).dialog( "close" );
            //   }
            // },
            {
              // 'Отмена' в unicode
              text : "\u041E\u0442\u043C\u0435\u043D\u0430",
              click : function()
              {
                $( this ).dialog( "close" );
              }
            }
            ]
          });

  $('#confirm_head_span_div').addClass('hidden');
  $('div.confirm_checkbox_div').addClass('hidden');

  let disabled = true ;
  let checked = false ;

  if( conf == '1' )
    checked = true ;

// Поля Подготовка / КД и МТК
// Поле Комплектация / Поставка
// Поле Кооперация / Поставка
// Согласование с производством

if( 
    field == 'pd1' || 
    // field == 'pd2'|| 
    field == 'pd3'|| 
    field == 'pd13' || 
//    field == 'pd7' || 
    field == 'pd_coop2' )
{

 // if( prod || user_id == 5  || user_id == 1 )
 //  disabled = false ;

     let user_arr = $('#confirm_production_checkbox').data('list').toString().split(",")
     
     if( user_arr.indexOf( user_id.toString()) != -1 )
      disabled = false;

$('#confirm_head_span_div').removeClass('hidden');
$('#confirm_production_checkbox').data({ 'id' : id, 'field' : field } ).attr( { 'data-id': id, 'data-field' : field }).prop(
{
  'checked' : checked,
  'disabled' : disabled
}).parent('div').removeClass('hidden');

need_conf = 1 ;
confirmed = checked;

}


// Поле Подготовка / Нормы расхода.
// Согласование с ОМТС
if( field == 'pd2' )
{

     let user_arr = $('#confirm_equipment_checkbox').data('list').toString().split(",")

     if( user_arr.indexOf( user_id.toString()) != -1 )
      disabled = false;

$('#confirm_head_span_div').removeClass('hidden');
$('#confirm_equipment_checkbox').data( { 'id' : id, 'field' : field } ).attr( { 'data-id': id, 'data-field' : field }).prop(
{
  'checked' : checked,
  'disabled' : disabled
}).parent('div').removeClass('hidden');

need_conf = 1 ;
confirmed = checked;

}

// Поле Производство / Окончание.
// Согласование с КО
if( field == 'pd8' )
{
     let user_arr = $('#confirm_commertion_checkbox').data('list').toString().split(",")
     if( user_arr.indexOf( user_id.toString()) != -1 )
      disabled = false;

    $('#confirm_head_span_div').removeClass('hidden');
    $('#confirm_commertion_checkbox').data( { 'id' : id, 'field' : field } ).attr( { 'data-id': id, 'data-field' : field }).prop(
    {
      'checked' : checked,
      'disabled' : disabled
    }).parent('div').removeClass('hidden');

    need_conf = 1 ;
    confirmed = checked;

  }

    if( need_conf )
      $('#status_change_dialog_send_request_button').show(); 
        else
          $('#status_change_dialog_send_request_button').hide(); 

  if ( can_change )
  {
    // if( need_conf )
    // {
    //   if( confirmed )
    //   {
    //     $('#status_change_dialog_ok_button').button('enable');
    //     $('#status_change_dialog_send_request_button').button('disable');
    //   } // if( confirmed )
    //   else
    //   {
    //     $('#status_change_dialog_ok_button').button('disable');
    //     $('#status_change_dialog_send_request_button').button('enable');
    //   } // else if( confirmed )
    // } // if( need_conf )
    // else
    {
      $('#status_change_dialog_ok_button').button('enable');          
      $('#status_change_dialog_send_request_button').hide(); 
    } // else if( need_conf )
  } // if ( can_change )
  else
  {
    $('#status_change_dialog_ok_button').button('disable');
    $('#status_change_dialog_send_request_button').button('disable');
    
    // Если требуется подтверждение, но пункт уже закрыт, запретить изменение состояния чекбокса "Подтверждение"
    if( confirmed && ! initial_state )
      $('.confirm_checkbox').prop('disabled', true);
  } // else if ( can_change )

// cons( ! initial_state + ' : ' + can_change + ' : ' + need_conf + ' : ' + confirmed )

} // function showChangeStatusDialog()


function showChangeListDialog()
{
  let field = $( this ).parents('td').data('str-id');
  let id = $( this ).parents('tr').data('id');
    let cnt = $( this ).text().replace(/\D/g,''); // delete nondigit symbols

    if( cnt == 0 )
      return ;

    if( cnt == 1 )
      cnt ++ ;

    let height = cnt * 16 + 80;

    if( cnt >= 16 )
      height = 400;

    $('#change_list_table_div').empty().height( height - 32 );

    $( "#change_list_dialog" ).dialog({
      resizable: false,
      height: height + 105,
      width: 1000,
      modal: true,
      closeOnEscape: true,

      position: { my: "left top", at: "left bottom", of: this },


      open : function()
      {

        $.post(
          "project/plan_fact/ajax.getChangeList.php",
          {
            id   : id ,
            field : field
          },
          function( data )
          {
            $('#change_list_table_div').append( data ).find('tr:odd').addClass('odd_row');
            $('#change_list_dialog').removeClass('hidden');
            check_empty_cells();
          }
          );
      },
      create : function()
      { $('div.ui-widget-header').css('background','#FF9933');
            }, // Цвет заголовка диалога
            buttons: {
            // 'Закрыть' в unicode url : https://r12a.github.io/apps/conversion/
            "\u0417\u0430\u043A\u0440\u044B\u0442\u044C": function()
            {
              $( this ).dialog( "close" );
            }
          }
        });
  }

  function adjustLoadingAnimation()
  {
    let imgObj = $("#loadImg").hide();
    let centerY = $(window).height() / 2  - imgObj.height()/2 ;
    let centerX = $(window).width()  / 2  - imgObj.width()/2;

    // установка координат изображения:
    imgObj.offset( { top: centerY, left: centerX } );
  }

function startLoadingAnimation() // - функция запуска анимации
{
  let imgObj = $("#loadImg").show();
}

function stopLoadingAnimation() // - функция останавливающая анимацию
{
  $("#loadImg").hide();
}

function adjustCellColor( el, state , chdate )
{
  let now = new Date();
  let day = now.getDate() < 10 ? "0" + now.getDate() : now.getDate() ;
  let month = now.getMonth() + 1 < 10 ? "0" + ( now.getMonth() + 1 )  : now.getMonth() + 1 ;
  let year = now.getFullYear()

  let field = $( el ).parents('td[data-str-id]');

  let count_list_link_val = Number( $( field ).find( 'a.count_list_link' ).text().replace(/\D/g,'') ) + 1 ;

  $( field ).find( 'a.ch_date_link' ).text( day + "." + month + "." + year );
  $( field ).find( 'a.count_list_link' ).text( "[" + count_list_link_val + "]");

  $( field ).find( 'div' ).removeClass('cell_state_good cell_state_over');

  $( field ).find( 'div' ).removeClass('cell_state_good cell_state_over');

  if( state )
    $( field ).find( 'div' ).addClass('cell_state_good');
  else
   $( field ).find( 'div' ).addClass('cell_state_over');

 if (
  $(field).find('div').find('input').data('conf') == 0 && 
   field.data("str-id") != 'pd10' &&
   field.data("str-id") != 'pd9' &&
   // field.data("str-id") != 'pd13' &&
   field.data("str-id") != 'pd4' &&
   field.data("str-id") != 'pd12' &&
   field.data("str-id") != 'pd11') {

  $( field ).find( 'div' ).removeClass('cell_state_good cell_state_over');
$( field ).find( 'div' ).addClass('cell_state_not_conf');
}

if( chdate )
{
  let date = new Date( chdate.substr(3,2) + "." + chdate.substr(0,2) + "." + chdate.substr(6,4) );
  $( field ).find( 'div' ).removeClass('cell_state_good cell_state_over');
  $( field ).find( 'a.ch_date_link' ).text( chdate );

  if( date < now )
    $( field ).find( 'div' ).addClass('cell_state_over');

}
}

function make_notification( id, new_stage, why, description, field )
{
  $.post(
    "project/plan_fact/ajax.makeNotification.php",
    {
      id   : id ,
      stage : new_stage,
      why : why,
      description : description,
      user_id : user_id,
      field : field
    },
    function( data )
    {
      // console.log( data )
    }
    );
}

function check_empty_cells()
{
  let empy_cells = $('.cell_state_over');
  let num = 0 ;

  $.each( empy_cells , function( key, value )
  {
    let a = $( value ).find('a.ch_date_link');
    if( $( a ).text().length == 0 )
     $( value ).removeClass('cell_state_over');
 });
}

function check_to_add_button_enable()
{
//      let text_area = $( '#change_date_dialog_textarea').val().length ;
let new_date = $( '#change_date_current_date_span').text().length;
let cause = 1 * $( '#change_date_dialog_select option:selected' ).val() ;

if ( new_date && cause )
  $('#change_date_dialog_add_button').button('enable');
}

function taskCarriesImgClick()
{
  let id = $( this ).parent().parent().data('id');
  let url ='/index.php?do=show&formid=269&p0=' + id ;
  window.open( url , '_blank');
}

function hideImgClick()
{
  $('#form_div *').hide()
  localStorage.removeItem( 'filter_bar' )    
  localStorage.setItem( 'filter_bar', '1' )
  $('.show_img').show().removeClass('hidden')
}

function showImgClick()
{
  $( this ).addClass('hidden')
  $('#form_div *').show()
  localStorage.removeItem( 'filter_bar' )    
  localStorage.setItem( 'filter_bar', '0' )
}

function print_filter_button_click()
{
  let trs = $('tr[data-group_member]');
  let type = $('input[name=\"radio\"]:checked').val()

  if( type == undefined )
    type = '';

  let from = $('#from_date').val();
  let to = $('#to_date').val();

  let arr = [];

  $.each( trs , function( key, item )
  {
    let id = $( item ).data('id');
    arr.push( id )
  });

  let list = arr.join(',')
  url = "print.php?do=show&formid=279&p0=" + list + "&p1=" + type + "&p2=" + from + "&p3=" + to;
  url = "print.php?do=show&formid=280&p0=" + list + "&p1=" + type + "&p2=" + from + "&p3=" + to;  
  window.open( url, "_blank" );
}

function cons( arg )
{
  console.log( arg )
}

function getFilteredData()
{

// dates data collect

let from_date = $('#from_date').val() ;
let to_date = $('#to_date').val() ;

// **********************************************************************
// stage and status data collect

if( $( this ).attr('name') == 'radio' && from_date.length == 0 && to_date.length == 0 )
  return;

let stageCheckedList = $( "#dropdownStage" ).find("input:checked");
let stageArr =[];
let statusCheckedList = $( "#dropdownStatus" ).find("input:checked");
let statusArr =[];

$.each( stageCheckedList , function( key, value )
{
  stageArr[ stageArr.length ] = $( value ).data('id');
});

$.each( statusCheckedList , function( key, value )
{
  statusArr[ statusArr.length ] = $( value ).data('id');
});

// **********************************************************************

// radiosel data collect


let radio_sel = $('.radio_div').find('input:checked').val() ;
let ord_type_sel = $('.ord_type_div').find('input:checked').val() ;
let stage_sel = $('.radio_div').find('input:checked').val() ;

// **********************************************************************

startLoadingAnimation();
$.post(
  "project/plan_fact/ajax.getFilteredData.php",
  {
    stage     : stageArr  ,
    status    : statusArr ,
    radio     : radio_sel ,
    ord_type  : ord_type_sel,
    from_date : from_date ,
    to_date   : to_date,
    user_id   : user_id
  },

  function( data )
  {
    stopLoadingAnimation();
    $('#table_div').empty();
    $('#table_div').append( data );
    FixAction('order_table',2,0,100,100);
    adjust_ui();
    check_empty_cells();
  }
  );
} // getFilteredData