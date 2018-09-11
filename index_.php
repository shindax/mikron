<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////
	error_reporting( E_ERROR );

	define("MAV_ERP", TRUE);

	$print_mode = "off";

	include "start.php";
if (isset($_GET['dse_view'])) {
setcookie("O_show39", $_GET['dse_view'], time()+(60*60*24*30));

}

	if ($use_gzip) gzip_start();

	if (($user==0) && ($use_loginform)) {
		include "project/loginform.php";
		die();
	}

/////////////////////////////////////////////////////////////////////////////////////
//
// EVENTS
//
/////////////////////////////////////////////////////////////////////////////////////

	$event_onscroll = " onScroll='document.cookie = \"scroll=\"+this.scrollTop+\"x\"+this.scrollLeft+\"; expires=Fri, 31 Dec ".NextYear()." 23:59:59 GMT;\";'";
	$event_divscroll = "";

	$event_bonscroll = " onScroll='document.cookie = \"bscroll=\"+this.scrollTop+\"x\"+this.scrollLeft+\"; expires=Fri, 31 Dec ".NextYear()." 23:59:59 GMT;\";'";
	$event_bdivscroll = "";
	$bottomheight = 50;
	if (isset($_COOKIE["bottomheight"])) $bottomheight = $_COOKIE["bottomheight"];

	if (isset($_GET["event"])) {
		$scroll = explode("x",$_COOKIE["scroll"]);
		$event_divscroll = "scrollvpdiv(".($scroll[0]*1).",".($scroll[1]*1)."); ";

		$bscroll = explode("x",$_COOKIE["bscroll"]);
		$event_bdivscroll = "scrollbvpdiv(".($bscroll[0]*1).",".($bscroll[1]*1)."); ";
	}

/////////////////////////////////////////////////////////////////////////////////////
//
// BODY
//
/////////////////////////////////////////////////////////////////////////////////////


// Диалог при изменении статуса shindax
$new_notify_dialog  = "
<div id='new_notify_dialog' class='hidden' title='Новое уведомление.'>
  <div>
      <h2 id='notify_dialog_caption'><span class='ui-icon ui-icon-alert' style='float:left; margin:auto;'></span>Изменение этапа</h2>
      <div id='new_notification_div'><p></p><p></p><p></p><p></p><p></p><!--a href='index.php?do=show&formid=241' target='_blank'>Zzz</a--></div>
  </div>
</div>";


echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<HTML>
<HEAD>
	<TITLE>$title</TITLE>
	<meta http-equiv='Content-Type' content='text/html; charset=".$html_charset."'>

	<LINK rel='stylesheet' href='style/style.css' type='text/css'>
	<LINK rel='stylesheet' href='style/jquery-ui/jquery-ui.css' type='text/css'>

	<link href='favicon.png' rel='icon' type='image/png'>
	<script type='text/javascript' charset='utf-8' src='uses/jquery.js'></script>
	<script type='text/javascript' charset='utf-8' src='uses/jquery-ui.js'></script>
	<script type='text/javascript' charset='utf-8' src='uses/headers.js'></script>
	<script type='text/javascript' charset='utf-8' src='uses/vue.min.js'></script>
	<script type='text/javascript' src='uses/script.js'></script>


  <style>
  #notify_link, #notify_link:hover, #notify_link:visited, #notify_link:active, #notify_link:link
  {
    cursor : pointer;
    margin-left : 10px;
  }

  .flash_red, #notify_link.flash_red
  {
    background : red ;
    color : yellow ;
  }

  #msg_span
  {
    cursor:pointer;
  }
  .hidden
  {
    display:none;
  }

  .underline
  {
    text-decoration: underline;
  }
  </style>

  <script>
  function clock()
  {
      var d = new Date();
      var year = d.getFullYear();
      var month = d.getMonth();
      var day = d.getDate();
      var hours = d.getHours();
      var minutes = d.getMinutes();
      var seconds = d.getSeconds();

      var monthes = ['янв.','фев.','мар.','апр.','май.','июнь.','июль.','авг.','сен.','окт.','ноя.','дек.'];

      month = monthes[ month ];

      $('#msg_span').addClass('hidden');

//      if( hours >= 16 && ( $('#msg_span').attr('data-show') == 0 ) )
//        $('#msg_span').removeClass('hidden');

      if ( month <= 9)
        month = '0' + month;

      if (day <= 9)
        day = '0' + day;

      if (hours <= 9)
        hours = '0' + hours;

      if (minutes <= 9)
        minutes = '0' + minutes;

      if (seconds <= 9)
        seconds = '0' + seconds;

      var date_time = day + ' ' + month + ' ' + year + ' ';


        if( seconds % 2 )
           date_time += hours + ':' + minutes ;
            else
              date_time += hours + ' ' + minutes ;

      $('#clock_span').html( date_time );

      if( $('#msg_span').hasClass('flash_red') )
          $('#msg_span').removeClass('flash_red');
            else
              $('#msg_span').addClass('flash_red');

      if( $('#notify_link').hasClass('flash_red') )
          $('#notify_link').removeClass('flash_red');
            else
              $('#notify_link').addClass('flash_red');


      setTimeout( clock, 1000 );
  }

  clock();
  </script>

	<script type='text/javascript'>
	var user_id = " . $user['ID'] . ";
	var form_id = " . $_GET['formid'] . ";
	var p0 = '" . $_GET['p0'] . "';
	var id = " . (isset($_GET['id']) ? $_GET['id'] : 0) . ";
	</script>
</HEAD>
<BODY onLoad='vpdiv(); ".$event_divscroll."' onMouseMove='bottom_mousemove();'>
$new_notify_dialog";
$us_id = $user['ID'];
if ($user['ID']!=='28'){
echo"<div id='curloadingpage1' style='position:fixed; left:35%; top:40%; display:block;z-index:998'>
<img src='project/img/loading_2.gif' width='200px'>
<div style='position:absolute; left:18px; top:85px; width:165px; height:25px; background:#888'>
</div>
<div style='position:absolute; left:30px; top:90px;'>
<font color='yellow'><b>Страница загружается</b></font>
</div>
</div>
";}
if ($copy_state) echo "<img style='position: fixed; top: 85%; left: 80%;' src='style/copy.gif'>\n";

echo "<TABLE class='form'><tr class='top'><td>
";

	bar();
	menu();

echo "
</td></tr><tr><td class='viewport'>
";

echo "\n<!-- Viewport -->\n";
echo "<div id='vpdiv' class='viewport' name='vpdiv' ".$event_onscroll.">\n";
echo "	<table class='view'><tr><td>\n";

	if ($user==0) {
		login_form();
	} else {
		include "includes/do_".$do.".php";


	}
$mem_usage = memory_get_peak_usage(true)/1024;
$exec_time = microtime(true) - $start_time;

echo "
	<table class='A4W'><tr><td>&nbsp;</td></tr></table>
	</td></tr></table>".$echo_file_form."
</div>

</td></tr>
";
if ( isset( $show_bottom_page ) )
if ($show_bottom_page == true) {
echo "
<tr id='bvptr' style='height: 50px;'><td class='viewport' style='background: #fff;'>

<!--Bottom Viewport -->
<table class='hrtbl' onClick='bottom_click();'><tr><td></td></tr></table>
<div id='bvpdiv' class='bviewport' name='bvpdiv' ".$event_bonscroll.">
	<table class='bview'><tr><td>
	<div id='BView' class='clear'>

	<!-- form -->
	<form method='post' action='".$pageurl."'>
";
RenderBottomView();
echo "
	</form>

	</div>
	<table class='A4W'><tr><td>&nbsp;</td></tr></table>
	</td></tr></table>
</div>
</td></tr>
<script language='javascript'>
	//".$event_bdivscroll."
	bottom_set(".$bottomheight.");
	bvpdiv();
</script>
";
}

echo "<tr class='bottom'><td>

<!-- Info -->
	<span style='margin-right: 50px;'>".$loc["14"].": ".number_format($exec_time, 3, ',', ' ')."</span>
	<span style='margin-right: 50px;'>".$loc["15"].": ".number_format($mem_usage, 0, ',', ' ')."</span>
	<span style='margin-right: 50px;'>".$loc["16"].": ".$dbquery_index."</span>

</td></tr></table>
";
if ($MESSAGE!=="") alert($MESSAGE);
?>
<script type='text/javascript'>
if ('$us_id' !== '28'){
window.onLoad = document.getElementById('curloadingpage1').style.display = 'none';;
}

$(function () {
	setInterval(function ()
	{
		$.getJSON("/project/request_events/watcher.php?mode=getEventCount&user_id=" + user_id, function (data) {
			if (data.all != 0) {
				$("#request_events_all_menu span").text("(" + data.all + ") ");

				if (data.it == 0) {
					$("#request_events_it_menu span").text("");
				} else {
					$("#request_events_it_menu span").text("(" + data.it + ") ");
				}

				if (data.hr == 0) {
					$("#request_events_hr_menu span").text("");
				} else {
					$("#request_events_hr_menu span").text("(" + data.hr + ") ");
				}


				if (data.zakreq == 0) {
					$("#request_events_zakreq_menu span").text("");
				} else {
					$("#request_events_zakreq_menu span").text("(" + data.zakreq + ") ");
				}


				if (data.ogi == 0) {
					$("#request_events_ogi_menu span").text("");
				} else {
					$("#request_events_ogi_menu span").text("(" + data.ogi + ") ");
				}


				if (data.tmc == 0) {
					$("#request_events_tmc_menu span").text("");
				} else {
					$("#request_events_tmc_menu span").text("(" + data.tmc + ") ");
				}

				$("#request_events_menu span").first().text("(" + data.all + ") ");
			} else {
				$("#request_events_all_menu span").text("");
				$("#request_events_it_menu span").text("");
				$("#request_events_zakreq_menu span").text("");
				$("#request_events_zak_menu span").text("");
				$("#request_events_hr_menu span").text("");
				$("#request_events_ogi_menu span").text("");
				$("#request_events_menu span").first().text("") ;
			}
		})
	}, 35000);
});

// shindax ----------------------------------------------------------------------------

    $( '#new_notify_dialog' ).dialog({
        resizable: false,
        height: 'auto',
        width: 400,
        autoOpen  :false,
        modal: true,
        closeOnEscape: true,
        create : function()
                                  {
                                    $('div.ui-widget-header').css( { 'background':'#F00' });
                                    $('.ui-dialog-title').css( { 'color':'#FFF'}, { 'font-weight':'bold'} );
                                  }, // Цвет заголовка диалога
        buttons: [
          {
            id : 'status_change_dialog_bring_to_meet_button',
            // 'На совещание' в unicode url : https://r12a.github.io/apps/conversion/
            text : "\u041D\u0430 \u0441\u043E\u0432\u0435\u0449\u0430\u043D\u0438\u0435",
            click : function()
            {
                  var id = $('#new_notification_div').attr('data-id') ;

                $.post(
                    "project/plan_fact/ajax.makeNotificationAck.php",
                    {
                        id   : id ,
                        where : 2
                    },
                    function( data )
                    {
                    }
                );

                  $( this ).dialog( "close" );
            }
           },
          {
            id : 'status_change_dialog_ok_button',
            // 'Прочитать' в unicode url : https://r12a.github.io/apps/conversion/
            text : "\u041F\u0440\u043E\u0447\u0438\u0442\u0430\u043D\u043E",
            click : function()
            {
                  var id = $('#new_notification_div').attr('data-id') ;

                $.post(
                    "project/plan_fact/ajax.makeNotificationAck.php",
                    {
                        id   : id ,
                        where : 1
                    },
                    function( data )
                    {
                    }
                );

                  $( this ).dialog( "close" );
            }
           },
           {
            // 'Отмена' в unicode
            text : '\u041E\u0442\u043C\u0435\u043D\u0430',
            click : function()
            {
                $( this ).dialog( "close" );
            }
          }
        ]
    });

  setInterval(function ()
  {
    $.getJSON("/project/plan_fact/ajax.getNotification.php?user_id=" + user_id ,
              function (data)
              {
                if( data.length )
                {

                  console.log( data );

                  //   data = data[0];
                  //  $('div.ui-widget-header').css( { 'background':'#F00' });
                  //  $('.ui-dialog-title').css( { 'color':'#FFF'}, { 'font-weight':'bold'} );

                  // $('#new_notification_div').attr('data-id', data['id'] );
                  // $('#new_notification_div p').eq(0).text( "ДСЕ : " + data['dse'] );
                  // $('#new_notification_div p').eq(1).html( "Заказ : " + "<a class='notify_link'>" + data['ord_name'] + "</a>");
                  // $('#new_notification_div p').eq(2).text( "Чертеж : " + data['draw'] );

                  // $('a.notify_link').unbind('click').bind('click', function()
                  //   {
                  //     $('#new_notify_dialog').dialog('close');
                  //     var url = "index.php?do=show&formid=241&id=" + data['ord_id'] +"&rec_id=" + data['id'];
                  //     window.open( url, '_blank');
                  //   });

                  //          if( data['why'] == 1 ) // Наступление запланированной даты
                  //          {
                  //             $('#notify_dialog_caption').html("<span class='ui-icon ui-icon-alert' style='float:left; margin:auto;'></span>Наступление запланированной даты").css('margin-left', '20px');
                  //             $('#new_notification_div p').eq(3).text( "Текущий этап : " + data['stage_name'] );
                  //             $('#new_notification_div p').eq(4).text( data['description'] );
                  //           }

                  //          if( data['why'] == 2 ) // Изменение этапа
                  //          {
                  //             $('#notify_dialog_caption').html("<span class='ui-icon ui-icon-alert' style='float:left; margin:auto;'></span>Изменение этапа") ;
                  //             $('#new_notification_div p').eq(3).text( "Новый этап : " + data['stage_name'] );
                  //             $('#new_notification_div p').eq(4).text( data['description'] );
                  //           }

                  //          if( data['why'] == 3 ) // Один день до окончания этапа
                  //          {
                  //             $('#notify_dialog_caption').html("<span class='ui-icon ui-icon-alert' style='float:left; margin:auto;'></span>Один день до окончания этапа") ;
                  //             $('#new_notification_div p').eq(3).text( "Текущий этап : " + data['stage_name'] );
                  //             $('#new_notification_div p').eq(4).text( data['description'] );
                  //           }

                  //          if( data['why'] == 4 ) // Изменение срока
                  //          {
                  //             $('#notify_dialog_caption').html("<span class='ui-icon ui-icon-alert' style='float:left; margin:auto;'></span>Изменение срока") ;
                  //             $('#new_notification_div p').eq(3).text( "Текущий этап : " + data['stage_name'] );
                  //             $('#new_notification_div p').eq(4).text( data['description'] );
                  //           }

                  //          if( data['why'] == 5 ) // Просрочка этапа
                  //          {
                  //             $('#notify_dialog_caption').html("<span class='ui-icon ui-icon-alert' style='float:left; margin:auto;'></span>Просрочка этапа") ;
                  //             $('#new_notification_div p').eq(3).text( "Текущий этап : " + data['stage_name'] );
                  //             $('#new_notification_div p').eq(4).text( data['description'] );
                  //           }

                  //          if( data['why'] == 6 ) // Начало этапа
                  //          {
                  //             $('#notify_dialog_caption').html("<span class='ui-icon ui-icon-alert' style='float:left; margin:auto;'></span>Дата окончания этапа") ;
                  //             $('#new_notification_div p').eq(3).text( "Текущий этап : " + data['stage_name'] );
                  //             $('#new_notification_div p').eq(4).text( data['description'] );
                  //           }

                  //          if( data['why'] == 7 ) // 10 дней до окончания этапа
                  //          {
                  //             $('#notify_dialog_caption').html("<span class='ui-icon ui-icon-alert'></span>10 дней до окончания этапа") ;
                  //             $('#new_notification_div p').eq(3).text( "Текущий этап : " + data['stage_name'] );
                  //             $('#new_notification_div p').eq(4).text( data['description'] );
                  //           }

                  //          if( data['why'] == 8 ) // 5 дней до окончания этапа
                  //          {
                  //             $('#notify_dialog_caption').html("<span class='ui-icon ui-icon-alert' style='float:left; margin:auto;'></span>5 дней до окончания этапа") ;
                  //             $('#new_notification_div p').eq(3).text( "Текущий этап : " + data['stage_name'] );
                  //             $('#new_notification_div p').eq(4).text( data['description'] );
                  //           }


                  // $('#new_notify_dialog').removeClass('hidden');
                  // $('#new_notify_dialog').dialog('option', 'title', 'Новое уведомление. Непрочитанных : ' + data['total_count']).dialog('open');

                } // if( data.length )
              });
//    $('#new_notify_dialog').dialog('open');

  }, 1000);

// shindax ----------------------------------------------------------------------------

</script>
<!-- Yandex.Metrika counter <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter44916226 = new Ya.Metrika({ id:44916226, clickmap:true, trackLinks:true, accurateTrackBounce:true, ut:"noindex" }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/44916226?ut=noindex" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
</BODY>
</HTML>
<?php


if ($use_gzip) gzip_output();
?>