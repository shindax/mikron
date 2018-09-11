function insertOneRowAfter( ) 
{
  if( $('#temp_row' ).length )
    return ;

  var el = $( this ) ; // ������� �������
 
  var user_id = el.data('userid');
  var proj_id = el.data('projid');
  var level = el.data('level');
  var in_name = el.data('inname');  
  var row_id = el.data('rowid');    

 BubbleStop();
 var row_index = el.parent().index();
 var id = el.parent().attr('id'); // id ������� ������  
 var list_len = $( "tr[name='" + id + "']" ).length; //
   
 var id = el.parent().attr('id'); // id ������� ������  
 var offset = el.next().css('padding-left'); // �������� �������� ���������� ��������
 var parent_id = el.attr('id') ; // id �������� �������

// ���� ��������� ������ ������, �� ���������� ��
    if( $( "tr[name='" + id + "']" ).css('display') === 'none' )   // ��� ������ ?
    {
       $( "tr[name='" + id + "']" ).css('display','table-row') ;   // ��, ����������
       // ������ '+' �� '-' � ������� ������
       $( "td[name='" + id + "'] span.collspan " ).html('&#9660;');       
    }
 
$.post(
  "project/MyJobs/InsertOneRowAJAX.php",
  {
    proj_id   : proj_id ,
    user_id   : user_id ,
    parent_id : parent_id,
    offset    : offset,
    level     : level,
    in_name   : in_name,
    row_id    : row_id
  },
  insertOneRowAfterServerResponse
);    

}

// �������� ������ ������
function insertOneRowAfterServerResponse( data )
{
   var row = $( data );
   var id = row.data('rowid')  
   var name = id ;
//   row.attr({ 'data-name' : name }).attr({ 'data-id' : id + Math.random() });
   row.attr({ 'data-id' : id + Math.random() });

// ������ ���������� � ������� ������    
   var list = $( "tr[name^='" + id + "']" );

   if( list.length )
    {
       row.attr({'data-lastchild':0 });
       list.last().after( row );
    }
   else
    {
       row.attr({'data-lastchild':1 });
       $("tr[id='" + id + "']").after( row );
    }
    
    $( ".combobox" ).combobox();
    $( ".custom-combobox-input" ).css('width','100px');
    $( "ul.ui-autocomplete" ).addClass('scroll');

    $('img#del_one_row_button').click( function() { $('#temp_row' ).remove();} );

    $('.one_row_data').blur( OneRowInsertCheck );
    $('.one_row_data').change( function(){ $("#onerowbutton").prop("disabled", true ); } );
    $('#one_row_order_name').focus();
    $( ".ui-widget" ).autocomplete({ change: OneRowInsertCheck });

}
