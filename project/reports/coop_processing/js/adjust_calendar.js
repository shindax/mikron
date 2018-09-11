var monthNames = ['\u042F\u043D\u0432\u0430\u0440\u044C','\u0424\u0435\u0432\u0440\u0430\u043B\u044C','\u041C\u0430\u0440\u0442','\u0410\u043F\u0440\u0435\u043B\u044C','\u041C\u0430\u0439','\u0418\u044E\u043D\u044C',
        '\u0418\u044E\u043B\u044C','\u0410\u0432\u0433\u0443\u0441\u0442','\u0421\u0435\u043D\u0442\u044F\u0431\u0440\u044C','\u041E\u043A\u0442\u044F\u0431\u0440\u044C','\u041D\u043E\u044F\u0431\u0440\u044C','\u0414\u0435\u043A\u0430\u0431\u0440\u044C'];
var monthNamesShort = ['\u042F\u043D\u0432','\u0424\u0435\u0432','\u041C\u0430\u0440','\u0410\u043F\u0440','\u041C\u0430\u0439','\u0418\u044E\u043D',
        '\u0418\u044E\u043B','\u0410\u0432\u0433','\u0421\u0435\u043D','\u041E\u043A\u0442','\u041D\u043E\u044F','\u0414\u0435\u043A'];
var dayNames = ['\u0432\u043E\u0441\u043A\u0440\u0435\u0441\u0435\u043D\u044C\u0435','\u043F\u043E\u043D\u0435\u0434\u0435\u043B\u044C\u043D\u0438\u043A','\u0432\u0442\u043E\u0440\u043D\u0438\u043A','\u0441\u0440\u0435\u0434\u0430','\u0447\u0435\u0442\u0432\u0435\u0440\u0433','\u043F\u044F\u0442\u043D\u0438\u0446\u0430','\u0441\u0443\u0431\u0431\u043E\u0442\u0430'];
var dayNamesShort = ['\u0432\u0441\u043A','\u043F\u043D\u0434','\u0432\u0442\u0440','\u0441\u0440\u0434','\u0447\u0442\u0432','\u043F\u0442\u043D','\u0441\u0431\u0442'];
var dayNamesMin = ['\u0412\u0441','\u041F\u043D','\u0412\u0442','\u0421\u0440','\u0427\u0442','\u041F\u0442','\u0421\u0431'];


function adjust_calendar( selector )
{
    $( selector ).datepicker(
    {
        closeText: '\u041F\u0440\u0438\u043D\u044F\u0442\u044C', // Принять
        prevText: '&#x3c;\u041F\u0440\u0435\u0434', //
        nextText: '\u0421\u043B\u0435\u0434&#x3e;',
        currentText: '\u0422\u0435\u043A. \u043C\u0435\u0441\u044F\u0446',// тек. месяц
        showButtonPanel: false,
        monthNames: monthNames,
        monthNamesShort : monthNamesShort,
        dayNames : dayNames,
        dayNamesShort : dayNamesShort,
        dayNamesMin : dayNamesMin,
        dateFormat: 'dd.mm.yy',
        firstDay: 1,
        changeMonth : true,
        changeYear : true,
        isRTL: false,
                    onSelect: function ()
                      {
                        date_process( this )
                      }

    });
}


