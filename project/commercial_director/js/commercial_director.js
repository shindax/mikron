$( function()
{
  $('[data-toggle="tooltip"]').tooltip();
  $('.delete_theme').unbind('click').bind('click', delete_theme_img_click );

  $('.rate_input').unbind('keyup').bind('keyup', rate_input_keyup );
  $('.note_input').unbind('keyup').bind('keyup', note_input_keyup );

  $('.common_rate_input').unbind('keyup').bind('keyup', common_rate_input_keyup );
  $('.common_note_input').unbind('keyup').bind('keyup', common_note_input_keyup );


});

function common_note_input_keyup()
{
  var data = $( this ).val();
  var id = $( this ).data('id');
  save_common_data( id, 'note', data );  
}


function common_rate_input_keyup()
{
  var num = $( this ).val();
  var id = $( this ).data('id');

  if( isNaN( num ) )
  {
       num = 1;
       $( this ).addClass("bg-warning text-white").tooltip('hide').attr('data-original-title','\u041D\u0435\u0432\u0435\u0440\u043D\u043E\u0435 \u0437\u043D\u0430\u0447\u0435\u043D\u0438\u0435').tooltip('show');
  }
  else{
          $( this ).removeClass("bg-warning text-white").tooltip('hide').attr('data-original-title','\u0422\u0435\u043A\u0443\u0449\u0435\u0435 \u0437\u043D\u0430\u0447\u0435\u043D\u0438\u0435 \u0448\u0442\u0440\u0430\u0444\u0430').tooltip('show');
          num *= 1 ;
      }

  $( this ).val( num );

  save_common_data( id, 'rate', num );
}

function note_input_keyup()
{
  var data = $( this ).val();
  var id = $( this ).data('id');
  save_data( id, 'note', data );  
}

function rate_input_keyup()
{
  var num = $( this ).val();
  var id = $( this ).data('id');

  if( isNaN( num ) )
  {
       num = 1;
       $( this ).addClass("bg-warning text-white").tooltip('hide').attr('data-original-title','\u041D\u0435\u0432\u0435\u0440\u043D\u043E\u0435 \u0437\u043D\u0430\u0447\u0435\u043D\u0438\u0435').tooltip('show');
  }
  else{
          $( this ).removeClass("bg-warning text-white").tooltip('hide').attr('data-original-title','\u0422\u0435\u043A\u0443\u0449\u0435\u0435 \u0437\u043D\u0430\u0447\u0435\u043D\u0438\u0435 \u0448\u0442\u0440\u0430\u0444\u0430').tooltip('show');
          num *= 1 ;
      }

  $( this ).val( num );

  save_data( id, 'rate', num );

}

function save_data( id, field, data )
{
    $.post(
        "project/commercial_director/ajax.UpdateData.php",
        {
            data   : data,
            field : field, 
            id : id
        },
        function( data )
        {
        }
    );

}


function delete_theme_img_click()
{
  $( this ).tooltip('hide');
  var id = $( this ).parents('tr').attr('id');

    $.post(
        "project/commercial_director/ajax.DeleteRow.php",
        {
            id : id
        },
        function( data )
        {
//            alert( data );
        }
    );

    $('tr[id = ' + id + ']').remove();

    var line = 1 ;
    var tr = $('tr[id]');

    $.each( tr , function( key, value )
    {
      $( value ).find('span').eq(0).text( line ++ );
    });

}

function save_common_data( id, field, data )
{
    $.post(
        "project/commercial_director/ajax.UpdateCommonData.php",
        {
            data   : data,
            field : field, 
            id : id
        },
        function( data )
        {
        }
    );
}
