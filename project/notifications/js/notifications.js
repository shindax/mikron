$( function()
{
	$('.btn').unbind('click').bind('click', btnclick );
});

function btnclick()
{
	var rec_id = $( this ).data('id');
    var where = 1 ;

    if( $( this ).hasClass('btn-default'))
        where = 2 ;

        $.post(
            "project/notifications/ajax.makeNotificationAck.php",
            {
                id   : rec_id,
                where : where
            },
            function( data )
            {
              $( '#card_' + data ).remove();
            }
        );


}