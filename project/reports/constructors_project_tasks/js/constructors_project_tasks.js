$( function()
{
    $('#report_date').bind('change', dateChange );
    $('#print_link').bind('click', link_click );
    
});

function link_click()
{
  var link = $('#print_link').attr('src');
  window.open( link ,'_blank');
}

function commentChange()
{
  var val = $( this ).val();
  var id = $( this ).data('id');
  
      $.post(
  "/project/reports/constructors_project_tasks/constructors_project_task_update_AJAX.php",
  {
    val : val,
    id : id
  },
  function( data )
    {
//      alert( data );
    }
   );

  
}

function dateChange()
{
  var date = $( this ).val();
  var year  = date.substr(0,4) ;
  var month = date.substr(5,2) ;
  var day  = 1 ; 
  
  var monthes = ['€нварь','февраль','март','апрель','май','июнь','июль','август','сент€брь','окт€брь','но€брь','декабрь'];
  var cur_month = monthes[ 1 * month - 1 ];

  $('#title').text('ѕлан работ конструкторского отдела на ' + cur_month + ' ' + year + 'г.');

  if( date == '')
  {
    $('#prod_shift_report').empty();
    $('#title').text('');	
    return ;
  }
    $.post(
  "/project/reports/constructors_project_tasks/constructors_project_tasks_AJAX.php",
  {
    date : date 
  },
  function( data )
    {
//        $('#seldiv').addClass('hidden');
        $('#constructors_project_tasks').empty().append( data );
        $('.alink').removeClass('hidden');
        $('.task_comment').unbind('keyup').bind('keyup', commentChange );

        $( '.nameField' ).each( function( index, element )
                {   
                  var id = $( element ).data('id');
                  $('[data-parent_id = ' + id + ']:even').addClass('even').removeClass('odd');
                });

        $('#print_link').attr('src','/print.php?do=show&formid=236&p0=' + date );
//        alert( $('#print_link').length );


    }
   );
  
}