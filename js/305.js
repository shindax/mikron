$( function()
{
	$.post(
	"/project/ajax.business_trips_getCount.php",
	{
	},
	function( data )
	{
		
		for ( key in data ) 
		{
			key *= 1
			let cnt = 0
			let val = data[ key ]
			
			 
				for ( skey in val )
				{ 
			console.log(val[skey]['count']);
					let scnt = parseInt( val[ skey ], 10);
					let count = val[ skey ]['count']; 
						skey = parseInt( skey , 10); 
						//skey = skey < 10 ? '0' + skey : skey 
						$( 'tr[data-month="' + skey  + '"][data-year="' + key + '"]' ).children("td").first().find("table td:nth-child(2)").append( "<div style='float:right;display:inline'>" + count + "</div>" )
					cnt += parseInt(count);
					
					if (val[skey]['hasExpired']) {
						$( 'tr[data-month="' + skey  + '"][data-year="' + key + '"]' ).css("background-color", "#ffd1d1");
						$( 'tr[data-year="' + key + '"][class="cltreef"]').css("background-color", "#ffd1d1");
					}
				}

			if( cnt == 0 )
				cnt = '';
			
			$('tr[data-year=' + key + ']').children("td").first().find("table td:nth-child(2)").append("<div style='float:right;display:inline'>" + ( cnt ) + "</div>");
			// console.log( key + ' : ' + cnt )				
		}

		// alert( data )
	}
	 ,'json'
	);

 	$.post(
	"/project/ajax.business_trips_getExpired.php",
	{
	},
	function( data )
	{ 
		for ( key in data ) 
		{ 
			let val = data[ key ]
				for ( skey in val )
				{ 
						$( 'tr[data-id="' + val  + '"]' ).css("background-color", "#ffd1d1"); 
				}
			
		}
	}
	 ,'json'
	);

});