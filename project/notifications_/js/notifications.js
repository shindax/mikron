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
              if( $( '*[id^="card_"]' ).length == 0 )
                    $('#accordion').append('<h3>\u{41D}\u{435}\u{442} \u{43D}\u{435}\u{43F}\u{440}\u{43E}\u{447}\u{438}\u{442}\u{430}\u{43D}\u{43D}\u{44B}\u{445} \u{443}\u{432}\u{435}\u{434}\u{43E}\u{43C}\u{43B}\u{435}\u{43D}\u{438}\u{439}</h3>')
            }
        );


}