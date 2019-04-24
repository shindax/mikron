// Actions after full page loading
$( function()
{
	"use strict"
	get_form( 'Form1')


function adjust_ui()
{
	$('.caption_data').unbind('keyup').bind('keyup', caption_data_change )
	$('.row_data').unbind('keyup').bind('keyup', row_data_change )
	$( 'a' ).unbind('click').bind('click', a_click )
}

function row_data_change()
{
	let readonly = $( this ).prop('readonly')
	let table = $( this ).closest('table').attr('id') 
	let data = $( this ).val();
	let data_field = $( this ).data('field');	

	let data_number = $( this ).parent().parent().data('number')
	let data_row = $( this ).parent().parent().data('row') ? 'row_' + $( this ).parent().parent().data('row') + '_' : ''
	let field = data_row + data_field

	if( !readonly )
		update_form( table, data_number, field, data )
}

function caption_data_change()
{
	let readonly = $( this ).prop('readonly')
	let table = $( this ).closest('table').attr('id') 	
	let data = $( this ).val();
	let data_number = $( this ).parent().parent().data('number')
	let field = 'caption'

	if( !readonly )
		update_form( table, data_number, field, data )
}

function update_form( table, data_number, field, data )
{
    $.post(
        "project/cooperation_database/ajax.UpdateTable.php",
        {
        	table : table,
            id   :  data_number,
            field : field,
            data : data,	
            user_id : user_id
        },
        function( data )
        {
        	$('.actuality_date').html( data )
        	$( 'a.active').removeClass('expired')
        }
    );
}


function a_click()
{
	let form = $( this ).data('form')
	get_form( form )
}

function get_form( form )
{
	    $.post(
        "project/cooperation_database/ajax.Get" + form + ".php",
        {
        	user_id : user_id
        },
        function( data )
        {
        	$( '#coop_' + form ).html( data );
        	adjust_ui();
        }
    );

}

});
