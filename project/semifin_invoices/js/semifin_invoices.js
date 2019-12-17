$( function()
{
  $( '#year-select' ).prop('disabled', false);
  $( '#inv-num-select' ).prop('disabled', false);
  $('body').css('cursor','default')

  let host_master_ack = $( '.host_master_ack' )

   $.each( host_master_ack , function( key, item )
  {
    if( (  $( item ).data('host_master_id') == res_id || res_id == 1 ) && ! $( item ).prop('checked') )
      $( item ).prop('disabled',false)
  });

// ******************************************************************
$( "#storage_place_dialog" ).removeClass('hidden').dialog({
      resizable: false,
      width: 600,
      height : 180,
      modal: true,
      autoOpen : false,  // true, 
      buttons: 
      [
          {
        // Сохранить
        text : "\u0421\u043E\u0445\u0440\u0430\u043D\u0438\u0442\u044C",
        id : "save_storage_place",
        disabled : true, 
        click : function() 
          {
            let id = $( this ).data('id')
            let that = this
            let data = []
            let trs = $( '#storage_place_dialog tr.storage_place')
            let link = $( this ).data('that')
            let count = 0 

            $.each( trs , function( key, item )
              {
                count += parseInt( $( item ).find('.storage_place_dialog_count_input').val() )
                data.push( {
                  "id" : $( item ).closest('tr').data('id'),
                  "draw_name" : $( 'tr[data-id=' + id + ']').find('.draw_name').text(),
                  "count" : $( item ).find('.storage_place_dialog_count_input').val(),
                  "wh" : $( item ).find('.wh_select').find('option:selected').val(),
                  "cell" : $( item ).find('.cell_select').find('option:selected').val(),
                  "tier" : $( item ).find('.tier_select').find('option:selected').val(),
                  "comments" : $( item ).find('.storage_place_dialog_comment_input').val()
                })
             });

           $.post(
            "project/semifin_invoices/ajax.saveStoragePlace.php",
            {
              id   : id ,
              data : data,
              user_id : user_id
            },
            function( data )
            {
                // console.log( data )
                $( link ).text( count )
                $( that ).dialog( "close" ); 
            }
                );
          }
        },
        {
        // Добавить место          
        text : "\u0414\u043E\u0431\u0430\u0432\u0438\u0442\u044C \u043C\u0435\u0441\u0442\u043E",
        id : "add_storage_place",
        disabled : false,         
        click : function() 
          {
            let tr_count = $( '.storage_place_table').find('tr').length
            let tr = $( '.storage_place_table').find('tr:last')
            let num = parseInt( $( tr ).find('span.num').text() ) + 1 
            let tr_new = tr.clone()

            $( tr_new ).find('.storage_place_dialog_count_input').val(0)
            $( tr_new ).find('.storage_place_dialog_comment_input').val('')
            $( tr_new ).find('span.num').text( num )
            $( tr ).after( tr_new )
            $( '.storage_place_table').find('tr:last').data('id',0).prop('data-id',0).attr('data-id',0)
            disable_button('#add_storage_place')
            disable_button('#save_storage_place')

            $( tr_new ).find('.wh_select option').prop('selected', false )
            clear_options( $( tr_new ).find('.cell_select option') , 0 )
            clear_options( $( tr_new ).find('.tier_select option') , 0 )

            adjust_ui();
            if( tr_count < 6 )
            {
              let height = $("#storage_place_dialog").dialog("option", "height") + 28
              $("#storage_place_dialog").dialog("option", "height", height )
            }
          }
        },
        {
        text : "\u0417\u0430\u043a\u0440\u044b\u0442\u044c",
        click : function() 
          {
            $( this ).dialog( "close" );
          }
        }
      ]
      ,
      open: function(){
          let zero_sum = check_dialog_count_values()
          if( zero_sum.zero_val )
              disable_button('#add_storage_place')
          let id = $( this ).data('id')
      }
    }).prev(".ui-dialog-titlebar").addClass("storage_place_dialog_title");

$( "#accept_by_QCD_dialog" ).removeClass('hidden').dialog({
      resizable: false,
      width: 240,
      height : 130,
      modal: true,
      autoOpen : false, // true, 
      buttons: 
      [
        {
        // Принять
        text : "\u{41F}\u{440}\u{438}\u{43D}\u{44F}\u{442}\u{44C}",
        id : "accept_button",
        disabled : true,         
        click : function() 
          {
            let that = this 
            let rec_id = $( this ).data('id')
            let count = $('#accept_by_QCD_input').val()

           $.post(
            "project/semifin_invoices/ajax.accept.php",
            {
              id   : rec_id ,
              count : count,
              user_id : user_id
            },
            function( data )
            {
                $( 'tr[data-id=' + rec_id + ']' ).find( 'a.accepted_by_QCD_a').parent().html( data )
                
                console.log( data )
                $( that ).dialog( "close" ); 
            }
                );            

            $( that ).dialog( "close" );
          }
        },
        {
        text : "\u0417\u0430\u043a\u0440\u044b\u0442\u044c",
        click : function() 
          {
            $( this ).dialog( "close" );
          }
        }
      ]
      ,
      open: function(){
          let zero_sum = check_dialog_count_values()
          if( zero_sum.zero_val )
              disable_button('#add_storage_place')
          let id = $( this ).data('id')
      }
    }).prev(".ui-dialog-titlebar").addClass("storage_place_dialog_title");

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
  $( '.accepted-by-QCD').unbind('keyup').bind('keyup', accepted_by_QCD_keyup );
  $( '.storage_place_dialog_count_input').unbind('keyup').bind('keyup', storage_place_dialog_count_input_keyup )  
  $('.storage_place_button').unbind('click').bind('click', storage_place_click );

  $('.host_master_ack').unbind('click').bind('click', host_master_ack_click ); 
  $('.accepted_by_QCD_a').unbind('click').bind('click', accepted_by_QCD_a_click ); 
  $( '#accept_by_QCD_input').unbind('keyup').bind('keyup', accept_by_QCD_input_keyup )  

  $( 'input.count').unbind('keyup').bind('keyup', check_before_save )
  $( 'select.master_select').unbind('change').bind('change', master_select_change )
  $( 'select.operation_select').unbind('change').bind('change', check_before_save )
}

function accept_by_QCD_input_keyup()
{
  let can_accept = $('#accept_by_QCD_input').data( 'can-accept')
  let val = $( this ).val()
  if( val.length )
  {
     if( val > parseInt( $( this ).data('max') ) && can_accept )
      $( this ).val( $.trim($( this ).val()).slice(0, -1));    
        else
          enable_button('#accept_button', '#accept_by_QCD_dialog')
  }
      else
        disable_button('#accept_button')
  
}

function accepted_by_QCD_a_click()
{
  let that = this
  let tr = $( this ).closest( 'tr' )
  let staff_arr = $( this ).data('staff').split( "," )
  let can_accept = false

  if( staff_arr.indexOf( res_id.toString() ) !== -1 )
    can_accept = true

    let rec_id = $( tr ).data('id')
  $('#accept_by_QCD_input').data( 'can-accept', can_accept ).data('max', $( this ).data('max')).val('')
  $( "#accept_by_QCD_dialog" ).data('id', rec_id ).data('can_accept', can_accept ).dialog('open')
}

function host_master_ack_click()
{
  let that = this
  let tr = $( this ).closest( 'tr' )
  let rec_id = $( tr ).data('id')
 
$.post(
            "project/semifin_invoices/ajax.updateMasterAck.php",
            {
              rec_id : rec_id
            },
            function( data )
            {
                // console.log( data )
                $( that ).siblings('.host_master_span').after( "<br><span class='host_master_ack_datetime'>" + data + "</span>" )
                $( that ).prop('disabled', true)
            }
                );
}

function master_select_change()
{
   let master_id = $( this ).find("option:selected").val()
   let master_select_arr = $( 'select.master_select')

  $.each( master_select_arr , function( key, item )
  {
    $( item ).find('option[value=' + master_id + ']').prop("selected", true )
  });

   check_before_save()  
}

function wh_select_change()
{
    let val = $( this ).find('option:selected' ).val();
    let tr = $( this ).parents('tr');    
    let cell_sel = $( tr ).find('select.cell_select');    
    let cell_options = $( cell_sel ).find('option');
    let tier_options = $( tr ).find('select.tier_select').find('option');
  
    clear_options( cell_options, val )
    clear_options( tier_options, 0 )

    // check_before_save()
}

function cell_select_change()
{
    let val = $( this ).find('option:selected' ).val();
    let tr = $( this ).parents('tr');    
    let tier_options = $( tr ).find('select.tier_select').find('option');
    clear_options( tier_options, val )
    check_before_save()    
}

function clear_options( list, val )
{
	$( list ).addClass('hidden').removeAttr('selected');

  $.each( list , function( key, item )
  {
    let parent_id = $( item ).data('parent_id');
    if( parent_id == val )
     $( item ).removeClass('hidden');
        else
          $( item ).addClass('hidden');
 });

  $( list ).eq(0).removeClass('hidden')
 }

function tier_select_change()
{
  check_before_save()  
}

function operation_select_change()
{
	let select = $( this );
	let row = $( this ).parents('tr.order_row');
	let id = $( row ).data( 'id' );
	let operation_id = $( row ).find('.op_select').find('option:selected' ).val();

 $.post(
  "project/semifin_invoices/ajax.updateOperation.php",
  {
    id   : id ,
    operation_id : operation_id   
  },
  function( data )
  {
    if( operation_id )
      $( select ).prop('disabled', true)
//    		console.log( operation_id )      
}
);	
}

function print_button_click()
{
	let id = $( this ).data('id')
  url = "print.php?do=show&formid=252&p0=" + id;
  window.open( url, "_blank" );
}

function year_select_change()
{
  let year = $( '#year-select option:selected' ).val();
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
  $( '#inv-num-select' ).prop('disabled', true);
  $( '#year-select' ).prop('disabled', true);
  $('body').css('cursor','wait')
  let year = $( '#year-select option:selected' ).val();
  let num = $( '#inv-num-select option:selected' ).val();
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
      $( '#inv-num-select' ).prop('disabled', false );
      $( '#year-select' ).prop('disabled', false);
      $('body').css('cursor','default')
      adjust_ui();
    }
    );
}

// http://mic.ru/index.php?do=show&f &p0=315405,315406,238822
function save_button_click()
{
  let rows = $( '#semifin_inv_create' ).find('.order_row');
  let obj = []
  let inv_num = $('#inv_num').val();

  $.each( rows , function( key, row )
  {

    obj.push(   
    {
      'id' : $( row ).attr('data-id'),
      'zakdet_id' : $( row ).attr('data-zakdet-id'),
      'dse_name' : $( row ).find('span.dse_name').text(),
      'order_name' : $( row ).find('span.order_name').text(),
      'draw_name' :$( row ).find('span.draw_name').text(),
      'operation_id' : $( row ).find('select.operation_select option:selected').val(),
      'part_num' :$( row ).find('input.part_num').val(),
      'count' : $( row ).find('input.count').val(),
      'transfer_place' : $( row ).find('input.transfer_place').val(),
      'storage_time' : $( row ).find('select.storage_time option:selected').val(),
      'master_id' : $( row ).find('select.master_select option:selected').val(),
      'note' : $( row ).find('input.note').val()
    }
    )
  });


    console.log( obj )

$.post(
  "project/semifin_invoices/ajax.createInvoice.php",
  {
    obj : obj ,
    num : inv_num,
    user_id : user_id
  },
  function( data )
  {
    // console.log( data )
    window.location.href = "/index.php?do=show&formid=250"            
  }
  );

}

function add_dse_button_click()
{
 let line = 1 + $('.order_row').length;
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
              let tagsByOrderDSEName = [];

              for( let i = 0 ; i < respond.length ; i ++ )
              {
                let dse_name = respond[ i ][ 'dse_name' ] + ' : ' + respond[ i ][ 'tid' ] + ' ' + respond[ i ][ 'name' ];
                let name = respond[ i ][ 'tid' ] + ' ' + respond[ i ][ 'name' ];
                let id = respond[ i ][ 'id' ];
                
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
  let el = this ;
  let id = ui.item.id;
  let name = ui.item.name;
  $( this ).addClass('hidden').siblings().text( name ).removeClass( 'hidden' );

  let data = { 'id' : id };

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
              let tagsByOrderDSEName = [];

              for( let i = 0 ; i < respond.length ; i ++ )
              {
                let draw = respond[ i ][ 'draw' ] ;
                let name = respond[ i ][ 'name' ] ;
                let id = respond[ i ][ 'id' ];
                
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
  let id = ui.item.id;
  let name = ui.item.value;
  let draw = ui.item.draw;
  let label = ui.item.label;
  $( this ).parent().parent().find('span.draw_name').text( draw )
  $( this ).addClass('hidden').siblings().text( name ).removeClass( 'hidden' )
}

function create_button_click( event )
{
  event.preventDefault()
  url = "index.php?do=show&formid=251";
  window.open( url, "_blank" );
}

function accepted_by_QCD_keyup()
{
  let val = parseInt( $( this ).val() )
  let tr = $( this ).closest('tr')
  let id = $( tr ).data('id')

  if( isNaN( val ) )
   val = 0 
  
  if( val > parseInt( $( this ).data('max') ) )
    // delete last char
      $( this ).val( $.trim($( this ).val()).slice(0, -1));    

   $.post(
    "project/semifin_invoices/ajax.updateQCD.php",
    {
      id   : id ,
      count : val   
    },
    function( data )
    {
      if( val == 0 )
        $( tr ).find( 'select' ).prop('disabled', true )
      else
        $( tr ).find( 'select' ).prop('disabled', false )    

    //        console.log( operation_id )      
    }
  );  
}

function storage_place_dialog_count_input_keyup()
{
  let total_count = parseInt( $( '#storage_place_dialog' ).data('count'))
  let zero_sum = check_dialog_count_values()
  let val = parseInt( $( this ).val() ) 

  if( zero_sum.sum <= total_count && ! zero_sum.zero_val )
  {
    enable_button('#save_storage_place', '#storage_place_dialog' )
    if( zero_sum.sum == total_count )
      disable_button('#add_storage_place')
      else
        enable_button('#add_storage_place', '#storage_place_dialog')
  }
  else
  {
    // delete last char
      $( this ).val( $.trim($( this ).val()).slice(0, -1));
      enable_button('#save_storage_place', '#storage_place_dialog')
  }

    check_before_save()
}

function check_dialog_count_values()
{
  let accepted_by_QCD = $( '#storage_place_dialog' ).data('count')
  let inputs = $( '#storage_place_dialog' ).find('.storage_place_dialog_count_input')
  let selects = $( '#storage_place_dialog' ).find('select')  

  let zero_val = 0 
  let sum = 0 
  let unselected = 0

  $.each( inputs , function( key, item )
  {
    let val = parseInt( $( item ).val() )

    // if even one input has zero or empty value
    if( val == 0 )
      zero_val ++

    sum += val 
  });  

  $.each( selects , function( key, item )
  {
    if( parseInt( $( item ).find('option:selected').val()) == 0 )
      unselected ++;
  });  

  return { zero_val : zero_val , sum : sum, unselected : unselected, accepted_by_QCD : accepted_by_QCD };
} //


function enable_button( but_selector, dialog_selector = 0 )
{
  if( dialog_selector == '#storage_place_dialog' )
  {
    if( $( dialog_selector ).data('can_edit'))
        $( but_selector ).button({ disabled: false });
        else
          $( but_selector ).button({ disabled: true });
  }

  if( dialog_selector == '#accept_by_QCD_dialog' )
  {
    if( $( dialog_selector ).data('can_accept'))
        $( but_selector ).button({ disabled: false });
        else
          $( but_selector ).button({ disabled: true });
  }

}

function disable_button( selector )
{
  $( selector ).button({ disabled: true });
}

function check_before_save()
{
  let total_count = $( '#storage_place_dialog' ).data('count')
  let state = check_dialog_count_values()
    if( ! state.unselected && ! state.zero_val )
      enable_button('#save_storage_place', '#storage_place_dialog')
        else
          disable_button('#save_storage_place')
}

function cons( arg1='', arg2='', arg3='', arg4='', arg5='')
{
  let str = arg1 ;
  if( String(arg2).length )
    str += ' : ' + arg2
  if( String(arg3).length )
    str += ' : ' + arg3
  if( String(arg4).length )
    str += ' : ' + arg4
  if( String(arg5).length )
    str += ' : ' + arg5

  console.log( str )
}

function storage_place_click()
{
  let that = this
  let id = $( this ).data('id')
  let tr = $( this ).closest('tr')
  let count = parseInt( $( tr ).find('.accepted-by-qcd-count').text())
  let can_edit = $( tr ).find('.host_master_ack').prop('checked')
  $("#storage_place_dialog").data('can_edit', can_edit )

  $("#storage_place_dialog *").prop('disabled', true).attr('disabled', 'disabled')

           $.post(
            "project/semifin_invoices/ajax.getStoragePlace.php",
            {
              id   : id
            },
            function( data )
            {
              $('#storage_place_dialog div').find('table').remove()
              $('#storage_place_dialog div').html( data )
              let tr_count = $( data ).find('.storage_place').length
              
              if( tr_count > 5 )
                tr_count = 5

              let height = 180 + ( tr_count - 1 ) * 28
              

              let dialog_state = check_dialog_count_values()
              
              if( dialog_state.sum >= dialog_state.accepted_by_QCD )
                disable_button('#add_storage_place')
                  else
                    enable_button('#add_storage_place', '#storage_place_dialog')

              if( dialog_state.zero_val )
                disable_button('#save_storage_place')
                  else
                    enable_button('#save_storage_place', '#storage_place_dialog')

              let title = "\u041C\u0435\u0441\u0442\u043E \u0445\u0440\u0430\u043D\u0435\u043D\u0438\u044F. \u0420\u0430\u0441\u043F\u0440\u0435\u0434\u0435\u043B\u0435\u043D\u043E " + dialog_state.sum + " \u0438\u0437 " + count

              adjust_ui();

              $("#storage_place_dialog").dialog("option", "height", height ).data('count', count ).data('id', id ).data('that', that ).dialog( "option", "title", title ).dialog( 'open' );
            }
               );

} // function storage_place_click()



function check_before_save()
{
  let can_save = true
  
  if( 
      check_if_nonempty( 'input.count' ) 
      && check_if_nonempty( 'select.master_select option:selected' ) 
      && check_if_nonempty( 'select.operation_select option:selected' ) 
    )
    $( '#save' ).prop('disabled', false )
      else
        $( '#save' ).prop('disabled', true )
}

function check_if_nonempty( selector )
{
  let result = true
  let items = $( selector )
  $.each( items , function( key, item )
  {
    let val = parseInt( $( item ).val() )
    if( val == 0 || isNaN( val ))
      result = false
  });
  return result
}