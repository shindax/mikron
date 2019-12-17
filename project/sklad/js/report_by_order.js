$( function()
{

// ********************************* Корзина *********************************

$( "#basket-dialog" ).dialog({
	  dialogClass: "basket",
      resizable: true,
      height: "auto",
      width: 400,
      minWidth: 200,
      modal: false,
      autoOpen: false,
      position : { my: "right top", at: "right top", of: $('div.bar') },
      open : function()
      {

      },
      buttons: {
        "\u0417\u0430\u043F\u0440\u043E\u0441\u0438\u0442\u044C \u0432\u0441\u0435": function() 
        {
        	let that = this
        	$.post(
					'project/zadan/ajax.RequestAllFromBasket.php',
					{
						user_id : user_id
					},
					function( data )
					{
						// cons( data )
						let arr = JSON.parse( data )
						// cons( data )
						for ( key in arr ) 
						{
							let id_zakdet = parseInt(arr[key]['id_zakdet'])
							let operation_id = parseInt(arr[key]['operation_id'])
							let count = parseInt(arr[key]['count'])
							// $('a.in_wh[data-zakdet-id=' + id_zakdet + '][data-operation-id=' + operation_id + ']').remove()
						}
						$( that ).dialog( "close" );
					}
					);	
        },
        "\u041E\u0447\u0438\u0441\u0442\u0438\u0442\u044C \u0438 \u0437\u0430\u043A\u0440\u044B\u0442\u044C": function() 
        {
        	let that = this
        	$.post(
					'project/zadan/ajax.EmptyBasket.php',
					{
						user_id : user_id
					},
					function( data )
					{
						let arr = JSON.parse( data )
						for ( key in arr ) 
						{
							cons( arr[key] )
							let id_zakdet = parseInt(arr[key]['id_zakdet'])
							let operation_id = parseInt(arr[key]['operation_id'])
							let count = parseInt(arr[key]['count'])
							let a = $('a.in_wh[data-zakdet_id=' + id_zakdet + '][data-operation_id=' + operation_id + '][data-pattern="' + arr[key]['pattern'] + '"]')
							let old_count = parseInt($( a ).html()) 
							
							$( a ).html( old_count + count )
						}
          				$( that ).dialog( "close" );
					}
					);	
        }
      }
    })

// ******************************************************************


	$( '#warehouse_dialog' ).dialog({
		resizable: false,
		modal: true,
		closeOnEscape: true,
		height: 250,
		width: 1000,
		autoOpen : false,
create : function()
{
},
open : function()
{
	$( '.get_from_wh_req_input' ).first().focus();
},

buttons:
[
{
	id : 'issue_button',
	text: '\u{417}\u{430}\u{43F}\u{440}\u{43E}\u{441}\u{438}\u{442}\u{44C}',
	disabled : true, 
	click : function ()
	{
		let that = this
		let arr = [];

		let trs = $( 'tr.items_to_issue' )
	    $.each( trs , function( key, item )
	    {
	      	let operation_id = $( item ).data('id');
			let get_from_wh_req_input = parseInt( $( item ).find('.get_from_wh_req_input').val())
			let get_from_wh_req_comment_input = $( item ).find('.get_from_wh_req_comment_input').val()
			let id_zakdet = $( item ).find('.get_from_wh_req_input').data('id_zakdet')
			let id_zadan = $( item ).find('.get_from_wh_req_input').data('id_zadan')
			let pattern = $( item ).data('pattern');

			if( get_from_wh_req_input )
			arr.push( {
					'operation_id' : operation_id,
					'count' : get_from_wh_req_input,
					'comment' : get_from_wh_req_comment_input,
					'id_zakdet' : id_zakdet,
					'id_zadan' : id_zadan,
					'pattern' : pattern
				   })
	    });


// ***************************************************************************************
		// $.post(
		// 	'project/zadan/ajax.ReserveItems.php',
		// 	{
		// 		user_id : user_id,
		// 		arr : arr
		// 	},
		// 	function( data )
		// 	{
		// 	        console.log( data );
		// 			$( that ).dialog('close');
		// 	}
		// 	);
// ***************************************************************************************

		$.post(
			'project/zadan/ajax.AddToDSEBasket.php',
			{
				arr,
				user_id : user_id
			},
			function( data )
			{
				// cons( data )
					OpenBasket()
					$( that ).dialog('close');
					for ( key in arr ) 
					{
						let id_zakdet = parseInt(arr[key]['id_zakdet'])
						let operation_id = parseInt(arr[key]['operation_id'])
						let count = parseInt(arr[key]['count'])
						let a = $('a.in_wh[data-zakdet_id=' + id_zakdet + '][data-operation_id=' + operation_id + '][data-pattern="' + arr[key]['pattern'] + '"]')
						let old_count = parseInt($( a ).html()) 
						$( a ).html( old_count - count )
					}					
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
});

if( items_in_basket )
	OpenBasket()

adjust_ui()

function adjust_ui()
{
	$('.acount').unbind('click').bind('click', acount_click );
    $('.get_from_wh_req_input').unbind('keyup').bind('keyup', get_from_wh_req_input_keyup );    
	$('.del_pict').unbind('click').bind('click', del_pic_click );    
}

function get_from_wh_req_input_keyup()
{
	let val = parseInt( $( this ).val() );
	let max = parseInt( $( this ).data('max') );

    if( val > max || val == 0 || isNaN( val ) )
    {
    // delete last char
      $( this ).val( $.trim($( this ).val()).slice(0, -1));
      
      if( isNaN( parseInt($( this ).val())) )
      	disable_button('#issue_button')
     }
      else
      		enable_button('#issue_button')
}

function enable_button( selector )
{
  $( selector ).prop('disabled', false).button({ disabled: false });
}

function disable_button( selector )
{
  $( selector ).prop('disabled', true).button({ disabled: true })
}


function acount_click()
{
	let zakdet_id = + $( this ).data('zakdet_id')
	let operation_id = + $( this ).data('operation_id')	
	let zadan_id = + $( this ).data('zadan_id')
	let pattern = $( this ).data('pattern')
	OpenReportWarehouseDialog( zakdet_id, operation_id, zadan_id, pattern );
}

function OpenReportWarehouseDialog( zakdet_id, operation_id, zadan_id, pattern )
{

	$('div.ui-widget-header').css('background','#008B8B').css('color','white').css('font-weight','bold'); // Цвет заголовка диалога
	$('#copy_dialog_copy_button').addClass('ui-state-disabled');
	$.post(
		'project/zadan/ajax.FindInWarehouse.php',
		{
			pattern : pattern,
			id_zadan : zadan_id,
			id_zakdet : zakdet_id,
			id_operation : operation_id
		},
		function( data )
		{
			// cons( data )
			$('#warehouse_dialog').html( data ).dialog('open');
			adjust_ui();
		}
		);
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

function OpenBasket()
{
	$.post(
	'project/zadan/ajax.GetBasket.php',
	{
		user_id : user_id
	},
	function( data )
	{
		$('#basket-dialog').html( data ).dialog('open')
		adjust_ui()
	}
	);
}

function del_pic_click()
{
	let tr = $( this ).closest('tr');
	let id = parseInt( $( tr ).data('id'))
	let id_zakdet = parseInt( $( tr ).data('zakdet_id'))
	let operation_id = parseInt( $( tr ).data('oper_id') )
	let pattern = $( tr ).data('pattern')
	let count = parseInt( $( tr ).find('.count').text() )
	$( tr ).remove();
	if( ! $('.basket_table').find('tr').length )
		$( "#basket-dialog" ).dialog('close')

	let a = $('a.in_wh[data-zakdet_id=' + id_zakdet + '][data-operation_id=' + operation_id + '][data-pattern="' + pattern + '"]')

	let old_count = parseInt($( a ).html()) 
	$( a ).html( old_count + count )

	$.post(
			'project/zadan/ajax.DeleteRowInBasket.php',
			{
				id : id
			},
			function( data )
			{
				// cons( data )
				adjust_ui();
			}
		);
}

});