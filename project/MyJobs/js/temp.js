// Подавление распостранения события
function BubbleStop() 
{
    event = event || window.event // кросс-браузерно
     
    if ( event.stopPropagation ) 
        // Вариант стандарта W3C:
        event.stopPropagation();
else 
    {
        // Вариант Internet Explorer:
        event.cancelBubble = true;
    }
}    
