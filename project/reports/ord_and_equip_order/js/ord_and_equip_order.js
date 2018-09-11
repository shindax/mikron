var sortFunc ;

function doSortSelectBySernum ( select_id )
{
    var sortedVals = $.makeArray($( select_id +' option')).sort(function(a,b)
    {
        return $(a).data('sernum') > $(b).data('sernum') ? 1 : $(a).data('sernum') < $(b).data('sernum') ? -1 : 0 ;
    });
    $( select_id ).empty().html( sortedVals );
}

function doSortSelectByFullOrderName ( select_id )
{
    var sortedVals = $.makeArray($( select_id +' option')).sort(function(a,b)
    {
        return $(a).html() > $(b).html() ? 1 : $(a).html() < $(b).html() ? -1 : 0 ;
    });
    $( select_id ).empty().html( sortedVals );
}


function GetSelectedOrdersList()
{
    var list = [];
    $('#sel_orders option').each(
        function( index )
        {
            list[ index ] = $( this ).val() ;
        }
    );

    return list ;
}

$( function()
{
    $('#all_button').bind('click',AllButtonClick);
    $('#sel_button').bind('click',SelButtonClick);
    $('#query_button').bind('click',QueryButtonClick);
    $('#clear_button').bind('click',ClearButtonClick);

    $('#all_orders').bind('dblclick',AllButtonClick);
    $('#sel_orders').bind('dblclick',SelButtonClick);
    $('tr.ord_row').unbind('click').bind('click',OrdRowClick);
    sortFunc = doSortSelectBySernum;
    sortFunc( '#all_orders' );
});

function OrdRowClick()
{
    var park_id = $( this ).data('id');
    var list = GetSelectedOrdersList();

    $('#eq_tbl .ord_row').removeClass('selected');
    $( this ).addClass('selected');

// Отправляем запрос
    $.ajax({
        url: '/project/reports/ord_and_equip_order/ajax.get_equip_distribution.php',
        type: 'POST',
        data : {
            "list" : list,
            "park_id"   : park_id
        },
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
        {
            // Если все ОК
            if( typeof respond.error === 'undefined' )
            {
//                alert( respond.length );

                var str = '';
                var total = 0 ;
                var len = respond.length;
                respond.forEach(
                    function( item, i, arr )
                    {
                        var id = arr[i]["id"];
                        var ord = $("option[value='" + id + "']").html();
                        total += arr[i]["norm"];
                        str += "<tr class='park_row'>";
                        str += "<td class='Field'>" + ord + "</td>";
                        str += "<td class='Field AC'>" + arr[i]["norm"] + "</td>";
                        str += "<td class='Field AC'>" + arr[i]["perc"] + "%</td>";
                        str += "</tr>";
                    });
                    
                    if( len > 1 )
                    {
                        str += "<tr class='park_row'>";
                        str += "<td class='Field'>Итого</td>";
                        str += "<td class='Field AC'>" + total.toFixed(2) + "</td>";
                        str += "<td class='Field AC'>100%</td>";
                        str += "</tr>";
                     }

                $('#dist_tbl .park_row').remove();
                $('#dist_tbl').append( $( str ) );

            }
            else
            {
                console.log('AJAX request errors detected. Server said : ' + respond.error );
            }
        },
        error: function( jqXHR, textStatus, errorThrown )
        {
            console.log('AJAX request errors in coop_orders.js detected : ' + textStatus + errorThrown );
        }
    });

}

function QueryButtonClick( event )
{
    event.preventDefault();
    var list = GetSelectedOrdersList();

// Отправляем запрос
    $.ajax({
        url: '/project/reports/ord_and_equip_order/ajax.get_equip.php',
        type: 'POST',
        data : {
            "list" : list
        },
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
        {
            // Если все ОК
            if( typeof respond.error === 'undefined' )
            {
                var str = '';
                respond.forEach(
                    function( item, i, arr )
                    {
                     if( ( arr[i]["name"] ).length )
                      {
                        str += "<tr class='ord_row' data-id='" + arr[i]["id"] + "'>";
                        str += "<td class='Field AC'>" + arr[i]["line"] + "</td>";
                        str += "<td class='Field'>" + arr[i]["name"] + " - " + arr[i]["type"] + "</td>";
                        str += "<td class='Field AC'>" + arr[i]["norm"] + "</td>";
                        str += "</tr>";
                      }
                    });

                $('#eq_tbl .ord_row').remove();
                $('#eq_tbl').append( $( str ) );
                $('tr.ord_row').unbind('click').bind('click',OrdRowClick);
            }
            else
            {
                console.log('AJAX request errors detected. Server said : ' + respond.error );
            }
        },
        error: function( jqXHR, textStatus, errorThrown )
        {
            console.log('AJAX request errors in coop_orders.js detected : ' + textStatus + errorThrown );
        }
    });

}

function ClearButtonClick( event )
{
    event.preventDefault();
    $('#all_orders').append( $("#sel_orders option") );
    sortFunc( '#all_orders' );
    $("option[data-sernum=1]").attr('selected','selected');
    $("#query_button").attr('disabled','disabled');
    $("#clear_button").attr('disabled','disabled');

    $('#eq_tbl .ord_row').remove();
    $('#dist_tbl .park_row').remove();
}

function AllButtonClick( event )
{
    event.preventDefault();
    var all_sel = $('#all_orders').val();

    $('#eq_tbl .ord_row').remove();
    $('#dist_tbl .park_row').remove();

    if( all_sel )
    {
        var option = $("option[value='" + all_sel + "']");
        $('#sel_orders').append( option ).focus();
        sortFunc( '#sel_orders' );
        $("#query_button").attr('disabled',false);
        $("#clear_button").attr('disabled',false);
    }
}

function SelButtonClick( event )
{
    event.preventDefault();
    var sel_sel = $('#sel_orders').val();

    if( sel_sel )
    {
        var option = $("option[value='" + sel_sel + "']");
        $('#all_orders').append( option ).focus();
        sortFunc( '#all_orders' );        
    }

    if( $('#sel_orders option').length == 0 )
    {
        $("#query_button").attr('disabled', 'disabled');
        $("#clear_button").attr('disabled', 'disabled');
    }
}