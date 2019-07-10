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
	<script type='text/javascript' src='uses/script.js?ver=2'></script>

  <style>
  #notify_link, #notify_link:hover, #notify_link:visited, #notify_link:active, #notify_link:link
  {
    cursor : pointer;
    margin-left : 10px;
  }

  #coord_pages_link, #coord_pages_link:hover, #coord_pages_link:visited, #coord_pages_link:active, #coord_pages_link:link,#conf_request_link, #conf_request_link:hover, #conf_request_link:visited, #conf_request_link:active, #conf_request_link:link
  {
    cursor : pointer;
  }

  .flash_red, #notify_link.flash_red, #coord_pages_link.flash_red
  {
    background : red ;
    color : yellow ;
  }

  .flash_orange, #notify_link.flash_orange, #coord_pages_link.flash_orange
  {
    background : orange ;
    color : yellow ;
  }

  #conf_request_link.flash_cyan
  {
    background : #AFEEEE ;
    font-weight: bold ;
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
  
  #new_notification_div p, #new_notification_div a
  {
    font-weight: bold;
    font-size: 18px;
  }
  
  #dialog-confirm h1
  {
    font-weight: bold;
    font-size: 42px;
    text-align : center ;
  }
  
  #new_notification_div a
  {
    color: blue;
  }

  #new_notification_div p:nth-child(5)
  {
    font-size: 12pt !important;
    color : navy !important;  
    font-weight : bold !important;  
  }

  #notify_dialog_caption, h2 .ui-icon, h2 .ui-icon-alert
  {
    margin-left: 0 !important;
    font-size: 18pt !important;
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
     
     if( $('#coord_pages_link').hasClass('important') )
          $('#coord_pages_link').toggleClass('flash_red').removeClass('flash_orange');
			else
          		$('#coord_pages_link').toggleClass('flash_orange').removeClass('flash_red');

     $('#conf_request_link').toggleClass('flash_cyan')
     
     setTimeout( clock, 1000 );
  }   
  
  clock(); 
  </script>

	<script type='text/javascript'>
	var user_id = " .  (!empty($user['ID']) ? $user['ID'] : 0) . ";
	var form_id = " . (!empty($_GET['formid']) ? $_GET['formid'] : 0) . ";
	var p0 = '" . (!empty($_GET['p0']) ? $_GET['p0'] : 0). "';
	var id = " . (isset($_GET['id']) ? $_GET['id'] : 0) . ";
	</script>
</HEAD>
<BODY onLoad='vpdiv(); ".$event_divscroll."' onMouseMove='bottom_mousemove();'>"; 
$us_id = $user['ID'];


// shindax 28.09.2018
echo "<div id='dialog-confirm' title='Закрытие старого обмена' class='hidden'>
	<span class='ui-icon ui-icon-alert' style='transform: scale(2); float:left; margin:12px 12px 20px 0;'></span>
	<h1>Уважаемые сотрудники.</h1>
  <p>
До 1 октября 2018 года необходимо перенести всю свою рабочую информацию на новый обмен в личную папку или общую папку отдела.<br>
При возникновении ошибок или вопросов по процессу переноса и правам доступа на новом обмене обращаться в отдел ИТ (тел. 1015).<br>
По окончанию срока информация на старом обмене будет удалена.
</p>
</div>";

// if ($user['ID']!=='28'){
// echo"<div id='curloadingpage1' style='position:fixed; left:35%; top:40%; display:block;z-index:998'>
// <img src='project/img/loading_2.gif' width='200px'>
// <div style='position:absolute; left:18px; top:85px; width:165px; height:25px; background:#888'>
// </div>
// <div style='position:absolute; left:30px; top:90px;'>
// <font color='yellow'><b>Страница загружается</b></font>
// </div>
// </div>
// ";}

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

// shindax 28.08.2018
//echo "~".$user['ID']."~";
if( $user['ID'] == 240 || $user['ID'] == 241 )
  echo "<!-- Yandex.Metrika counter --> <script type='text/javascript' > (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter44916226 = new Ya.Metrika({ id:44916226, clickmap:true, trackLinks:true, accurateTrackBounce:true, ut:'noindex' }); } catch(e) { } }); var n = d.getElementsByTagName('script')[0], s = d.createElement('script'), f = function () { n.parentNode.insertBefore(s, n); }; s.type = 'text/javascript'; s.async = true; s.src = 'https://mc.yandex.ru/metrika/watch.js'; if (w.opera == '[object Opera]') { d.addEventListener('DOMContentLoaded', f, false); } else { f(); } })(document, window, 'yandex_metrika_callbacks'); </script> <noscript><div><img src='https://mc.yandex.ru/watch/44916226?ut=noindex' style='position:absolute; left:-9999px;' alt='' /></div></noscript> <!-- /Yandex.Metrika counter -->";

file_put_contents('exec_time.log', number_format($exec_time, 3, ',', ' ') . ' - ' . $_SERVER['REQUEST_URI'] . "\n", FILE_APPEND);

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
if ('$us_id' !== '28')
{
  // window.onLoad = document.getElementById('curloadingpage1').style.display = 'none';
}

// function checkSwapReplaceNotification()
// {
// 	var result = 0 ;

// 	$.post(
//             "ajax.checkSwapReplaceNotification.php",
//             {
//                 user_id   : user_id
//             },
//             function( data )
//             {
//             	if( Number( data ) == 0 )
//             	{
// 				$( "#dialog-confirm" ).dialog({
// 				      resizable: false,
// 				      height: "auto",
// 				      width: 800,
// 				      height: 500,
// 				      modal: true,
// 					  open: function( event, ui ) 
// 					  {  

// 						$('.ui-dialog-buttonpane').css('text-align','center')
// 						$('.ui-dialog-buttonset').css('float','none')

// 						$(".ui-dialog-titlebar-close", ui.dialog | ui).hide();

// 					  	$('.ui-dialog-title').css( { 'color' : 'white', 'font-size' : '14px', 'font-weight' : 'bold'});
// 						$('.ui-dialog-titlebar').css( { 'background' : 'red' });

// 					  	$('.ui-dialog-content p').css( { 'font-size' : '20px', 'font-weight' : 'bold', 'line-height' : '50px' });

// 					  	$('.ui-button-text').css( { 'font-size' : '20px' });
						
// 						window.setInterval(function()
// 							{
// 								var val = Number( $('#read').find('.ui-button-text').text() );
// 									if( ! isNaN( val ) )
// 									{
// 										val --;

// 										if( ! isNaN( val ) && val == 0 )
// 										{
// 											val = 'Прочитано';
// 											$('#read').button('enable');
// 										}

// 										$('#read').find('.ui-button-text').text( val )
// 									}
// 							}, 1000);

// 					  },
// 				      buttons: 
// 				      [
// 				       {
// 				       	id : "read",
// 				      	text : "5",
// 				      	width: '400px',
// 				      	disabled : true, 
// 				        click : function() 
// 				        {

// 				        	$.post(
// 						            "ajax.confirmSwapReplaceNotification.php",
// 						            {
// 						                user_id   : user_id
// 						            },
// 						            function( data )
// 						            {
// 						            }
// 							       );
// 				          $( this ).dialog( "close" );
// 				        }
// 				       }
// 				      ]
// 				    });
//             	}
//             }
//         );
// }


// $(function () {
// 	if (user_id != '0'){
// 		checkSwapReplaceNotification();
// 	}

// 	setInterval(function ()
// 	{
// 		$.getJSON("/project/request_events/watcher.php?mode=getEventCount&user_id=" + user_id, function (data) {
// 			if (data.all != 0) {
// 				$("#request_events_all_menu span").text("(" + data.all + ") "); 
				
// 				if (data.it == 0) {
// 					$("#request_events_it_menu span").text("");
// 				} else {
// 					$("#request_events_it_menu span").text("(" + data.it + ") ");
// 				}
								
// 				if (data.hr == 0) {
// 					$("#request_events_hr_menu span").text("");
// 				} else {
// 					$("#request_events_hr_menu span").text("(" + data.hr + ") ");
// 				}
				 
				
// 				if (data.zakreq == 0) {
// 					$("#request_events_zakreq_menu span").text("");
// 				} else {
// 					$("#request_events_zakreq_menu span").text("(" + data.zakreq + ") ");
// 				}
				
				
// 				if (data.ogi == 0) {
// 					$("#request_events_ogi_menu span").text("");
// 				} else {
// 					$("#request_events_ogi_menu span").text("(" + data.ogi + ") ");
// 				}
				
				
// 				if (data.tmc == 0) {
// 					$("#request_events_tmc_menu span").text("");
// 				} else {
// 					$("#request_events_tmc_menu span").text("(" + data.tmc + ") ");
// 				}
				
// 				$("#request_events_menu span").first().text("(" + data.all + ") ");
// 			} else {
// 				$("#request_events_all_menu span").text("");
// 				$("#request_events_it_menu span").text("");
// 				$("#request_events_zakreq_menu span").text("");
// 				$("#request_events_zak_menu span").text("");
// 				$("#request_events_hr_menu span").text("");
// 				$("#request_events_ogi_menu span").text("");
// 				$("#request_events_menu span").first().text("") ;
// 			}
// 		})
// 	}, 10000);
// });

// shindax ----------------------------------------------------------------------------
  // setInterval(function ()
  // {

  //               $.post(
  //                   "project/plan_fact/ajax.getNotificationCount.php",
  //                   {
  //                       user_id   : user_id,
  //                       why_arr : [2,3,4,5,6,7,8,9,10,11,12,13,14,15,16]
  //                   },
  //                   function( data )
  //                   {
  //                     if( Number( data ) )
  //                       $('#notify_link').text( "\u041D\u043E\u0432\u044B\u0445 \u0443\u0432\u0435\u0434\u043E\u043C\u043B\u0435\u043D\u0438\u0439 : " + data ).removeClass('hidden');
  //                       else
  //                         $('#notify_link').addClass('hidden');
  //                   }
  //               );

  //               $.post(
  //                   "project/plan_fact/ajax.getNotificationCount.php",
  //                   {
  //                       user_id   : user_id,
  //                       why_arr : [11,12]
  //                   },
  //                   function( data )
  //                   {
  //                     if( Number( data ) )
  //                       $('#coord_pages_link').text( "\u041B\u0438\u0441\u0442\u044B \u0441\u043E\u0433\u043B : " + data ).removeClass('hidden');
  //                       else
  //                         $('#coord_pages_link').addClass('hidden');
  //                   }
  //               );

  //               $.post(
  //                   "project/plan_fact/ajax.getNotificationCount.php",
  //                   {
  //                       user_id   : user_id,
  //                       why_arr : [16]
  //                   },
  //                   function( data )
  //                   {
  //                     if( Number( data ) )
  //                       $('#conf_request_link').text( "\u{41D}\u{43E}\u{432}\u{44B}\u{445} \u{437}\u{430}\u{43F}\u{440}\u{43E}\u{441}\u{43E}\u{432} : " + data ).removeClass('hidden');
  //                       else
  //                         $('#conf_request_link').addClass('hidden');
  //                   }
  //               );

  // }, 5000);
  
  // shindax ----------------------------------------------------------------------------

</script>
 </BODY>
</HTML>
<?php


if ($use_gzip) 
  gzip_output();

?>