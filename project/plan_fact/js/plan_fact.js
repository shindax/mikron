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

  var penalties_list = getParams('penalties_list');
  if( penalties_list !== undefined )
    {
        var penalties_arr = penalties_list.split(',');
        penalties_arr.forEach(function(item, i, arr)
        {
            var arr = item.split(':');
            var row_id = arr[0] ;
            var pd = arr[1].toLowerCase();

            $('tr[data-id=' + row_id + '][data-group_member="0"]').find('td[data-str-id=' + pd + ']').find('div:first').addClass('penalty') ;
        });

//        console.log( penalties_arr );
    }

  var key = 1 * localStorage.getItem( 'filter_bar' )

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
var search = window.location.search.substr(1);
 var keys = {};

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

  var id = $( this ).data('id');
  var url ='/index.php?do=show&formid=39&id=' + id ;
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
  var total = found.length;
  var num = 1 ;

    $.each( found , function( key, value )
    {
      var id = $( value ).attr('data-id');
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

   var found = $('td.ord_head:containsNC("' + $( '#find' ).val() + '")').parent('tr').removeClass('hidden') ;
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
    $('.production').attr( 'colspan',4 );
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
  var id = $( this ).data('id');
  var field = $( this ).data('field');
  var state =  $( this ).prop('checked');

  var can_change = $('tr[data-id="' + id + '"][data-group_member]').find('td[data-str-id="'+ field + '"]').find('div').find('input').data('conf', state == true ? 1 : 0 ).data('can_change');
	var div = $('tr[data-id="' + id + '"][data-group_member]').find('td[data-str-id="'+ field.replace("_conf", "") + '"]').find("div");

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

                      if ( can_change &&  state )
                        $('#status_change_dialog_ok_button').button('enable');
                          else
                            $('#status_change_dialog_ok_button').button('disable');
                    }

                );
}

function ordHeadSelectClick()
{
    var status = $( this ).val();
    var id = $( this ).parents('tr').data('id' );


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
  var state = $( this ).prop('data-state' );

  if( state == undefined || state == 1 )
  {
    $( this ).prop('data-state', 0 );
    $( '.hiddenly' ).hide();
    $('.production').attr( 'colspan',4 );
    $('.ord_head').attr( 'colspan',16 );
    $('.empty_row').attr( 'colspan',16 );
    $('.arr_div').html( '&#9668;&#9658;' );
  }

  if( state == 0 )
  {
    $( this ).prop('data-state', 1 );
    $( '.hiddenly' ).show();
    $('.production').attr( 'colspan',7 );
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

    var el = this ;
    var field = $( el ).parents('td').data('str-id');
    var id = $( el ).parents('tr').data('id');

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
                var date = $('#change_date_current_date_span').text();
                var comment = $('#change_date_dialog_textarea').val();
                var cause_val = 1 * $( '#change_date_dialog_select option:selected' ).val() ;
                var cause_text = $( '#change_date_dialog_select option:selected' ).text() ;

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
                        var ch_count = Number( $('tr[data-id=' + id + ']').find('td[data-str-id=' + field + ']').find('a.count_list_link').text().replace(/\s/g, '').slice(1, -1)) + 1;
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

                        var who = "\u042D\u0442\u0430\u043F \u0438\u0437\u043C\u0435\u043D\u0435\u043D\u0438\u044F : ";
                        switch( field )
                        {
                            case 'pd1' : who += "\u041A\u0414" ; break ;
                            case 'pd2' : who += "\u041D\u043E\u0440\u043C\u044B \u0440\u0430\u0441\u0445\u043E\u0434\u0430" ; break ;
                            case 'pd3' : who += "\u041C\u0422\u041A" ; break ;

                            case 'pd4' : who += "\u041F\u0440\u043E\u0440\u0430\u0431\u043E\u0442\u043A\u0430" ; break ;
                            case 'pd7' : who += "\u041F\u043E\u0441\u0442\u0430\u0432\u043A\u0430" ; break ;

                            case 'pd12' : who += "\u0414\u0430\u0442\u0430 \u043D\u0430\u0447. \u043F\u0440\u043E\u0438\u0437\u0432\u043E\u0434\u0441\u0442\u0432\u0430" ; break ;
                            case 'pd8' : who += "\u0414\u0430\u0442\u0430 \u043E\u043A\u043E\u043D\u0447. \u043F\u0440\u043E\u0438\u0437\u0432\u043E\u0434\u0441\u0442\u0432\u0430" ; break ;
                            case 'pd13' : who += "\u0418\u043D\u0441\u0442\u0440\u0443\u043C\u0435\u043D\u0442 \u0438 \u043E\u0441\u0442\u043D\u0430\u0441\u0442\u043A\u0430" ; break ;

                            case 'pd9' : who += "\u041F\u0440\u0435\u0434\u043E\u043F\u043B\u0430\u0442\u0430" ; break ;
                            case 'pd10' : who += "\u041E\u043A\u043E\u043D\u0447.\u0440\u0430\u0441\u0447\u0435\u0442" ; break ;
                            case 'pd11' : who += "\u041F\u043E\u0441\u0442\u0430\u0432\u043A\u0430" ; break ;
                       }

                        // why = 4 - изменение срока
                        make_notification( id, 0, 4, who + '. \u041F\u0440\u0438\u0447\u0438\u043D\u0430 \u0438\u0437\u043C\u0435\u043D\u0435\u043D\u0438\u044F : ' + cause_text + ". " + comment, field );
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
    var field = $( this ).parents('td').data('str-id');
    var can_change = $( this ).data('can_change');

    var conf = $( this ).attr('data-conf');

    var groups = $( this ).parents('tr[data-group_member]').data('group_member');

    var prep  = ( groups & 16 ) ;
    var equip = ( groups & 8 )  ;
    var coop  = ( groups & 4 )  ;
    var prod  = ( groups & 2 )  ;
    var comm  = ( groups & 1 )  ;

    var id = $( this ).parents('tr').data('id');

    var el = this ;

    var initial_state =  $( el ).prop('checked');

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
            // 'Изменить' в unicode url : https://r12a.github.io/apps/conversion/
            text : "\u0418\u0437\u043C\u0435\u043D\u0438\u0442\u044C",
            click : function()
            {
                if( initial_state )
                    $( el ).prop('checked', 'checked');
                else
                    $( el ).prop('checked', '' );

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
                        var span = $( '#data-stage_' + id );
                        var stage = '';
                        var new_stage = Number( data );

                        switch( new_stage )
                        {
                          case 0  : stage = '\u0417\u0430\u043F\u0443\u0441\u043A'; break ;
                          case 10 : stage = '\u041F\u043E\u0434\u0433\u043E\u0442\u043E\u0432\u043A\u0430'; break ;
                          case 20 : stage = '\u0417\u0430\u043A\u0443\u043F\u043A\u0430'; break ;
                          case 30 : stage = '\u0412 \u043F\u0440\u043E\u0438\u0437\u0432\u043E\u0434\u0441\u0442\u0432\u0435'; break ;

                          case 40 : stage = '\u0413\u043E\u0442\u043E\u0432 \u043A \u043E\u0442\u0433\u0440\u0443\u0437\u043A\u0435'; break ;
                          case 41 : stage = '\u041E\u0442\u0433\u0440\u0443\u0436\u0435\u043D \u0431\u0435\u0437 \u0440\u0430\u0441\u0447\u0435\u0442\u0430'; break ;

                          case 50 : stage = '\u0412\u044B\u043F\u043E\u043B\u043D\u0435\u043D'; break ;
                          default : stage = '\u041D\u0435\u0434\u043E\u043F\u0443\u0441\u0442\u0438\u043C\u043E'; break ;
                        }

                        var old_stage = $( span ).attr( 'data-stage');
                        $( span ).text( stage ).attr( 'data-stage', new_stage );

// Notification

                        if( old_stage != new_stage)
                        {
//                            console.log( 'Notification. Zak id = ' + id + " new stage_id : " + new_stage + " stage : " + stage );
                              // why = 2 - изменение этапа
                              make_notification( id, new_stage, 2, "\u0418\u0437\u043C\u0435\u043D\u0435\u043D\u0438\u0435 \u044D\u0442\u0430\u043F\u0430. \u041D\u043E\u0432\u044B\u0439 \u044D\u0442\u0430\u043F : " + stage, field );
                        }


                        stopLoadingAnimation();
                        adjustCellColor( el, initial_state, 0 );
                    }
                );

                $( this ).dialog( "close" );
           }
           },
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

	if (can_change)
		$('#status_change_dialog_ok_button').button('enable');
	else
		$('#status_change_dialog_ok_button').button('disable');

      var disabled = true ;
      var checked = false ;

      if( conf == '1' )
        checked = true ;

// Поля Подготовка / КД и МТК
// Поле Комплектация / Поставка
// Поле Кооперация / Поставка
// Согласование с производством

    if( field == 'pd1' || field == 'pd3' || field == 'pd7' || field == 'pd_coop2' )
    {
       if( prod || user_id == 5  || user_id == 1 )
       {
            disabled = false ;
	   }

      $('#confirm_head_span_div').removeClass('hidden');
      $('#confirm_production_checkbox').data({ 'id' : id, 'field' : field } ).attr( { 'data-id': id, 'data-field' : field }).prop(
        {
          'checked' : checked,
          'disabled' : disabled
        }).parent('div').removeClass('hidden');

      if ( can_change /*&&  $('#confirm_production_checkbox').prop('checked') */ )
        $('#status_change_dialog_ok_button').button('enable');
          else
            $('#status_change_dialog_ok_button').button('disable');
    }


// Поле Подготовка / Нормы расхода.
// Согласование с ОМТС
    if( field == 'pd2' )
    {
       if( equip  || user_id == 15  || user_id == 1 )
          disabled = false ;

      $('#confirm_head_span_div').removeClass('hidden');
      $('#confirm_equipment_checkbox').data( { 'id' : id, 'field' : field } ).attr( { 'data-id': id, 'data-field' : field }).prop(
        {
          'checked' : checked,
          'disabled' : disabled
        }).parent('div').removeClass('hidden');

      if ( can_change /* &&  $('#confirm_equipment_checkbox').prop('checked') */ )
        $('#status_change_dialog_ok_button').button('enable');
          else
            $('#status_change_dialog_ok_button').button('disable');
    }


// Поле Производство / Окончание.
// Согласование с КО
    if( field == 'pd8' )
    {
     /*  if( coop )
          disabled = false ;*/

	if ( user_id == 145 || user_id == 39 || user_id == 1 )
     {
		disabled = false;
	}

      $('#confirm_head_span_div').removeClass('hidden');

		$('#confirm_commertion_checkbox').data( { 'id' : id, 'field' : field } ).attr( { 'data-id': id, 'data-field' : field }).prop(
          {
          'checked' : checked,
          'disabled' : disabled
        }).parent('div').removeClass('hidden');

      if ( can_change /* &&  $('#confirm_commertion_checkbox').prop('checked')  */ )
        $('#status_change_dialog_ok_button').button('enable');
          else
            $('#status_change_dialog_ok_button').button('disable');
    }
}


function showChangeListDialog()
{
    var field = $( this ).parents('td').data('str-id');
    var id = $( this ).parents('tr').data('id');
    var cnt = $( this ).text().replace(/\D/g,''); // delete nondigit symbols

    if( cnt == 0 )
      return ;

    if( cnt == 1 )
      cnt ++ ;

    var height = cnt * 16 + 80;

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
                    // console.log( data )                      
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

function adjustCellColor( el, state , chdate )
{
    var now = new Date();
    var day = now.getDate() < 10 ? "0" + now.getDate() : now.getDate() ;
    var month = now.getMonth() + 1 < 10 ? "0" + ( now.getMonth() + 1 )  : now.getMonth() + 1 ;
    var year = now.getFullYear()

    var field = $( el ).parents('td[data-str-id]');

    var count_list_link_val = Number( $( field ).find( 'a.count_list_link' ).text().replace(/\D/g,'') ) + 1 ;

    $( field ).find( 'a.ch_date_link' ).text( day + "." + month + "." + year );
    $( field ).find( 'a.count_list_link' ).text( "[" + count_list_link_val + "]");

    $( field ).find( 'div' ).removeClass('cell_state_good cell_state_over');

    $( field ).find( 'div' ).removeClass('cell_state_good cell_state_over');

    if( state )
        $( field ).find( 'div' ).addClass('cell_state_good');
          else
               $( field ).find( 'div' ).addClass('cell_state_over');

			  if ($(field).find('div').find('input').data('conf') == 0

			  && field.data("str-id") != 'pd10' &&
	field.data("str-id") != 'pd9' &&
	field.data("str-id") != 'pd13' &&
	field.data("str-id") != 'pd4' &&
	field.data("str-id") != 'pd12' &&
	field.data("str-id") != 'pd11') {

						$( field ).find( 'div' ).removeClass('cell_state_good cell_state_over');
						$( field ).find( 'div' ).addClass('cell_state_not_conf');
			  }

    if( chdate )
    {
        var date = new Date( chdate.substr(3,2) + "." + chdate.substr(0,2) + "." + chdate.substr(6,4) );
        $( field ).find( 'div' ).removeClass('cell_state_good cell_state_over');
        $( field ).find( 'a.ch_date_link' ).text( chdate );

        if( date < now )
          $( field ).find( 'div' ).addClass('cell_state_over');

    }
}

function getFilteredData()
{

// dates data collect

  var from_date = $('#from_date').val() ;
  var to_date = $('#to_date').val() ;

// **********************************************************************
// stage and status data collect

 if( $( this ).attr('name') == 'radio' && from_date.length == 0 && to_date.length == 0 )
  return;

 var stageCheckedList = $( "#dropdownStage" ).find("input:checked");
 var stageArr =[];
 var statusCheckedList = $( "#dropdownStatus" ).find("input:checked");
 var statusArr =[];

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


  var radio_sel = $('.radio_div').find('input:checked').val() ;
  var ord_type_sel = $('.ord_type_div').find('input:checked').val() ;
  var stage_sel = $('.radio_div').find('input:checked').val() ;

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
                                  field : field,
                                },
                                            function( data )
                                            {
                                              //console.log( data )
                                            }
                                  );
}

function check_empty_cells()
{
    var empy_cells = $('.cell_state_over');
    var num = 0 ;

      $.each( empy_cells , function( key, value )
    {
      var a = $( value ).find('a.ch_date_link');
        if( $( a ).text().length == 0 )
           $( value ).removeClass('cell_state_over');
    });
}

function check_to_add_button_enable()
{
//      var text_area = $( '#change_date_dialog_textarea').val().length ;
    var new_date = $( '#change_date_current_date_span').text().length;
    var cause = 1 * $( '#change_date_dialog_select option:selected' ).val() ;

     if ( new_date && cause )
        $('#change_date_dialog_add_button').button('enable');
}

function taskCarriesImgClick()
{
  var id = $( this ).parent().parent().data('id');
  var url ='/index.php?do=show&formid=269&p0=' + id ;
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
  var trs = $('tr[data-group_member]');
  var type = $('input[name=\"radio\"]:checked').val()

  if( type == undefined )
      type = '';
    
  var from = $('#from_date').val();
  var to = $('#to_date').val();

  var arr = [];

  $.each( trs , function( key, item )
  {
    var id = $( item ).data('id');
    arr.push( id )
  });

  var list = arr.join(',')
  console.log( list )

  url = "print.php?do=show&formid=279&p0=" + list + "&p1=" + type + "&p2=" + from + "&p3=" + to;
  window.open( url, "_blank" );
}