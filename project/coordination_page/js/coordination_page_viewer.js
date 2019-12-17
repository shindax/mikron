// Actions after full page loading
$( function()
{
  $( "#delete_page_dialog" ).dialog({
      resizable: false,
      height: "auto",
      width: 400,
      modal: true,
      autoOpen: false,
      create: function()
      {
        $('div.ui-widget-header').css('background','#DC143C').css('color','white').css('font-weight','bold')

        $(this).closest(".ui-dialog")
      .find(".ui-dialog-titlebar-close")
      .css( { 'padding':'0'} )
      .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
      },
      buttons: {
        "\u0423\u0434\u0430\u043B\u0438\u0442\u044C": function() 
        {
          id = $( this).data('id')
          $( this ).dialog( "close" );

          $.post(
              '/project/coordination_page/ajax.delete_page.php',
              {
                id : id
              },
              function( data )
              {
                console.log( data )
              }
            ); 

          var but = $( '.head_button.btn-primary' ).data('id')
          if( but == 'in_work')
            get_incompleted_pages();
              else
                get_completed_pages();
              
        },
        '\u041E\u0442\u043C\u0435\u043D\u0430': function() 
        {
          $( this ).dialog( "close" );
        }
      }
    });

  get_incompleted_pages();
  adjust_ui();
});

function adjust_ui()
{
  $( '.head_button' ).unbind('click').bind('click', button_click )
  $( '.del_page' ).unbind('click').bind('click', del_page_click )
}

function button_click( event )
{
  event.preventDefault();
  $( '.head_button' ).removeClass('btn-primary').addClass('btn-secondary')
  $( this ).addClass('btn-primary').removeClass('btn-secondary')
  var id = $( this ).data('id')

  if( id == 'completed' )
    get_completed_pages();

  if( id == 'in_work' )
    get_incompleted_pages();
}

function del_page_click()
{
  var tr = $( this ).closest( 'tr');
  var id = $( tr ).data('id');
  $( "#delete_page_dialog" ).data('id',id ).dialog('open');
}

function get_incompleted_pages()
{
$.post(
        '/project/coordination_page/ajax.get_incompleted_pages.php',
        {
          can_delete_arr : can_delete_arr,
          user_id : user_id
        },
        function( data )
        {
          $('#table_div').empty().append( data )

              $.post(
              '/project/coordination_page/ajax.get_relevant_pages.php',
              {
                can_delete_arr : can_delete_arr,
                user_id : user_id
              },
              function( data )
              {
                $('#table_div').append( data )
                adjust_ui()
              }
            );
              
          adjust_ui()
        }
      );
}

function get_completed_pages()
{
 $.post(
        '/project/coordination_page/ajax.get_completed_pages.php',
        {
          can_delete_arr : can_delete_arr,
          user_id : user_id
        },
        function( data )
        {
          $('#table_div').empty().append( data )
          adjust_ui()
        }
      ); 
}