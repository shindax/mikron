$(document).keyup(function(e) {
  if (e.keyCode === 27) 
    $('tr[data-rec-id=""').remove()
});

function adjust_ui()
{
  $('.res_count').unbind('click').bind('click', issue_button_click );

  $('.print_button').unbind('click').bind('click', print_button_click );
  $('.storekeeper_button').unbind('click').bind('click', storekeeper_button_click );
  $('.issues_count_button').unbind('click').bind('click', issues_count_button_click );

  $('#create').unbind('click').bind('click', create_button_click );    
  $('.del_img').unbind('click').bind('click', del_img_click );      
  $('.give_out_input').unbind('keyup').bind('keyup', give_out_input_keyup );
  $('.issued_count').unbind('click').bind('click', issued_count_click );
}

function issues_count_button_click()
{
  let batch = $( this ).closest('tr').data('batch')
  url = "index.php?do=show&formid=303&p0=" + batch;
  window.open( url, "_blank" );  
}

function storekeeper_button_click( e ) 
{
  e.preventDefault();
  let batch = $( this ).closest('tr').data('batch')
  let trs = $('tr[data-batch=' + batch + ']')
  let arr = []

  $.each( trs , function( key, item )
  {
    let id = $( item ).data('rec-id');
    arr.push( id )
  });

  let list = arr.join(",")

  url = "print.php?do=show&formid=311&p0=" + list;
  // url = "index.php?do=show&formid=311&p0=" + list;  
  window.open( url, "_blank" );
}

$( function()
{
  $('table.A4W' ).remove()

  adjust_ui()

  $( '#warehouse_dialog' ).dialog({
    resizable: false,
    modal: true,
    closeOnEscape: true,
    autoOpen : false,
    height: 350,
    width: 1000,
    create : function()
    {
      OpenWarehouseDialog()          
    },
    create : function()
    {
      $('div.ui-widget-header').css('background','rgb(0, 139, 139)').css('color','white').css('font-weight','bold')

      $(this).closest(".ui-dialog")
      .find(".ui-dialog-titlebar-close")
      .css( { 'padding':'0'} )
      .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
    },
    open : function()
    {
      OpenWarehouseDialog();
    },

    buttons:
    [
    {
      id : 'iss_button',
      text: '\u{412}\u{44B}\u{434}\u{430}\u{442}\u{44C}', //'Выдать',
      disabled : false, // true, 
      click : function ()
      {
        let res_id = $( '#warehouse_dialog').data('id')
        let batch = $( '#warehouse_dialog').data('batch')
        let inputs = $( '.give_out_input').filter( function () { 
          return !! this.value 
        })
        let arr = []

        $.each( inputs , function( key, item )
        {
          let tr = $( item ).closest('tr')
          let id = parseInt( $( tr ).data('warehouse-detiitem-record-id') )
          let inv_id = parseInt( $( tr ).data('inv-id') )
          let wh_id = parseInt( $( tr ).find('span.wh').data('id') )
          let cell_id = parseInt( $( tr ).find('span.cell').data('id') )
          let tier_id = parseInt( $( tr ).find('span.tier').data('id') )
          let count = parseInt( $( item ).val() )
          arr.push( {
            "id" : id,
            "inv_id" : inv_id,
            "wh" : wh_id,
            "cell" : cell_id,
            "tier" : tier_id,
            "count" : count,
            "comments" : ''
          }
          )
        });

        $.post(
          "project/sklad/ajax.Issue.php",
          {
            res_id   : res_id,
            arr : arr,
            user_id : user_id,
            batch : batch,
          },
          function( data )
          {
            cons( data )
            let arr = data.split(",")
            let issued = arr[0]
            let issues = arr[1]
            let state = arr[2]
            let state_str = arr[3]            

            let tr_batch = $( 'tr[data-batch=' + batch + ']')
            $( tr_batch ).find(".state_text").text( state_str )

            let tr = $( 'tr[data-rec-id=' + res_id + ']')
            let src_count_a = $( tr ).find('a.res_count')
            let src_count = parseInt( $( src_count_a ).text() ) 
            
            let issued_count_a = $( tr ).find('.issued_count')
            let issued_count = parseInt( issued )

            $( tr ).find("button.issues_count_button").removeClass("hidden").find("span.glyphicon").text( issues )
            
            if( issued_count_a.length )
            {
              let issued = parseInt( $( issued_count_a ).text() ) + issued_count
              $( issued_count_a ).text( issued )
            }
            else
            {
              $( tr ).find('.issued_count_span').replaceWith('<a href="#" class="issued_count">' + issued_count + '</a>')
            }

            src_count -= issued_count
            $( src_count_a ).text( src_count )
            if( src_count == 0 )
                  $( src_count_a ).replaceWith('<span class="issued">0</span>')

            if( state == 1 )
              $( tr_batch ).addClass('not_completed')   
              else
                  $( tr_batch ).removeClass('not_completed')                
            adjust_ui(); 
            $( '#warehouse_dialog').dialog('close')
          }
          );

      }
    },
    {
      text : '\u041E\u0442\u043C\u0435\u043D\u0430',
      click : function ()
      {
        $(this).dialog('close');
      }
    }
    ]
    }); //   $( '#warehouse_dialog' ).dialog({


    $( '#issue_history_dialog' ).dialog({
      resizable: false,
      modal: true,
      closeOnEscape: true,
      autoOpen : false,
      height: 242,
      width: 1000,
      create : function()
      {
      },
      create : function()
      {
        $('div.ui-widget-header').css('background','rgb(0, 139, 139)').css('color','white').css('font-weight','bold')
        $(this).closest(".ui-dialog")
        .find(".ui-dialog-titlebar-close")
        .css( { 'padding':'0'} )
        .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
      },
      open : function()
      {
        let inv_id = $( this ).data('inv-id')
        let rec_id = $( this ).data('id')

        let operation_id = $( this ).data('operation-id')

        $.post(
          "project/sklad/ajax.get_history.php",
          {
            inv_id  : inv_id,
            rec_id  : rec_id,
            operation_id : operation_id,
          },
          function( data )
          {
            adjust_ui(); 
            $( '#issue_history_dialog').html( data ).dialog('open')
            let tr_count = $( data ).find('tr.data_row').length

           if( tr_count < 6 )
            {
              let height = $("#issue_history_dialog").dialog("option", "height") + tr_count * 22
              $("#issue_history_dialog").dialog("option", "height", height )
            }

          }
          );

      },

      buttons:
      [
      {
        text : '\u{417}\u{430}\u{43A}\u{440}\u{44B}\u{442}\u{44C}', // Закрыть
        click : function ()
        {
          $(this).dialog('close');
        }
      }
      ]
    });


  });


function issue_button_click(event)
{
	event.preventDefault();
  let pattern = $( this ).data('pattern')

  let tr = $( this ).closest('tr')
  let id_zakdet = $( tr ).data('id-zakdet')
  let operation_id = $( tr ).data('operation-id')

  if( operation_id === undefined )
    operation_id = 0 ;

  let id = $( tr ).data('rec-id')
  let inv_id = $( tr ).data('inv-id')
  let batch = $( tr ).data('batch')
  
  let res_count_a = $( tr ).find('a.res_count')
  let iss_count_a = $( tr ).find('a.issued_count')
  let count = parseInt( $( res_count_a ) .text() )

  $.post(
    "project/sklad/ajax.IssueDialogOpen.php",
    {
      id : id,
      count : count,
      id_zakdet : id_zakdet,
      operation_id : operation_id,
      pattern : pattern
    },
    function( data )
    {
      $( '#warehouse_dialog').html( data ).data( 'id', id ).data( 'batch', batch ).data( 'inv-id', inv_id ).dialog('open')
      adjust_ui();      
    }
    );
} // function issue_button_click(event)

function create_button_click( event )
{
  event.preventDefault();
  var line = 1 + Number( $('.line').last().text() );
  var inv_num = 1 + Number( $('.inv_num').last().text() );

  $.post(
    "project/sklad/ajax.row_create.php",
    {
      line : line, 
      user_id : user_id
    },
    function( data )
    {
      $( '#semifin_invoices' ).append( data )
      $.ajax({
        url: '/project/sklad/ajax.get_data.php',
        type: 'POST',
        dataType: 'json',
        success: function( respond, textStatus, jqXHR )
        {
                    // Если все ОК
                    if( typeof respond.error === 'undefined' )
                    {

                      $(".dse_name_input").autocomplete({
                        source: respond,
                        select: function( event, ui )
                        {
//                                console.log( ui )

var el = this ;

var label = ui.item.label ;
var id = ui.item.id ;
var comment = ui.item.comment ;
var count = ui.item.count ;
var inv_num = ui.item.inv_num ;

var warehouse_name = ui.item.warehouse_name;
var cell_name = ui.item.cell_name;
var tier_name = ui.item.tier_name ;

var tr = $( this ).parent().parent();
$( tr ).data('id',id ).attr('data-id', id );
$( tr ).find('.comment').text( comment )
$( tr ).find('.count').text( count )
$( tr ).find('.inv_num').text( inv_num )

$( tr ).find('.warehouse').text( warehouse_name );
$( tr ).find('.warehouse_cell').text( cell_name );
$( tr ).find('.warehouse_tier').text( tier_name );

$( tr ).find('.res_count_input').prop( 'disabled', false ).unbind('keyup').bind('keyup', res_count_input_keyup ); 
$( tr ).find('.add_invoice_button').unbind('click').bind('click', add_invoice_button_click ); 
}
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
    );

}

function res_count_input_keyup()
{
  var tr = $( this ).parent().parent();
  var id = $( tr ).data( 'id' );
  var val = Number( $( this ).val() );
  var count = Number( $( tr ).find( '.count' ).text() )

  var trs = $('tr[data-id="' + id + '"');

  $.each( trs , function( key, item )
  {
    if( $( item ).find('.issue_button').data('state') == 0 )
      val += Number( $( item ).find( '.res_count' ).text() )
  });

  if( isNaN( val ) || val > count || Number( $( this ).val() ) == 0 )
  {
    $( tr ) .find('.add_invoice_button').prop( 'disabled', true );
    $( this ).addClass('error');
  }
  else
  {
    $( tr ) .find('.add_invoice_button').prop( 'disabled', false );
    $( this ).removeClass('error');
  }

}

function add_invoice_button_click( event )
{
 event.preventDefault();
 var el = this ;
 var tr = $( this ).parent().parent();
 var id = $( tr ).data('id');

 var input = $( tr ).find('.dse_name_input');
 var text = $( input ).addClass('hidden').val();
 $( input ).siblings( 'span' ).removeClass('hidden').text( text );

 input = $( tr ).find('.res_count_input');
 var count = $( input ).addClass('hidden').val();
 $( input ).siblings( 'span' ).removeClass('hidden').text( count );

  $.post(
  "project/sklad/ajax.insert_invoice.php",
  {
    id   : id,
    count : count,
    user_id : user_id
  },
  function( data )
  {
    $( tr ).data( 'rec-id', data ).attr('data-rec-id', data );
    $( el ).text( '\u0412\u044B\u0434\u0430\u0442\u044C' ).removeClass('add_invoice_button').addClass('issue_button');
    $( tr ).find( '.del_img_dis' ).attr('src','/uses/del.png').removeClass('del_img_dis').addClass('del_img').prop('disabled',false ).unbind('click').bind('click', del_img_click );            
    adjust_ui();
  }
  );
}

function del_img_click()
{
  var tr = $( this ).parent().parent();
  var id = $( tr ).data('rec-id');

  $.post(
    "project/sklad/ajax.row_delete.php",
    {
      id : id
    },
    function( data )
    {
      $( 'tr[data-rec-id="' + id + '"]').remove();
      var lines = $( '.line');
      var line = 1 ;
      $.each( lines , function( key, item )
      {
        $( item ).text( line );
        line ++ ;
      });

    }
    );
}

function print_button_click( event )
{
  event.preventDefault(); 
  let el = this ;
  let tr = $( this ).parent().parent();
  let batch = $( tr ).data('batch');

  url = "print.php?do=show&formid=271&p0=" + batch;
  // url = "index.php?do=show&formid=271&p0=" + batch;  
  window.open( url, "_blank" );
}

function OpenWarehouseDialog()
{
}

function give_out_input_keyup()
{
  let need_populate = parseInt( $( this ).closest('table').data('count') )
  let val = $( this ).val()
  let count = parseInt( $( this ).closest('tr').find('.count').text())
  let total = 0

  if( val > count )
    $( this ).val( $.trim($( this ).val()).slice(0, -1));

  total = count_total()

  if( total > need_populate )
  {
    $( this ).val( $.trim($( this ).val()).slice(0, -1));
//    disable_button( '#iss_button' )
    total = count_total()
  }
  else
  {
    if( total )
      enable_button( '#iss_button' )
    else
      disable_button( '#iss_button' )
  }

  $('.total_give_out').text( total )

}

function enable_button( selector )
{
  $( selector ).button({ disabled: false });
  $( selector ).prop({ disabled: false }).removeClass('disabled');
}

function disable_button( selector )
{
  $( selector ).button({ disabled: true });
  $( selector ).prop({ disabled: true }).addClass('disabled');
}

function count_total()
{
  let total = 0 ;
  let inputs = $( '.give_out_input' )    
  $.each( inputs , function( key, item )
  {
    let val = parseInt( $( item ).val() )
    if( !isNaN( val ) )
      total += val
  });
  return parseInt( total );
}

function issued_count_click()
{
  let tr = $( this ).closest( 'tr' )
  let id = $( tr ).data('rec-id')
  let inv_id = $( tr ).data('inv-id')
  let operation_id = $( tr ).data('operation-id')  

  $( '#issue_history_dialog' ).data('id', id ).data('operation-id', operation_id ).data('inv-id', inv_id ).dialog('open');
}

function cons( arg )
{
  console.log( arg )
}


