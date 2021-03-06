$( function() 
{
  "use strict"  

  $( "#accordion" ).accordion(
      { 
        heightStyle: "content", 
        active: false, 
        collapsible: true, 
        animate: 
          { 
              duration: 0
          }}).removeClass('hidden');
  adjust_ui();
  make_dialog()

function adjust_ui()
{
  $('.price_input').unbind('keyup').bind('keyup', price_input_keyup ).unbind('blur').bind('blur', price_input_blur );
  $('.note_input').unbind('keyup').bind('keyup', note_input_keyup );  
  
  $('#add_material').unbind('click').bind('click', add_material_press );  
//  $('button[data-id]').unbind('click').bind('click', add_sortament_press );  
  $('.add_sort_img').unbind('click').bind('click', add_sortament_press );    

  $('#dialog_material').unbind('change').bind('click', dialog_material_change );  

  adjust_calendar( $('.actuality_input') ) ;
  adjustCombo();
  $('.del_sort').unbind('click').bind('click', del_img_press );  
}


function note_input_keyup()
{
  var data = $( this ).val();
  var id = $( this ).data('id');
  var field = $( this ).data('field');
  save_data( id, 'note', data );
}

function price_input_keyup()
{
  	var num = $( this ).val();
  	var id = $( this ).data('id');
  	var field = $( this ).data('field');

	var	RegEx=/\s/g;
	num = num.replace( RegEx,"" );
	num = 1 * num.replace( ",","." );

  if( isNaN( num ) )
  {
       $( this ).addClass("bg-warning");
  }
  else{
          $( this ).removeClass("bg-warning");
          num *= 1 ;
    		  save_data( id, field, num );
          let price_without_VAT = ( num / 120 * 100 ).toFixed(2)
          $( this ).parent().parent().find('.price_without_VAT').text(price_without_VAT)
    		  $( this ).data('prev-val', num );
      }

}

function save_data( id, field, data  )
{
    $.post(
        "project/reports/materials/ajax.UpdateData.php",
        {
            data   : data,
            field : field, 
            id : id
        },
        function( result )
        {
          $('tr[data-id=' + id + ']').find('.actuality_input').val( result )          
        }
 	);
}

function price_input_blur()
{
	var num = $( this ).data('prev-val');
	var el = this ;

	    $.post(
	        "project/reports/materials/ajax.NumberFormat.php",
	        {
	            number   : num
	        },
	        function( result )
	        {
	       		$( el ).val( result ).removeClass("bg-warning");
	        }
	 	);
}

function date_process( el )
{
  var date = $( el ).datepicker( "getDate" );
  var year = date.getFullYear();
  var month = 1 + date.getMonth();
  var day = date.getDate();

  var id = $( el ).data('id');  

  save_data( id, 'actuality', year + '-' + month + '-' + day );  
}

function add_sortament_press()
{
  var id = $( this ).data('id');
  var el = this ;

  $.post(
          "project/reports/materials/ajax.AddSortament.php",
          {
              id : id
          },
          function( result )
          {
              $( el ).closest('table').append( result );
            adjust_ui();
          }
    );

}


function adjustCombo()
{
  var rows = $( ".sort_select" );

    $.each( rows , function( key, item )
    {
      var list = $( item ).parent().parent().parent().find('tr.first').data('ids');

    $( item ).autocomplete({
      source: function( request, response )
      {
       $.getJSON(
          "project/reports/materials/ajax.GetSortament.php", 
          { list: list, value : request.term }, response ); 
      },
      search: function( event, ui )      
      {
      },
      minLength: 1,
      select: function( event, ui ) 
      {
        var data = ui.item.data_id ;
        var value = ui.item.value ;
        var id = $( item ).parent().parent().data('id');
        $( item ).parent().text( value );
        var field = 'id_sort';

        $.post(
        "project/reports/materials/ajax.UpdateData.php",
        {
            data   : data,
            field : field, 
            id : id
        },
        function( result )
        {
          var list = $("tr[data-id=" + id + "]").parent().find('tr.first').data('ids') + ',' + data;
          $("tr[data-id=" + id + "]").parent().find('tr.first').data('ids', list );
        }
            );

        
        $.post(
        "project/reports/materials/ajax.UpdateData.php",
        {
            data   : value,
            field : 'sort_note', 
            id : id
        },
        function( result )
        {
        }
         );
      }
    });

    });
}


function del_img_press()
{
  // var tr = $( this ).parent().parent();
  var tr = $( this ).closest('tr');
  var id = $( tr ).data('id');

    $.post(
        "project/reports/materials/ajax.DeleteSortament.php",
        {
            id : id
        },
        function( result )
        {
            $( tr ).remove();
        }
  );

}

function make_dialog()
{
      $( "#create_dialog" ).dialog({
        resizable: false,
        height: 150,
        width: 400,

        modal: true,
        closeOnEscape: true,
        autoOpen : false,

        // position: { my: "left top", at: "left bottom", of: el },
        create : function()
          {
                $('div.ui-widget-header').css('background','#5F9EA0');
          },
        close : function()
          {
          },

        buttons:
        [
            {
            id : 'create',
            disabled : true,

            text: "\u0414\u043e\u0431\u0430\u0432\u0438\u0442\u044c",
            click : function ()
            {
              let mat_id = $( '#dialog_material option:selected' ).val();
              $('#create').button('disable')
              $('#dialog_material').find('option[value=0]').prop('disabled', false).prop('selected', true )
              $( '#dialog_material').find('option[value=' + mat_id + ']' ).remove();

              let that = this 

              $.post(
                      "project/reports/materials/ajax.AddMaterial.php",
                      {
                          id : mat_id,
                          user_id : user_id
                      },
                      function( data )
                      {
                        $('#accordion').accordion("destroy").html( data ).accordion({ heightStyle: "content", active: false, collapsible: true, animate: { duration: 50}});
                        adjust_ui();
                        $( that ).dialog("close");                
                      }
                );

            } // click : function ()
            },
            {
            // 'Отмена' в unicode
            text : "\u041E\u0442\u043C\u0435\u043D\u0430",
            click : function () 
                    {
                        $('#create').button('disable')
                        $('#dialog_material').find('option[value=0]').prop('disabled', false).prop('selected', true )
                        $(this).dialog("close");
                    }
            }
        ]
    }).dialog('option', 'title', '\u0414\u043e\u0431\u0430\u0432\u043b\u0435\u043d\u0438\u0435 \u043d\u043e\u0432\u043e\u0433\u043e \u043c\u0430\u0442\u0435\u0440\u0438\u0430\u043b\u0430' );

}// function make_dialog( el, caption )


function add_material_press()
{
  $( "#create_dialog" ).dialog('open');
}

function dialog_material_change()
{
  $( this ).find('option[value=0]').prop('disabled', true )
  let val = $( this ).val()

  if( val )
    $('#create').button('enable')
      else
        $('#create').button('disable')
}


function cons( arg )
{
  console.log( arg )
}

});
