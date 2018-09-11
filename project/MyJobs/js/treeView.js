// Действия после загрузки страницы : 
$(function()
{ 
 // привязываем функцию  нажатия на строку с классом "collapsed"
  $('tr .collapsed').click(function () 
  { 
   var name = $(this).attr('name'); // name нажатой строки
   var id = $(this).attr('id');     // id нажатой строки    

//    alert( 'id : ' + id + '\nname : ' + name );
     
    if( $( "tr[name='" + id + "']" ).css('display') === 'none' )   // Уже скрыта ?
    {

       $( "tr[name='" + id + "']" ).css('display','table-row') ;   // Да, отображаем
         
       // Меняем '+' на '-' у нажатой строки
       $( "td[name='" + id + "'] span.collspan " ).html('&#9660;');       
    }
       else
       {
          $( "tr[name^='" + id + "']" ).css('display','none'); // Нет, скрываем строки
          // Меняем '-' на '+'
          $( "td[name^='" + id + "'] span.collspan" ).html('&#9658;') ;// &#9675
          
          
       }
  }); 
}); 
