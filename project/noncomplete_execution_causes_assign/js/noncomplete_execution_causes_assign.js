$( function()
{
	"use strict"
  
  get_data( 0 )

	$( "#user_job_dialog" ).dialog({
      resizable: false,
      height: "auto",
      width: 500,
      height : 270,
      modal: true,
      autoOpen : false,
      buttons: 
      [
        {
        id : "apply",
        // Применить
        text: "\u041f\u0440\u0438\u043c\u0435\u043d\u0438\u0442\u044c",
        // disabled : true,
        click : function() 
        {
         	let id = 	$( "#user_job_dialog" ).data('id');
			    let list = $( '#user_select_to option' )
			    let member_arr = []
				  let str = "";

    			$.each( list , function( key, item )
        		{
    				  member_arr.push( $( item ).val() )
    				  str += '<span>' + ( $( item ).text() ) + '</span>'
        		});

				let member_list = member_arr.join(',')
				$('.res_change_button[data-id=' + id + ']').data('list', member_list )

    			$.post(
                          '/project/noncomplete_execution_causes_assign/ajax.update_persons.php',
                          {
                              id  : id,
                              arr : member_arr
                          },
                          function( data )
                          {
                          	$( '.res_list[data-id=' + id + ']').html( str )
                          }
                    );			

              	$( this ).dialog( "close" );
          }
        },
        // закрыть
        {
          id : "close",
          text : "\u0417\u0430\u043a\u0440\u044b\u0442\u044c",
          click : function() 
          {
            $( this ).dialog( "close" );
          }
        }
      ],
      open : function()
      {
      },
      classes:
      {
      	"ui-dialog-titlebar" : "user_job_dialog_title"
      }
    });// .dialog({ classes : { "ui-dialog-titlebar" : "user_job_dialog_title" }});


$( function() {
    $( "#delete_dialog" ).dialog({
      resizable: false,
      height: "auto",
      width: 400,
      modal: true,
      autoOpen: false,
      buttons: {
        "\u0423\u0434\u0430\u043B\u0438\u0442\u044C": function() 
        {
            let id = $( this ).data('id')
            let aim = $( this ).data('aim')
            let el = this 

            if( aim == 'expl' )
              $.post(
                '/project/noncomplete_execution_causes_assign/ajax.delete_cause_expl.php',
                    {
                        id  : id,
                    },
                    function( data )
                    {
                      // cons( data )
                      $( '.cause_expl_input[data-id=' + id + ']' ).remove()
                      $( el ).dialog( "close" );
                    }
                  );

            if( aim == 'cause' )
              $.post(
                '/project/noncomplete_execution_causes_assign/ajax.delete_cause.php',
                    {
                        id  : id,
                    },
                    function( data )
                    {
                      $( 'input.cause_description[data-id=' + id + ']' ).closest( 'tr' ).remove()
                      rows_renumber()
                      $( el ).dialog( "close" );
                    }
                  );
        },
        "\u041E\u0442\u043C\u0435\u043D\u0430": function() {
          $( this ).dialog( "close" );
        }
      },
      classes:
      {
        "ui-dialog-titlebar" : "delete_dialog_title"
      }
    });
  } );

function  res_change_button_click() 
{
	let id = $( this ).data('id')
	
	// Очистить список участников
	let list = $( '#user_select_to option' )
	move_selected_options( '#user_select_from' , list )

	// Перенести членов обсуждения в список участников
	let member_list = String( $( this ).data('list') );

	if( member_list.length )
	{
		let member_arr = member_list.split(',');

		if( member_arr.length )
		{
			member_arr.forEach(function(item, i, arr) 
			{
				var option = $( 'option[value=' + item + ']' )
				$( '#user_select_to' ).append( $( option ) )
			});

			sort_select ( '#user_select_to' )
		}
	}

	$( "#user_job_dialog" ).data('id', id ).dialog('open')
}


function user_job_dialog_select_dblclick()
{
  var cls = $( this ).parent().attr('id');

  if( res_id == $( this ).val() )
    return;

	if( cls == 'user_select_from')
		$('#user_select_to').append( this )
			else
				$('#user_select_from').append( this )
	
	$( this ).prop('selected', false )

	sort_select ( '#user_select_from' )
	sort_select ( '#user_select_to' )
}

function sort_select ( select )
{
    var options = jQuery.makeArray( $( select ).find('option') );
    var sorted = options.sort(function(a, b) 
    {
        return (jQuery(a).text() > jQuery(b).text()) ? 1 : -1;
    });
    $( select ).append(jQuery( sorted )).attr('selectedIndex', 0);
};

function add_to_team_click()
{
	var list = $( '#user_select_from option:selected' )
	move_selected_options( '#user_select_to' , list )
}

function remove_from_team_click()
{
	var list = $( '#user_select_to option:selected' )
	move_selected_options( '#user_select_from' , list )
}

function move_selected_options( cls, list )
{
	$.each( list , function( key, item )
    {
      if( $( item ).val() != res_id )
        $( cls ).append( $( item ) )
    });

    sort_select ( cls )
    $( cls + ' option' ).attr('selected', false)
}

function add_cause_click() 
{
  get_data( 1 )
}

function get_data( add = 0 )
{
$.post( '/project/noncomplete_execution_causes_assign/ajax.get_data.php',
                          {
                              add  : add,
                              can_edit  : can_edit                              
                          },
                          function( data )
                          {
                            $( '#table_div' ).html( data )
                            adjust_ui() 

                            let divs = $( '.res_cell_wrap')
                            $.each( divs , function( key, item )
                            {
                              $( item ).height( $( item ).parent().height() );
                            });

                            divs = $( '.cause_expl_div_wrap')
                            $.each( divs , function( key, item )
                            {
                              $( item ).height( $( item ).parent().height() );
                            });
                          }
                    );
}


function adjust_ui() 
{
	$('.res_change_button').unbind('click').bind('click', res_change_button_click )
	$('#user_job_dialog option').unbind('dblclick').bind('dblclick', user_job_dialog_select_dblclick );

	$( '#add_to_team' ).unbind('click').bind('click', add_to_team_click )
	$( '#remove_from_team' ).unbind('click').bind('click', remove_from_team_click )
	$('#add_cause').unbind('click').bind('click', add_cause_click )
	
  $('.cause_description').unbind('keyup').bind('keyup', cause_description_keyup )
  
  $('.cause_expl').unbind('keyup').bind('keyup', cause_explanation_keyup )
  $('.del_expl_img').unbind('click').bind('click', del_img_click )
  $('.add_cause_expl').unbind('click').bind('click', add_cause_expl_click )
  $('.del_cause_img').unbind('click').bind('click', del_cause_img_click )

}

function cause_description_keyup() 
{
	let id = $( this ).data('id')
	let val = $( this ).val()

$.post(
      '/project/noncomplete_execution_causes_assign/ajax.update_cause.php',
      {
          id  : id,
          cause : val
      },
      function( data )
      {
      	cons( data )
      }
);			

}

function del_img_click()
{
  let id = $( this ).siblings('input').data('id')
  $('#delete_dialog .dialog_text').text('\u0414\u0430\u043D\u043D\u043E\u0435 \u043F\u043E\u0434\u0442\u0432\u0435\u0440\u0436\u0434\u0435\u043D\u0438\u0435 \u0431\u0443\u0434\u0435\u0442 \u0443\u0434\u0430\u043B\u0435\u043D\u043E. \u0412\u044B \u0443\u0432\u0435\u0440\u0435\u043D\u044B?')

  $( "#delete_dialog" ).data('id', id ).data('aim', 'expl' ).dialog('open')
}

function cause_explanation_keyup() 
{
  let id = $( this ).data('id')
  let val = $( this ).val()

  $.post(
      '/project/noncomplete_execution_causes_assign/ajax.update_cause_expl.php',
        {
            id  : id,
            explanation : val
        },
        function( data )
        {
          // cons( data )
        }
      );
}


function add_cause_expl_click()
{
  let id = $( this ).data('id')

    $.post(
      '/project/noncomplete_execution_causes_assign/ajax.add_cause_expl.php',
        {
            id  : id,
        },
        function( data )
        {
          $( '.cause_expl_div[data-id=' + id + ']').append( data )
          adjust_ui() 
          // cons( data )
        }
      );
}

function del_cause_img_click()
{
  let id = $( this ).data('id')
  $('#delete_dialog .dialog_text').text('\u0414\u0430\u043D\u043D\u0430\u044F \u043F\u0440\u0438\u0447\u0438\u043D\u0430 \u0438 \u0432\u043B\u043E\u0436\u0435\u043D\u043D\u044B\u0435 \u043F\u043E\u0434\u0442\u0432\u0435\u0440\u0436\u0434\u0435\u043D\u0438\u044F \u0431\u0443\u0434\u0443\u0442 \u0443\u0434\u0430\u043B\u0435\u043D\u044B. \u0412\u044B \u0443\u0432\u0435\u0440\u0435\u043D\u044B?')

  $( "#delete_dialog" ).data('id', id ).data( 'aim', 'cause' ).dialog('open')
}


function rows_renumber()
{
  let line = 1 
  let rows = $( '.row_number' )

  $.each( rows , function( key, item )
  {
    $( item ).text( line ++ );
  });
}

function cons( argument ) 
{
	console.log( argument )
}

});