// Actions after full page loading
$( function()
{
	$('.master_select').bind( 'change' , master_select_change );
});

function master_select_change()
{
	var res_id = $( this ).find( 'option:selected' ).val();
	var row_id = $( this ).closest( 'tr' ).data('id');
	
    $.post(
        "project/master_binding/ajax.bindMaster.php",
        {
            row_id   : row_id ,
			res_id   : res_id 
        },
        function( data )
        {

        }
    )
}