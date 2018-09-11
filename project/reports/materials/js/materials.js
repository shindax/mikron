  $( function() 
{
  $( "#accordion" ).accordion({ heightStyle: "content", active: false, collapsible: true, animate: { duration: 50}}).removeClass('hidden');
  adjust_ui();
});

function adjust_ui()
{
  $('.price_input').unbind('keyup').bind('keyup', price_input_keyup ).unbind('blur').bind('blur', price_input_blur );
  $('.note_input').unbind('keyup').bind('keyup', note_input_keyup );  
  $('button').unbind('click').bind('click', button_press );  
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

function button_press()
{
  var id = $( this ).data('id');
  var el = this ;

      $.post(
        "project/reports/materials/ajax.AddMaterial.php",
        {
            id : id
        },
        function( result )
        {
          $( el ).parent().parent().find('table').append( result );
          adjust_ui();
        }
  );


}

function adjustCombo()
{
  var rows = $( ".sort_select" );

    $.each( rows , function( key, item )
    {
      // var list = $( item ).parents('table').find('tr.first').data('ids');
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
  var tr = $( this ).parent().parent();
  var id = $( tr ).data('id');

    $.post(
        "project/reports/materials/ajax.DeleteMaterial.php",
        {
            id : id
        },
        function( result )
        {
            $( tr ).remove();
        }
  );

}
