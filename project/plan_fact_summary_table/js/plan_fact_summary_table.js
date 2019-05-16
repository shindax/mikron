// Actions after full page loading
$( function()
{
      // Замена старой верстки

    $('#vpdiv').append( $('#main_div') ).append( $("#loadImg").hide() );
    $('.A4W').remove();
    $('table.view').hide();

    adjustDropDownSelect();

    adjust_calendars( '#from_date' );
    adjust_calendars( '#to_date' );

    adjust_ui();

$.extend($.expr[":"],
    {
        "containsNC": function(elem, i, match, array)
        {
            return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
        }
  });

});

function reset_filter_button_click()
{
  $('#from_date').val('');
  $('#to_date').val('');

  $('.ord_type_div').find('input').prop('checked', false );

  resetDropdown( "#dropdownStage" );
  resetDropdown( "#dropdownStatus" );

  getFilteredData();
}

function reset_date_filter_button_click()
{
  $('#from_date').val('');
  $('#to_date').val('');
  $('.ord_type_div').find('input').prop('checked', false ) ;
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
  $('#order_table tr').addClass('hidden');
  $('#order_table tr.empty_row').removeClass('hidden').css('height','96px');

   var found = $('td.ord_head:containsNC("' + $( '#find' ).val() + '")').parent('tr').removeClass('hidden') ;
   renumberRow( found );
}

function adjust_ui()
{
    $('#reset_filter_button').unbind('click').bind( 'click', reset_filter_button_click );
    $('#reset_date_filter_button').unbind('click').bind( 'click', reset_date_filter_button_click );

    $("input[name='radio']").unbind('click').bind( 'click', getFilteredData );
    $("input[name='ord_type']").unbind('click').bind( 'click', getFilteredData );

    $('.pressable').prop('data-state', 0 );
    $( '.hiddenly' ).hide();
    $('.production').attr( 'colspan',4 );
    $('.ord_head').attr( 'colspan',16 );
    $('.empty_row').attr( 'colspan',16 );
    $('.arr_div').html( '&#9668;&#9658;' );

    $( "input:disabled" ).css('cursor','default');
    $( "a.disabled" ).css('cursor','default');

    $('tr.data_row').removeClass('odd_row');
    $('tr.data_row:odd').addClass('odd_row');

    $( 'span.value_span').unbind('click').bind('click', span_click );
    $( 'span.zero_span').unbind('click');
    $( 'button span').unbind('click');
    $( '.direction_name').unbind('click').css('cursor','default');
    

    var unread_notes =  $( '.unread_notes'); 

    $.each( unread_notes , function( key, value )
    {
      var to_id = $( value ).data('to-id');
  
    if( to_id == user_id )
        $( value ).unbind('click').bind('click', unread_notes_click );
          else
              $( value ).unbind('click').css('cursor', 'default' );

    });
  
    check_summ();
}

function span_click()
{
  var zak_list = $( this ).data('id');
  var penalties_list = $( this ).data('penalties-list');
  var url = "index.php?do=show&formid=241&list=" + zak_list ;

  if( penalties_list !== undefined )
    url += "&penalties_list=" + penalties_list ;

  window.open( url, "_blank");
}

function getFilteredData()
{

// dates data collect

  var from_date = $('#from_date').val() ;
  var to_date = $('#to_date').val() ;

  // alert( from_date + ' : ' + to_date )

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

  var date_filter_radio = $('.date_filter_radio_div').find('input:checked').val() ;
  var ord_type_sel = $('.ord_type_div').find('input:checked').val() ;

// **********************************************************************


    startLoadingAnimation();
    $.post(
        "project/plan_fact_summary_table/ajax.getFilteredData.php",
        {
          stage         : stageArr  ,
          status        : statusArr ,
          date_filter  : date_filter_radio ,
          ord_type    : ord_type_sel,
          from_date  : from_date ,
          to_date      : to_date
        },

        function( data )
        {
            stopLoadingAnimation();
            $('#table_div').empty().html( data );
            adjust_ui();
        }
    );
}

function check_summ()
{
  var sum = 0;
  var divs = $('.plan_fact_summary_div');

    $.each( divs , function( key, value )
    {
      var summ = $( value ).find('summ' );
      var penalty = $( value ).find('.plan_fact_summary_expired_span_div').find('span.summ').data('summ');

      var total = 0 ;
       $.each( summ , function( key, value )
         {
              var val = Number( $( value ).text() );
              total += val;
         });

      var sum = $( value ).find('span.summ' );
      var span = $( value ).find('div.plan_fact_summary_expired_span_div span' );
      var div = $( value ).find('div.plan_fact_summary_expired_span_div' );

      total += penalty ;

      $( sum ).text( total );

      if( $( sum ).text() == 0 )
//      if( total == 0 )
        {
            $( div ).removeClass('penalties');
            $( span ).addClass('empty_span');
        }
          else
          {
          $( div ).addClass('penalties');
          $( span ).removeClass('empty_span');
          }

    });

}

function unread_notes_click()
{
   window.open( "index.php?do=show&formid=263", "_blank");
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
