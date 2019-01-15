if(!window.jQuery)
  document.write('<script type="text/javascript" src="/uses/jquery.js"></script>')

$( function() 
{
  $("[class^='recalc_input_']").unbind('keyup').bind('keyup', InputChangeKeyUp );
});

function addSpaces( nStr )
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ' ' + '$2');
	}
	return x1 + x2;
}

function InputChangeKeyUp()
{
  var val =  parseFloat( ($( this ).val().replace(' ','' ).replace(',','.' )).replace(' ','' ) ) ;
  
  var type = $( this ).data('type');
  var ves = $( this ).data('ves');
  var d1_d2 = $( this ).data('d1_d2');
  var dtemp = $( this ).data('dtemp'); 
  var count = $( this ).data('count');   
  var dall = $( this ).data('dall');
  var dp4 = $( this ).data('dp4');
  var margin = 0 ;

  
  
  var price_without_nds = ( dall / count ).toFixed(0);
      
  var price = 0 ;

  
  if( type == 'prib' )
  {
      price = parseInt( ( val  * ves ) / ( d1_d2 * 1000 ) ) ;
      val =  (( (  d1_d2 * price + dtemp ) * 1000 ) / ves ).toFixed(2);
      $(".recalc_input_13" ).val( addSpaces( val ) );
      
      dall = ( d1_d2 * price ) + dtemp ;
      price_without_nds = ( dall / count ).toFixed(0);
      $(".recalc_input_14" ).val( addSpaces( price_without_nds ) );
      $(".recalc_input_15" ).html( addSpaces( price_without_nds * count ) );
      margin = d1_d2 * price - dp4;        
      $(".recalc_input_11" ).html( addSpaces( margin ) );      
  }

  if( type == 'rurtonn' )
  {
    price = parseInt( ( ( val * ves / 1000 ) - dtemp ) / d1_d2 ) ;
    
    val =  (( 1000 * (( d1_d2 ) * price ) ) / ves ).toFixed(2);
    $(".recalc_input_12" ).val( addSpaces( val ) );
    
    dall = ( d1_d2 * price ) + dtemp ;
    price_without_nds = ( dall / count ).toFixed(0);
    $(".recalc_input_14" ).val( addSpaces( price_without_nds ) );    
    $(".recalc_input_15" ).html( addSpaces( price_without_nds * count ) );    
    margin = d1_d2 * price - dp4;        
    $(".recalc_input_11" ).html( addSpaces( margin ) );      
  }
  
  if( type == 'price_without_nds' )
  {
      dall = val * count ;
      price = ( dall - dtemp ) / d1_d2 ;
      
      val =  (( 1000 * (( d1_d2 ) * price ) ) / ves ).toFixed(2);      
      $(".recalc_input_12" ).val( addSpaces( val ) ).attr('data-dall', dall );      
      $(".recalc_input_14" ).attr( 'data-dall', dall );      

      val =  (( (  d1_d2 * price + dtemp ) * 1000 ) / ves ).toFixed(2);
      $(".recalc_input_13" ).val( addSpaces( val ) ).attr('data-dall', dall );
      $(".recalc_input_15" ).html( addSpaces( dall ) );      
      margin = d1_d2 * price - dp4;        
      $(".recalc_input_11" ).html( addSpaces( margin ) );      
  }

  price = price.toFixed(0);
  $('[name="price"]').val( price );
}


