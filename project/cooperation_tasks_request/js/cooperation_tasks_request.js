// Actions after full page loading
$( function()
{
	adjust_ui();

$( "#dialog-confirm" ).dialog({
      resizable: false,
      height: "auto",
      width: 400,
      modal: true,
      autoOpen: false,
      buttons: {
        "\u0423\u0434\u0430\u043B\u0438\u0442\u044C": function() 
        {
            var id = $( "#dialog-confirm" ).data('id') 

            $.post(
                "/project/cooperation_tasks_request/ajax.DeleteRecord.php",
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
	$('#add_cagent').unbind('click').bind( 'click', add_cagent_btn_click )
	var datepickers = $('.datepicker');
    $.each( datepickers , function( key, item )
    {
    	adjust_calendar( item )
    });

    $('.state_comment_select').unbind('change').bind('change', select_change )
    $('.state_comment_input').unbind('keyup').bind('keyup', input_keyup )
    $('.state_comment_input').unbind('blur').bind('blur', input_blur )

    $('.pricing_select').unbind('change').bind('change', select_change )
    $('.pricing_input').unbind('keyup').bind('keyup', input_keyup )
    $('.pricing_input').unbind('blur').bind('blur', input_blur )
    $('.selected').unbind('change').bind('change', select_radio_change )

    $('.del_cagent').unbind('click').bind('click', del_cagent_click )

    check_selected()
}

function add_cagent_btn_click ()
{
	var req_id = $('#requisition_tasks_table').data('id')
    $.post(
        "/project/cooperation_tasks_request/ajax.AddCagent.php",
        {
            coop_req_id  : req_id,
            user_id : user_id
          },
                function( data )
                {
                	var line = 1 + Number( $('span.line').last().text() )
                	$('#requisition_tasks_table').append( data )
                	$('span.line').last().text( line )
                	$( ".combobox" ).combobox()
               	    adjust_ui();
                }
              );

}

function select_change()
{
	var field_raw = $( this ).data('field')
	var field = field_raw ;

	if( field == 'state')
		field += '_comment';

	var data_id = $( this ).data('id');
	var option_id = Number( $( this ).find( 'option:selected' ).val() );


	if( option_id == 1 )
		input_show( data_id, field ) 
		
	updateRecord( data_id , field_raw , option_id );
	updateRecord( data_id , field_raw + '_note', '' );
}

function input_keyup()
{
    check_select_input( this )
}

function check_select_input( el )
{
    var field_raw = $( el ).data('field')
    var field = field_raw;

    if( field == 'state')
        field += '_comment';

    var data_id = $( el ).data('id');
    var val = $( el ).val();
    if( val.length == 0 )
    {
        var sel = $("." + field + "_select[data-id='" + data_id + "']");
        select_show( data_id, field ) 
        $( sel ).find("option").removeAttr('selected');
        $( sel ).find("option[value='0']").attr('selected', 'false');
        updateRecord( data_id ,field_raw , 0 );
        updateRecord( data_id ,field_raw + '_note' , '' );
    }
    else
    {
            updateRecord( data_id , field_raw, 1 );
            updateRecord( data_id , field_raw + '_note', val );
    }
}


function input_blur()
{
    check_select_input( this )
}

function select_show( id, cls )
{
		$( '.' + cls + '_input' + "[data-id='" + id + "']").addClass('hidden');
		$( '.' + cls + '_select' + "[data-id='" + id + "']").removeClass('hidden');
}


function input_show( id, cls )
{
		$( '.' + cls + '_select' + "[data-id='" + id + "']").addClass('hidden');
        $( '.' + cls + '_input' + "[data-id='" + id + "']").removeClass('hidden').focus();        
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
                "/project/cooperation_tasks_request/ajax.UpdateRecord.php",
                {
                    id  : id,
                    field : field, 
                    val : val
                  },
                        function( data )
                        {
                        }
                      );
}


// function clearSelected( req_id )
// {
//         $.post(
//                 "/project/cooperation_tasks_request/ajax.ClearSelected.php",
//                 {
//                     req_id  : req_id
//                   },
//                         function( data )
//                         {
//                         	console.log( data )
//                         }
//                );
// }

function select_radio_change()
{
	var selected = $( this ).prop('checked') ? 1 : 0 ;
	var id = $( this ).parents('tr').data('id');
	var req_id = $( '#requisition_tasks_table').data('id');
	//clearSelected( req_id )
  
  // alert(selected)
	updateRecord( id, 'selected', selected )
  if( selected )
//	$( 'tr' ).removeClass('table-info');	
	 $( this ).parent().parent().addClass('table-info');	
    else
      $( this ).parent().parent().removeClass('table-info'); 
}

function check_selected()
{
	var radio = $('input[type="checkbox"][name="radio"]');

	 $.each( radio , function( key, item )
    {
	    if( $( item ).prop("checked") )
	      $( item ).parent().parent().addClass('table-info');
	      	else
	      		$( item ).parent().parent().removeClass('table-info');
    });
}

function del_cagent_click()
{
	var id = $( this ).parent().parent().data('id');
    $( "#dialog-confirm" ).data('id', id ).dialog('open')
}