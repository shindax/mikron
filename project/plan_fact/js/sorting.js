function back() 
{ 
}


function sortArrowClick()
{
  i = 0 ;
  var sort_what = $( this ).attr('id');
  setTimeout( function(){ $('#loadImg').show().css('display','block'); } , 1, sort_what );
  setTimeout( Sort, 10, sort_what );

/*
var result = document.querySelector('.result');

if (window.Worker) 
{
	var myWorker = new Worker("/project/plan_fact/js/worker.js");

	  myWorker.postMessage([ sort_what ]); // Sending message as an array to the worker
	  console.log('Message posted to worker');

	myWorker.onmessage = function(e) 
	{
		result.textContent = e.data;
		console.log('Message received from worker' + e.data );
	};
}
*/
}


function Sort( sort_what )
{
  var el = $( '#' + sort_what );
  var sort =  $( el ).data( 'sort' );
  $('.sort_arrow').css('color','black').data( 'sort', 1 ).html('&#9660;&#9650;');
  var sort_what = $( el ).attr('id');

  if( sort == 0 )
  {
    sort = 1;
    $( el ).html('&#9650');
  }
      else  
      {
        sort = 0;
        $( el ).html('&#9660');
      }
  
  $( el ).data( 'sort', sort ).css('color','white');  
      var data_rows = $('tr[data-id] td.ord_head').parent('tr');  
  
  if( sort_what == 'sort_stage_arrow' )
      sortStub( data_rows, sort, StageSortAsc, StageSortDesc );

  if( sort_what == 'sort_status_arrow' )
      sortStub( data_rows, sort, StatusSortAsc, StatusSortDesc );

     var len = data_rows.length ;
     var line = 1 ;

// Перемещение второй строки заказа ( с датами )  
    $.each( data_rows, function( key, value )
    {
      $( value ).find('td.line_td span').text( line ++ + ' / ' + len );
      var tr = $('tr[data-id="' + $( value ).data('id') + '"]:not(:has( td.ord_head ))') ;
      $( value ).after( tr );      
    });
    
  $('#loadImg').css('display', 'none' );    
    
}

function StageSortAsc( a , b ) 
{
//  back();

  var str1 = $( a ).find('div.stage_div span').text().toUpperCase().trim() ;
  var str2 = $( b ).find('div.stage_div span').text().toUpperCase().trim() ;
  
  var name1 = $( a ).find('a.ord_link').text().toUpperCase().trim() ;
  var name2 = $( b ).find('a.ord_link').text().toUpperCase().trim() ;  
  
  if( str1 == str2 )
    {
      if( name1 == name2 )
            return 0;
        return name1 > name2 ? 1 : -1 ;      
    }
      
  return str1 > str2 ? -1 : 1 ;      
            
}

function StageSortDesc( a , b ) 
{
//  back();
  var str1 = $( a ).find('div.stage_div span').text().toUpperCase().trim() ;
  var str2 = $( b ).find('div.stage_div span').text().toUpperCase().trim() ;
  
  var name1 = $( a ).find('a.ord_link').text().toUpperCase().trim() ;
  var name2 = $( b ).find('a.ord_link').text().toUpperCase().trim() ;  
    
  if( str1 == str2 )
    {
      if( name1 == name2 )
            return 0;
        return name1 > name2 ? 1 : -1 ;      
    }
      
  return str1 > str2 ? 1 : -1 ;      
}


function StatusSortAsc( a , b ) 
{
//  back();
  var str1 = $( a ).find('div.status_div select option:selected').val().toUpperCase().trim() ;
  var str2 = $( b ).find('div.status_div select option:selected').val().toUpperCase().trim() ;
  
  var name1 = $( a ).find('a.ord_link').text().toUpperCase().trim() ;
  var name2 = $( b ).find('a.ord_link').text().toUpperCase().trim() ;  
  
  if( str1 == str2 )
    {
      if( name1 == name2 )
            return 0;
        return name1 > name2 ? 1 : -1 ;      
    }
      
  return str1 > str2 ? 1 : -1 ;      
}

function StatusSortDesc( a , b ) 
{
//  back();  
  var str1 = $( a ).find('div.status_div select option:selected').val().toUpperCase().trim() ;
  var str2 = $( b ).find('div.status_div select option:selected').val().toUpperCase().trim() ;
  
  var name1 = $( a ).find('a.ord_link').text().toUpperCase().trim() ;
  var name2 = $( b ).find('a.ord_link').text().toUpperCase().trim() ;  
  
  if( str1 == str2 )
    {
      if( name1 == name2 )
            return 0;
        return name1 > name2 ? 1 : -1 ;      
    }
      
  return str1 > str2 ? -1 : 1 ;      
}


function sortStub( rows, state, ascFunc, descFunc )
{
  switch( state )
  {
    case 0 :

              sortFunc = descFunc;
              break;
    case 1 :
              sortFunc = ascFunc;
              break;
  }

  rows.sort( sortFunc );

//   Вставить отсортированные строки после строки проекта
  $( '#order_table' ).append( $( rows ) );
}
