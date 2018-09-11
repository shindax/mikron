$( function()
{

//* ***********************************************************************************
// Функции для задержки при прокручивании


    var delayedExec = function(after, fn)
    {
        var timer;
        return function()
        {
            timer && clearTimeout(timer);
            timer = setTimeout(fn, after);
        };
    };

// 100ms bebounce
    $('div.center').unbind('scroll').bind('scroll',  delayedExec( 5, table_scroll ) );


// ***********************************************************************************

    var container = $('.wrapper')
    $('#vpdiv').prepend( container ).css('overflow-x','hidden').css('overflow-y','auto');

    $('#order_search').unbind('keyup').bind('keyup', order_search_keyup );
    $('#order_search').unbind('keydown').bind('keydown', order_search_keydown );
    $('#show_work_orders').unbind('click').bind('click', show_work_orders_click );


    adjustUI();
    $('.view').hide();

    $( "#dialog-confirm" ).dialog(
      {
      autoOpen : false,
      modal: true,
      buttons: {
        '\u041F\u043E\u0441\u043B\u0430\u0442\u044C': function()
        {
                    alert('send');
                    $( "#dialog-confirm" ).dialog('close');
        },
        '\u041E\u0442\u043C\u0435\u043D\u0430': function()
        {
                    alert('cancel');
                    $( "#dialog-confirm" ).dialog('close');
        }
      }
    });

    $( "#input-data-dialog" ).dialog(
      {
      autoOpen : false,
      modal: true,
      buttons: {
        '\u041E\u041A': function()
        {
                    $( "#input-data-dialog" ).dialog('close');
        }
      }
    });

    $('#orders_input').hide();

});


function show_work_orders_click2()
{

    if( $( "#working_orders_pane" ).dialog( "instance" ) == undefined )
          $( "#working_orders_pane" ).dialog(
            {
              autoOpen : true,
              modal: false,
              width: $( window ).width() - 10,
              height: $( window ).height() / 2 + 100,
              position:['top'],
            });
          else
            if( $( "#working_orders_pane" ).dialog( "isOpen" ) )
                $( "#working_orders_pane" ).dialog( "close" );
                    else
                        $( "#working_orders_pane" ).dialog( "open" );

    $(".ui-dialog-titlebar").css({'background':'#98b8e2'});// hide();

}

function adjustUI()
{
    $('.coll_image').unbind('click').bind('click', coll_img_click );
    $('.note_btn').unbind('click').bind('click', note_btn_click );
    $('.alink').unbind('click').bind('click', alink_click );
    $('#orders_input').unbind('click').bind('click', orders_input_click );
    $('#sel_div ul li').unbind('click').bind('click', orders_select_li_click );
    $('#close_order_list').unbind('click').bind('click', close_order_list );
    }

function orders_select_li_click()
{
  // Супер функция для покрутки
    $( "#" + $( this ).attr('zak-id') ).get(0).scrollIntoView( true );

// shindax
//    console.log( $( "#827" ).data('zakdet_arr') );

   show_found_row();

//  $( "#sel_div" ).dialog("destroy");
}

function orders_input_click()
{
  make_select_dialog()  ;
}


function make_select_dialog()
{
      var li =     $('#sel_div ul li');
      var li_cnt = li.length ;

      if( ! li_cnt )
          return ;

      var height = 220;

      if( li_cnt < 10 )
      {
        switch( li_cnt )

              {
                  case 1: height = 72; break ;
                  case 2: height = 92; break ;
                  case 3: height = 105; break ;
                  case 4: height = 120; break ;
                  case 5: height = 145; break ;
                  case 6: height = 150; break ;
                  case 7: height = 170; break ;
                  case 8: height = 200; break ;
                  case 9: height = 210; break ;
              }

//              height = li_cnt * 22 + 41 ;
  }

      $( "#sel_div" ).dialog(
      {
          position: { my: "right bottom", at: "right bottom" },
          minWidth: 100,
          width: 100,
          maxHeight: height,
          height: height
      }).dialog( "moveToTop" );

    $(".ui-dialog-titlebar").hide();
    $( '#order_search' ).focus();
}

function note_btn_click()
{
       var row = $( this ).closest('tr') ;
       var id = $(  row ).attr('data-oper-item-id');
       var msg = $( row ).find('.inp').val();

       if( msg.length )
       {
            $('div.ui-widget-header').css('background','#98b8e2'); //.hide();
            $( "#dialog-confirm" ).dialog('open');
       }

//      alert( id + ' : ' + msg );
}

function alink_click()
{
       var id = $( $( this ).closest('tr') ).attr('data-oper-item-id');
       alert( id );
}

function coll_img_click()
{
    $('.dse_row, .operation_row').remove();
    $('.coll_image').attr( {'src' : 'uses/collapse.png' });
    var state = $( this ).attr( 'data-state' );

    if( state == '0' )
    {
        $(this).attr( {'data-state' : '1', 'src' : 'uses/expand.png' });

        var row = $( this ).closest('tr') ;
        var id = $( row ).attr('id');

        var data = new FormData();
        data.append( 'id', id );

        $.post(
            '/project/work_order/ajax.get_order_tasks.php',
            {
                id   : id
            },
            function( data )
            {
                $( row ).after( data );
                adjustUI();
            }
        );
    }
    else
        $(this).attr( {'data-state' : '0', 'src' : 'uses/collapse.png' });
}


function close_order_list()
{
    if ($("#sel_div").hasClass('ui-dialog-content'))
          $( "#sel_div" ).dialog("destroy");
}

function order_search_keydown( e )
{
        if (e.which == 13)
        {
            find_orders();
        }
}


function order_search_keyup()
{
    find_orders();
}

function find_orders()
{
    var val = $( "#order_search" ).val();
    $('tr.zak_row').removeData('zakdet_arr');
    $('tr.dse_row').remove();
    $('tr.operation_row').remove();
    $('img.coll_image').attr( {'data-state' : '0', 'src' : 'uses/collapse.png' });

     close_order_list();

    if( val.length == 0 )
      return ;

     $.post(
        '/project/work_order/ajax.get_found_orders.php',
      {
        val        :  val
      },
      processOrderSearch,
      'json'
      );
}

function processOrderSearch( data )
{
//  console.log( data[1] );
  $('#order_ul').empty();

  var ord_arr = [];

    if( data )
    {
        if( data[0] )
             $( data[0] ).each( function( index, element )
                    {
                        if( element['zak_id'] != undefined )
                            {
                                var zak_id = element['zak_id'];
                                ord_arr[ zak_id ] = element['zak_type'] + " " + element['zak_name']
                            }
                     });

        if( data[1] )
            {
                 $( data[1] ).each( function( index, element )
                        {
                            if( element['zak_id'] != undefined )
                                {
                                    var zak_id = element['zak_id'];
                                    ord_arr[ zak_id ] = element['zak_type'] + " " + element['zak_name']
                                }
                         });

            // shindax

            var arr = data[1];
//            console.log( arr );

                  arr.forEach(function( item, i, arr)
                  {
                      var zak_id = item['zak_id'];
                      var zakdet_id = item['zakdet_id'];
                      var tr = $('#' + zak_id );

                      if( $( tr ).data('zakdet_arr') == undefined )
                        $( tr ).data( 'zakdet_arr', [ zakdet_id ] );
                          else
                            $( tr ).data( 'zakdet_arr' ).push( zakdet_id );
                  });

            }
    }

            for ( var i = 0; i < ord_arr.length; i++)
            {
                if( ord_arr[ i ] != undefined )
                  $('#order_ul').append(  "<li zak-id='" + i + "'>" + ord_arr[ i ] + "</li>" ) ;
            }


    show_found_row();
    make_select_dialog();
    $('#sel_div ul li').unbind('click').bind('click', orders_select_li_click );

  adjustUI();
}

// function zak_sort( a, b )
// {
//   var str1 = $( a ).attr('data-zak-name');
//   var str2 = $( b ).attr('data-zak-name');

//   if( str1 == str2 )
//       return 0;

//   return str1 > str2 ? 1 : -1 ;
// }

function table_scroll()
{
  show_found_row();
}

function show_found_row( )
{
  var visible_row = visible_row_check();

//  console.log( visible_row );

  visible_row.forEach( function( item, i, arr )
    {
        var tr = $( '#' + item );
        var zakdet_id = $( tr ).data('zakdet_arr') ;
        if(  zakdet_id != undefined )
        {
            $.post(
              '/project/work_order/ajax.get_found_dse_html.php',
              {
                zakdet_id    :  zakdet_id
              },
                  function( data )
                  {
                    console.log( data );
                    $( tr ).after( data );
                    adjustUI();
                  }
              );
          $( tr ).removeData('zakdet_arr') ;
        }
    });

//  console.log( visible_row );
}

function show_work_orders_click3()
{
}


function show_work_orders_click()
{
      var frame_state = $('#shift_order').attr('data-closed');
      var src ="index.php?do=show&formid=158&p0=20180201&p1=1"
      var shift = 1 * $( '#shift_sel option:selected' ).val();

      if( !shift )
      {
          $('#input-data-dialog').dialog('open');
      }
      else
      {
              if( frame_state == '0')
              {
                $('#shift_order').attr('src',src ).attr('data-closed','1').show();
                $('#show_work_orders').text('\u0421\u043A\u0440\u044B\u0442\u044C \u0441\u043C\u0435\u043D\u043D\u044B\u0435 \u0437\u0430\u0434\u0430\u043D\u0438\u044F');
              }
              else
              {
                    $('#shift_order').attr('src',src ).attr('data-closed','0').hide();            
                    $('#show_work_orders').text('\u041F\u043E\u043A\u0430\u0437\u0430\u0442\u044C \u0441\u043C\u0435\u043D\u043D\u044B\u0435 \u0437\u0430\u0434\u0430\u043D\u0438\u044F');
              }            
      }
}