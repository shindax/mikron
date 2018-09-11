$( function()
{
	adjust_ui();
});

function adjust_ui()
{
	$('.wh_select').unbind('change').bind('change', wh_select_change );
	$('.cell_select').unbind('change').bind('change', cell_select_change );
	$('.tier_select').unbind('change').bind('change', tier_select_change );	
	$('.op_select').unbind('change').bind('change', operation_select_change );		
  $('.print').unbind('click').bind('click', print_button_click );	
  $( '#year-select').unbind('change').bind('change', year_select_change );    
  $( '#inv-num-select').unbind('change').bind('change', inv_num_select_change ); 
  $( '#save').unbind('click').bind('click', save_button_click ); 
  $( '#add_dse').unbind('click').bind('click', add_dse_button_click );
  $( '#create').unbind('click').bind('click', create_button_click );
}

function wh_select_change()
{
	var select = $( this );
	var row = $( this ).parents('tr.order_row');
	var val = select.find('option:selected' ).val();
	var cell_sel = $( row ).find('select.cell_select');
	var options = $( cell_sel ).find('option');
	var tier_options = $( row ).find('select.tier_select').find('option');
	clear_options( options, val )
	clear_options( tier_options, 0 )
}

function cell_select_change()
{
	var select = $( this );
	var row = $( this ).parents('tr.order_row');
	var val = select.find('option:selected' ).val();
	var options = $( row ).find('select.tier_select').find('option');
	clear_options( options, val )
}

function clear_options( list, val )
{
	$( list ).addClass('hidden').removeAttr('selected');

    $.each( list , function( key, item )
    {
     	var parent_id = $( item ).data('parent_id');
     	if( parent_id == val )
     		$( item ).removeClass('hidden');
     			else
     				$( item ).addClass('hidden');
    });

    $( list ).eq(0).removeClass('hidden')
}

function tier_select_change()
{
	var select = $( this );
	var row = $( this ).parents('tr.order_row');
	var id = $( row ).data( 'id' );
	var operation = $( row ).find('.op_select').find('option:selected' ).text();
	var comment = $( row ).find('span.note').text();
	var tier = select.find('option:selected' ).val();
	var dse_name = $( row ).find('.dse_name').text();
	var order_name = $( row ).find('.order_name').text();
	var draw_name = $( row ).find('.draw_name').text();
	var count = $( row ).find('.count').text();

	    $.post(
        "project/semifin_invoices/ajax.updateWarehouse.php",
        {
            id   : id ,
            tier : tier,
            operation : operation,
            comment : comment,
            dse_name : dse_name,
            order_name : order_name,
            draw_name : draw_name,
            count : count     
        },
        function( data )
        {
    		// console.log( data )      
        }
    );
	
}

function operation_select_change()
{
	var select = $( this );
	var row = $( this ).parents('tr.order_row');
	var id = $( row ).data( 'id' );
	var operation_id = $( row ).find('.op_select').find('option:selected' ).val();

	    $.post(
        "project/semifin_invoices/ajax.updateOperation.php",
        {
            id   : id ,
            operation_id : operation_id   
        },
        function( data )
        {
//    		console.log( operation_id )      
        }
    );	
}

function print_button_click()
{
	var id = $( this ).data('id')
    url = "print.php?do=show&formid=252&p0=" + id;
    window.open( url, "_blank" );
}

function year_select_change()
{
    var year = $( '#year-select option:selected' ).val();
        $.post(
        "project/semifin_invoices/ajax.viewer.yearChange.php",
        {
            year   : year
        },
        function( data )
        {
            $('div.container').empty().append( data );
            adjust_ui();
        }
    );
}

function inv_num_select_change()
{
    var year = $( '#year-select option:selected' ).val();
    var num = $( '#inv-num-select option:selected' ).val();    
    $.post(
        "project/semifin_invoices/ajax.viewer.numChange.php",
        {
            year   : year,
            num : num
        },
        function( data )
        {
            $('div.container').find('table').remove();
            $('div.container').append( data );
            adjust_ui();
        }
    );
}

// http://mic.ru/index.php?do=show&f &p0=315405,315406,238822
function save_button_click()
{
    var rows = $( '#semifin_inv_create' ).find('.order_row');
    var obj = []
    var inv_num = $('#inv_num').val();

    $.each( rows , function( key, row )
    {

      obj.push(   
      {
          'id' : $( row ).attr('data-id'),
          'dse_name' : $( row ).find('span.dse_name').text(),
          'order_name' : $( row ).find('span.order_name').text(),
          'draw_name' :$( row ).find('span.draw_name').text(),
          'operation_id' : $( row ).find('select.operation_select option:selected').val(),
          'part_num' :$( row ).find('input.part_num').val(),
          'count' : $( row ).find('input.count').val(),
          'transfer_place' : $( row ).find('input.transfer_place').val(),
          'storage_time' : $( row ).find('select.storage_time option:selected').val(),
          'note' : $( row ).find('input.note').val()
      }
       )
    });


//    console.log( obj )

        $.post(
        "project/semifin_invoices/ajax.createInvoice.php",
        {
            obj : obj ,
            num : inv_num,
            user_id : user_id
        },
        function( data )
        {
            window.location.href = "/index.php?do=show&formid=250"            
        }
    );

}

function add_dse_button_click()
{
 var line = 1 + $('.order_row').length;
 $.post(
        "project/semifin_invoices/ajax.viewer.addDSE.php",
        {
            line : line
        },
        function( data )
        {
            $('#semifin_inv_create').append( data );

              $.ajax({    
        url: '/project/semifin_invoices/ajax.get_orders.php',
        type: 'POST',
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
          {
            // Если все ОК
            if( typeof respond.error === 'undefined' )
            {
              var tagsByOrderDSEName = [];
            
              for( var i = 0 ; i < respond.length ; i ++ )
                {
                  var dse_name = respond[ i ][ 'dse_name' ] + ' : ' + respond[ i ][ 'tid' ] + ' ' + respond[ i ][ 'name' ];
                  var name = respond[ i ][ 'tid' ] + ' ' + respond[ i ][ 'name' ];
                  var id = respond[ i ][ 'id' ];
                
                  tagsByOrderDSEName[ i ] =  { label : dse_name, value : dse_name, name : name, id : id };
                }

              $( ".order_name_input" ).autocomplete({
                                                        source: tagsByOrderDSEName,
                                                        select : OrderDSENameChange
                                                      });
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
    }); // $.ajax({

        }
    );
}

function OrderDSENameChange( event, ui )
{
    var el = this ;
    var id = ui.item.id;
    var name = ui.item.name;
    $( this ).addClass('hidden').siblings().text( name ).removeClass( 'hidden' );

    var data = { 'id' : id };

$.ajax({    
        url: '/project/semifin_invoices/ajax.get_dse.php',
        type: 'POST',
        data: data,        
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
          {
            // Если все ОК

            if( typeof respond.error === 'undefined' )
            {
              var tagsByOrderDSEName = [];
            
              for( var i = 0 ; i < respond.length ; i ++ )
                {
                  var draw = respond[ i ][ 'draw' ] ;
                  var name = respond[ i ][ 'name' ] ;
                  var id = respond[ i ][ 'id' ];
                
                  tagsByOrderDSEName[ i ] =  { label : name + ' : ' + draw, value : name, id : id, draw : draw };
                }

                $( el ).parent().parent().find( '.dse_name_input' ).autocomplete({
                                            source: tagsByOrderDSEName,
                                            select : DSENameChange
                                            });
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

function DSENameChange( event, ui )
{
    var id = ui.item.id;
    var name = ui.item.value;
    var draw = ui.item.draw;
    var label = ui.item.label;
    $( this ).parent().parent().find('span.draw_name').text( draw )
    $( this ).addClass('hidden').siblings().text( name ).removeClass( 'hidden' )
}

function create_button_click( event )
{
    event.preventDefault()
    url = "index.php?do=show&formid=251";
    window.open( url, "_blank" );
}
