var rows_arr = [];


function ResetSort()
{
  event.stopPropagation();
  var id = $( this ).addClass('hidden').data('id');
  if( rows_arr[ id ] )
  {
    var rows = rows_arr[ id ];
    $('tr[id="' + id + '"]').after( rows );
    rows_arr[ id ] = 0 ;
    MoveSortImages( id, rows );
    $( 'img.prj_ord_sort_img[data-id="' + id + '"]').attr({ 'src':'project/img5/c1.gif','val':'0'});
  }

}

function BindEventListeners()
{
  $('#prj_name_sort').unbind('click').bind('click', sortProjectByName );
  $('#prj_beg_date_sort').unbind('click').bind('click', sortProjectByBegDate );
  $('#prj_auth_sort').unbind('click').bind('click', sortProjectByAuthor );
  $('.prj_ord_sort_img').unbind('click').bind('click', sortProjectOrder ).attr({'src':'project/img5/c1.gif', 'val':'0'}).addClass('ralign hidden');
}

// После загрузки страницы
$( function()
{
  BindEventListeners();
  $('img.reset_sort').attr({'src':'project/img/hiditr.png'}).click( ResetSort );
});

function sortProjectOrder()
{
  var sort_name = $( this ).attr('data-sortname') ;

  if( sort_name == 'prj_ord_sort_name' )
    sortProjectOrderStub( this, ProjectOrderNameSortAsc , ProjectOrderNameSortDesc );

  if( sort_name == 'prj_ord_sort_beg_date' )
    sortProjectOrderStub( this, ProjectOrderBegDateSortAsc , ProjectOrderBegDateSortDesc );

  if( sort_name == 'prj_ord_sort_executor' )
    sortProjectOrderStub( this, ProjectOrderExecutorAsc , ProjectOrderExecutorDesc );

}

function sortProjectByName()
{
  sortProjectStub( this, NameSortAsc, NameSortDesc );
  BindEventListeners();  
}

function sortProjectByAuthor()
{
  sortProjectStub( this, AuthorSortAsc, AuthorSortDesc );
  BindEventListeners();
}

function sortProjectByBegDate()
{
  sortProjectStub( this, DateSortAsc, DateSortDesc );
  BindEventListeners();  
}

function DateSortAsc( a , b ) 
{
  var str1 = $('td.prj_beg_date_plan', $( a ) ).text() ;
  var str2 = $('td.prj_beg_date_plan', $( b ) ).text() ;  

  var date1 = str1.substring(6,10) + str1.substring(3,5) + str1.substring(0,2);
  var date2 = str2.substring(6,10) + str2.substring(3,5) + str2.substring(0,2);
  
  if( date1 == date2 )
      return 0;
  
  return date1 > date2 ? 1 : -1 ;      
}

function DateSortDesc( a , b ) 
{
  var str1 = $('td.prj_beg_date_plan', $( a ) ).text() ;
  var str2 = $('td.prj_beg_date_plan', $( b ) ).text() ;
  
  var date1 = str1.substring(6,10) + str1.substring(3,5) + str1.substring(0,2);
  var date2 = str2.substring(6,10) + str2.substring(3,5) + str2.substring(0,2);
  
  if( date1 == date2 )
      return 0;
      
  return date1 > date2 ? -1 : 1 ;
     
}

function NameSortAsc( a , b ) 
{
  var str1 = $('span.proj_item', $( a )).text().toUpperCase() ;
  var str2 = $('span.proj_item', $( b )).text().toUpperCase() ;
  
  if( str1 == str2 )
      return 0 ;
      
  return str1 > str2 ? -1 : 1 ;            
}

function NameSortDesc( a , b ) 
{
  var str1 = $('span.proj_item', $( a )).text().toUpperCase() ;
  var str2 = $('span.proj_item', $( b )).text().toUpperCase() ;
  
  if( str1 == str2 )
      return 0;
      
  return str1 > str2 ? 1 : -1 ;      
        
}
// **************************************************************************************

function AuthorSortAsc( a , b ) 
{
  var str1 = $('td.prj_author', $( a )).text().toUpperCase() ;
  var str2 = $('td.prj_author', $( b )).text().toUpperCase() ;
  
  if( str1 == str2 )
      return 0 ;
      
      
  return str1 > str2 ? -1 : 1 ;
}

function AuthorSortDesc( a , b ) 
{
  var str1 = $('td.prj_author', $( a )).text().toUpperCase() ;
  var str2 = $('td.prj_author', $( b )).text().toUpperCase() ;
  
  if( str1 == str2 )
      return 0;
      
  return str1 > str2 ? 1 : -1 ;      
}

// **************************************************************************************

function sortProjectStub( element, ascFunc, descFunc )
{
  var state = + $( element ).attr('val') ;
  var sortFunc = 0 ;

  var main_tr_list = $('tr.project');
  var table = $('#project_table');
  var trs_array = [] ;
    
    main_tr_list.each( function( index, element )
    {   
        var id = $( element) .attr('id');
// выборка строк с name начинающимся с id ( порядок иерархического построения в дереве )                
        trs_array[ id ] = $( "tr[name^='" + id + "']" );
    });    

  switch( state )
  {
    case 0 :
              $('.prj_sort_img').attr({'src':'project/img5/c1.gif', 'val': 0 }) ;
              $( element ).attr({'src':'project/img5/u1.gif', 'val': 1 }) ;
              state = 1 ;
              sortFunc = descFunc;
              break;
    case 1 :
              $( element ).attr({'src':'project/img5/d1.gif', 'val': 2 }) ;
              state = 2 ;              
              sortFunc = ascFunc;
              break;
    case 2:
    
              $( element ).attr({'src':'project/img5/u1.gif', 'val': 1 }) ;    
              state = 1 ;              
              sortFunc = descFunc;              
              break;              
  }

// Удаляем все строки    
  $('tr[data-name]').remove();  
     
    main_tr_list.sort( sortFunc );
  
    main_tr_list.each( function( index, element )
    {   
        table.append( element );
        table.append( trs_array[ $( element ).attr('id') ] );
    });    
  
  UI_adjust();
  SetFileUploadProcs();
}

// **************************************************************************************

function sortProjectOrderStub( element, ascFunc, descFunc )
{
  event.stopPropagation();
  var state = + $( element ).attr('val') ;
  var row_id = $( element ).attr('data-id');
  var proj_row = $('tr[id="' + row_id + '"]');
  var coll_img = $( 'img.coll_exp' , $( 'tr[id="' + row_id + '"]' ) ).attr('data-state','0');
  var rows = $( $('tr[name^="' + row_id + '"]') );

  var res_img = $( 'img.reset_sort', proj_row ).removeClass('hidden');

// Сохранение первоначального порядка строк
  if( ! rows_arr[ row_id ] )
        rows_arr[ row_id ] = rows.slice();

// Вызов функции coll_exp_img_click с контекстом кнопки "раскрыть всё дерево"
  coll_exp_img_click.call( coll_img );

  switch( state )
  {
    case 0 :
              $('.prj_ord_sort_img').attr({'src':'project/img5/c1.gif', 'val': 0 }) ;
              $( element ).attr({'src':'project/img5/u1.gif', 'val': 1 }) ;
              state = 1 ;
              sortFunc = descFunc;
              break;
    case 1 :
              $( element ).attr({'src':'project/img5/d1.gif', 'val': 2 }) ;
              state = 2 ;              
              sortFunc = ascFunc;
              break;
    case 2:
    
              $( element ).attr({'src':'project/img5/u1.gif', 'val': 1 }) ;    
              state = 1 ;              
              sortFunc = descFunc;              
              break;              
  }

  rows.sort( sortFunc );

  MoveSortImages( $( element ).attr('data-id'), rows );

//   Вставить отсортированные строки после строки проекта
  $( proj_row ).after( $( rows ) );
}

// *********************************************************************************************

function MoveSortImages( id , rows )
{
  if( rows.length == 1 )
    {
      $( 'img.prj_ord_sort_img[data-id="' + id + '"]').hide();
      return ;
    }
  var img_arr  = $( 'img.prj_ord_sort_img[data-id="' + id + '"]');
  var first_row = $( rows ).first();

    img_arr.each( function( index, element )
    {
        var sortname = $( element).attr('data-sortname');
        
        if(  sortname == 'prj_ord_sort_name')
            $( first_row ).find('span').last().append( $( element ) );
        
        if( sortname == 'prj_ord_sort_beg_date')
            $( first_row ).find('td.prj_ord_date_of_beg_plan_cell').append( $( element ) );
            
        if( sortname == 'prj_ord_sort_executor')
            $( first_row ).find('td.prj_ord_date_executor_cell').append( $( element ) );
            
    });    
}

// *********************************************************************************************

function ProjectOrderNameSortAsc( a , b ) 
{
  var str1 = $('span.proj_ord', $( a )).text().toUpperCase().trim() ;
  var str2 = $('span.proj_ord', $( b )).text().toUpperCase().trim() ;
  
  if( str1 == str2 )
      return 0;
      
  return str1 > str2 ? -1 : 1 ;      
}

function ProjectOrderNameSortDesc( a , b ) 
{
  var str1 = $('span.proj_ord', $( a )).text().toUpperCase().trim() ;
  var str2 = $('span.proj_ord', $( b )).text().toUpperCase().trim() ;
  
  if( str1 == str2 )
      return 0;
      
  return str1 > str2 ? 1 : -1 ;
}

// *******************************

function ProjectOrderBegDateSortAsc( a , b ) 
{
  var str1 = $('td.prj_ord_date_of_beg_plan_cell', $( a ) ).text() ;
  var str2 = $('td.prj_ord_date_of_beg_plan_cell', $( b ) ).text() ;  

  var date1 = str1.substring(6,10) + str1.substring(3,5) + str1.substring(0,2);
  var date2 = str2.substring(6,10) + str2.substring(3,5) + str2.substring(0,2);
  
  if( date1 == date2 )
      return 0;
  
  return date1 > date2 ? 1 : -1 ;      
}

function ProjectOrderBegDateSortDesc( a , b ) 
{
  var str1 = $('td.prj_ord_date_of_beg_plan_cell', $( a ) ).text() ;
  var str2 = $('td.prj_ord_date_of_beg_plan_cell', $( b ) ).text() ;  

  var date1 = str1.substring(6,10) + str1.substring(3,5) + str1.substring(0,2);
  var date2 = str2.substring(6,10) + str2.substring(3,5) + str2.substring(0,2);
  
  if( date1 == date2 )
      return 0;
  
  return date1 > date2 ? -1 : 1 ;      
}


// *********************************************************************************************

function ProjectOrderExecutorAsc( a , b ) 
{
  var str1 = $('td.prj_ord_date_executor_cell', $( a )).text().toUpperCase().trim() ;
  var str2 = $('td.prj_ord_date_executor_cell', $( b )).text().toUpperCase().trim() ;
  
  if( str1 == str2 )
      return 0;
      
  return str1 > str2 ? -1 : 1 ;      
}

function ProjectOrderExecutorDesc( a , b ) 
{
  var str1 = $('td.prj_ord_date_executor_cell', $( a )).text().toUpperCase().trim() ;
  var str2 = $('td.prj_ord_date_executor_cell', $( b )).text().toUpperCase().trim() ;
  
  if( str1 == str2 )
      return 0;
      
  return str1 > str2 ? 1 : -1 ;
}

// *******************************
