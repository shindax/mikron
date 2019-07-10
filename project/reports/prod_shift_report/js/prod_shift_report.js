// Действия после загрузки страницы
$( function()
{
    $('#report_date').bind('change', dateChange );
    $( '#print_link').unbind('click').bind('click', printLinkClick );
    $('tr.people_print_row').filter( ':odd' ).addClass('print_odd');
});

function printLinkClick()
{
  var src = $( this ).attr( 'src' );
  window.open( src ,'_blank');
}

function dateChange()
{
  var date = $( this ).val();
  var year  = date.substr(0,4) ;
  var month = date.substr(5,2) ;
  var day  = date.substr(8,2) ;    

  if( date == '')
  {
    $('#prod_shift_report').empty();
    $('#title').text('');
    return ;
  }

  // print_page_form_id id формы для печати 

  $('#title').text('Отчет о перечне работающего персонала за ' + day + '.' + month + '.' + year );
  
// Отправка на страницу оптимизированной для печати  
//  $('#print_link').attr('src',  'index.php?do=show&formid=' + print_page_form_id + '&p0=' + year + month + day );
  
// Отправка сразу напечать
  $('#print_link').attr('src',  'print.php?do=show&formid=' + print_page_form_id + '&p0=' + year + month + day );  
  
  $.post(
  "/project/reports/prod_shift_report/getDataAJAX.php",
  {
    date : date 
  },
  function( data )
    {
        $('#prod_shift_report').remove();
        $('#print_link').after( data );
        
        $('img.expang_img').unbind('click').bind('click', ImgClick );
        $('tr.people').filter( ':odd' ).addClass('odd');
        
        var count = $( data ).find('#total_count').text() ;
        
        if( count == 0 )
          $( '#print_link').hide();
            else
              $( '#print_link').show(); 
    }
     );
}

function ImgClick()
{
    var id = $( this ).data( 'id' );
    var state = $( this ).data( 'state' );
    state = state == '0' ? '1' : '0';
    $( this ).data( 'state' , state );
    
    if( state == '0' )
    {
       $('tr.row_' + id).addClass('hidden');
       $( this ).attr( 'src' , '/uses/collapse.png' ).attr( 'title' , 'Развернуть' );
    }
          else
          {
              $('tr.row_' + id).removeClass('hidden');
              $( this ).attr( 'src' , '/uses/expand.png' ).attr( 'title' , 'Cвернуть' );
          }
}
