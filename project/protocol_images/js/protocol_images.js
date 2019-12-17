function getWeekDay(date)
{
  // var days = ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб'];
  var days = [ 0, 1, 2, 3,4 ,5 ,6 ];
  return days[date.getDay()];
}

function getBoundWeekDay( date )
{
  var days = [ 3, 4, 4, 2, 2 ,2 ,2 ];
  return days[date.getDay()];
}



// Actions after full page loading
$( function()
{
//    alert( user_id + ' : ' + can_edit );

    adjust_ui();
    // Удаление старой верстки
    $('.A4W').remove();
    $('table.view').hide();

    var cur_date = year + '-' + month + '-01';

    $('.year').datepicker('setDate', new Date() ).data('cur_date', cur_date );

    setCaption( month , year );

// **************************************

    var data = new FormData();
    data.append( 'date', cur_date );
    startLoadingAnimation();

    // Отправляем запрос
    $.ajax({
        url: '/project/protocol_images/ajax.LoadRefDates.php',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Не обрабатываем файлы (Don't process the files)
        contentType: false, // Так jQuery скажет серверу что это строковой запрос
        success: function( respond, textStatus, jqXHR )
        {
            // if everything is OK
            if( typeof respond.error === 'undefined' )
            {
                stopLoadingAnimation();
                var project_plan_date = respond.project_plan_date;
                var plan_date = respond.plan_date;
                var report_date = respond.report_date;

                var data = new FormData();
                data.append( 'date', cur_date );
                startLoadingAnimation();

                // Отправляем запрос
                $.post(
                    "project/protocol_images/ajax.LoadData.php",
                    {
                        date   : cur_date ,
                    },
                    function( data )
                    {
                        stopLoadingAnimation();

                        $('#krz_table_div').empty().append( data );

                        adjust_ui();

                        $('#project_plan_date').datepicker('setDate', new Date( project_plan_date ) );
                        $('#plan_date').datepicker('setDate', new Date( plan_date ) );
                        $('#report_date').datepicker('setDate', new Date( report_date ) );

                        adjustCellColors();
                    }
                );
            }
            else
            {
                console.log('AJAX request errors detected. Server said : ' + respond.error );
            }

        },
        error: function( jqXHR, textStatus, errorThrown )
        {
            console.log('AJAX request errors in uploadProjectFiles.js detected : ' + textStatus + errorThrown );
        }
    });

    $('a.alink').attr('href', "print.php?do=show&formid=240&p0=" + cur_date );

$('img').css('display','block');

});


function upload_file_input_change ()
{
    var id = $( this ).attr('data-id');
    var what = $( this ).attr('data-what');
    var files = this.files;

    if( files.length == 0 )
        return;

    // Создадим данные формы и добавим в них данные файлов из files
    var data = new FormData();
    $.each( files, function( key, value )
    {
        data.append( key, value );
    });

    // Добавить id и what
    data.append( 'id', id );
    data.append( 'what', what );

    startLoadingAnimation();

    // Отправляем запрос
    $.ajax({
        url: '/project/protocol_images/ajax.UploadImage.php?uploadfiles',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Не обрабатываем файлы (Don't process the files)
        contentType: false, // Так jQuery скажет серверу что это строковой запрос
        success: function( respond, textStatus, jqXHR )
        {
            // if everything is OK
            if( typeof respond.error === 'undefined' )
            {
                stopLoadingAnimation();
                var el = $("img[data-id = '" + id + "'][data-what = '" + what + "']");

                if( $( el ).attr('data-current_image') == undefined || $( el ).attr('data-current_image') == 0 )
                    $( el ).attr('data-current_image', '1');

                $( el ).attr(
                            {
                                // Всего изображений
                                'title' : '\u0412\u0441\u0435\u0433\u043E \u0438\u0437\u043E\u0431\u0440\u0430\u0436\u0435\u043D\u0438\u0439 : ' + respond.total_images,
                                'src' : view_image ,
                                'data-total_images' : respond.total_images,
                            });

            $('span.gal_info_span').text( 1 + ' \u0438\u0437 ' + respond.total_images );


            var current_image = respond.total_images == 1 ? 1 : Number( $( el ).attr('data-current_image')) + 1 ;

                var data =
                    {
                        'id' : $( el ).attr('data-id'),
                        'what' : $( el ).attr('data-what'),
                        'total_images' : + respond.total_images,
                        'current_image' : current_image,
                        'dep_name' : $( el ).attr('data-dep_name')
                    }

                adjust_ui();
                viewPopupImages( data );
            }
            else
            {
                console.log('AJAX request errors detected. Server said : ' + respond.error );
            }
        },
        error: function( jqXHR, textStatus, errorThrown )
        {
            console.log('AJAX request errors in uploadProjectFiles.js detected : ' + textStatus + errorThrown );
        }
    });
}



function closeImageView()
{
    $('.my_popup, .my_overlay').css({'opacity':'0', 'visibility':'hidden'});
}


function adjust_ui()
{
    $('#vpdiv').append( $('#date_div') ).append( $('#krz_table_div') ).append( $('.my_popup, .my_overlay') ).append( $('#loadImg') );

    $('.my_popup .my_close_window, .my_overlay').unbind('click').bind('click', closeImageView );

    $('#upload_file_input').unbind('change').bind('change', upload_file_input_change );

    // Image click adjust
    $('.td_img img').unbind('click').bind('click', imgClick );

    $('img.but_load').unbind('click').bind('click', popup_but_load_img_click );
    $('img.but_del').unbind('click').bind('click', popup_but_del_img_click );

    $('img.right_arr_img').unbind('click').bind('click', popup_right_but_click );
    $('img.left_arr_img').unbind('click').bind('click', popup_left_but_click );

    $('._datepicker_').unbind('click').bind('click', function(){ $( this ).blur(); } );

    $('.btn-ok').unbind('click').bind('click', conf_ok_but_click );
    $('.btn-susp,.btn-empty').unbind('click').bind('click', conf_susp_but_click );

    // Date picker adjust
    datePicker();

    if( ! can_edit )
    {
        $('.but_load').css('cursor','default').attr('src', but_load_dis ).unbind('click').bind('click', emptyClick);
        $('.but_del').css('cursor','default').attr('src', but_del_dis ).unbind('click').bind('click', emptyClick);
        $('#report_date').prop( 'disabled',true );
        $('#plan_date').prop( 'disabled',true );
        $('#project_plan_date').prop( 'disabled',true );
    }

        var images = $('img[data-total_images="0"]');

        $.each( images, function( key, value )
        {
          var id = $( value ).attr('data-id');
          var what = $( value ).attr('data-what');

          if( $('input[data-id="' + id + '"][data-what="' + what + '"]' ).val().length == 0 || can_edit == 0 )
               $( value ).css('cursor','default').attr('src', load_image_dis ).unbind('click').bind('click', emptyClick).attr('data-state','dis');
                else
                  $( value ).css('cursor','pointer').attr('src', load_image ).unbind('click').bind('click', imgClick );
        });

}

// Нажатие кнопки "Перенос даты""
function conf_ok_but_click()
{
    var src = $( this ).parent('span').parent('div').find('input') ;
    var val = $( src ).val();
    var id = $( src ).attr('data-id');
    var target = $( 'input[data-id="' + id + '"]' ).eq(1);
    var what = $( target ).attr('data-what');

     var data = new FormData();
    data.append('id', id );
    data.append('what', what );
    data.append('date', val );

//                startLoadingAnimation();

   $('#replace_date_dialog').removeClass('hidden');

    $( "#replace_date_dialog" ).dialog({
        resizable: false,
        height: 150,
        width: 600,
        modal: true,
        closeOnEscape: true,

        position: { my: "left top", at: "left bottom", of: this },

        create : function() { $('div.ui-widget-header').css('background','#FF9933'); }, // Цвет заголовка диалога
        buttons:
        [
            {
             id : "change_date_dialog_add_button",
            // 'Уверен' в unicode url : https://r12a.github.io/apps/conversion/
            text: "\u0423\u0432\u0435\u0440\u0435\u043D",
            click : function ()
            {
                    $( target ).val( val );

                // Отправляем запрос
                $.ajax({
                    url: '/project/protocol_images/ajax.UpdateDates.php',
                    type: 'POST',
                    data: data,
                    cache: false,
                    dataType: 'json',
                    processData: false, // Не обрабатываем файлы (Don't process the files)
                    contentType: false, // Так jQuery скажет серверу что это строковой запрос
                    success: function (respond, textStatus, jqXHR) {
                        // if everything is OK
                        if (typeof respond.error === 'undefined')
                        {
                            what = 'data-confirmed';

                            $.post(
                                "/project/protocol_images/ajax.SendMail.php",
                                {
                                    id: id,
                                    what : what,
                                    val : val,
                                    email : email_secretary
                                },
                                function( data )
                                {
//                                  alert( "Data Loaded: " + data );
                                }
                              );

                            stopLoadingAnimation();
                            adjustCellColors();
                        }
                        else
                        {
                            console.log('AJAX request errors detected. Server said : ' + respond.error);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        console.log('AJAX request errors in uploadProjectFiles.js detected : ' + textStatus + errorThrown );
                    }
                }); // $.ajax({
                $(this).dialog("close");
            }
            },
            {
            // 'Отмена' в unicode
            text : "\u041E\u0442\u043C\u0435\u043D\u0430",
            click : function () {
                        $(this).dialog("close");
                    }
            }
        ]
    })

    $('.ui-dialog-titlebar-close').addClass('ui-button-icon-primary ui-icon ui-icon-closethick');
}

// Нажатие кнопки "Комментировать дату""
function conf_susp_but_click()
{
     if( $( this ).hasClass('btn-empty')  && ! can_confirm )
         return ;

    var src = $( this ).parent('span').parent('div').find('input') ;
    var val = $( src ).val();
    var id = $( src ).attr('data-id');
    var target = $( 'input[data-id="' + id + '"]' ).eq(1);
    var what = $( target ).attr('data-what');

     var data = new FormData();
    data.append('id', id );
    data.append('what', what );
    data.append('date', val );

    var el = this ;

//                startLoadingAnimation();

                // Отправляем запрос
                $.ajax({
                    url: '/project/protocol_images/ajax.GetComments.php',
                           type: 'POST',
                            data: data,
                            cache: false,
                            dataType: 'json',
                            processData: false, // Не обрабатываем файлы (Don't process the files)
                            contentType: false, // Так jQuery скажет серверу что это строковой запрос
                            success: function( respond, textStatus, jqXHR )
                            {
                                // if everything is OK
                                if( typeof respond.error === 'undefined' )
                                {
                                    stopLoadingAnimation();
                                    var resp = respond.comments;
                                    var str = "";

                                    if( resp )
                                    resp.forEach(function( item, i, resp )
                                   {
                                            str += '<p>' + makeDotDate( item.date ) + ' : ' + item.comment + '</p>';
                                    });

                                    str += '<hr>';

                                   $('#prev_comments').html( str ).find('p:even').addClass('even');

                                   $('#project_plan_date_comment_dialog').removeClass('hidden');

                                    $( "#project_plan_date_comment_dialog" ).dialog({
                                        resizable: false,
                                        width: 700,
                                        modal: true,
                                        closeOnEscape: true,
                                        open: function()
                                            {
                                                if( can_confirm )
                                                {
                                                                $('#project_plan_date_comment_dialog input').removeClass('hidden');
                                                                $('#project_plan_date_comment_dialog span').removeClass('hidden');
                                                                $('#add_comment_dialog_add_button').removeClass('hidden');
                                                }
                                                        else
                                                        {
                                                                $('#project_plan_date_comment_dialog input').addClass('hidden');
                                                                $('#project_plan_date_comment_dialog span').addClass('hidden');
                                                                $('#add_comment_dialog_add_button').addClass('hidden');
                                                        }
                                            },
                                        position: { my: "left top", at: "left bottom", of: el },
                                        create : function() { $('div.ui-widget-header').css('background','#FF9933'); }, // Цвет заголовка диалога
                                        buttons:
                                        [
                                            {
                                             id : "add_comment_dialog_add_button",
                                            // 'Добвить' в unicode url : https://r12a.github.io/apps/conversion/
                                            text: "\u0414\u043E\u0431\u0430\u0432\u0438\u0442\u044C",
                                            click : function ()
                                            {
                                                $( el ).removeClass('btn-empty');
                                                $.post(
                                                "project/protocol_images/ajax.AddComment.php",
                                                {
                                                    id   : id ,
                                                    comment : $('#project_plan_date_comment_dialog input').val()
                                                },
                                                function( data )
                                                {
                                                    stopLoadingAnimation();
                                                    adjust_ui();
                                                    adjustCellColors();
                                                }
                                            );
                                                $( this ).dialog("close");
                                            }
                                            },
                                            {
                                            // 'Отмена' в unicode
                                            text : "\u041E\u0442\u043C\u0435\u043D\u0430",
                                            click : function () {
                                                        $( this ).dialog("close");
                                                    }
                                            }
                                        ]
                                    });

                                    $('#project_plan_date_comment_dialog input').val('');
                                    $('.ui-dialog-titlebar-close').addClass('ui-button-icon-primary ui-icon ui-icon-closethick');
                                }
                                else
                                    console.log('AJAX request errors detected. Server said : ' + respond.error );
                            },
                            error: function( jqXHR, textStatus, errorThrown )
                            {
                                console.log('AJAX request errors in uploadProjectFiles.js detected : ' + textStatus + errorThrown );
                            }
                        });
}


function emptyClick()
{
    event.stopPropagation();
    return ;
}

function popup_left_but_click()
{
    if( + $( '.my_popup' ).data('current_image') == 0 )
        $( '.my_popup' ).data('current_image', 1 ) ;
    else
        $( '.my_popup' ).data('current_image', $( '.my_popup' ).data('current_image') - 1 ) ;

    var data =
        {
            'id' : $( '.my_popup' ).data('id'),
            'what' : $( '.my_popup' ).data('what'),
            'total_images' : + $( '.my_popup' ).data('total_images'),
            'current_image' : + $( '.my_popup' ).data('current_image'),
            'dep_name' : $( '.my_popup' ).data('dep_name')
        }

    viewPopupImages( data );
}

function popup_right_but_click()
{
    if( + $( '.my_popup' ).data('current_image') == + $( '.my_popup' ).data('total_images') )
            $( '.my_popup' ).data('current_image') = + $( '.my_popup' ).data('total_images');
            else
                $( '.my_popup' ).data('current_image', $( '.my_popup' ).data('current_image') + 1 ) ;

    var data =
        {
            'id' : $( '.my_popup' ).data('id'),
            'what' : $( '.my_popup' ).data('what'),
            'total_images' : + $( '.my_popup' ).data('total_images'),
            'current_image' : + $( '.my_popup' ).data('current_image'),
            'dep_name' : $( '.my_popup' ).data('dep_name')
        }

    viewPopupImages( data );
}

function popup_but_load_img_click()
{
    var id = $( '.my_popup' ).data('id');
    var what = $( '.my_popup' ).data('what');
    addImages( id, what );
}

function popup_but_del_img_click()
{
    var popup_div = $( this ).parent().parent().parent();
    var id = popup_div.data('id');
    var what = popup_div.data('what');
    showDeleteDialog();
}

function viewPopupImages( indata )
{
    // Создадим данные формы и добавим в них данные файлов из files
    var data = new FormData();
    // Добавить id и what
    data.append( 'id', indata.id );
    data.append( 'what', indata.what );
    data.append( 'total_images' , indata.total_images );
    data.append( 'image_num' , indata.current_image );

    $( '.my_popup' ).data('id', indata.id )
    $( '.my_popup' ).data('what', indata.what ) ;
    $( '.my_popup' ).data('total_images', indata.total_images ) ;
    $( '.my_popup' ).data('current_image', indata.current_image ) ;
    $( '.my_popup' ).data('dep_name', indata.dep_name ) ;

    $('img[data-id="' + indata.id + '"][data-what="' + indata.what + '"]' ).attr('data-current_image', indata.current_image );

    startLoadingAnimation();

    // Отправляем запрос
    $.ajax({
        url: '/project/protocol_images/ajax.LoadImage.php',
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Не обрабатываем файлы (Don't process the files)
        contentType: false, // Так jQuery скажет серверу что это строковой запрос
        success: function( respond, textStatus, jqXHR )
        {
            // if everything is OK
            if( typeof respond.error === 'undefined' )
            {
                stopLoadingAnimation();

                var id = indata.id;
                var what = indata.what;
                var total_images = + respond.total_images;
                var current_image = indata.current_image;
                var dep_name = indata.dep_name;

                $('#image').attr('src', respond.file_path );
                $('#image').attr('onclick', 'window.open(\'' + respond.file_path + '\')' );
 
                switch( what )
                {
                    case 'project_plan' : what = '\u041F\u0440\u043E\u0435\u043A\u0442 \u043F\u043B\u0430\u043D\u0430';  break ;
                    case 'plan' : what = '\u041F\u043B\u0430\u043D' ;  break ;
                    case 'report' : what = '\u041E\u0442\u0447\u0435\u0442' ;  break ;
                }

                $('span.gal_info_span').text( current_image + ' \u0438\u0437 ' + respond.total_images );
                $('span.gal_caption_span').html( dep_name + '<br>' + what ); // + '<br>' + 'id : ' + id + '<br>'  + current_image + ' \u0438\u0437 :' + total_images );

                AdjustArrows( current_image, respond.total_images );
                $('.my_popup, .my_overlay').css({'opacity': '1', 'visibility': 'visible'});
            }
            else
            {
                console.log('AJAX request errors detected. Server said : ' + respond.error );
            }
        },
        error: function( jqXHR, textStatus, errorThrown )
        {
            console.log('AJAX request errors in uploadProjectFiles.js detected : ' + textStatus + errorThrown );
        }
    });
}

function addImages( id, what )
{
    $('#upload_file_input').attr({'data-id': id, 'data-what': what}).click();
}

function imgClick()
{
    event.stopPropagation();

    var data =
        {
            'id' : $(this).data('id'),
            'what' : $(this).data('what'),
            'total_images' : +$(this).attr('data-total_images'),
            'current_image' : +$(this).attr('data-current_image'),
            'dep_name' : $(this).attr('data-dep_name')
        }

    if( + $( this ).attr('data-total_images') )
    {
        viewPopupImages( data );
    }
        else
    {
            addImages( $(this).data('id') , $(this).data('what') );
    }
}

function showDeleteDialog()
{
    $( "#dialog" ).dialog({
        resizable: false,
        height: "auto",
        width: 600,
        modal: true,
        closeOnEscape: true,
        create : function() { $('div.ui-widget-header').css('background','#FF9933'); }, // Цвет заголовка диалога
        buttons: {
            /* 'Удалить' в unicode url : https://r12a.github.io/apps/conversion/ */
            "\u0423\u0434\u0430\u043B\u0438\u0442\u044C" : function()
            {

                        var id = $('.my_popup').data('id');
                        var what = $('.my_popup').data('what');
                        var dep_name = $('.my_popup').data('dep_name');

                        // Создадим данные формы и добавим в них данные файлов из files
                        var data = new FormData();
                        // Добавить id и what
                        data.append( 'id', id );
                        data.append( 'what', what );
                        data.append( 'total_images' , $('.my_popup').data('total_images') );
                        data.append( 'image_num' , $('.my_popup').data('current_image') );

                        startLoadingAnimation();

                        // Отправляем запрос
                        $.ajax({
                            url: '/project/protocol_images/ajax.DeleteImage.php',
                            type: 'POST',
                            data: data,
                            cache: false,
                            dataType: 'json',
                            processData: false, // Не обрабатываем файлы (Don't process the files)
                            contentType: false, // Так jQuery скажет серверу что это строковой запрос
                            success: function( respond, textStatus, jqXHR )
                            {
                                // if everything is OK
                                if( typeof respond.error === 'undefined' )
                                {
                                    stopLoadingAnimation();

                                    var current_image = respond.current_image ;
                                    var total_images = respond.total_images ;

                                    var el = $("img[data-id = '" + id + "'][data-what = '" + what + "']");

                                    $( el ).attr(
                                        {
                                            // Всего изображений
                                            'title' : '\u0412\u0441\u0435\u0433\u043E \u0438\u0437\u043E\u0431\u0440\u0430\u0436\u0435\u043D\u0438\u0439 : ' + respond.total_images,
                                            'src' : view_image ,
                                            'data-total_images' : respond.total_images,
                                            'data-current_image' : respond.current_image
                                        });

                                    var data =
                                        {
                                            'id' : id,
                                            'what' : what,
                                            'total_images' : + respond.total_images,
                                            'current_image' : + respond.current_image,
                                            'dep_name' : dep_name
                                        }

                                    if( respond.total_images == 0 )
                                    {
                                        $('.my_popup, .my_overlay').css({'opacity': '0', 'visibility': 'hidden'});
                                        $( el ).attr('src', load_image );
                                        adjust_ui();
                                        AdjustArrows( current_image, total_images );
                                    }
                                            else
                                                {
                                                    $('.my_popup, .my_overlay').css({'opacity': '1', 'visibility': 'visible'});
                                                    adjust_ui();
                                                    AdjustArrows( current_image, total_images );
                                                    viewPopupImages( data );
                                                }


                                }
                                else
                                {
                                    console.log('AJAX request errors detected. Server said : ' + respond.error );
                                }
                            },
                            error: function( jqXHR, textStatus, errorThrown )
                            {
                                console.log('AJAX request errors in uploadProjectFiles.js detected : ' + textStatus + errorThrown );
                            }
                        });



                $( this ).dialog( "close" );
            },
            /* 'Отмена' в unicode*/
            "\u041E\u0442\u043C\u0435\u043D\u0430": function()
            {

                $( this ).dialog( "close" );
            }
        }
    });

    $('.ui-dialog-titlebar-close').addClass('ui-button-icon-primary ui-icon ui-icon-closethick');
}

function startLoadingAnimation() // - функция запуска анимации
{
  var imgObj = $("#loadImg").show();

  var centerY = $(window).height() / 2  - imgObj.height()/2 ;
  var centerX = $(window).width()  / 2  - imgObj.width()/2;

  // установка координат изображения:
  imgObj.offset( { top: centerY, left: centerX } );
}

function stopLoadingAnimation() // - функция останавливающая анимацию
{
  $("#loadImg").hide();
}


var monthNames = ['\u042F\u043D\u0432\u0430\u0440\u044C','\u0424\u0435\u0432\u0440\u0430\u043B\u044C','\u041C\u0430\u0440\u0442','\u0410\u043F\u0440\u0435\u043B\u044C','\u041C\u0430\u0439','\u0418\u044E\u043D\u044C',
    '\u0418\u044E\u043B\u044C','\u0410\u0432\u0433\u0443\u0441\u0442','\u0421\u0435\u043D\u0442\u044F\u0431\u0440\u044C','\u041E\u043A\u0442\u044F\u0431\u0440\u044C','\u041D\u043E\u044F\u0431\u0440\u044C','\u0414\u0435\u043A\u0430\u0431\u0440\u044C'];
var monthNamesShort = ['\u042F\u043D\u0432','\u0424\u0435\u0432','\u041C\u0430\u0440','\u0410\u043F\u0440','\u041C\u0430\u0439','\u0418\u044E\u043D',
    '\u0418\u044E\u043B','\u0410\u0432\u0433','\u0421\u0435\u043D','\u041E\u043A\u0442','\u041D\u043E\u044F','\u0414\u0435\u043A'];


var dayNames = ['\u0432\u043E\u0441\u043A\u0440\u0435\u0441\u0435\u043D\u044C\u0435','\u043F\u043E\u043D\u0435\u0434\u0435\u043B\u044C\u043D\u0438\u043A','\u0432\u0442\u043E\u0440\u043D\u0438\u043A','\u0441\u0440\u0435\u0434\u0430','\u0447\u0435\u0442\u0432\u0435\u0440\u0433','\u043F\u044F\u0442\u043D\u0438\u0446\u0430','\u0441\u0443\u0431\u0431\u043E\u0442\u0430'];
var dayNamesShort = ['\u0432\u0441\u043A','\u043F\u043D\u0434','\u0432\u0442\u0440','\u0441\u0440\u0434','\u0447\u0442\u0432','\u043F\u0442\u043D','\u0441\u0431\u0442'];
var dayNamesMin = ['\u0412\u0441','\u041F\u043D','\u0412\u0442','\u0421\u0440','\u0427\u0442','\u041F\u0442','\u0421\u0431'];


function setCaption( month, year )
{
    var headTitle = "\u041F\u043B\u0430\u043D-\u043E\u0442\u0447\u0435\u0442 \u0437\u0430 "; // План-отчет за
    var yearSuffix ="\u0433."; // г.

    $("#rep_head").text( headTitle + monthNames[ month - 1 ] + ' ' + year + ' ' + yearSuffix );
}

function datePicker()
{
    var now = new Date();
    var year = now.getFullYear();
    var month = now.getMonth() ;

    $.datepicker.regional['ru'] = {
        closeText: '\u041F\u0440\u0438\u043D\u044F\u0442\u044C', // Принять
        prevText: '&#x3c;Пред', //
        nextText: 'След&#x3e;',
        currentText: '\u0422\u0435\u043A. \u043C\u0435\u0441\u044F\u0446',// тек. месяц
        monthNames: monthNames,
        monthNamesShort : monthNamesShort,
        dayNames : dayNames,
        dayNamesShort : dayNamesShort,
        dayNamesMin : dayNamesMin,
        dateFormat: 'dd.mm.yy',
        firstDay: 1,
        isRTL: false
    };

    $('.year').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        beforeShow: function(input)
        {
            $(this).datepicker('widget').addClass('hide-calendar');
        },
        onClose: function(input, inst)
        {
            var month = Number( inst.selectedMonth ) + 1  ;

            setCaption( month , Number( inst.selectedYear ) );

            var cur_date = inst.selectedYear + '-' + ( month <= 9 ? '0' : '' ) + month + '-' + '01';

            $('a.alink').attr('href', "print.php?do=show&formid=240&p0=" + cur_date );

            $(this).datepicker('widget').removeClass('hide-calendar');
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1 ) );
            $(this).data('cur_date', cur_date );

            var data = new FormData();
            data.append( 'date', cur_date );
            startLoadingAnimation();

            // Отправляем запрос
            $.ajax({
                url: '/project/protocol_images/ajax.LoadRefDates.php',
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                processData: false, // Не обрабатываем файлы (Don't process the files)
                contentType: false, // Так jQuery скажет серверу что это строковой запрос
                success: function( respond, textStatus, jqXHR )
                {
                    // if everything is OK
                    if( typeof respond.error === 'undefined' )
                    {
                        stopLoadingAnimation();
                        var project_plan_date = respond.project_plan_date;
                        var plan_date = respond.plan_date;
                        var report_date = respond.report_date;

                        var data = new FormData();
                        data.append( 'date', cur_date );
                        startLoadingAnimation();

                        // Отправляем запрос
                        $.post(
                            "project/protocol_images/ajax.LoadData.php",
                            {
                                date   : cur_date ,
                            },
                            function( data )
                            {
                                stopLoadingAnimation();
                                $('#krz_table_div').empty().append( data );
                                adjust_ui();

                                $('#project_plan_date').datepicker('setDate', new Date( project_plan_date ) );
                                $('#plan_date').datepicker('setDate', new Date( plan_date ) );
                                $('#report_date').datepicker('setDate', new Date( report_date ) );

                                adjustCellColors();
                            }
                        );
                    }
                    else
                    {
                        console.log('AJAX request errors detected. Server said : ' + respond.error );
                    }

                },
                error: function( jqXHR, textStatus, errorThrown )
                {
                    console.log('AJAX request errors in uploadProjectFiles.js detected : ' + textStatus + errorThrown );
                }
            });
        }
    });


    gridDatePicker( $('#ref_date').data('cur_date') );
    $.datepicker.setDefaults($.datepicker.regional['ru']);
}

function makeDashDate( date )
{
   return date.substring(6, 10) + '-' + date.substring(3, 5) + '-' + date.substring(0, 2);
}

//2017-01-01
//01.01.2017

function makeDotDate( date )
{
  if( date )
   return date.substring(8, 10) + '.' + date.substring(5, 7) + '.' + date.substring(0, 4);
    else
      return '';
}

function gridDatePicker( cur_date )
{
    $('.datepicker').datepicker({
        changeMonth: false,
        changeYear: false,
        showButtonPanel: false,
        dateFormat: 'dd.mm.yy',

        beforeShow: function(input, inst)
        {
            var date = $( input ).val();
            if( date )
            {
//                date = date.substring(6, 10) + '-' + date.substring(3, 5) + '-' + date.substring(0, 2);
                  date = makeDashDate( date );
                $(this).datepicker('setDate', new Date( date ) );
            }
               else

                  {
                    var what = $(this).data('what');
                    if (what == 'report')
                  {
                    var in_date = new Date(cur_date);
                    var year = in_date.getFullYear();
                    var month = in_date.getMonth() + 1;
                    var day = in_date.getDate();
                    if (month == 12)
                    {
                        month = 1;
                        year++;
                    }
                    else
                        month++;

                    in_date = new Date(year, month - 1, day);
                  }

                    $(this).datepicker('setDate', in_date);
            }

        },
        onClose: function(input, inst)
        {
              if( ! $( this ).val().length )
                return ;

            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay));
            if ($(this).hasClass('td_date') == true )
            {
                var id = $( this ).attr('data-id');
                var what = $( this ).attr('data-what');
                var val = $( this ).val();

//                $("img[data-id='" + id + "'][data-what='" + what + "']").attr('src', can_edit ? load_image : load_image_dis ).unbind('click').bind('click', can_edit ? imgClick : emptyClick );

                var data = new FormData();
                data.append('id', id );
                data.append('what', what );
                data.append('date', val );

//                startLoadingAnimation();

                // Отправляем запрос
                $.ajax({
                    url: '/project/protocol_images/ajax.UpdateDates.php',
                    type: 'POST',
                    data: data,
                    cache: false,
                    dataType: 'json',
                    processData: false, // Не обрабатываем файлы (Don't process the files)
                    contentType: false, // Так jQuery скажет серверу что это строковой запрос
                    success: function (respond, textStatus, jqXHR) {
                        // if everything is OK
                        if (typeof respond.error === 'undefined')
                        {
                            var description = "";
                            stopLoadingAnimation();
                            adjustCellColors();

                        if(  what == "project_plan" && respond.date != respond.old_date )
                            $.post(
                                "/project/protocol_images/ajax.SendMail.php",
                                {
                                    id: id,
                                    what : what,
                                    val : val,
                                    email : email_boss
                                },
                                function( data )
                                {
//                                  alert( "Data Loaded: " + data );
                                }
                              );
                        }
                        else
                        {
                            console.log('AJAX request errors detected. Server said : ' + respond.error);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('AJAX request errors in uploadProjectFiles.js detected : ' + textStatus + errorThrown );
                    }
                }); // $.ajax({
            }
            else
            {
                var cur_date = $('#ref_date').data('cur_date');
                var project_plan_date = $('#project_plan_date').val();
                var plan_date = $('#plan_date').val();
                var report_date = $('#report_date').val();

                var data = new FormData();
                data.append('date', cur_date);

                data.append('project_plan_date', project_plan_date);
                data.append('plan_date', plan_date);
                data.append('report_date', report_date);

//                startLoadingAnimation();

                // Отправляем запрос
                $.ajax({
                    url: '/project/protocol_images/ajax.UpdateRefDates.php',
                    type: 'POST',
                    data: data,
                    cache: false,
                    dataType: 'json',
                    processData: false, // Не обрабатываем файлы (Don't process the files)
                    contentType: false, // Так jQuery скажет серверу что это строковой запрос
                    success: function (respond, textStatus, jqXHR) {
                        // if everything is OK
                        if (typeof respond.error === 'undefined')
                        {
                            stopLoadingAnimation();
                            project_plan_date = respond.project_plan_date;
                            plan_date = respond.plan_date;
                            report_date = respond.report_date;
                        }
                        else {
                            console.log('AJAX request errors detected. Server said : ' + respond.error);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('AJAX request errors in uploadProjectFiles.js detected : ' + textStatus + errorThrown);
                    }
                }); // $.ajax({
            } // if ($(this).hasClass('td_date') == false )

            adjustCellColors();
        },
    })    .on('keydown', function( evt )
        {
          if ( evt.keyCode === $.ui.keyCode.ESCAPE )
          {
              if( $( this ).val().length )
                $(this).blur();
                  else
                    $(this).datepicker('setDate', null ).blur();
        }
        });
}

function AdjustArrows( cur_img, cnt )
{
    cur_img --;

    if( cnt == 0 )
    {
        $('img.right_arr_img').hide();
        $('img.left_arr_img').hide();
        return ;
    }

    $('img.right_arr_img').show();
    $('img.left_arr_img').show();

    if( cur_img == 0 )
        $('img.left_arr_img').hide();


    if( cnt == 1 )
    {
        $('img.right_arr_img').hide();
        $('img.left_arr_img').hide();
    }

    if( cur_img + 1 == cnt )
        $('img.right_arr_img').hide();

}

function adjustCellColors()
{
    var report_ref_date = $('#project_plan_date').val();
//    var ref_date = report_ref_date.substring(6, 10) + '-' + report_ref_date.substring(3, 5) + '-' + report_ref_date.substring(0, 2);
    var ref_date = makeDashDate( report_ref_date );

    var project_plan_list = $('.td_date[data-what="project_plan"]');

    $.each( project_plan_list, function( key, value )
    {
        var val_day = $( value ).val();
//        var comp_date = val_day.substring(6, 10) + '-' + val_day.substring(3, 5) + '-' + val_day.substring(0, 2);
        var comp_date  = makeDashDate( val_day );

        var id = $( value ).attr('data-id');
        var what = $( value ).attr('data-what');
        var el = $('.td_date[data-id="' + id + '"][data-what="' + what + '"]').removeClass('before after good_job bad_job');

        if( val_day )
        {
            var a = new Date( comp_date );
            var b = new Date( ref_date );

          if( a  <= b )
            {
                $(el).addClass('before');
                    if( what == 'project_plan')
                    {
                        var bound = getBoundWeekDay( b )

//                        var bound = 2 ;

                        // switch( getWeekDay( b ) )
                        // {
                        //     case 0 : // воск
                        //                     bound = 3 ; break;
                        //     case 1 : // пон
                        //     case 2 : // вт
                        //                     bound = 4 ; break;
                        // }

                         if(  ( b.getTime() - a.getTime() ) / ( 1000*60*60*24 )  >= bound  )
                         {

                            $(el).addClass('good_job'); // Smile add
                        }
                    }
            }
            else
                $(el).addClass('after');
        }

    });


    var report_ref_date = $('#plan_date').val();
//    var ref_date = report_ref_date.substring(6, 10) + '-' + report_ref_date.substring(3, 5) + '-' + report_ref_date.substring(0, 2);
    var ref_date  = makeDashDate( report_ref_date );

    var plan_list = $('.td_date[data-what="plan"]');

    $.each( plan_list, function( key, value )
    {
        var val_day = $( value ).val();
//        var comp_date = val_day.substring(6, 10) + '-' + val_day.substring(3, 5) + '-' + val_day.substring(0, 2);
        var comp_date  = makeDashDate( val_day );

        var id = $( value ).attr('data-id');
        var what = $( value ).attr('data-what');
        var el = $('.td_date[data-id="' + id + '"][data-what="' + what + '"]').removeClass('before after');

        if( val_day )
        {
            if( new Date( comp_date )  <= new Date( ref_date ) )
                $(el).addClass('before');
            else
                $(el).addClass('after');
        }

    });

    var report_ref_date = $('#report_date').val();
//    var ref_date = report_ref_date.substring(6, 10) + '-' + report_ref_date.substring(3, 5) + '-' + report_ref_date.substring(0, 2);
    var ref_date  = makeDashDate( report_ref_date );

    var report_list = $('.td_date[data-what="report"]');

    $.each( report_list, function( key, value )
    {
        var val_day = $( value ).val();
//        var comp_date = val_day.substring(6, 10) + '-' + val_day.substring(3, 5) + '-' + val_day.substring(0, 2);
        var comp_date  = makeDashDate( val_day );

        var id = $( value ).attr('data-id');
        var what = $( value ).attr('data-what');
        var el = $('.td_date[data-id="' + id + '"][data-what="' + what + '"]').removeClass('before after');

        if( val_day )
        {
            if( new Date( comp_date )  <= new Date( ref_date ) )
                $( el ).addClass('before');
                else
                    $( el ).addClass('after');
        }
    });


  if( can_edit )
   {
    }
    else
    {
        $('input.datepicker').datepicker('destroy').unbind('click').bind('click', function(){ $( this ).blur(); } );
    }

       var confirm_buttons = $('.btn-susp');

       $.each( confirm_buttons,
                    function( key, value )
                                            {
                                                if( $( value ).parent('span').parent('div').find('input').val() )
                                                        $( value ).prop('disabled',false).removeClass('btn-dis');
                                            }
                );

        if( can_confirm )
        {
            var confirm_buttons = $('.btn-ok');

                $.each( confirm_buttons,
                    function( key, value )
                                            {
                                                if( $( value ).parent('span').parent('div').find('input').val() )
                                                        $( value ).prop('disabled',false).removeClass('btn-dis');
                                            }
                );
        }
                else
                    $('.btn-ok').prop('disabled',true).addClass('btn-dis');

  $('#ref_date').prop('disabled',false );

}

