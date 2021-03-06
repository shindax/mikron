$( function()
{
  "use strict"

  let today = new Date();
  let month = today.getMonth() + 1; //January is 0!
  let year = today.getFullYear();

  let options =
  {
      selectedYear: year,
      selectedMonth:month,      
      startYear: 2010,
      finalYear: 2020,
      monthNames: monthNamesShort
  };

  $('#monthpicker').monthpicker(options).bind('monthpicker-click-month', monthpicker_click_month).bind('monthpicker-change-year', function (e, year) { $('#monthpicker').val(''); }).val( monthNames[month - 1 ] + ' ' + year ).data( 'date', { month : month, year : year } );

  adjust_ui();
  getViolationCalendar()  

function monthpicker_click_month()
{
let year = $('#monthpicker').monthpicker('getDate').getFullYear();
let month = $('#monthpicker').monthpicker('getDate').getMonth();
$('#monthpicker').data('date', { 'month': month + 1 , 'year' : year });
$('#monthpicker').val( monthNames[ month ] + ' ' + year );
getViolationCalendar()
}

function adjust_ui()
{
  $('.print_button').unbind('click').bind('click', print_button_click )
  $('.print_total_button').unbind('click').bind('click', print_total_button_click )
  $('input[type=radio]').unbind('click').bind('click', input_radio_click )  
  $('.user_print_img').unbind('click').bind('click', user_print_img_click )  
  $('.norm_plan').unbind('keyup').bind('keyup', norm_plan_keyup )  
  $('.day_plan_input').unbind('keyup').bind('keyup', day_plan_input_keyup)  
}

function getViolationCalendar()
{
   var data = $( "#monthpicker" ).data( 'date' );

   $('.selected').removeClass('selected')
    var viol_radio = $("input[name='type']:checked");
    var viol_type = $( viol_radio ).val();
    $( viol_radio ).parent().addClass('selected')

//    console.log( data )

    if( !data )
        return ;

    $('.table_div').empty();

    var month = data['month'];
    var year = data['year'];
 

  startLoadingAnimation();
 
  if( viol_type == 0 || viol_type == 1 || viol_type == 2 || viol_type == 4 ) // По сотрудниками
  {
  $.post(
          '/project/labor_regulations_violation/ajax.getViolationCalendar.php',
          {
              year  : year,
              month  : month ,
              viol_type : viol_type,
              user_id : user_id
          },
          function( data )
          {
            $('.table_div').html( data );
            adjust_ui();
            stopLoadingAnimation();
          }
        );
  }
    if( viol_type == 3 ) //По предприятию
  {
      $.post(
          '/project/labor_regulations_violation/ajax.getViolationCalendarByEnterprise.php',
          {
              year  : year,
              month  : month ,
              can_edit_norm_plan : can_edit_norm_plan
          },
          function( data )
          {
            $('.table_div').html( data );
            adjust_ui();
            $('#curloadingpage1').hide()
            $('#loadImg').hide()
          }
        );
  }
    if( viol_type == 5 ) //По мастерам
  {
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
            $( '.table_div' ).empty().html( data )
            adjust_ui()
            stopLoadingAnimation()          
          }
        );
  }


}

function print_button_click( event )
{
  event.preventDefault();  
  var id = $( this ).attr( 'id' );
  var data = $( "#monthpicker" ).data( 'date' );
  var viol_type = $("input[name='type']:checked"). val();

  if( !data )
      return ;

  let month = data['month'];
  let year = data['year'];
  let url = null ;

  if( viol_type == 4 )
    url = "print.php?do=show&formid=286&p0=" + id + "&p1=" + year + '&p2=' + month + '&p3=' + viol_type; 

  if( viol_type != 4 )
    url = "print.php?do=show&formid=277&p0=" + id + "&p1=" + year + '&p2=' + month + '&p3=' + viol_type; 

  window.open( url, "_blank" );

}

function input_radio_click()
{
  getViolationCalendar()
}

function print_total_button_click( event )
{
  event.preventDefault();  
  var id = $( this ).data('id')
  var data = $( "#monthpicker" ).data( 'date' );
  var month = data['month'];
  var year = data['year'];

  let url = "print.php?do=show&formid=281&p0=" + id + "&p1=" + year + '&p2=' + month ; 
  window.open( url, "_blank" );
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

function user_print_img_click()
{
  let table = $( this ) .parents('table')
  
  let id = $( table ).attr('id')
  let dep_id = $( table ).data('dep_id')
  let date = $( "#monthpicker" ).data( 'date' );

  let month = date['month'];
  let year = date['year'];
  let url = null ;
  url = "print.php?do=show&formid=288&p0=" + id + "&p1=" + year + '&p2=' + month + '&p3=' + dep_id; 

  window.open( url, "_blank" );
}

function norm_plan_keyup() 
{
  let date = $( "#monthpicker" ).data( 'date' );
  let month = date['month'];
  let year = date['year'];

  let id = $( this ).data('id')
  let viol = $( this ).data('viol')

  let val = $( this ).val()

  if( isNaN( val ) || val.length == 0 )
     $( this ).addClass('error')
        else
        {
          $( this ).removeClass('error')
            $.ajax({    
            url : '/project/labor_regulations_violation/ajax.updateNormPlan.php',
            type : 'POST',
            data : {
                      year  : year,
                      month  : month ,
                      dep_id : id,
                      val : val,
                      viol : viol
                    },
            dataType: 'json',
            success: function( respond, textStatus, jqXHR )
              {
                if( typeof respond.error === 'undefined' )
                {
                  // console.log( respond )
                  $( 'span.norm_minus_viol[data-id=' + id + ']').text( respond[0] )
                  $( 'span.score[data-id=' + id + ']').text( respond[1] )
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

});



