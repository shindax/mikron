$( function()
{
	  $( "#accordion" ).accordion(
      { 
        heightStyle: "content", 
        active: false, 
        collapsible: true, 
        animate: 
          { 
              duration: 0
          }}).removeClass('hidden');


	$('.a_link').bind('click', a_click )
	$('.shift_link').bind('click', a_shift_link )

	function a_click( event ) 
	{
		let href = $( this ).attr('href')
		let a3 = $( this ).closest('h3')
		let state = $( a3 ).attr('aria-expanded')
		event.stopPropagation()
	}

	function a_shift_link( event ) 
	{
		document.cookie = "scroll= ; expires = Thu, 01 Jan 1970 00:00:00 GMT"
	}

    $.ajax({    
            url : '/project/noncomplete_execution_causes_responsible_persons/ajax.getData.php',
            type : 'POST',
            data : {
                      res_id : res_id
                    },
            dataType: 'json',
            success: function( respond, textStatus, jqXHR )
              {
                if( typeof respond.error === 'undefined' )
                {
                	let resp0 = respond[0]
                	let resp1 = respond[1]                	
                	
                	let arr = [
								{ caption: resp0.caption },
								{ name: resp0.name },
	        					{ colorByPoint: resp0.colorByPoint },
	        					{ data: resp0.data }
	        				]

					make_chart( 'total_chart', arr )

                	  arr = [
								{ caption: resp1.caption },
								{ name: resp1.name },
	        					{ colorByPoint: resp1.colorByPoint },
	        					{ data: resp1.data }
	        				]

					make_chart( 'personal_chart', arr )

					$( '#total_chart' ).show()
					$( '#personal_chart' ).show()	
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

});


function make_chart( id , series )
{

	// console.log( series )

Highcharts.chart( id, {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: series[0].caption
    },
    tooltip: {
        pointFormat: '<b>{point.y} \u{437}\u{430}\u{43F}.</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                // format: '<b>{point.name} : {point.y}  \u{437}\u{430}\u{43F}.</b>',
                format: '<b>{point.name}</b>',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: series
});

}