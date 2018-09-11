// ���������� �������������� �������
function BubbleStop() 
{
    event = event || window.event // �����-���������
     
    if ( event.stopPropagation ) 
    {
        // ������� ��������� W3C:
        event.stopPropagation()
    } else 
    {
        // ������� Internet Explorer:
        event.cancelBubble = true
    }
}    


function status_checkbox_changed()
{
    event.stopPropagation();
    
    if( ! confirm('�� ������������ ������ �������� ������ ���������� �������?') )
      return ;
    
    var el = $( this );
    var id = el.data('id');
    var user_name = el.data('user_name'); 
    var state = el.val();
    var checked_elements_list = $('select.ch_status_select');

    if( $( checked_elements_list ).length > 1 ) // ���� ������� ��������� �����
       if ( ! confirm('�������� ����� 1 �������, ���� � ��� �� ���������� ���� �� ��������� ������� �� ���, ��� �� ������, �� ������ ��� ������� ��������� �������!\n\n����������?\n'))
          {
            var ch_state_checkbox_list = $('input.ch_status_checkbox');
                $( ch_state_checkbox_list ).each( function( index, element )
                {   
                    $( element ).prop('checked', false );
                    
                    var id = $( element ).data('id');
                    var cell = $( '#state_cell_' + id );
                    var state = cell.data('state');
                        $( cell ).html('');
                        $( cell ).html( state );
                });
            
            return ;
          }
  
    var p1 = '?p1=';
    var p2 = '&p2='+ state ;    
    var p3 = '&p3=' + user_name ;


    // ������������ ������ ���������� �����
    $( checked_elements_list ).each( function( index, element )
    {   
      var id = $( element ).data('id');
      var cell = $( '#state_cell_' + id );
      var el_state = cell.data('state');
      var ch = 0 ;

//    '�����', '�����������', '������� � ����������', '���������', '�������', '�� ���������', '������������', '���������'];
        
        
        switch( state ) // �������� ������ ���������
        {
          case '������� � ����������' : if( el_state == '�����' || el_state == '�����������' || el_state == '�� ���������' )
                                            ch = 1 ; 
                                        break ;
         
          case '�������'              : if( el_state == '���������' )
                                            ch = 1 ; 
                                        break ;
          
          case '���������'            : if( el_state == '�������' )
                                            ch = 1 ; 
                                        break ;
                                        
          case '���������'            : if( 
                                            el_state == '�����'                || el_state == '�����������' || 
                                            el_state == '������� � ����������' || el_state == '�� ���������' 
                                           )
                                            ch = 1 ; 
                                        break ;

          case '�� ���������'         : if( el_state == '���������' )
                                            ch = 1 ; 
                                        break ;
          
          case '������������'         : ch = 1 ; 
                                        break ;
        }
       
        if( ch )
            p1 += $( element ).data('id') + '|';                   
    });

    var addr = 'project/itrzadan_mult_status.php' + p1 + p2 + p3 ;

// AJAX 

		var req = getXmlHttp();
		req.onreadystatechange = function() 
		{
			if (req.readyState == 4 )
				if(req.status == 200 ) 
					location.href = document.location;
		}

		req.open( 'GET', addr, true );
		req.send(null);

// AJAX 
  
		// �������� ��������		
		location.href = document.location;
  
}

function status_checkbox_click()
{
    event.stopPropagation();
}

function change_status_checkbox()
{
    event.stopPropagation();

    var el = $(this);
    var id = el.data('id');

    var cell = $( '#state_cell_' + id );
    var state = cell.data('state');

    if( state == '������������' || state == '���������' )
           {
                $( el ).prop('checked', false );
                return ;                
           }

    if( el.prop('checked') ) // Checkbox �������, ��������� <select>
   {
    var user_id     = el.data('user_id');
    var creator_id  = el.data('creator_id');
    var executor_id = el.data('executor_id');
    var checker_id  = el.data('checker_id');    
    var inlist      = el.data('inlist');    
    var user_name   = el.data('user_name');    
   
//    var state_arr = ['�����', '�����������', '������� � ����������', '���������', '�������', '�� ���������', '������������', '���������'];

    var state_arr = [];

    if( user_id == executor_id )
      {
        switch( state )
        {
          case '�����': 
          case '�� ���������':
          case '�����������':
                                state_arr.push('������� � ����������');
                                state_arr.push('���������');
                                break ;
          case '������� � ����������': 
                                state_arr.push('���������');
                                break ;
          case '���������': 
                                break ;
        }
      }

    if( user_id == checker_id )
      {
        switch( state )
        {
          case '���������': 
                            state_arr.push('�� ���������');      
                            state_arr.push('�������');
                            break;
        }
      }

    if( user_id == creator_id )
      {
        state_arr.push('������������');
        
        switch( state )
        {
          case '�������': 
                            state_arr.push('�� ���������');                
                            state_arr.push('���������'); 
                            break;
        }
      }
    
      var select = '<select class=\"ch_status_select\" data-user_name= \"' + user_name + '\" data-id=\"' + id + '\" id=\"ch_status_select_' + id + '\" style=\"width:90%\"><option selected>' + state + '</option>';
    
      state_arr.forEach( 
                          function( item, i, arr ) 
                            {
                                select += '<option>' + item + '</option>' ;
                            }
                       );
    
      select += '</select>';

     
      $( cell ).html( select );
      $( 'select#ch_status_select_' + id ).change( status_checkbox_changed ).click( status_checkbox_click );
   }
   else 
   {
       $( cell ).html('');
       $( cell ).html( state );
   }
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


function DateFormat( d1 ) 
{
    var year  = d1.substr(0,4) ;
    var month = d1.substr(5,2) ;
    var day  = d1.substr(8,2) ;    
    return day + '.' + month + '.' + year ;
}

function OneRowInsertCheck() 
{
    var ord_name = $("#one_row_order_name").val();
   
    var date_of_beg_plan  = $("#one_row_date1").val() ;
    var date_of_perf_plan = $("#one_row_date2").val() ;
   
//    var executor = $("#one_row_executor").val() ;

   var executor = $('ul#one_row_executor_ul li');

   var checker = $("#one_row_checker").val() ;

   var min_date = $("#one_row_date1").attr('min');
   var max_date = $("#one_row_date1").attr('max');
   var title = "����������� ����: " + DateFormat( min_date ) + "\n������������ ���� : " + DateFormat( max_date );
    
    if( CompareDate( min_date, date_of_beg_plan ) > 0 || CompareDate( date_of_beg_plan, max_date ) > 0 )
      {
        $("#one_row_date1").addClass('one_row_data_err') ;
        $("#one_row_date1").val('') ;
        $("#one_row_date1").attr('title', title ) ;
      }
        else
        {
          $("#one_row_date1").attr('title','') ;                          
          $("#one_row_date1").removeClass('one_row_data_err') ;
          $("#one_row_date1").attr('title', title ) ;          
          title = "����������� ����: " + DateFormat( date_of_beg_plan  ) + "\n������������ ���� : " + DateFormat( max_date );
          $("#one_row_date2").attr('title', title ) ;
          $("#one_row_date2").attr('min', date_of_beg_plan ) ;
        }

    if( 
        CompareDate( min_date, date_of_perf_plan ) > 0 // ���� ����������� ���� ������ ��������
        || 
        CompareDate( date_of_perf_plan, max_date ) > 0 // // ���� ������������ ���� ������ ��������
        ||
        CompareDate( date_of_beg_plan, date_of_perf_plan ) > 0 // ���� ������ ������ ���� ���������
      )
      {
        $("#one_row_date2").addClass('one_row_data_err') ;
        $("#one_row_date2").val('') ;
//        $("#one_row_date2").attr('title','������� ���������� ������') ;        
      }
        else
          {
            title = "����������� ����: " + DateFormat( date_of_beg_plan  ) + "\n������������ ���� : " + DateFormat( max_date );
            $("#one_row_date2").attr('title', title ) ;
            $("#one_row_date2").removeClass('one_row_data_err') ;
          }

      
// ���� ���� �� ����� ������, �� �����
    if( 
            ( ord_name.length ) &&
            ( ! $("#one_row_date1").hasClass('one_row_data_err') ) &&
            ( ! $("#one_row_date2").hasClass('one_row_data_err') ) &&
            ( executor.length ) &&
            ( checker.length )
      )
    $("#onerowbutton").prop("disabled", false );
        else
            // �� ���������, ���� ���������� ������
            $("#onerowbutton").prop("disabled", true );
            
}

function ZakView( link ) 
{
    event.stopPropagation();
    window.open( "/index.php?do=show&formid=39&id=" + link );
}    


function collapsed_proj_row_click_link()
{
// ����������� ��������� ������ ��� ��������   
 // ����������� �������  ������� �� ������ � ������� "collapsed_proj_row"
   var name = $(this).attr('name'); // name ������� ������
   var dataname = $( this).data('name');   
   var id = $(this).attr('id');     // id ������� ������    
      
    if( $( "tr[name='" + id + "']" ).css('display') === 'none' )   // ��� ������ ?
    {

       $( "tr[name='" + id + "']" ).css('display','table-row') ;   // ��, ����������
         
       // ������ '+' �� '-' � ������� ������
       $( "td[name='" + id + "'] span.collspan " ).html('&#9660;');
       $('.prj_ord_sort_img', $( this )).removeClass('hidden');
      
//      alert( $( 'tr[data-name="'+ dataname + '"]:visible').length + ' - ' + $( 'tr[data-name="'+ dataname + '"]').length + ' - ' + $('img.coll_exp[data-id^="' + id + '"]').length );
      
      if( $( 'tr[data-name="'+ dataname + '"]:visible').length == $( 'tr[data-name="'+ dataname + '"]').length )
        $('img.coll_exp[data-name="' + dataname + '"]').attr({ 'data-state' : 1 , 'src' : 'uses/expand.png','title': '������� ��� ������� ��������' } );
    
    }
       else
       {
          $( "tr[name^='" + id + "']" ).css('display','none'); // ���, �������� ������
          // ������ '-' �� '+'
          $( "td[name^='" + id + "'] span.collspan" ).html('&#9658;') ;// &#9675
          $('.prj_ord_sort_img', $( this )).addClass('hidden');
          
      if( $( 'tr[data-name="'+ dataname + '"]:visible').length != $( 'tr[data-name="'+ dataname + '"]').length )
          $('img.coll_exp[data-name="' + dataname + '"]').attr({ 'data-state' : 0 , 'src' : 'uses/collapse.png','title': '������� ��� ������� ��������' } );
          
       }
  }


function save_context()
{
      var page_inner = $('#project_div').html();
      localStorage.setItem("cur_proj_screen", page_inner );
     
      var one_row_order_name = $('input#one_row_order_name').val() ;
      localStorage.setItem("one_row_order_name", one_row_order_name );

      var one_row_date1 = $('input#one_row_date1').val() ;
      localStorage.setItem("one_row_date1", one_row_date1 );

      var one_row_date2 = $('input#one_row_date2').val() ;
      localStorage.setItem("one_row_date2", one_row_date2 );
      
      var one_row_text_area = $('textarea#onerowtextarea').val() ;
      localStorage.setItem("one_row_text_area", one_row_text_area );

      var executor = $("#one_row_executor").val() ;    
      localStorage.setItem("executor", executor );

      var checker = $("#one_row_checker").val() ;        
      localStorage.setItem("checker", checker );
}

function proj_img_click()
{ 
      event.stopPropagation();

      save_context();

      var row_id = $(this).attr('id') ;

//������������ ���� �� ��������      
      var maxdata = $(this).attr('data-maxdate') ;
      
      localStorage.setItem("row_id", row_id );
      
//      var loc = location.origin + '/index.php?do=show&formid=' + edit_project_page + '&id=' + row_id + '&mindate=' + maxdata;
      var loc = location.origin + '/index.php?do=show&formid=' + edit_project_page + '&id=' + row_id ;
      location.href = loc;
}

  
function link_click()
{ 
      event.stopPropagation();

      save_context();          
      var row_id = $(this).attr('data-id') ;
      localStorage.setItem("row_id", row_id );
}  

function coll_exp_img_click()
{
    event.stopPropagation();

    var img = $( this );
    var tr = $( $( img ).parent().get( 0 ) ).parent().get( 0 );
    var name = $( tr ).data('name');
    var str = "tr[data-name='" + name + "']" ;
    var state = + $( img ).attr('data-state');
    var display, arrow ;
    var id = $( this ).parent().parent().attr('id');
    
    if( state ) // ��� �������, ���������
    {
    
// ��������������� ����������� ������� �����    
        if( rows_arr[ id ] )
     {
          var rows = rows_arr[ id ];
          $('tr[id="' + id + '"]').after( rows );
          rows_arr[ id ] = 0 ;
          MoveSortImages( id, rows );
          $( 'img.prj_ord_sort_img[data-id="' + id + '"]').attr({ 'src':'project/img5/c1.gif','val':'0'});
     }
      
       $( img ).attr( {'src':'uses/collapse.png', 'data-state':'0' }).attr('title','������� ��� ������� �������');
       display = 'none';
       arrow = '&#9658;'
       $('.prj_ord_sort_img', $(tr)).addClass('hidden').attr({'src':'project/img5/c1.gif', 'val': 0 }) ;;
    }
    else// �������, ���������
    {
        $( img ).attr( {'src':'uses/expand.png', 'data-state': '1' }).attr('title','������� ��� ������� �������');
        display = 'table-row' ;
        arrow = '&#9660;' ;
        $('.prj_ord_sort_img', $(tr)).removeClass('hidden');        
        var rows = $('tr[name^="' + id + '"]');
        MoveSortImages( id, rows );
    }

    // ��������� ���������
    $( str ).each( function( index, element )
    {   
        $( element ).find('span.collspan').html( arrow );
        $( element ).css( 'display' , display );
    });
    
    // ������� ������ '������' ������ ������������
    $( tr ).css( 'display' , 'table-row' );    
}

function UI_adjust()
{ 
    $(window).keydown(function(event)
    { //����� ������� ������� �������
    if( event.keyCode == 27 && $('#temp_row' ).length )
      { //���� ��� Esc
        $('#temp_row' ).remove();
      } 
    }); 
   
$('.edit_project_img').unbind( 'click').bind('click', proj_img_click );
$('.link').unbind( 'click').bind('click', link_click );
$('tr.collapsed_proj_row').unbind( 'click').bind('click', collapsed_proj_row_click_link );
$('img.coll_exp').unbind( 'click').bind('click', coll_exp_img_click );

  $('td.coll_edited' ).unbind( 'click').bind('click', function () 
  { 
   var name = $(this).attr('name'); // name ������� ������
   var id = $(this).attr('id');     // id ������� ������    
     
    if( $( "tr[name='" + id + "']" ).css('display') === 'none' )   // ��� ������ ?
    {

       $( "tr[name='" + id + "']" ).css('display','table-row') ;   // ��, ����������
       // ������ '+' �� '-' � ������� ������
       $( "td[name='" + id + "'] span.collspan " ).html('&#9660;');       
      
    }
       else
       {
          $( "tr[name^='" + id + "']" ).css('display','none'); // ���, �������� ������
          
          // ������ '-' �� '+'
          $( "td[name^='" + id + "'] span.collspan" ).html('&#9658;') ;// &#9675
          
       }
  }); 
   
    if( ! can_add )
    {
        $('.show_hide').css('display', 'none' );
        $('.new_proj_link').remove();
    }
    
    $('.show_hide').css( {'cursor':'pointer','padding-left':'3px'} );
    $('.show_hide').unbind( 'click').bind('click', insertOneRowAfter );
    $('td.AR, td.AC, td.AL').addClass('field');
} 

// �������� ����� �������� �������� : 
$( function()
{

    var key = localStorage.getItem("cur_proj_screen");
              localStorage.removeItem("cur_proj_screen");

    if( key )
         $('#project_div').html( key );

// �������������� �������� ��������� ������ �������� ������� 

    var order_name = localStorage.getItem("one_row_order_name");
              localStorage.removeItem("one_row_order_name");
    if( order_name )
         $('input#one_row_order_name').val( order_name );

    var one_row_date1 = localStorage.getItem("one_row_date1");
              localStorage.removeItem("one_row_date1");
    if( one_row_date1 )
         $('input#one_row_date1').val( one_row_date1 );

    var one_row_date2 = localStorage.getItem("one_row_date2");
              localStorage.removeItem("one_row_date2");
    if( one_row_date2 )
         $('input#one_row_date2').val( one_row_date2 );


    var one_row_text_area = localStorage.getItem("one_row_text_area");
              localStorage.removeItem("one_row_text_area");
    if( one_row_text_area )
         $('textarea#onerowtextarea').val( one_row_text_area );

// ************************************************************************************************

    var row_id = localStorage.getItem("row_id");
                 localStorage.removeItem("row_id");
              
    var cell_state = localStorage.getItem("cell_state");
                     localStorage.removeItem("cell_state");
                     
    var cell_new_date = localStorage.getItem("cell_new_date");
                        localStorage.removeItem("cell_new_date");
    
    var cell_new_comment = localStorage.getItem("cell_new_comment");
                           localStorage.removeItem("cell_new_comment");

    if( ( cell_new_comment !== null ) && cell_new_comment != 'undefined' && cell_new_comment != undefined )
      $('#comment_cell_' + row_id ).text( cell_new_comment );
    
    var cell_state_class = '';
    
    if( row_id != null && cell_state != '' && cell_state !== null && cell_state != 'undefined' )
       {
       var td = $('#state_cell_' + row_id );

    // 1 - ������� � ���������� 
    // 2 - ���������
    // 3 - �������
    // 4 - ��������� �� ���������
    // 6 - ���������

         if( cell_state )
         {
            td.removeAttr('class').attr('class', 'field AC');
            
            switch( cell_state )
                {
                    case '������� � ����������': 
                            cell_state_class = 'state_accepted_to_work'; 
                            break ;
                    case '���������': 
                            cell_state_class = 'state_completed'; 
                            if( cell_new_date )
                            {
                                 var td_date = $('#state_exec_cell_' + row_id );
                                 td_date.text( cell_new_date );
                             }
                            break ;
                    case '�������': 
                            cell_state_class = 'state_accepted'; 
                            break ;
                    case '�� ���������': 
                            cell_state_class = 'state_rework'; 
                            break ;
                    case '������������': 
                            cell_state_class = 'state_annulated'; 
                            break ;
                    case '���������': 
                            cell_state_class = 'state_executed';
                            if( cell_new_date )
                            {
                                 var td_date = $('#state_exec_cell_' + row_id );
                                 td_date.text( cell_new_date );
                             }
                            break ;
                   case '�����������' :
                   default            :
                   case null          :
                   case 'null'        : 
                                cell_state_class = 'state_viewed';
                                break ;
                }
            }
                    td.addClass( cell_state_class );
                    td.text( cell_state );
            }

    UI_adjust ();
   
    $( "#one_row_executor" ).combobox();
    $( "#one_row_checker" ).combobox();
    $( ".custom-combobox-input" ).css('width','90px');
    $( "ul.ui-autocomplete" ).addClass('scroll');

 // ������� ������ combobox �� jquery-ui ???    
    
    var list = $('span.custom-combobox');
   $( list ).eq(1).remove();
   $( list ).eq(3).remove();   
    
   var executor = localStorage.getItem("executor");
              localStorage.removeItem("executor");
              
   if( executor )
   {
        var executor_name = $("#one_row_executor option[value=" + executor + "]").text() ;                   
        $("#one_row_executor").val( executor );
        $('div#ui-widget_executor span.custom-combobox input').val( executor_name );
   }

  var checker = localStorage.getItem("checker");
                localStorage.removeItem("checker");
              
   if( checker )
    {
        var checker_name = $("#one_row_checker option[value=" + checker + "]").text() ;        
        $("#one_row_checker").val( checker );
        $('div#ui-widget_checker span.custom-combobox input').val( checker_name );
    }

    if( $('#temp_row' ).length )
        OneRowInsertCheck();

   $('img#del_one_row_button').unbind( 'click').bind('click', function() { $('#temp_row' ).remove();} );
   $('.one_row_data').blur( OneRowInsertCheck );
   $('.one_row_data').change( function(){ $("#onerowbutton").prop("disabled", true ); } );
   $('#one_row_order_name').focus();

   $( ".ui-widget" ).autocomplete({ change: OneRowInsertCheck });
   $('.ui-widget').blur( OneRowInsertCheck );

   $('input.ch_status_checkbox').unbind( 'click').bind('click', change_status_checkbox );
   $('.prj_coll_all_img').unbind('click').bind( 'click', collapse_all_projects );         

});


function collapse_all_projects()
{
 
  var img_list = $('img.coll_exp');

  
  if( $( this ).attr('data-state') == '1' ) // open
  {
    $( this ).attr({ 'data-state' : '0' , 'src' : 'uses/collapse.png','title':'������� ��� ������� ��������' } );  

    $( img_list ).each( function( index, element )
    {   
      if( $( element ).attr('data-state' ) == '1') 
          element.click();
    });
    
  }
  else // close
  {
    $( this ).attr({ 'data-state' : 1 , 'src' : 'uses/expand.png','title': '������� ��� ������� ��������' } );

    $( img_list ).each( function( index, element )
    {   
      if( $( element ).attr('data-state' ) == '0') 
          element.click();
    });
  }

}