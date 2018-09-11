// ********************************************************************************************
// Оптимизированная версия. Учитывается только высота строки.
// Заранее задается контейнер и класс проверяемой строки

function isElementInViewport( element )
{
    var elementRect = $( element )[0].getBoundingClientRect();
    var parentRect = $( '.center' )[0].getBoundingClientRect();

            return (
                elementRect.top >= parentRect.top &&
                elementRect.bottom <= parentRect.bottom
            );
}

function visible_row_check()
{
    var tr = $('tr.zak_row');
    var tr_len = tr.length ;
    var visible_row = [],i, cur;

    for (  i = 0; i < tr_len ; i ++ )
    {
        var cur = tr[ i ];
        if ( isElementInViewport( cur ) )
            visible_row.push( cur.id );
    }
    return visible_row;
}

// ********************************************************************************************
// Общий случай
// function isElementInViewport( par , el )
// {
//     var elRect = $( el )[0].getBoundingClientRect();
//     var parRect = $( par )[0].getBoundingClientRect();

//     return (
//         elRect.top >= parRect.top &&
//         elRect.left >= parRect.left &&
//         elRect.bottom <= parRect.bottom &&
//         elRect.right <= parRect.right
//     );
// }

// function check()
// {
//     var container = $( '.center' );
//     var tr = container.find('tr');
//     var tr_len = tr.length ;
//     var visible_row = [],i, cur;

//     for (  i = 0; i < tr_len ; i ++ )
//     {
//         var cur = tr[ i ];
//         if ( isElementInViewport( container, cur ) )
//             visible_row.push( cur.id );
//     }
//     console.log("Visible rows:", visible_row.join(", "));
//     return visible_row;
// }
