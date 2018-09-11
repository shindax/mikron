function scrolify( selector , height )
{
    var oTbl = $( selector )
   
    // для очень длинных таблиц вы можете удалить 4 следующие линии
    // и поместить таблицу в ДИВ и назначить ему высоту и свойство overflow
    var oTblDiv = $("<div id='outer_det_table_div'/>");
    oTblDiv.css( { 'height': height ,'overflow-y':'auto', 'overflow-x':'hidden' , 'width' : oTbl.width() - 1 } );
    oTbl.wrap(oTblDiv);
    
    // сохраняем оригинальную ширину
    oTbl.attr("data-item-original-width", oTbl.width());
    
    oTbl.find('thead tr td').each(function()
    {
        $(this).attr("data-item-original-width",$(this).width());
    });
    
    oTbl.find('tbody tr:eq(0) td').each(function()
    {
        $(this).attr("data-item-original-width",$(this).width());
    });
    
    // клонируем оригинальную таблицу
    var newTbl = oTbl.clone();
    
    // удаляем заголовки из оригинальной таблицы
    oTbl.find('thead tr').remove();
    
    // удаляем тело таблицы из новой таблицы
    newTbl.find('tbody tr').remove();
    
    oTbl.parent().parent().prepend(newTbl);
    newTbl.wrap("<div />");
    
    // заменяем исходную ширину столбца
    newTbl.width(newTbl.attr('data-item-original-width') );
    
    newTbl.find('thead tr td').each(function()
    {
        $(this).width($(this).attr("data-item-original-width"));
    });
    
    oTbl.width(oTbl.attr('data-item-original-width'));
    
    oTbl.find('tbody tr:eq(0) td').each(function()
    {
        $(this).width($(this).attr("data-item-original-width"));
    });
}
