// Actions after full page loading
$( function()
{
	adjust_ui();

$( "#delete-town-dialog-confirm" ).dialog({
      resizable: false,
      height: "auto",
      width: 400,
      modal: true,
      autoOpen: false,
      buttons: {
        "\u0423\u0434\u0430\u043B\u0438\u0442\u044C": function() 
        {
            var id = $( "#delete-town-dialog-confirm" ).data('id') 

            $.post(
                "/project/reports/logistic_base/ajax.DeleteRecord.php",
                {
                    id  : id
                  },
                        function( data )
                        {
                         $( 'tr[data-id="' + id + '"]' ).remove();
                         var lines = $('span.line')
                         var num = 1

                         $.each( lines , function( key, item )
                         {
                             $( item ).text( num ++ )
                         });
                        }
                      );

            $( this ).dialog( "close" );
        },
        "\u041E\u0442\u043C\u0435\u043D\u0430": function() {
          $( this ).dialog( "close" );
        }
      }
    });
});


function adjust_ui()
{
	$('#add_city').unbind('click').bind( 'click', add_city_btn_click )
	var datepickers = $('.datepicker');
    $.each( datepickers , function( key, item )
    {
    	adjust_calendar( item )
    });

    $('.val_input').unbind('keyup').bind('keyup', input_keyup )
    $('.text_input').unbind('keyup').bind('keyup', text_input_keyup )
    
    $('.city_input').unbind('keyup').bind('keyup', city_input_keyup )

    $('.del_city').unbind('click').bind('click', del_city_click )

}

function add_city_btn_click()
{
	var req_id = $('#logistic_table').data('id')
    $.post(
        "/project/reports/logistic_base/ajax.AddCity.php",
        {
            user_id : user_id
          },
                function( data )
                {
                  console.log( data );
                	var line = 1 + Number( $('span.line').last().text() )
                	$('#logistic_table').append( data )
                	$('span.line').last().text( line )
               	  adjust_ui();
                }
              );

}

function city_input_keyup()
{
  input_process( this )
}


function input_keyup()
{
  var val = Number( $( this ).val() );
  if( isNaN( val ))
  {
    $( this ).addClass( 'nan' )
  }
  else
  {
   $( this ).removeClass( 'nan' )
   input_process( this );
  }
}


function text_input_keyup()
{
   input_process( this );
}

function input_process( el )
{

  var id = $( el ).parent().parent().data('id');    
  var field = $( el ).data('field');
  var data = $( el ).val();
  updateRecord( id, field, data );
}

function date_process( el )
{
    var id = $( el ).parents( 'tr' ).data('id')
    var field = $( el ).data('field')
    var date = $( el ).datepicker('getDate')
    var year = date.getFullYear();
    var month = 1 + date.getMonth();
    var day = date.getDate();
    updateRecord( id, field, year + '-' + month + '-' + day  )
}


function updateRecord( id, field, val )
{
        $.post(
                "/project/reports/logistic_base/ajax.UpdateRecord.php",
                {
                    id  : id,
                    field : field, 
                    val : val
                  },
                        function( data )
                        {
                          // alert('updated')
                        }
                      );
}

function del_city_click()
{
	var id = $( this ).parent().parent().data('id');
  $( "#delete-town-dialog-confirm" ).data('id', id ).dialog('open')
}
