$( function()
{
	$( '#date_input' ).unbind( 'change' ).bind( 'change', date_input_change )

	function date_input_change()
	{
		let date = $( '#date_input' ).val()		
        get_data( date )
	}

    function get_data( date )
    {
        var data = new FormData();
        data.append( 'date', date );        

$.ajax({
                  url: 'project/test/ajax.getData.php',
                  type: 'POST',
                  data: data,
                  cache: false,
                  dataType: 'json',
                  processData: false, 
                  contentType: false, 
                  success: function( respond, textStatus, jqXHR )
                  {
                      // if everything is OK
                      if( typeof respond.error === 'undefined' )
                        {
                            console.log( respond )
                            $('.machine_time').text( respond.machine_on_time )
                            $('.machine_perc').text( respond.machine_perc )
                            $('.tool_perc').text( respond.tool_perc )
                            $('.date').text( respond.date )
                            $('.machine_on_time_str').text( respond.machine_on_time_str )
                            $('.machine_off_time_str').text( respond.machine_off_time_str )
                        }
                      else
                          console.log('AJAX request errors detected. Server said : ' + respond.error );
                  },
                  error: function( jqXHR, textStatus, errorThrown )
                  {
                      console.log('AJAX request errors in uploadProjectFiles.js detected : ' + textStatus + errorThrown );
                  }
              });
    }

setInterval(function () 
{
    $.getJSON( "/project/test/ajax.getState.php", function(data) 
    {
        $("table, th, td").css("border", "1px solid " + data['status_color']);
        $(".line").css("background-color", data['status_color']);
        let date = $( '#date_input' ).val()     
        get_data( date )
    });  
}, 1000);

});	
