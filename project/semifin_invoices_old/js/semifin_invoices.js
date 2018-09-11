$( function()
{
	    $('#save_and_print').unbind('click').bind('click', saveAndPrintButtonClick );
      $('button[data-id]').unbind('click').bind('click', printButtonClick );
      $('#year-select').unbind('change').bind('change', yearSelectChange );
      $('#inv-num-select').unbind('change').bind('change', invNumSelectChange );
      yearSelectChange();
});

function invNumSelectChange()
{
      var year = $( "#year-select option:selected" ).val();
      var num = $( "#inv-num-select option:selected" ).val();
      viewerDataChange( year, num );
      $('#print').attr('data-id', num );
}

function yearSelectChange()
{
      var year = $( "#year-select option:selected" ).val();

        $.post(
          "project/semifin_invoices/ajax.viewer.yearChange.php",
            {
              year   : year
            },
                        function( data )
                        {
                            $('#inv-num-select').empty().append( data );
                            viewerDataChange( year, 0 );
                        }
                    );
}

function viewerDataChange( year, num )
{
    if(  + num )
    $.post(
      "project/semifin_invoices/ajax.viewer.change.php",
        {
          year   : year,
          num  : num
        },
                    function( data )
                    {
                      $('.order_row ').remove();
                      $('#invoices_table').append( data );
                      $('#print').show();
                    }
                );
    else
          $.post(
            "project/semifin_invoices/ajax.view_all.php",
              {
                year   : year,
                num  : num
              },
                          function( data )
                          {
//                            $('.order_row ').remove();
                            $('#invoices_table').append( data );
                            $('#print').hide();
                            $('button[data-id]').unbind('click').bind('click', printButtonClick );
                          }
                      );
}

function saveAndPrintButtonClick()
{

  var tr_arr = $('.order_row');
  var today = $('#today').attr('data-day');
  var p6 = $('#inv_num').val();

  var ord_arr = [];
  var url= '';

  var p0 = '';
  var p1 = '';
  var p2 = '';
  var p3 = '';
  var p4 = '';
  var p5 = '';
  var p7 = '';

      $.each( tr_arr , function( key, value )
      {
        var id = $( value ).attr('data-id');

        var dse_name = $( value ).find('.dse_name').text();
        var order_name = $( value ).find('.order_name').text();
        var draw_name = $( value ).find('.draw_name').text();

        var part_num = $( value ).find('.part_num').val();
        var count = $( value ).find('.count').val();
        var transfer_place = $( value ).find('.transfer_place').val();
        var storage_time = $( value ).find('.storage_time').val();
        var note = $( value ).find('.note').val();

        ord_arr [ key ] =
        {
            "id" : id,
            "dse_name" : dse_name,
            "order_name" : order_name,
            "draw_name" : draw_name,
            "inv_num" : p6,
            "today" : today,
            "part_num" : part_num,
            "count" : count,
            "transfer_place" : transfer_place,
            "storage_time" : storage_time,
            "note" : note,
          };

        p0 += id + ",";
        p1 += part_num + ",";
        p2 += count + ",";
        p3 += transfer_place + ",";
        p4 += storage_time + ",";
        p7 += note + ",";
      });

      url = "print.php?do=show&formid=250&p0=" + p0.slice(0, -1) + "&p1=" + p1.slice(0, -1) + "&p2=" + p2.slice(0, -1) + "&p3=" + p3.slice(0, -1) + "&p4=" + p4.slice(0, -1) + "&p6=" + p6 + "&p7=" + p7.slice(0, -1);

    $.post(
      "project/semifin_invoices/ajax.saveData.php",
        {
          data   : ord_arr
        },
                    function( data )
                    {
//                      console.log( data );
                      document.location.href = url ;
                    }
        );
}

function printButtonClick()
{
  var inv_id = $( this ).data('id');



  var tr_arr = $('tr[data-inv-num="' + inv_id + '"]');
  var p6 = inv_id;

  var p5 = $( "#year-select option:selected" ).val() + "\u0433.";
  var url= '';

  var p0 = '';
  var p1 = '';
  var p2 = '';
  var p3 = '';
  var p4 = '';
  var p7 = '';

      $.each( tr_arr , function( key, value )
      {
        var id = $( value ).attr('data-id');
        var part_num = $( value ).find('.part_num').text();
        var count = $( value ).find('.count').text();
        var transfer_place = $( value ).find('.transfer_place').text();
        var storage_time = $( value ).find('.storage_type').attr('data-bin');
        var note = $( value ).find('.note').text();

        p0 += id + ",";
        p1 += part_num + ",";
        p2 += count + ",";
        p3 += transfer_place + ",";
        p4 += storage_time + ",";
        p7 += note + ",";
      });

      url = "print.php?do=show&formid=252&p0=" + p0.slice(0, -1) + "&p1=" + p1.slice(0, -1) + "&p2=" + p2.slice(0, -1) + "&p3=" + p3.slice(0, -1) + "&p4=" + p4.slice(0, -1) + "&p5=" + p5 + "&p6=" + p6 + "&p7=" + p7.slice(0, -1);

        //document.location.href = url ;
        window.open( url, '_blank' );
}
