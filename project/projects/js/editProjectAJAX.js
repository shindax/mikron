// Jquery AJAX-запрос
function insertOneRow( proj_id , user_id , parent_id )
{
  var offset = '5px';
$.post(
  "project/MyJobs/InsertOneRowAJAX.php",
  {
    proj_id   : proj_id ,
    user_id   : user_id ,
    parent_id : parent_id,
    offset    : offset
  },
  insertOneRowServerResponse
);    
}

// Jquery AJAX-ответ
function insertOneRowServerResponse( data )
{
    var id = '#project_table' ;
    $( id ).append( data );
  
    $( '#new_ord_edit' ).focus();
            
// Привязка обработчика нажатия на строки с классом collapsed
    UI_adjust();
}

// make asynchronous HTTP request using the XMLHttpRequest object 
function addOrder( proj_id , user_id, parent_id , level , in_name, row_id )
{
  // proceed only if the xmlHttp object isn't busy
    var ord_name          = $("#one_row_order_name").val();
    
    var date_of_beg_plan  = $("#one_row_date1").val() ;
    var date_of_perf_plan = $("#one_row_date2").val() ;
    var date_of_perf_fact = $("#one_row_date3").val() ;
    
    var checker = $("#one_row_checker").val() ;        
    var descr = $('#onerowtextarea').val() ;

    var exec_list = $('li', '#one_row_executor_ul' );
  
    var executors = [];
    
    exec_list.each( function( index, element )
    {
        var id = $( element).attr('data-id');
        executors [ index ] = id;
    });    
   
    $.post(
    "project/MyJobs/AddOrderAJAX.php",
    {
      proj_id           : proj_id ,
      user_id           : user_id ,
      parent_id         : parent_id ,
      ord_name          : ord_name ,
      date_of_beg_plan  : date_of_beg_plan ,
      date_of_perf_plan : date_of_perf_plan ,
      date_of_perf_fact : date_of_perf_fact ,
      executors         : executors,
      checker           : checker,
      level             : level,      
      in_name           : in_name,
      row_id            : row_id,
      descr             : descr
    },
    addOrderServerResponse,
    'html'
        );    
        
}

// executed automatically when a message is received from the server
function addOrderServerResponse( in_data )
{
    var pos = in_data.indexOf("<tr");
    var str = in_data.substring( pos );
    
    var index = $('#temp_row').index() ;
    var last_child = + $('#temp_row').data('lastchild') ;

 // Получаем необходимые данные со временной строки
    var id = $('#temp_row').data('id');
    var name = $('#temp_row').data('name');

// Заменяем временную строку новой
   $('#temp_row').replaceWith( str );
    
// Находим новую строку и нажатую родительскую              
   var row = $( '#project_table tr' ).eq( index + 2 );
   var prev_row = $( '#project_table tr' ).eq( index + 1 );
        
   $( row ).attr('data-name', $( prev_row ).attr('data-name') );

// Если нажатая строка не была ранее узлом "дерева", то она им становится 
   prev_row.attr({'class' : 'collapsed_proj_row'});
   if( last_child )
   {
        prev_row.click( collapsed_proj_row_click_link ); 
        prev_row.find('span.ordspan').toggleClass('ordspan collspan').html('&#9660');
   }

   // Назначение данных для работы "дерева"
   row.attr({'name' : name },{'id' : id });
   // Обработка нажатий на новую строку
   row.find('.link').click( link_click );
   
   row.find('.show_hide').css( {'cursor':'pointer','padding-left':'3px'} );
   row.find('td.AR, td.AC, td.AL').addClass('field');
   row.find('.show_hide').click( insertOneRowAfter );
   row.find('input.ch_status_checkbox').click( change_status_checkbox );
}
