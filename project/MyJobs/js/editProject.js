$( function()
{
   $('#edit_project_end_date').blur( edit_project_end_date );
   $('#edit_project_beg_date').unbind('change').bind('change', edit_project_beg_date_change );  
   $('#edit_project_end_date').unbind('change').bind('change', edit_project_end_date_change ); 
   
   $('#date_change_comment').val('');
});

   function edit_project_end_date()
{
  var proj_beg_date = $('#edit_project_end_date').attr('data-proj_beg');
  var proj_max_date = $('#edit_project_end_date').attr('min');  
  var cur_value = $('#edit_project_end_date').val();  
 
  if( cur_value.length > 0 )
    if( ! ( CompareDate( proj_beg_date, cur_value  ) == -1 && CompareDate( proj_max_date, cur_value ) <= 0 ) )
        {
          alert( 'Неверное значение даты.\nДата окончания проекта не может быть меньше даты начала проекта\nи меньше даты окончания одного из заданий проекта.' );
          $('#edit_project_end_date').val('');
        }
}

function date_conv( val , step )
{
 var date = new Date( val );
 var norm_date = ( ( date.getDate() + step ) + '.' + ( date.getMonth() + 1 ) + '.' + date.getFullYear() );
 var dash_date = ( date.getFullYear() + '-' + ( date.getMonth() + 1 ) + '-' + ( date.getDate()  + step ) );
 return { norm_date, dash_date };
  
}


function edit_project_beg_date_change()
{
 var val = $( this ).val();
 var val_str = date_conv( val , 2 );

 $('#edit_project_end_date').attr( { 'min': val_str.dash_date , 'title' : 'Дата окончания проекта\nминимальная дата : ' + val_str.norm_date });
}

function edit_project_end_date_change()
{
 var val = $( this ).val();
 var val_str = date_conv( val , -1 ); 
 $('#edit_project_beg_date').attr( { 'max': val.dash_date , 'title' : 'Дата начала проекта\nмаксимальная дата : ' + val_str.norm_date  });
}

function CompareDate( d1, d2 )
{
    var year  = d1.substr(0,4) ;
    var month = d1.substr(5,2) ;
    var day  = d1.substr(8,2) ;    
    var date1 = new Date( year, month, day );

    year  = d2.substr(0,4) ;
    month = d2.substr(5,2) ;
    day  = d2.substr(8,2) ;    
    var date2 = new Date( year, month, day );
    
    if( ( date2 - date1 ) == 0 )
        return 0 ;
    
    return ( date1 - date2 ) > 0 ? 1 : -1 ;
}
