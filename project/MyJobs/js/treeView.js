// �������� ����� �������� �������� : 
$(function()
{ 
 // ����������� �������  ������� �� ������ � ������� "collapsed"
  $('tr .collapsed').click(function () 
  { 
   var name = $(this).attr('name'); // name ������� ������
   var id = $(this).attr('id');     // id ������� ������    

//    alert( 'id : ' + id + '\nname : ' + name );
     
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
}); 
