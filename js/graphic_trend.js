  $( function()
  {
    $( '#year_select, #month_select').unbind( "change" ).bind( "change", select_change )

    // $.post(
    //     "/project/ajax.getGrahicData.php",
    //     {
    //       year  : year,
    //       month  : month ,
    //       order_id : id,
    //     },
    //     function( data )
    //     {
    //       cons( data )
    //     }
    // );

    get_graphic_data()

  	function make_graph( 
  		title1, 
  		title2, 
  		title3, 
  		title4, 
  		title5, 
  		title6,
  		days,
  		series
  		)
  	{
  		Highcharts.chart('container', {
  			chart: {
  				type: 'spline',
  				height : '500px'
  			},

  			accessibility: {
  				description: ''
  			},

  			legend: {
  				symbolWidth: 40
  			},

  			title: {
          style: {
                    color: 'green',
                    fontSize: '14px',
                    fontFamily: 'Verdana, sans-serif',
                },
  				text: title2
  			},

  			subtitle: {
  				text: title3
  			},

  			yAxis: {
  				title: {
  					text: title4
  				}
  			},

  			xAxis: {
  				title: {
  					text: title5,
  				},
  				accessibility: {
  					description: title6
  				},
  				categories: days,
  			},

  			tooltip: {
  				split: true
  			},

  			plotOptions: {
  				series: {
  					point: {
  						events: {
  							// click: function () {
  							// 	window.location.href = this.series.options.website;
  							// }
  						}
  					},
  					cursor: 'pointer',
  				}
  			},

  			series: series,
  			responsive: {
  				rules: [{
  					condition: {
  						maxWidth: 500,
  					},
  					chartOptions: {
  						legend: {
  							itemWidth: 250,
  						}
  					}
  				}]
  			}
  		});
  	
    } // function make_graph( 

    function get_graphic_data()
    {
        if( year && month && id )
        $.ajax({    
          url : '/project/ajax.getGrahicData.php',
          type : 'POST',
          data : {
            year  : year,
            month  : month ,
            order_id : id,
          },
          dataType: 'json',
          success: function( respond, textStatus, jqXHR )
          {
            if( typeof respond.error === 'undefined' )
            {
              let title4 = '\u{41D}\u{43E}\u{440}\u{43C}\u{43E}-\u{447}\u{430}\u{441}\u{44B} / \u{448}\u{442}\u{443}\u{43A}\u{438}'

              // cons( respond.series )

              make_graph( 'Title 1', respond.main_title, '', title4, respond.month_title, 'Title6', 
                    respond.month_arr, respond.series)
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

  function select_change()
  {
    year = $( '#year_select option:selected').val()
    month = $( '#month_select option:selected').val()
    get_graphic_data()
  }
})