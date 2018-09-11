var id, date, state ;

// Действия после загрузки страницы
$( function()
{
    $('td.tabel_td').unbind('click').bind( 'click', tabel_td_click );

    $("#add_vac_doc_issued_check").unbind('change').bind( 'change', doc_issued_check_click ); 
    $("#absent_doc_issued_check").unbind('change').bind( 'change', doc_issued_check_click ); 

});

function tabel_td_click()
{
  id = $( this ).data( 'res_id' );
  date = $( this ).data( 'day' );
  
  state = 1 * $( this ).data( 'state' );
  
  var tid = $( this ).data( 'tid' );

  $("#add_vac_div").hide();
  $("#absent_div").hide();

  if( tid == 2 )
    {
      $( "#add_vac_doc_issued_check" ).prop('checked', false );
      $("#add_vac_div").show();
      if( state )
          $( "#add_vac_doc_issued_check" ).prop('checked', true );
    }

  if( tid == 6 )
    {
      $( "#absent_doc_issued_check" ).prop('checked', false );    
      $("#absent_div").show();
      if( state )
          $( "#absent_doc_issued_check" ).prop('checked', true );
    }
}


function doc_issued_check_click()
{
  var state = $( this ).prop('checked');

  if( state )
    $.post( "project/tabel/ajaxTabelUpdate.php", { id : id , date : date , val : 1 } , AddVacClickResponseFunc );
      else
        $.post( "project/tabel/ajaxTabelUpdate.php", { id : id , date : date , val : 0 } , AddVacClickResponseFunc );
}


function AddVacClickResponseFunc( data )
{
  var class_name = 'Field tabel_td ';
  
  data *= 1 ;
  
  switch( data )
  {
    case 0 : class_name += 'td_doc_not_issued'; break ;
    case 1 : class_name += 'td_doc_returned'; break ;
  }

  $('#' + date + id ).attr( 'class', class_name ).data('state', data );
}
