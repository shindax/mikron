function adjustDropDownSelect() 
{
var dropdownList = $('dl[id^=dropdown]');

$.each( dropdownList, function( key, value )
{
    var sel_str = '#' + $(value).attr('id');

    $( sel_str + ' dt a').on('click', function() 
    {
      $('dl[id^=dropdown]').find('ul').hide();
      $( sel_str + ' dd ul').slideToggle('fast');
    });

    $( sel_str + ' dd ul li a').on('click', function() 
    {
      $( sel_str + ' dd ul').hide();
    });

});

$('mutliSelect input[type=checkbox]').prop('checked', false );

$( document ).bind('click', function(e) 
{
  var $clicked = $(e.target);
  var parent_id = $clicked.parents('dl').attr('id');
  var updateNeed = 0 ;

  var openedDropDownWin = $('dl[id^=dropdown]');

  if ( ! parent_id ) 
  {
    var dropdownList = $('dl[id^=dropdown]');

    $.each( dropdownList, function( key, value )
    {
    
      var sel_str = '#' + $( value ).attr('id');
      var changed = + $( sel_str ).attr('data-changed') ;

        if( changed )
        {
          updateNeed ++ ;
          $( sel_str ).attr('data-changed','0') ;
        }

      $( sel_str + ' dd ul').hide();
    });
  }

  if( updateNeed )
    update( 'need to update ' + updateNeed );
    
});

$('.mutliSelect input[type="checkbox"]').on('click', function() 
{
  var parent_id = '#' + $( this ).parents('dl').attr('id');
  var title = $(this).closest( parent_id +'.mutliSelect').find('input[type="checkbox"]').val();
  title = $(this).val() + ",";

  if ($(this).is(':checked')) 
  {
    var html = '<span data-title="' + title + '">' + title + '</span>';
    $( parent_id + ' .multiSel').append(html);
    $( parent_id + ' .hida').hide();
  }
  else 
  {
    var itemListCnt = $( parent_id + ' li input:checked').length;
    $( parent_id +' span[data-title="' + title + '"]').remove();

    if( itemListCnt == 0 )
      $( parent_id + ' .hida').show();    
  }
  
  $( parent_id ).attr('data-changed','1');
  
});

} //function adjustDropDownSelect() 

function getSelectedValue( id ) 
{
    var checkedItemsList = $( "#" + id ).find("input:checked");
    var checkedItemsNames = [];

    if( checkedItemsList.length )
    $.each( checkedItemsList, function( key, value )
    {
        checkedItemsNames[ key ] = $( value ).data('id');
    });

  return checkedItemsNames;
}

function update( str )
{
 var checkList = $('.mutliSelect input[type="checkbox"]');
 var str = '';

    $.each( checkList, function( key, value )
    {
         str += $( value ).prop('checked') == true ? '1, ' : '0, ';
    });

    var dropdownList = $('dl[id^=dropdown]');

    $.each( dropdownList, function( key, value )
    {
        var sel_str = '#' + $(value).attr('id');
        var sel_list = $( sel_str + ' .multisel span');
        var sel_list_cnt = sel_list.length ;

        $.each( sel_list, function( sel_key, sel_value )
        {
            var str = $( sel_value ).text().trim();
            if( sel_key + 1 == sel_list_cnt )
                $( sel_value ).text( removeComma( str ) );
                    else
                        $( sel_value ).text( addComma( str ) );
        });

    });
  
  getFilteredData();
}

function removeComma( str )
{
    var last = str.length - 1;
    if( str.charAt(last) == ',')
        return str.substring(0, str.length - 1);
}

function addComma( str )
{
    var last = str.length - 1;
    if( str.charAt(last) == ',')
        return str;
            else
                return str + ', ';
}

function resetDropdown( id )
{
  $( id ).find("input").prop('checked', false ) ;
  $( id ).find(".hida").show() ;
  $( id ).find(".multisel").empty() ;
}