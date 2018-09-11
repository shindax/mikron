$(document).keyup(function(e) {
    if (e.keyCode === 27) 
        $('tr[data-rec-id=""').remove()
});

$( function()
{
	$('.issue_button').unbind('click').bind('click', issue_button_click );
  $('.print_button').unbind('click').bind('click', print_button_click );
  $('#create').unbind('click').bind('click', create_button_click );    
  $('.del_img').unbind('click').bind('click', del_img_click );    
});


function issue_button_click(event)
{
	event.preventDefault();

	var el = this ;
	var tr = $( this ).parent().parent();
	var id = $( tr ).data('id');
  var rec_id = $( tr ).data('rec-id');
	var cnt = $( tr ).find('.count').text();
	var res_cnt = $( tr ).find('.res_count').text();

    $.post(
    "project/sklad/ajax.Issue.php",
    {
        id   : id,
        cnt : cnt,
        res_cnt : res_cnt,
        rec_id : rec_id
    },
    function( data )
    {
      $( el ).removeClass('btn-info').prop('disabled',true).text('\u0412\u044B\u0434\u0430\u043D\u043E');
 
      var trs = $('tr[data-id="' + id + '"');
       $.each( trs , function( key, item )
        {
            $( item ).find('.count').text( data );
        });

       $( el ).data('state', '1' ).attr('data-state', '1' );

       $( tr ).find('.del_img').removeClass('del_img').addClass('del_img_dis').attr('src','/uses/del_dis.png').unbind('click')
    }
    );
}

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
        $('.issue_button').unbind('click').bind('click', issue_button_click );
        $( tr ).find( '.del_img_dis' ).attr('src','/uses/del.png').removeClass('del_img_dis').addClass('del_img').prop('disabled',false ).unbind('click').bind('click', del_img_click );            
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
  var el = this ;
  var tr = $( this ).parent().parent();
  var id = $( tr ).data('rec-id');

  url = "print.php?do=show&formid=271&p0=" + id;
  window.open( url, "_blank" );
}