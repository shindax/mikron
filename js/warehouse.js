$( function()
{

	$( "#count_dialog" ).dialog({
		resizable: false,
		height: "auto",
		width: 200,
		height : 130,
		modal: true,
		autoOpen : false,
		buttons: 
		[
		{
			id : "count_dialog_save",
	        // Сохранить
	        text: "\u{421}\u{43E}\u{445}\u{440}\u{430}\u{43D}\u{438}\u{442}\u{44C}",
	        disabled : !can_edit,
	        click : function() 
	        {
	        	let ref_id = $( this ).data('ref_id')
	        	let tier_item_id = $( this ).data('tier_item_id')
	        	let count =   $( "#count_input" ).val()
	        	let old_count =  $( "#count_input" ).data('old_count')

	        	$.post(
	        		"ajax.saveWarehouseDSECount.php",
	        		{
	        			user_id : user_id,	            	
	        			ref_id : ref_id,
	        			tier_item_id : tier_item_id,
	        			count : count,
	        			old_count : old_count
	        		},
	        		function( data )
	        		{
	        			$( 'tr[data-yarus-item-id="' + tier_item_id + '"]').find('a.tier_count').text( data )
	              // cons( data )
	              $('#count_dialog').dialog('close');
	          }
	          );
	        }
	    },
        // закрыть
        {
        	id : "close",
        	text : "\u0417\u0430\u043a\u0440\u044b\u0442\u044c",
        	click : function() 
        	{
        		$( this ).dialog( "close" );
        	}
        }
        ],
        classes:
        {
        	"ui-dialog-titlebar" : "count_dialog_title"	
        }
    }).dialog({ classes : { "ui-dialog-titlebar" : "count_dialog_title" }});


	$( "#operation_dialog" ).dialog({
		resizable: false,
		height: "auto",
		width: 200,
		height : 130,
		modal: true,
		autoOpen : false,
		buttons: 
		[
		{
			id : "operation_dialog_save",
	        // Сохранить
	        text: "\u{421}\u{43E}\u{445}\u{440}\u{430}\u{43D}\u{438}\u{442}\u{44C}",
	        disabled : !can_edit,
	        click : function() 
	        {
	        	let ref_id = $( this ).data('ref_id')
	        	let tier_item_id = $( this ).data('tier_item_id')
	        	let op_name = $( '#operation_select option:selected' ).text();
	        	let op_id = $( '#operation_select option:selected' ).val();
	        	let old_op_id = $( '#operation_select' ).data('old_id');	         
	        	$.post(
	        		"ajax.saveWarehouseOperationName.php",
	        		{
	        			user_id : user_id,	            	
	        			ref_id : ref_id,
	        			tier_item_id : tier_item_id, 
	        			op_id : op_id,
	        			old_op_id : old_op_id
	        		},
	        		function( data )
	        		{
	        			$( 'tr[data-yarus-item-id=' + tier_item_id + ']' ).find('a.operation_a').text( data ).data('id',op_id)
	        			$( '#operation_select' ).data('old_id', op_id);	
	        			$('#operation_dialog').dialog('close');
	        		}
	        		);
	        }
	    },
        // закрыть
        {
        	id : "close",
        	text : "\u0417\u0430\u043a\u0440\u044b\u0442\u044c",
        	click : function() 
        	{
        		$( this ).dialog( "close" );
        	}
        }
        ],
        classes:
        {
        	"ui-dialog-titlebar" : "count_dialog_title"	
        }
    }).dialog({ classes : { "ui-dialog-titlebar" : "count_dialog_title" }});

	$( '#operation_select' ).unbind('change').bind('change', operation_select_change )
	$( '.operation_a' ).unbind('click').bind('click', operation_a_click )
	$( '.tier_count' ).unbind('click').bind('click', tier_count_click )
	$( '#count_input' ).unbind('keyup').bind('keyup', count_input_keyup )

	function operation_select_change()
	{
		let val = $( this ).find('option:selected' ).val();
		let old_val = $( this ).data('old_id');
		if( old_val != val )
			$('#operation_dialog_save').button( "enable" );
		else
			$('#operation_dialog_save').button( "disable" );		
	}

	function operation_a_click()
	{
		let id = $( this ).data('id')	;
		let tr = $( this ).closest('tr');
		let yarus_item_id = $( tr ).data('yarus-item-id')
		let ref_id = $( tr ).data('ref_id')		
		$( '#operation_select' ).data('old_id', id);
		$( '#operation_select option[value=' + id + ']' ).prop('selected', 'true');
		$( "#operation_dialog" ).data('ref_id', ref_id).data('tier_item_id', yarus_item_id).dialog('open')
	}

	function tier_count_click()
	{
		let val = $( this ).text()
		let tr = $( this ).closest('tr');
		let yarus_item_id = $( tr ).data('yarus-item-id')
		let ref_id = $( tr ).data('ref_id')
		$('#count_dialog_save').button( "disable" );

		$( "#count_input" ).val( val )
		$( "#count_input" ).data('old_count', val)
		$( "#count_dialog" ).data('tier_item_id', yarus_item_id).data('ref_id', ref_id).dialog('open')

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

	function alr( arg1='', arg2='', arg3='', arg4='', arg5='')
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

		alert( str )
	}


	function count_input_keyup()
	{
		let count = $( this ).val();
		let old_count = $( this ).data('old_count');

		if( count.length && can_edit && count != old_count )
			$('#count_dialog_save').button( "enable" );
		else
			$('#count_dialog_save').button( "disable" );
	}


});