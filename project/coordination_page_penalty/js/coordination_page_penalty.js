 $( function()
{
	"use strict"

    $( '#datepicker_from, #datepicker_to' ).datepicker(
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
              update_page();
            }
        });

        let now = new Date();
        let year = now.getFullYear()
        let month = now.getMonth()
        let last_day = getLastDayOfMonth(year, month) 
        month ++ ;

        $( '#datepicker_from' ).datepicker( "setDate", "01." + month + "." + year );
        $( '#datepicker_to' ).datepicker( "setDate", last_day + "." + month + "." + year );
        $('.final_span').unbind('click').bind('click', final_span_click )

        if( ! debug )
            update_page()


function getLastDayOfMonth(year, month) 
{
  let date = new Date(year, month + 1, 0);
  return date.getDate();
}

function update_page()
{
  var date_from = $( '#datepicker_from' ).datepicker( 'getDate' );
  var date_to = $( '#datepicker_to' ).datepicker( 'getDate' );

  if( date_from || date_to )
    {
        let day_from = '';
        let month_from = '';
        let year_from = '';
		let day_to = '';
        let month_to = '';
        let year_to = '';
		
		if( date_from )
		{
			day_from = date_from.getDate();
			month_from = date_from.getMonth() + 1 ;
			year_from = date_from.getFullYear() ;
		}
		
		if( date_to )
		{
			day_to  = date_to.getDate();
			month_to = date_to.getMonth() + 1;
			year_to = date_to.getFullYear() ;
		}
		
          startLoadingAnimation()
          $.post(
            "project/coordination_page_penalty/ajax.GetData.php",
              {
                year_from : year_from,
                month_from : month_from,
                day_from : day_from,
				        year_to : year_to,
				        month_to : month_to,
				        day_to : day_to
              },
                          function( data )
                          {
                            $('#table_div').empty();
                            $('#table_div').html( data );
                            $('#print_btn').show();
                            $('.final_span').unbind('click').bind('click', final_span_click )                            
                            stopLoadingAnimation()                            
                          }
          );


    }
    else
    {
    }

}


function startLoadingAnimation() // - функция запуска анимации
{
    var imgObj = $("#loadImg").show();
}

function stopLoadingAnimation() // - функция останавливающая анимацию
{
    $("#loadImg").hide();
}

function adjustLoadingAnimation()
{
    var imgObj = $("#loadImg").hide();
    var centerY = $(window).height() / 2  - imgObj.height()/2 ;
    var centerX = $(window).width()  / 2  - imgObj.width()/2;

    // установка координат изображения:
    imgObj.offset( { top: centerY, left: centerX } );
}

    adjustLoadingAnimation()

function final_span_click()
{
    let div = $( this ).parent().find('div')
    if( $( div ).hasClass('hidden') )
        $( div ).removeClass('hidden')
            else
                $( div ).addClass('hidden')
    
    // console.log( $( div ).attr('class') )
}

});