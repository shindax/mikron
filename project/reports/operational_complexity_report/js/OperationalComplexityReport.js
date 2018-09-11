$( function()
{
   init();
});


function init()
{
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"50%"}
    }
    for (var selector in config) 
    {
      $( selector ).chosen( config[selector] );
    }
   
   $('select').unbind('change').bind('change', selectChange );
   $( '#print_link').unbind('click').bind('click', printLinkClick );
}

function printLinkClick()
{
  var src = $( this ).attr( 'src' );
  window.open( src ,'_blank');
}

function selectChange()
{
  var full_id = $("select :selected").val();
  var arr = full_id.split('&') ;
  var id = ( arr[2].split('='))[1];

  var full_name = $("select :selected").text();
  var name_arr = full_name.split('/') ;
  var name = name_arr[0];

  $('#title').text( 'Заказ № ' + name );
  $('#print_link').attr('src',  'print.php?do=show&formid=228&p0=' + id + '&p1=' + db_prefix);    
    
  $.post(
  "project/reports/operational_complexity_report/OperationalComplexityReportAJAX.php",
  {
    id     : id ,
    db_prefix : db_prefix 
  },
  selectChangeChangeResponse );
  
}

function selectChangeChangeResponse( data )
{
  $( '.row' ).remove();
  $( '.first' ).after( data );
  init();
  $('tr.row:even').addClass('even');  
  
  if( $("td[id='nodata']").length )
    $('#print_link').addClass('hidden');  
     else
      $('#print_link').removeClass('hidden');
/*
  
	var hours_total = 0.0;

	var tds = $("td:contains('Заготовка - Зачистка')");
	
	var i = 1;
	
	tds.each(function () {
		var tr = $(this).closest("tr.row, tr.row_even");
		
		var f = parseFloat($(this).next().html());
		
		if (!isNaN(f)) {
			hours_total = parseFloat(hours_total) + f;
		}
		
		if (i != tds.length) {
			tr.remove();
		}
		
		++i;
	})
	
	tds.last().next().html(hours_total.toFixed(2));
*/	

}

