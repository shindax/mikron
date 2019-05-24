function getDateStr( date, glue = '' )
{
  var mm = date.getMonth() + 1;
  var dd = date.getDate();
  var arr = null ;

  if( glue == '-' )
    arr = [ date.getFullYear(), ( mm > 9 ? '' : '0' ) + mm, ( dd > 9 ? '' : '0' ) + dd ];

 if( glue == '.' )
    arr = [ ( dd > 9 ? '' : '0' ) + dd, ( mm > 9 ? '' : '0' ) + mm, date.getFullYear() ];

 if( glue == '' )
    arr = [ date.getFullYear(), ( mm > 9 ? '' : '0' ) + mm, ( dd > 9 ? '' : '0' ) + dd ];

  return arr.join( glue );
};

function shiftDate( date, shift )
{
       return new Date( date.valueOf() + shift * 86400000 );
}

function nextDay( date )
{
       return shiftDate( date, 1 );
}

function prevDay( date )
{
       return shiftDate( date, -1 );
}
