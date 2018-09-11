function BubbleStop() 
{
    event = event || window.event // кросс-браузерно
     
    if ( event.stopPropagation ) 
    {
        // Вариант стандарта W3C:
        event.stopPropagation()
    } else 
    {
        // Вариант Internet Explorer:
        event.cancelBubble = true
    }
}    


$( function()
{
   
  $('.show_hide').css('border-right-width', '0' );
  $('.after_show_hide').css('border-left-width', '0' );
  $('td.AR, td.AC, td.AL').addClass('field');
    
// Если user не vj;tn ничего добавлять удяляем кнопку 'добавить'
    if( can_add )
    {
       $('div.add_img_div').each(function( index, element )
            {
                var el = $(element);
                var id = el.attr('id');
                var name = el.attr('name');
                if( name == 'order_group')
                    el.html("<img name='order_group' data-title='Добавить новое задание' id='" + id + "' class='add_img_div_img' src='/uses/plus.png'/>");
                if( name == 'order')
                    el.html("<img name='order' data-title='Добавить подчиненное задание' id='" + id + "' class='add_img_div_img' src='/uses/plus.png'/>");           
            });

        $('.add_img_div_img').click(function () 
        { 
            BubbleStop();
            alert($(this).attr('id'));
        });

        $('.add_img_div_img').mousemove(function () 
        { 
//            $(this).attr('src','/uses/plus_green.png');
            $(this).css( { 'background':'yellow'} );
        });
        
        $('.add_img_div_img').mouseout(function () 
        { 
//            $(this).attr('src','/uses/plus.png');
            $(this).css( { 'background':''} );
        });
        
        
        
    }
         else
           $('.add_img_div').remove();

    $( document ).tooltip();

});
    