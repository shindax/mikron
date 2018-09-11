function CellClick()
{
  startLoadingAnimation();

  $('*').css( { 'cursor':'wait'} );
  
  var el = $( this );
 
  var day = el.data('day');
  var ev_id = el.parent().data('ev_id');
  var month = el.parent().data('month');
  var year = el.parent().data('year');
  
  $(document).unbind( 'click' );

  var old_day = $('#det_div').data('day');
  var old_month = $('#det_div').data('month');
  var old_year = $('#det_div').data('year');  
  var old_ev_id = $('#det_div').data('ev_id');  

  if( old_day == day && old_month == month && old_year == year && old_ev_id == ev_id && $('#det_div').css( 'display') == 'block' )
      HideDetailDiv();
        else
        {
          $('#det_div').data('day', day );        
          $('#det_div').data('month', month );
          $('#det_div').data('year', year );  
          $('#det_div').data('ev_id', ev_id );  
          $.post( "project/act_cal/ajaxGetDetailTable.php", { month : month , year : year , day : day , ev_id : ev_id } , CellClickResponseFunc );
        }
}

function HideDetailDiv()
{
      $('*').css( { 'cursor':'default'} );
      $('.ord_cell').css( { 'cursor':'pointer'} );
  
      $(document).click(function(event) 
      {
        if ($(event.target).closest("#det_div").length) 
          return;
      $("#det_div").fadeOut();
      event.stopPropagation();
      });
      stopLoadingAnimation();      
}

function CellClickResponseFunc( data )
{
  $('#det_div').css( 'display','none').html( data ).css( 'display','block');  
  DetDivRecalc();
  HideDetailDiv();
}

function DetDivRecalc()
{
  var row_count = $('tr', '#det_table').length - 1 ;

//  $('#det_table_div').height( ( row_count > 4 ? 9 : row_count + 2 ) * $('tr.even_row').first().height() + ( row_count <= 4 ? 0 : 5 ) );


  $('#det_table_div').height( ( row_count > 6 ? 9 : row_count + 1 ) * $('tr.even_row').first().height() + ( row_count <= 4 ? 10 : 15 ) );


  $('#det_div').height( $('#det_table_div').height() + ( row_count <= 4 ? 10 : 5 ) );
  $('#det_div').width( $('#table_div').width() );

  var top = $( document ).height() - 220 ;   
  $('#det_div').offset({ top: top });
  
  scrolify( '#det_table', $('#det_table_div').height() ); 

  $('#outer_det_table_div').height( $('#det_table_div').height() -  $('tr.even_row').first().height() - 5 );
}

function dateChange()
{
  startLoadingAnimation();

  var months = ["январь","февраль","март","апрель","май","июнь","июль","август",
                "сентябрь","октябрь","ноябрь","декабрь"]; 
  
  var month = $( "#month_sel" ).val() ;
  var year = $( "#year_sel").val() ;

  $('*').css( { 'cursor':'wait'} );    
  $('#det_div').css('display','none');
  
  $('#capt').html('Регистрация событий за ' + months[ month - 1 ] + ' ' + year + 'г.');

  $.post( "project/act_cal/ajaxMakeEventTable.php", { month : month , year : year } , monthChangeResponseFunc );
}

function monthChangeResponseFunc( data )
{
  $('*').css( { 'cursor':'default'} );
  $('#table_div').html( data );
  adjustMetrics();
//  $('#table_div').css( { 'display':'block'} );  
  
  stopLoadingAnimation();
  adjustDatascreen();
} 

// Настройка вывода
function adjustDatascreen()
{
  $('#capt_first_col').width( $('#first_col').width() );
  $('#capt_second_col').width( $('#second_col').width() );
  $('#capt_third_col').width( $('#third_col').width() );  
  $('#capt_second_col').width( $('#second_col').width() );

  $('#header').width( $('#second_col').width() + $('#first_col').width() + $('#third_col').width() );
  
  $('#main_div').width( $('#header').width() + 17 );
  $('#main_div').height( $( document ).height() - 360 );
  
  $('#table_div').width( $('#header').width() );
  $('#table_div').height( $('#main_div').height() );

  var day_count = $('td.head_col').length ;
  
  for( var i = 1 ; i <= day_count ; i ++ )
   {
      var maxWidth = 0;
 
      $( 'td[data-day="' + i + '"]' ).each(function()
      {
        var len = $(this).text().length * 5 + 8 ;
        
        if ( len > maxWidth ) 
            maxWidth = len ;
      });
      
      $('td[data-day="' + i + '"]').width( maxWidth );
   }

  $('#capt_second_col').bind( 'scroll', scrollCaptMonth );  
  $('#second_col').bind( 'scroll', scrollMonth );

  if( $('tr[data-ev_id]').length == 0 )
    $('#main_div').css('display','none');
      else
        $('#main_div').css('display','block');

  var real_height = $('#second_col_table').height();
  if( real_height < $('#table_div').height() )
    {
      $('#table_div').height( real_height ) ;
      $('#main_div').height( real_height ) ;
    }

  var real_width = $('#first_col_table').width() + $('#second_col_table').width() + $('#third_col_table').width() ; 

  if( real_width <= $('#table_div').width() )
    {
      $('#table_div').width( real_width ); 
      $('#main_div').width( real_width  ).css( { 'overflow-x':'hidden' } ) ;
    }
}

// Настройка высот и ширин
function adjustMetrics()
{
  var mid_width = 300 ;

  $('.ord_cell').unbind( 'click' ).bind( 'click', CellClick );
  
  $('#second_col').width( $( window ).width() - mid_width );

  $(window).resize(function()
  {
    $('#main_div').width( $(window).width());
    $('#second_col').width( $(window).width() - mid_width );
    $('#det_div').width( $( window ).width() - 50 );
    $("#det_div").fadeOut();
    adjustDatascreen();  
    adjustMetrics();
  });


// Выравнивание высот строк
var maxHeight = 0;
 
  $( ".odd_row" ).each(function()
    {
      if ( $(this).height() > maxHeight ) 
          maxHeight = $(this).height();
    });
 
  $( ".odd_row" ).height( maxHeight );

var maxHeight = 0;
 
  $( ".even_row" ).each(function()
    {
      if ( $(this).height() > maxHeight ) 
          maxHeight = $(this).height();
    });
 
  $( ".even_row" ).height( maxHeight );
  
 DetDivRecalc();
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


// Действия после загрузки страницы : 
$( function()
{
  adjustMetrics();
  $( "#month_sel" ).change( dateChange );
  $( "#year_sel" ).change( dateChange );  

  adjustDatascreen();  

});

function scrollCaptMonth()
{
  var val = $('#capt_second_col').scrollLeft();
  $('#second_col').scrollLeft( val );
};

function scrollMonth()
{
  var val = $('#second_col').scrollLeft();
  $('#capt_second_col').scrollLeft( val );
};



