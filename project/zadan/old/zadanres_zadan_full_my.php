<script type="text/javascript" src="/project/zadan/js/zadanres_zadan_full.js?12"></script>


<style>
.report, .copy_button
{
	cursor: pointer;
}
.copy_button
{
	width : 100%;
}

#copy_dialog
{
	display : flex;
	padding : 5px ;
	margin : 0 ;
	align-items : center ;
}

#copy_dialog_shift
{
	width : 80px;
	margin : 0 ;
	padding : 0;
	margin-right:10px;
}
#copy_dialog_date
{
	width : 100px;
	margin : 0 ;
	padding : 0px ;
	cursor: pointer;
	margin-right:10px;	
}
#copy_dialog_resurs
{
	width : 240px;
	margin : 0 ;
	padding : 0px ;
}

.ui-widget-content a {
    cursor: pointer !important;
    font-size: 10pt !important;
    color: black !important;
    font-weight: normal !important;
}
</style>


<script>

// shindax 29.08.2017
$( function()
{
	$('.report').unbind('click').bind('click', reportButtonClick );
});

function delete_res( el, message )
{
	var checks = $('.empty_emp:checked');
	var list = '';

	if( checks.length )
	{
		var arr = [];
		$.each( checks , function( key, item )
		    {
		      var id = $( item ).data('id');
		      arr.push( id );
			  console.log 
		      $('tr[data-id=' + id + ']').remove();
		    });
		
		list = arr.join(',');
		console.log( 'delete ' + list );
	}
	else
	{
		var list = $( el ).data('id');
		$('tr[data-id=' + list + ']').remove();		
		console.log( 'delete ' + list );	
	}
}

function reportButtonClick()
{
  var sel_arr = $("input[name='cur_zad_sel']:checked:enabled");
  var str = '&p0=';

  if( sel_arr.length )
		{
		  $.each( sel_arr , function( key, value )
		  {
		    var id = $( value ).attr('id');
		    if( id.length )
		    	if( id.indexOf( '_item_zad_') == -1 )
		    		str += id.replace('item_zad_','') + ",";
		  });

//		 alert( str );

			 var url = "/index.php?do=show&formid=251" + str.slice(0,-1) ;
			 window.open( url );
		}
}

// shindax 29.08.2017



function SetNewValue(from_id,to_id,val,url) 
{
	fr_obj = document.getElementById(from_id);
	xxx = fr_obj.value*val;
	yyy = Math.ceil(xxx*100);
	to_obj = document.getElementById(to_id);
	to_obj.value = yyy/100;
	vote(to_obj,url+to_obj.value);
	}

function HLID(x) {
	obj = document.getElementById("row1_"+x);
	obj.className = "htr";
	obj = document.getElementById("row2_"+x);
	obj.className = "htr";
	obj = document.getElementById("row3_"+x);
	obj.className = "htr";
	}


function DHLID(x) {
	obj = document.getElementById("row1_"+x);
	obj.className = "";
	obj = document.getElementById("row2_"+x);
	obj.className = "";
	obj = document.getElementById("row3_"+x);
	obj.className = "";
	}


window.onload=
	document.getElementById('vpdiv').parentNode.parentNode.parentNode.rows[0].style.display='none'; 
	doc_cook = document.cookie;
	get_top_scrl_full = doc_cook.substr((doc_cook.indexOf('scroll')+7), 5);
	get_top_scrl_x = get_top_scrl_full.indexOf('x');
	if (get_top_scrl_x > 0) 
		get_top_scrl_numb = get_top_scrl_full.substr(0, get_top_scrl_x);
      else
        get_top_scrl_numb = get_top_scrl_full;

//	setTimeout("document.getElementById('vpdiv').scrollTop = get_top_scrl_numb",3000);
</script>

<style>
	table.tbl tr.htr {
		background: #ebf3fe;
	}
	table.tbl tr.highlite 
	{
		background: #cbdef4;
	}
	
	
	a.acl {
		font-size: 11pt;
		text-decoration: none;
	}
	
	div.AR
	{
    float : right ;
    padding:0;
    margin:0;
    width:100%;
	}
	.ch_resource_button
	{
    width : 100% ;
	}

    
.change_resource_select_class_div
{
    position:relative;
    overflow: hidden ;     
}

.change_resource_select
{
    display:block;
    position:absolute;
    overflow: auto ; 
}    

#caption
{
    position : fixed ;
    padding: 12px 0 13px 0px !IMPORTANT;
    width : 2000;
    top: 0px;
    left:0px;
    z-index : 1000;
    background-color : #DDD ;
	
}

div.popup
{
    z-index : 1001 !IMPORTANT;
}

#table_caption_div
{
    position : absolute ;
    padding: 9px 0 ;
    top: 38px;
    height: 76px !IMPORTANT;
    z-index : 102;
    overflow : hidden;
}

#main_table_div
{
  position : relative ;
  top: 22px;
}

.Field .res_status
{
	background-color:#fff;
	border:1px solid #222;
	color:#111; 
	padding:0 2px 0 2px;
	font-weight:bold;
	margin:2px 0px 0px 5px;
	}

</style>

<?php

$pass = 0 ;
$offset = -93;
$offset2 = -146;
setlocale(LC_ALL, 'en_US.UTF8');

echo "<script> var offset=$offset; </script>";

$smena = $_GET["p1"];
if (($smena!=="1") && ($smena!=="2") && ($smena!=="3")) $smena = "1";
$pdate = $_GET["p0"]*1;
$date = IntToDate($pdate);

echo "<script>cur_date = $pdate; smena = $smena; </script>";

// shindax 26.12.2017

echo "
<div id='copy_dialog' class='hidden' title='Копировать сменное задание'>
<select id='copy_dialog_shift'><option value='0'>...</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option></select>&nbsp;
<input id='copy_dialog_date' />&nbsp;
<select id='copy_dialog_resurs'></select>
</div>";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$pageurl_addzad = "index.php?do=show&formid=65&p0=".$pdate."&p1=".$smena."&p2=";
		$calendar_url = "index.php?do=show&formid=63&p0=".$date;
		$print_url = "index.php?do=show&formid=84&p0=".$pdate."&p1=".$smena;
		$print_resurs_url = "index.php?do=show&formid=83&p0=".$pdate."&p1=";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	include "project/calc_zak.php";

	$editing = false;
	$modering = false;
	$editingplan = false;

	$today = explode(".",$today_0);
	$today = $today[2]*10000+$today[1]*100+$today[0];

	$real_today = $today;

	if ($pdate>=$today) $editingplan = true;

	if ($pdate<$today) $modering = true;
	if ($pdate==$today) $modering = true;

	$theday = mktime (0,0,0,date("m") ,date("d") ,date("Y"));
	$today_m=date("d.m.Y",$theday-(8*86400));
	$today = explode(".",$today_m);
	$today = $today[2]*10000+$today[1]*100+$today[0];

	if ($pdate>$today) $editing = true;
	if ($pdate==$today) $editing = true;

	if ($pdate<$today) $modering = false;

	if (db_check("db_zadan","MEGA_REDACTOR")) $editing = true;
	if (db_check("db_zadan","MEGA_REDACTOR")) $editingplan = true;
	if (db_check("db_zadan","MEGA_REDACTOR")) $modering = true;

	$redirected = false;


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Действие ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// addnewres /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// shindax 21.12.2016
  if( isset($_GET['used_ignore']) )
    $used_ignore = 1 ;
      else
        $used_ignore = 0 ;
// shindax 21.12.2016

	if (isset($_POST['addnewres'])) {
		$ids = $_POST['addnewres'];
		if ((db_adcheck("db_zadanres")) && ($editing)) {
			for ($j=0;$j < count($ids);$j++) {
				dbquery("INSERT INTO ".$db_prefix."db_zadanres (DATE, SMEN, ID_resurs) VALUES ('".$pdate."', '".$smena."', '".$ids[$j]."')");
			}
		}
		redirect($pageurl."&event","script");
		$redirected = true;
	}
	

// delresid /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ( isset($_GET['delresid']) )
{
		$dodelid = $_GET['delresid'];
		$used = true;
		$xxx1 = dbquery("SELECT * FROM ".$db_prefix."db_zadanres where  (ID='".$dodelid."')");
		if ($xxx = mysql_fetch_array($xxx1)) 
			{
				$used = false;
				$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where  (DATE='".$xxx["DATE"]."') and (SMEN='".$xxx["SMEN"]."') and (ID_resurs='".$xxx["ID_resurs"]."')");
				if (mysql_fetch_array($result)) 
					$used = true;
			} 
		
    if( $used_ignore )
    {
       $used_ignore_res = $xxx["ID_resurs"];
       $used_ignore_date = $xxx["DATE"] ;
       $used_ignore_smen = $xxx["SMEN"] ;
       $query = "DELETE FROM ".$db_prefix."db_zadan where  (DATE='$used_ignore_date') and (SMEN='$used_ignore_smen') and (ID_resurs='$used_ignore_res')";
       $result = dbquery( $query );
       $used = 0 ;
    }
	
		$current = mysql_fetch_assoc(dbquery("SELECT * FROM okb_db_zadanres WHERE  ID = " . $dodelid), 0);
	
		$prev_name = mysql_result(dbquery("SELECT NAME FROM `okb_db_zadanres`
		
		LEFT JOIN okb_db_resurs ON okb_db_resurs.ID = okb_db_zadanres.ID_resurs
										WHERE SMEN = " . $current['SMEN'] .
										" AND okb_db_zadanres.DATE = '" . $current['DATE']. "'". 
										" AND ID_resurs = " . $current['ID_resurs']), 0);
								
		$next_resource = mysql_result(dbquery("SELECT okb_db_zadanres.`ID_resurs` FROM `okb_db_zadanres`
		LEFT JOIN okb_db_resurs ON okb_db_resurs.ID = okb_db_zadanres.ID_resurs
										WHERE SMEN = " . $current['SMEN'] .
										" AND okb_db_zadanres.DATE = '" . $current['DATE'] . "'" . 
										" AND NAME > '" . $prev_name . "' ORDER BY NAME LIMIT 1"), 0);
	
		if ((!$used) && (db_adcheck("db_zadanres")) && ($editing)) {
			dbquery("DELETE from ".$db_prefix."db_zadanres where (ID='".$dodelid."')");
		}
		
		redirect($pageurl."&event&current_resource_id=" . $next_resource,"script");
		$redirected = true;
}

// Добавление заданий //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if (isset($_POST["add_zadan_to_resurs"])) 
	{

		$idresurs = $_POST["add_zadan_to_resurs"];
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID = '".$idresurs."')");
		if (($resurs = mysql_fetch_array($xxx)) && ($editing)) {

		   // Добавление заданий к заказам
			if (db_adcheck("db_zadan")) {
			$zak_zad = $_POST["zak_zad"];

			for ($j=0;$j < count($zak_zad);$j++) {
				$ID_zakdet = "0";
				$ID_zak = "0";
				$ID_park = "0";
				$xxx = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID = '".$zak_zad[$j]."')");
				if ($xxx = mysql_fetch_array($xxx)) {
					$ID_zakdet = $xxx["ID_zakdet"];
					$ID_park = $xxx["ID_park"];
					$yyy = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID = '".$ID_zakdet."')");
					if ($yyy = mysql_fetch_array($yyy)) $ID_zak = $yyy["ID_zak"];
				}
				if ($ID_zakdet!=="0") dbquery("INSERT INTO ".$db_prefix."db_zadan (SMEN, ID_park, ID_zak, ID_zakdet, ID_operitems, ID_resurs, DATE, EDIT_STATE) VALUES ('".$smena."', '".$ID_park."', '".$ID_zak."', '".$ID_zakdet."', '".$zak_zad[$j]."', '".$idresurs."', '".$pdate."', '0')");
			}
			}

		}
		redirect($pageurl."&event","script");
		$redirected = true;
	}

// okoperid /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if (isset($_GET['okoperid'])) 
	{
		$id = $_GET['okoperid'];

		if (db_check("db_operitems","STATE")) {
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (ID_operitems = '".$id."') and (EDIT_STATE = '0')");
		if (!mysql_fetch_array($result)) {

		   // Обновили операцию
			dbquery("Update ".$db_prefix."db_operitems Set STATE:='1' where (ID = '".$id."')");

		   // пересчитали заказ
			// ??	CalculateOperitem($xxxzad["ID_operitems"]);
		}
		}

		redirect($pageurl."&event","script");
		$redirected = true;
	}

// addbytabel /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if (isset($_GET['addbytabel'])) 
	{
		if ((db_adcheck("db_zadanres")) && ($editing)) 
		{

		   ///////////////////////////////////////////
			$resurs_IDs = Array();
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_otdel where (INSZ = '1')");
			while($otdel = mysql_fetch_array($xxx)) 
			{
				$xxxs = dbquery("SELECT * FROM ".$db_prefix."db_shtat where (ID_otdel = '".$otdel["ID"]."')");
          while($shtat = mysql_fetch_array($xxxs)) 
            if (!in_array($shtat["ID_resurs"],$resurs_IDs)) 
              $resurs_IDs[] = $shtat["ID_resurs"];
			}
		   ///////////////////////////////////////////

			$ids = array();
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_tabel where (SMEN='".$smena."') and (DATE='".$pdate."') and (TID='0')");
			while ($res = mysql_fetch_array($xxx)) 
			{
				// 21.09.2017 - не выводим дневных мастеров
				if (in_array($res['ID_resurs'], array(545, 84, 304, 678))) {
					continue;
				}
				
			   if (in_array($res["ID_resurs"],$resurs_IDs)) 
			   {
            $xxres = dbquery("SELECT * FROM ".$db_prefix."db_zadanres where (SMEN='".$smena."') and (DATE='".$pdate."') and (ID_resurs='".$res["ID_resurs"]."')");
            if (!mysql_fetch_array($xxres)) 
              dbquery("INSERT INTO ".$db_prefix."db_zadanres (DATE, SMEN, ID_resurs) VALUES ('".$pdate."', '".$smena."', '".$res["ID_resurs"]."')");
			   }
			}
		}
		redirect($pageurl."&event","script");
		$redirected = true;
	}


if (!$redirected) 
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Вывод списка ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function OpenID( $item, $active = 1 ) 
{
	global $db_prefix, $editing, $pageurl_addzad, $pdate, $smena, $date, $pageurl, $print_resurs_url, $ID_resurs_mults;

	$result = dbquery("SELECT ID FROM ".$db_prefix."db_zadan where (DATE = '".$pdate."') and (ID_resurs = '".$item["ID_resurs"]."') and (SMEN = '".$smena."') order by ORD");
	$cnt = mysql_num_rows ($result);

    if( $active )
    {
      $highlight = 'highlite';
      $prefix = '';
    }
        else
        {
          $highlight = 'highlite_inactive';
          $prefix = '_';
        }

	   // СУММЫ ПЛАН И ФАКТ ///////////////////////////////////////////

			$plan_n = 0;
			$fact_n = 0;
			$fact = 0;

		$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE = '".$pdate."') and (ID_resurs = '".$item["ID_resurs"]."') and (SMEN = '".$smena."') order by ORD");
		while($res = mysql_fetch_array($result))
		{
			$plan_n = $plan_n + (1*$res["NORM"]);
			$fact_n = $fact_n + (1*$res["NORM_FACT"]);
			$fact = $fact + (1*$res["FACT"]);
		}

	   // Строка
		echo "<tr data-id='".$item["ID"]."' data-date='" . $item["DATE"] . "' data-resource-id='" . $item["ID_resurs"] . "' class='$highlight' style='height: 25px;'>";

	   // Сортировка
		Field($item,"db_zadanres","ORD",$editing,"style='width: 30px;'","","rowspan='2'");

	   // Ресурс
		$resource_tid = dbquery("SELECT TID FROM okb_db_tabel where (DATE = '".$item["DATE"]."') and (ID_resurs = '".$item["ID_resurs"]."') GROUP BY DATE");
		$resource_tid = mysql_fetch_array($resource_tid);
		// echo $resource_tid['TID'];
		$resource_status_array = array(1=>'ОТ', 2=>'ДО', 3=>'Х', 4=>'Б', 8=>'ЛЧ', 9=>'НВ', 10=>'К', 11=>'РП', 12=>'У', 13=>'ПК', 14=>'НП', 15=>'ВО', 16=>'ГО');
		
		if(isset($resource_status_array[$resource_tid['TID']]))
			$resource_status = '<span class="res_status">'.$resource_status_array[$resource_tid['TID']].'</span>';
		else
			$resource_status = '';
		
 		echo "<td id='".$prefix."id_res_zadanres_".$item["ID_resurs"]."' class='Field' style='text-align: left;' colspan='5' rowspan='2'>
		
		<input type='checkbox' name='is_multimachine' style='float:right' title='Многостаночник' " . ($item['is_multimachine'] == 1 ? "checked" : "") . "/> 
		<input type='text' name='multimachine_fact' style='float:right;width:18px;' title='Часы многостаночника' value='" . ($item['multimachine_fact'] != 0 ? $item['multimachine_fact'] : "") . "' />
		<b>".FVal($item,"db_zadanres","ID_resurs")."</b>".$resource_status;
		echo "<a href='".$print_resurs_url.$smena."&p2=".$item["ID_resurs"]."' target='_blank' style='margin-left:15px;'>Распечатать</a>";
		echo " | <a href='index.php?do=show&formid=211&p0=".$_GET['p0']."&p1=".$_GET['p1']."&p2=".$item['ID_resurs']."' target='_blank'>Распечатать (новая)</a>";
		echo "</td>";

		echo "<td class='Field' rowspan='2'></td>";
		echo "<td class='Field' rowspan='2'></td>";
		echo "<td class='Field' rowspan='2'><b>".$plan_n."</b></td>";
		echo "<td class='Field' rowspan='2'></td>";
		echo "<td class='Field'></td>";
		echo "<td class='Field'><b>".$fact_n."</b></td>";
		echo "<td class='Field'><b>".$fact."</b></td>";
		echo "<td class='Field' rowspan='2'></td>";
		echo "<td class='Field' colspan='2' rowspan='2'><a href='".$pageurl."&event'><- Обновить</a></td>";
		echo "<td class='Field' rowspan='2'><input class='".( $cnt ? "" : "empty_emp")."' data-id='".$item["ID"]."' type='checkbox' id='".$prefix."sel_all_".$item["ID_resurs"]."' onchange='sel_all_zad(".$item["ID_resurs"].", this);'></td>";

	   // Действие
		$showdel = "";

		if ((db_adcheck("db_zadanres")) && ($editing)) 
		{
			$used = false;
			$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where  (DATE='".$item["DATE"]."') and (SMEN='".$item["SMEN"]."') and (ID_resurs='".$item["ID_resurs"]."')");
			if (mysql_fetch_array($result)) $used = true;
			$ID_resurs_mults = $item['ID_resurs'];


			$item_id = $item["ID"];
			$message = "Уверены, что хотите удалить ресурс из списка ?";

			$class= '';
			$location = '';

			if (!$used) 
				$location = "location.href=\"$pageurl&delresid=$item_id\";";
			else
			{
				$class= "hidden";	
				$id = "del_res_img_".$item["ID_resurs"];
			}
        	
        	$showdel = "<img id='$id' data-id='$item_id' class='$class' onclick='if (confirm(\"$message\")) $location' style='cursor: hand;' alt='Удалить' src='uses/del.png'>";

        	$showdel = "<img id='$id' data-id='$item_id' class='$class' onclick='delete_res( this, \"$message\")' style='cursor: hand;' alt='Удалить' src='uses/del.png'>";
		}

		echo "<td class='Field' rowspan='2'>".$showdel."</td>";

		echo "</tr>\n";


	   // Спец задания

			$in_tabel = false;
			$result_tabel = dbquery("SELECT * FROM ".$db_prefix."db_tabel where (DATE = '".$pdate."') and (ID_resurs = '".$item["ID_resurs"]."')");
			if ($tabel = mysql_fetch_array($result_tabel)) $in_tabel = true;

		echo "<tr data-id='".$item["ID"]."' class='$highlight'>";
		echo "<td class='Field' colspan='2'><b>Спец. зад.</b></td>";

			if (!$in_tabel) echo "<td class='Field'></td>";
			if ($in_tabel) Field($tabel,"db_tabel","SPEC",$editing,"",""," style='max-width: 50px;' ");

		echo "</tr>\n";

		// Коэффициент трудоёмкости
		
			$used = 0;
			$itognorm = 0;
			$itognormfact = 0;
			$itogfact = 0;
			$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where  (DATE='".$item["DATE"]."') and (SMEN='".$item["SMEN"]."') and (ID_resurs='".$item["ID_resurs"]."') and (EDIT_STATE='1')");
			while($usres=mysql_fetch_array($result)) {
				$result2 = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID='".$usres['ID_operitems']."')");
				$usres2=mysql_fetch_array($result2);
				$itognorm=$itognorm+$usres['NORM'];
				$itognormfact=$itognormfact+$usres['NORM_FACT'];
				$itogfact=$itogfact+$usres['FACT'];
				$used = 1;
			}
			if($used == 1) {
				if ($itogfact == '0'){
					$itogfact2 = '0';
				}else{
					$itogfact2 = round(($itognormfact/$itogfact),2);
				}
				echo "<tr><td class='Field' colspan='2' style='text-align:right;background:#cbdef4;'><b>Итого:</b>
				</td><td class='Field' style='background:#cbdef4;'>Коэффициент трудоёмкости <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(".$itogfact2.")</b></td></tr>";
			}

	   // ЗАДАНИЯ НА РЕСУРС ///////////////////////////////////////////

		$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE = '".$pdate."') and (ID_resurs = '".$item["ID_resurs"]."') and (SMEN = '".$smena."') order by ORD");
		while($res = mysql_fetch_array($result)) 
			OpenZadanID($res, $active);
}

function OpenZadanID( $item , $active = 1 ) 
	{
		global $db_prefix, $editing, $modering, $editingplan, $pageurl, $ID_resurs_mults, $smena, $date;
		
    $item_id = $item["ID"];

    if( $active )
      $prefix = '';
        else
          $prefix = '_';
        
	   // Строка
		//echo "<tr id='row1_".$item["ID"]."' onmouseover=\"HLID(".$item["ID"].");\" onmouseout=\"DHLID(".$item["ID"].");\">";

		$result = dbquery("SELECT * FROM ".$db_prefix."db_zak where (ID = '".$item["ID_zak"]."')");
		$zak = mysql_fetch_array($result);
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID = '".$item["ID_zakdet"]."')");
		$izd = mysql_fetch_array($result);
		$result = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID = '".$item["ID_operitems"]."')");
		$oper = mysql_fetch_array($result);
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID_zak = '".$item["ID_zak"]."') and (PID = '0')");
		$first_dse = mysql_fetch_array($result);
		echo "<tr id='".$prefix."row1_".$item["ID"]."' data-id='" . $item['ID'] . "' data-dse-id='" . $izd['ID'] . "'>";

		$tid = FVal($zak,"db_zak","TID");
		$name = $tid." ".$zak["NAME"];
	   // №
		Field($item,"db_zadan","ORD",$editing,"style='width: 30px;'","","rowspan='2' ");

	   // ID
		echo "<td class='Field' style='text-align: center;position:relative' rowspan='2'><b>".$item["ID"]."</b><br/>
		

		</td>";

		$in_sklad = false;

		if (!empty($izd["OBOZ"]) && mysql_num_rows(mysql_query("SELECT 1 FROM `okb_db_sklades_detitem` WHERE REPLACE(`NAME`, ' ', '') LIKE '%" . str_replace(' ', '', $izd['OBOZ']) . "%' LIMIT 1")) > 0) {
			$in_sklad = true;
		}

	   // Заказ / ДСЕ 
		echo "<td class='Field dse' style='text-align: left;" . ($in_sklad ? 'background-color:#e2ffe3' : '') . "'><span style='float:right'><a href='#'><img class='print' src='/style/print.png' style='width:70%'/></a></span>
		<span style='float:right'><img class='report' src='/style/report.png' style='width:70%;height:90%'/></span>
		<span style='color: #004e7a;'><b>".$name."</b> ".$zak["DSE_NAME"]."</span><br>".$izd["OBOZ"]." ".$izd["NAME"]."</td>";

	   // №
		Field($oper,"db_operitems","ORD",false,"","","");

	   // Операция
		$pic = "";
		if (($item["EDIT_STATE"]=="1") && ($oper["STATE"]=="0")) {
			$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (ID_operitems = '".$item["ID_operitems"]."') and (EDIT_STATE = '0')");
			if (!mysql_fetch_array($result)) {
				$pic = "<img onclick='if (confirm(\"Перевести операцию в статус - выполнено?\")) location.href=\"$pageurl&ID_zak=".$item["ID_zak"]."&okoperid=".$item["ID_operitems"]."\";' style='cursor: hand; margin-right: 5px;' alt='Перевести операцию в статус - выполнено' src='uses/ok.png'>";
			}
		}
		Field($item,"db_zadan","ID_operitems",false,"",$pic,"");

	   // Оборудование
		echo "<td class='Field'><select onchange='change_park_sz(".$item['ID'].", this.options[this.selectedIndex].id.substr(10));'><option id='".$prefix."mark_p_sz_0'>-----";
		$result3 = dbquery("SELECT ID, MARK FROM ".$db_prefix."db_park order by MARK");
		while ($res_t3 = mysql_fetch_array($result3)) 
		{
			if ($item['ID_park']==$res_t3['ID']) { echo "<option id='".$prefix."mark_p_sz_".$res_t3['ID']."' selected>".$res_t3['MARK'];}else{ echo "<option id='".$prefix."mark_p_sz_".$res_t3['ID']."'>".$res_t3['MARK'];}
		}
		echo "</select></td>";

	   // Кол-во операций на заказ
		$rcount = $oper["NUM_ZAK"]*1 - $oper["NUM_ZADEL"]*1;
		if ($oper["BRAK"]*1==1) $rcount = $oper["NUM_ZAK"]*1;

	   // План
	    $nummsumm_expl = explode("|", $item['NUMSUMM_PLAN']);
		if (($editing) && (db_adcheck("db_zadan"))) 
		{
			echo "<td class='Field'><input style='width:45px;' onchange='add_numsumm(".$item['ID_resurs'].",".$item['ID_operitems'].",0,this.value);' value='".$nummsumm_expl[0]."'>/<input style='width:45px;' onchange='add_numsumm(".$item['ID_resurs'].",".$item['ID_operitems'].",1,this.value);' value='".$nummsumm_expl[1]."'></td>";
		}else
		{
			echo "<td class='Field'>".$nummsumm_expl[0]."/".$nummsumm_expl[1]."</td>";
		}
		$nxx = 0;
		if ($rcount>0) 
		$nxx = $oper["NORM_ZAK"]/$rcount;
		$calculator = "";
		$from_id = $prefix."num_".$item["ID"];
		$to_id = $prefix."norm_".$item["ID"];
		$churl = "db_edit.php?db=db_zadan&field=NORM&id=".$prefix."".$item["ID"]."&value=";
		if ($editingplan) $calculator="<a href='javascript:void(0);' onClick=\"SetNewValue('".$from_id."','".$to_id."',".$nxx.",'".$churl."')\" title='Пересчитать Н/Ч'><img src='project/img/calc.png' alt='Пересчитать Н/Ч'></a>";
		Field($item,"db_zadan","NUM",$editingplan," id='".$prefix."".$from_id."' ",$calculator," style='max-width: 60px;' ");
		Field($item,"db_zadan","NORM",$editingplan," id='".$prefix."".$to_id."' ",""," style='max-width: 50px;' ");

	   // На заказ
		$ost = 0;
		if ($oper["NORM_ZAK"]>0) $ost = $rcount*(($oper["NORM_ZAK"]-$oper["NORM_FACT"])/$oper["NORM_ZAK"]);
		$ost = number_format( $ost, 0, '.', ' ');
		echo "<td class='Field'><center><b>". (float) round($oper["NORM_ZAK"]-$oper["NORM_FACT"], 2)." (".$ost.")</b><br>".$oper["NORM_ZAK"]." (".$rcount.")<br>".round(($oper["NORM"])/(60),2)."</center></td>";
		
	   // Факт
		$nxx = 0;
		if ($rcount>0) $nxx = $oper["NORM_ZAK"]/$rcount;
		$calculator = "";
		$from_id = "fnum_".$item["ID"];
		$to_id = "fnorm_".$item["ID"];
		$churl = "db_edit.php?db=db_zadan&field=NORM_FACT&id=".$prefix."".$item["ID"]."&value=";
		if ($modering) $calculator="<a href='javascript:void(0);' onClick=\"SetNewValue('".$from_id."','".$to_id."',".$nxx.",'".$churl."')\" title='Пересчитать Н/Ч'><img src='project/img/calc.png' alt='Пересчитать Н/Ч'></a>";
		Field($item,"db_zadan","NUM_FACT",$modering,"id='".$prefix."".$from_id."' ",$calculator," style='max-width: 60px;' ");
		Field($item,"db_zadan","NORM_FACT",$modering," id='".$prefix."".$to_id."' ",""," style='max-width: 50px;' ");
		Field($item,"db_zadan","FACT",$modering,"",""," style='max-width: 50px;' ");
		Field($item,"db_zadan","ID_zadanrcp",$modering,"style='width: 140px;' ","","");

	   // Цехи
	   // Цехи
		if (($editing) && (db_adcheck("db_zadan"))) {
			echo "<td class='Field'><select onchange=\"vote(this , 'db_edit.php?db=db_zadan&field=CEH1&id=".$prefix."".$item['ID']."&value='+this.options[this.options.selectedIndex].value);\">";
			echo "<option value=0"; if ($item['CEH1']=="0") { echo " selected";} echo ">---";
			echo "<option value=1"; if ($item['CEH1']=="1") { echo " selected";} echo ">А-1";
			echo "<option value=2"; if ($item['CEH1']=="2") { echo " selected";} echo ">А-2";
			echo "<option value=3"; if ($item['CEH1']=="3") { echo " selected";} echo ">А-3";
			echo "<option value=4"; if ($item['CEH1']=="4") { echo " selected";} echo ">А-4";
			echo "<option value=5"; if ($item['CEH1']=="5") { echo " selected";} echo ">А-5";
			echo "<option value=6"; if ($item['CEH1']=="6") { echo " selected";} echo ">А-6";
			echo "<option value=7"; if ($item['CEH1']=="7") { echo " selected";} echo ">Б-1";
			echo "<option value=8"; if ($item['CEH1']=="8") { echo " selected";} echo ">Б-2";
			echo "<option value=9"; if ($item['CEH1']=="9") { echo " selected";} echo ">Б-3";
			echo "<option value=10"; if ($item['CEH1']=="10") { echo " selected";} echo ">Б-4";
			echo "<option value=11"; if ($item['CEH1']=="11") { echo " selected";} echo ">В-1";
			echo "<option value=12"; if ($item['CEH1']=="12") { echo " selected";} echo ">В-2";
			echo "<option value=13"; if ($item['CEH1']=="13") { echo " selected";} echo ">В-3";
			echo "<option value=14"; if ($item['CEH1']=="14") { echo " selected";} echo ">В-4";
			echo "<option value=15"; if ($item['CEH1']=="15") { echo " selected";} echo ">Д-1";
			echo "<option value=16"; if ($item['CEH1']=="16") { echo " selected";} echo ">Д-2";
			echo "<option value=17"; if ($item['CEH1']=="17") { echo " selected";} echo ">Д-3";
			echo "<option value=18"; if ($item['CEH1']=="18") { echo " selected";} echo ">Д-4";
			echo "</select></td>";
		}else{
			echo "<td class='Field'></td>";
		}
		if (($editing) && (db_adcheck("db_zadan"))) {
			echo "<td class='Field'><select onchange=\"vote(this , 'db_edit.php?db=db_zadan&field=CEH2&id=".$prefix."".$item['ID']."&value='+this.options[this.options.selectedIndex].value);\">";
			echo "<option value=0"; if ($item['CEH2']=="0") { echo " selected";} echo ">---";
			echo "<option value=1"; if ($item['CEH2']=="1") { echo " selected";} echo ">А-1";
			echo "<option value=2"; if ($item['CEH2']=="2") { echo " selected";} echo ">А-2";
			echo "<option value=3"; if ($item['CEH2']=="3") { echo " selected";} echo ">А-3";
			echo "<option value=4"; if ($item['CEH2']=="4") { echo " selected";} echo ">А-4";
			echo "<option value=5"; if ($item['CEH2']=="5") { echo " selected";} echo ">А-5";
			echo "<option value=6"; if ($item['CEH2']=="6") { echo " selected";} echo ">А-6";
			echo "<option value=7"; if ($item['CEH2']=="7") { echo " selected";} echo ">Б-1";
			echo "<option value=8"; if ($item['CEH2']=="8") { echo " selected";} echo ">Б-2";
			echo "<option value=9"; if ($item['CEH2']=="9") { echo " selected";} echo ">Б-3";
			echo "<option value=10"; if ($item['CEH2']=="10") { echo " selected";} echo ">Б-4";
			echo "<option value=11"; if ($item['CEH2']=="11") { echo " selected";} echo ">В-1";
			echo "<option value=12"; if ($item['CEH2']=="12") { echo " selected";} echo ">В-2";
			echo "<option value=13"; if ($item['CEH2']=="13") { echo " selected";} echo ">В-3";
			echo "<option value=14"; if ($item['CEH2']=="14") { echo " selected";} echo ">В-4";
			echo "<option value=15"; if ($item['CEH2']=="15") { echo " selected";} echo ">Д-1";
			echo "<option value=16"; if ($item['CEH2']=="16") { echo " selected";} echo ">Д-2";
			echo "<option value=17"; if ($item['CEH2']=="17") { echo " selected";} echo ">Д-3";
			echo "<option value=18"; if ($item['CEH2']=="18") { echo " selected";} echo ">Д-4";
			echo "</select></td>";
		}else{
			echo "<td class='Field'></td>";
		}

	   // Действие
		$showdel = "<img onclick='if (confirm(\"Уверены, что хотите удалить задание ID: ".$item["ID"]."?\")) vote5(this,".$item["ID"].",".$item["ID_operitems"].", ".$item['ID_resurs'].");' style='cursor: hand;' alt='Удалить' src='uses/del.png'> ";
		echo "<td class='Field'><input type='checkbox' name='cur_zad_sel' name2='parent_res_".$item['ID_resurs']."' name3='".$item["EDIT_STATE"]."' name4='".$item["ID_operitems"]."' id='".$prefix."item_zad_".$item["ID"]."'></td>
		<td class='Field'>";
		if ($item["EDIT_STATE"]=="0") {
			echo "<table><tr><td style='text-align: left; padding-right: 5px;'>";
		if (($editing) && (db_adcheck("db_zadan"))) echo $showdel;
			echo "</td><td style='text-align: right; padding-left: 5px;'>";
		if (($modering) && (db_adcheck("db_zadan"))) echo " <a style='cursor:pointer;' onclick='reload_page(); vote6(this,".$item["ID"].",".$item["ID_operitems"].", ".$item['ID_resurs'].");'><img alt='Готово' src='uses/ok.png'></a>";
			echo "</td></tr></table>";
		} else {
			if ((db_adcheck("db_zadan")) && ($oper["STATE"]=="0")) echo " <a style='cursor:pointer;' onclick='reload_page(); vote7(this,".$item["ID"].",".$item["ID_operitems"].", ".$item['ID_resurs'].");'><img alt='Возобновить' src='uses/restore.png'></a>";
		}
		echo "</td>";

		echo "</tr>\n";
		//echo "<tr id='row3_".$item["ID"]."' onmouseover=\"HLID(".$item["ID"].");\" onmouseout=\"DHLID(".$item["ID"].");\">
		echo "<tr id='".$prefix."row3_".$item["ID"]."'>
		<td style='width:125px;' class='Field'><span style='margin-right: 10px;'>Инициатор:</span>";
		Field($item,"db_zadan","MORE",$editing,"","<span style='margin-right: 10px;'>Примечание:</span>","colspan='10'");
		

// shindax 26.12.2017		
		if( $active )
		{
		
    echo "
   
   <td class='Field AC'><button class='copy_button' type='button' data-id='".$item["ID"]."'><img src='uses/file_copy.png'>Копировать СЗ</button></td>
    
    <td class='Field' colspan='4'>
		<div style='float:right;display:none;position:absolute;margin-left:-126px;margin-top:25px;z-index:99999' class='change_smen_date_block'>
		<select name='change_resource_select_smen' style='position:absolute;margin-left:-101px;text-align:center;width:100px' size='5'>
			<option value='0' style='color:red'>Смена:</option>
			<option value='1'" . ($_GET['p1'] == 1 ? ' selected="selected"' : '') . ">1</option>
			<option value='2'" . ($_GET['p1'] == 2 ? ' selected="selected"' : '') . ">2</option>
			<option value='3'" . ($_GET['p1'] == 3 ? ' selected="selected"' : '') . ">3</option>
		</select>
		<select name='change_resource_select_date' style='text-align:center;width:120px' size='5'></select>
		<select name='change_resource_select_date_resource' style='display:none;text-align:center;width:216px;margin-top:-78px;margin-right:-272px;' size='5'></select>
		
		</div>

		
		<button class='change_smen_date_link' style='width:100%;'>
		<img src='/uses/view.gif'/>
		Изменить ресурс 
		</button>
		
		
    </td>";
    }
    else
        echo "<td class='Field' colspan='4'></td>";
        
        echo "</tr>\n";
}

   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   // ФОРМА /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//		echo "</form>\n";

		$usedres[] = "0";
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zadanres where (DATE='".$pdate."') and (SMEN='".$smena."') order by ORD");
		while($res = mysql_fetch_array($xxx)) 
			$usedres[] = $res["ID_resurs"];


// shindax
   echo "<div id='caption'>";
		echo "<table style='padding: 0px;' cellpadding='0' cellspacing='0'><tr><td id='links_btn' style='text-align: left;'>\n";
			if (($editing) && (db_adcheck("db_zadanres"))) 
			{
				echo "<div name='add_pers' class='links'>";
				echo "<span class='popup' onClick='chClass(this,\"hpopup\",\"popup\");'>Добавить ресурс";

//					echo "<div class='popup' onClick='window.event.cancelBubble = true;'>";
					echo "<div class='popup' name='tasks_popup_div_$pass'>";					
					$pass ++ ;
					echo "<form  method='post' action='".$pageurl."' style='padding: 0px; margin: 0px;'>";
					echo "<SELECT name='addnewres[]' style='height: 300px;' MULTIPLE>";
						$xxx2 = dbquery("SELECT * FROM ".$db_prefix."db_shtat where ((ID_resurs != '0') and ((ID_otdel = '18') or (ID_otdel = '19') or (ID_otdel = '21') or (ID_otdel = '22'))) ");
						$fruits_1 = array();
						while($res2 = mysql_fetch_array($xxx2)){
							$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID = '".$res2['ID_resurs']."' ) ");
							$res = mysql_fetch_array($xxx);
							$fruits_1[$res["ID"]] = $res["NAME"];
						}
						$xxx3 = dbquery("SELECT * FROM ".$db_prefix."db_shtat where ((ID_resurs != '0') and (ID_otdel != '18') and (ID_otdel != '19') and (ID_otdel != '21') and (ID_otdel != '22') AND `presense_in_shift_orders`=1 ) ");
						$fruits_2 = array();
						while($res3 = mysql_fetch_array($xxx3)){
							$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID = '".$res3['ID_resurs']."') ");
							$res = mysql_fetch_array($xxx);
							$fruits_2[$res["ID"]] = $res["NAME"];
						}
asort($fruits_1);
asort($fruits_2);
    echo "<option style='color:red; width:150px;' value='0'>--- (производство)";

    foreach ($fruits_1 as $keey_1 => $vaal_1) 
      echo "<option style='width:150px;' value='".$keey_1."'>".$vaal_1;

    echo "<option style='color:red; width:150px;' value='0'>--- (остальной персонал)";
    
    foreach ($fruits_2 as $keey_1 => $vaal_1) 
      echo "<option style='width:150px;' value='".$keey_1."'>".$vaal_1;
	
					echo "</SELECT>";
					echo "<br><br><input type='submit' value='Добавить'>";
					echo "</form>";
					echo "</div>";
					
				echo "</span>\n";
				echo " | <a class='acl' href='$pageurl&addbytabel'>Из табеля</a>";
				echo "</div>";
	}
			
		echo "</td><td style='text-align: right;'>";
			echo "<div class='links'>";
			$smen_1_def = "";
			$smen_2_def = "";
			$smen_3_def = "";
			if ($smena == 1){ $smen_1_def = "selected";}
			if ($smena == 2){ $smen_2_def = "selected";}
			if ($smena == 3){ $smen_3_def = "selected";}
			echo "<select id='cur_sz_smen_158' value='".$smena."' onchange='location.href=\"index.php?do=show&formid=158&p0=".$pdate."&p1=\"+this.value;'><option value='1' ".$smen_1_def.">1 смена</option><option value='2' ".$smen_2_def.">2 смена</option><option value='3' ".$smen_3_def.">3 смена</option></select><input type='date' id='smen_dt_sz' class='acl' min='1970-01-01' max='2099-01-01' value='".substr($pdate,0,4)."-".substr($pdate,4,2)."-".substr($pdate,6,2)."' onchange='location.href=\"index.php?do=show&formid=158&p0=\"+this.value.substr(0,4)+this.value.substr(5,2)+this.value.substr(8,2)+\"&p1=\"+document.getElementById(\"cur_sz_smen_158\").value+\";\";'>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;";
			echo "<a id='prnt_dt_sz2' class='acl' href='index.php?do=show&formid=210&p0=".$_GET['p0']."&p1=".$_GET['p1']."' target='_blank'>Печать сводной</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
			echo "<a id='prnt_dt_sz3' class='acl' href='index.php?do=show&formid=211&p0=".$_GET['p0']."&p1=".$_GET['p1']."' target='_blank'>Версия для печати (новая)</a>"; // &nbsp;&nbsp;|&nbsp;&nbsp;";
//			echo "<a id='prnt_dt_sz' class='acl' href='".$print_url."' target='_blank'>Версия для печати</a>";
			echo "</div>";
      echo "</td></tr></table></div>";

// *************************************************************************************************************      

	   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
		$RsursIDs = Array();
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zadanres where (DATE='".$pdate."') and (SMEN='".$smena."') order by ORD");
		while($res = mysql_fetch_array($xxx)) 
			$RsursIDs[] = $res["ID_resurs"];
		
    echo "<div id='table_caption_div' style='z-index:102 !IMPORTANT;'>";		
		echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1650px;' border='1' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>\n";
		echo "<td colspan=2 width='80'>".substr($pdate,6,2).".".substr($pdate,4,2).".".substr($pdate,0,4)."<br>см. ".$smena."</td>\n";
		echo "<td name='links_btn2'></td>\n";
		echo "<td colspan='2'>Операция</td>\n";
		echo "<td rowspan='2' width='160'>Оборудование</td>\n";
		echo "<td rowspan='2' width='55'>Деталей в работе/Сделано факт.</td>\n";
		echo "<td colspan='2'>План</td>\n";
		echo "<td rowspan='2' width='80'>На заказ<br><b>осталось</b> / всего,<br>Н/Ч (шт) / <br>норма на ед.</td>\n";
		echo "<td colspan='4'>Факт</td>\n";
		echo "<td rowspan='2' width='50'>Откуда<br>взять</td>\n";
		echo "<td rowspan='2' width='50'>Куда<br>положить</td>\n";
		echo "<td colspan='2' rowspan='2' width='50'>multiselect</td>\n";
		echo "</tr>\n";


		echo "<tr class='first'>\n";
		echo "<td width='40'>№</td>\n";
		echo "<td width='40'>ID</td>\n";
		echo "<td id='h0' width='300'>Заказ / ДСЕ<br>
		<select id='focus_to_resource'><option value=0 selected>Выберите ресурс</option>";
		$xxx_5 = dbquery("SELECT ID, NAME FROM ".$db_prefix."db_resurs order by binary (NAME)");
		while($resurs_5 = mysql_fetch_array($xxx_5)) {
		   if (in_array($resurs_5["ID"],$RsursIDs)) {
			   echo "<option value='".$resurs_5['ID']."'>".$resurs_5['NAME']."</option>";
		   }
		}
		echo "</select></td>\n";
		
		echo "<td width='20'>№</td>\n";
		echo "<td>Наименование</td>\n";
		echo "<td width='60'>Кол-во</td>\n";
		echo "<td width='50'>Н/Ч</td>\n";
		echo "<td width='60'>Кол-во</td>\n";
		echo "<td width='50'>Н/Ч</td>\n";
		echo "<td width='50'>Затр.<br>время, ч</td>\n";
		echo "<td width='150'>Причина невыполнения</td>\n";
		echo "</tr>\n";
		echo "</thead>";
		
		?>
		
		
	<script>
	$(document).on("change", "#focus_to_resource", function () {

		$("#id_res_zadanres_" + $(this).val()).get(0).parentElement.scrollIntoView(true);
		
		document.getElementById("vpdiv").scrollTop += -($(".rdtbl thead").outerHeight() + $("#caption").outerHeight() + 7);


	});
	
	function getParameterByName(name) {
    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}
	
	$(function () {
		if (window.location.href.indexOf("event") == -1) {
			document.getElementById('vpdiv').scrollTop = get_top_scrl_numb;
		}
		
		var current_resource_id = getParameterByName("current_resource_id");
//alert(current_resource_id);

		var get_id_resource = $("#id_res_zadanres_" + current_resource_id).get(0);
		
		if (get_id_resource != undefined && get_id_resource != null) {
			$("#id_res_zadanres_" + current_resource_id).get(0).parentElement.scrollIntoView(true);
		}
if (current_resource_id > 0) {	
		document.getElementById("vpdiv").scrollTop += -($(".rdtbl thead").outerHeight() + $("#caption").outerHeight() + 7);

}
	})
	</script>
		<?php

// shindax

		echo "<tbody>";
	
	   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs order by binary (NAME)");
		while($resurs = mysql_fetch_array($xxx)) 
		  if (in_array($resurs["ID"],$RsursIDs)) 
		   {
          $xxxres = dbquery("SELECT * FROM ".$db_prefix."db_zadanres where (DATE='".$pdate."') and (SMEN='".$smena."') and (ID_resurs='".$resurs["ID"]."')");
          $res = mysql_fetch_array($xxxres);
          OpenID( $res , 0 );
		   }

		echo "</tbody>";

	
		echo "</table>";		
		echo "</div>";		
		
// *****************************************************************************************************
      
		echo "<form id='form1x' method='post' action='".$pageurl."'>";

    echo "<div id='main_table_div'>";
		echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1650px;' border='1' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>\n";
		echo "<td colspan=2 width='80'>".substr($pdate,6,2).".".substr($pdate,4,2).".".substr($pdate,0,4)."<br>см. ".$smena."</td>\n";
		echo "<td name='links_btn2'></td>\n";
		echo "<td colspan='2'>Операция</td>\n";
		echo "<td rowspan='2' width='160'>Оборудование</td>\n";
		echo "<td rowspan='2' width='55'>Деталей в работе/Сделано факт.</td>\n";
		echo "<td colspan='2'>План</td>\n";
		echo "<td rowspan='2' width='80'>На заказ<br><b>осталось</b> / всего,<br>Н/Ч (шт) / <br>норма на ед.</td>\n";
		echo "<td colspan='4'>Факт</td>\n";
		echo "<td rowspan='2' width='50'>Откуда<br>взять</td>\n";
		echo "<td rowspan='2' width='50'>Куда<br>положить</td>\n";
		echo "<td colspan='2' rowspan='2' width='50'>multiselect</td>\n";
		echo "</tr>\n";


		echo "<tr class='first'>\n";
		echo "<td width='40'>№</td>\n";
		echo "<td width='40'>ID</td>\n";
		echo "<td id='h0' width='300'>Заказ / ДСЕ</td>";

/*		
		<select onchange='document.getElementById(\"vpdiv\").scrollTop=(document.getElementById(\"id_res_zadanres_\"+this.value).offsetTop+25);'><option value=0 selected>Выберите ресурс</option>";
		$xxx_5 = dbquery("SELECT ID, NAME FROM ".$db_prefix."db_resurs order by binary (NAME)");
		while($resurs_5 = mysql_fetch_array($xxx_5)) {
		   if (in_array($resurs_5["ID"],$RsursIDs)) {
			   echo "<option value='".$resurs_5['ID']."'>".$resurs_5['NAME']."</option>";
		   }
		}
		echo "</select></td>\n";
*/
		echo "<td width='20'>№</td>\n";
		echo "<td>Наименование</td>\n";
		echo "<td width='60'>Кол-во</td>\n";
		echo "<td width='50'>Н/Ч</td>\n";
		echo "<td width='60'>Кол-во</td>\n";
		echo "<td width='50'>Н/Ч</td>\n";
		echo "<td width='50'>Затр.<br>время, ч</td>\n";
		echo "<td width='150'>Причина невыполнения</td>\n";
		echo "</tr>\n";
		echo "</thead>";


		echo "<tbody>";
				
	   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs order by binary (NAME)");
		while($resurs = mysql_fetch_array($xxx)) {
		   if (in_array($resurs["ID"],$RsursIDs)) {
			$xxxres = dbquery("SELECT * FROM ".$db_prefix."db_zadanres where (DATE='".$pdate."') and (SMEN='".$smena."') and (ID_resurs='".$resurs["ID"]."')");
			$res = mysql_fetch_array($xxxres);
			OpenID($res);
		   }
		}
		echo "</tbody>";

		echo "</table></div>\n";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}

/////////////////////////     изменение титульника по названию смены

echo "<script language='javascript'>
var per_clik_page;
function add_numsumm(id_res, id_oper, n_arr, val){
	var req = getXmlHttp();
	req.open('GET', 'project/zadan/zadanres_numsumm.php?p1='+id_res+'&p2='+id_oper+'&p3='+n_arr+'&p4='+val);
	req.send(null);
}
function sel_all_zad(id_res, obj){
	if (obj.checked==true){
		var all_checked = document.getElementsByName('cur_zad_sel').length;
		for (var a_a=0; a_a < all_checked; a_a++){
			if (document.getElementsByName('cur_zad_sel')[a_a].getAttribute(\"name2\")=='parent_res_'+id_res){
				document.getElementsByName('cur_zad_sel')[a_a].checked=true;
			}
		}
	}
	if (obj.checked==false){
		var all_checked = document.getElementsByName('cur_zad_sel').length;
		for (var a_a=0; a_a < all_checked; a_a++){
			if (document.getElementsByName('cur_zad_sel')[a_a].getAttribute(\"name2\")=='parent_res_'+id_res){
				document.getElementsByName('cur_zad_sel')[a_a].checked=false;
			}
		}
	}
}
function change_park_sz(id_sz_zadan, mark_park){
		var req = getXmlHttp();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
				}
			}
		}

		req.open('GET', 'project/change_park_sz.php?p1='+id_sz_zadan+'&p2='+mark_park, true);
		req.send(null);
}

function reload_page()
{
	clearTimeout(per_clik_page);
	per_clik_page = setTimeout('reload_page2()',800);
}

function reload_page2()
{
	location.href='".$pageurl."';
}

function vote5(obj, zadan_id, operit_id, id_res)
{
	var l_zad_res = document.getElementsByName('cur_zad_sel').length;
	var arr_zadan_id = '';
	var arr_operit_id = '';
	var ind_count = 0;
	for (var a_b=0; a_b < l_zad_res; a_b++){
		if (document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name3')=='0')
		{			
			if (document.getElementsByName('cur_zad_sel')[a_b].checked==true)
			{
				arr_zadan_id = arr_zadan_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('id').substr(9)+'|';
				arr_operit_id = arr_operit_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name4')+'|';
				
				var cur_tr_id = document.getElementsByName('cur_zad_sel')[a_b].parentNode.parentNode.getAttribute('id').substr(5);
				
				if (document.getElementById('row3_'+cur_tr_id) != null) {
					document.getElementById('row3_'+cur_tr_id).setAttribute('style','display:none;');
				}
								
				if (document.getElementById('row1_'+cur_tr_id) != null) {
					document.getElementById('row1_'+cur_tr_id).setAttribute('style','display:none;');
				}
				
				ind_count = ind_count + 1;
			}
		}
	}
	
	if (ind_count==0) 
	{
		arr_zadan_id = zadan_id;
		arr_operit_id = operit_id;
		
		document.getElementById('row3_'+zadan_id).setAttribute('style','display:none;');
		document.getElementById('row1_'+zadan_id).setAttribute('style','display:none;');
	}
	
	var req = getXmlHttp();
	req.open('GET', 'zadanres_delzad.php?id='+arr_zadan_id+'&operitems='+arr_operit_id);
	req.send(null);
}";

?>


$(function () {
	$(document).on("click", "input[type=checkbox][name=is_multimachine]", function () {
		var tr = $(this).closest("tr");
		var date = tr.data("date");
		var resource_id = tr.data("resource-id");
		
		if ($(this).is(":checked")) {
			$.get("/project/zadan/ajax.set_miltimachine.php?value=1&date=" + date + "&resource_id=" + resource_id);
		} else {
			$.get("/project/zadan/ajax.set_miltimachine.php?value=0&date=" + date + "&resource_id=" + resource_id);
		}
	});
	
	$(document).on("change", "input[type=text][name=multimachine_fact]", function () {
		var tr = $(this).closest("tr");
		var value = $(this).val();
		var date = tr.data("date");
		var resource_id = tr.data("resource-id");
		
		if (value != 0) {
			$.get("/project/zadan/ajax.set_miltimachine.php?value=1&date=" + date + "&resource_id=" + resource_id + "&multimachine_fact=" + value);
		} else {
			$.get("/project/zadan/ajax.set_miltimachine.php?value=0&date=" + date + "&resource_id=" + resource_id + "&multimachine_fact=" + value);
		}
	});
})

<?php

echo "
function vote6(obj, zadan_id, operit_id, id_res)
{
	obj.setAttribute('style','display:none;')

	var l_zad_res = document.getElementsByName('cur_zad_sel').length;
	var arr_zadan_id = '';
	var arr_operit_id = '';
	var ind_count = 0;
	for (var a_b=0; a_b < l_zad_res; a_b++){
		if (document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name3')=='0'){			
			if (document.getElementsByName('cur_zad_sel')[a_b].checked==true){
				arr_zadan_id = arr_zadan_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('id').substr(9)+'|';
				arr_operit_id = arr_operit_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name4')+'|';
				
				ind_count = ind_count + 1;
			}
		}
	}
	
	if (ind_count==0) 
	{
		arr_zadan_id = zadan_id;
		arr_operit_id = operit_id;
	}
		
	var req = getXmlHttp();
	req.open('GET', 'zadanres_okzad.php?id='+arr_zadan_id+'&operitems='+arr_operit_id);
	req.send(null);
}

function vote7(obj, zadan_id, operit_id, id_res)
{
	obj.setAttribute('style','display:none;')
	
	var l_zad_res = document.getElementsByName('cur_zad_sel').length;
	var arr_zadan_id = '';
	var arr_operit_id = '';
	var ind_count = 0;
	for (var a_b=0; a_b < l_zad_res; a_b++)
	{
		if (document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name3')=='1')
		{			
			if (document.getElementsByName('cur_zad_sel')[a_b].checked==true)
			{
				arr_zadan_id = arr_zadan_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('id').substr(9)+'|';
				arr_operit_id = arr_operit_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name4')+'|';
				
				ind_count = ind_count + 1;
			}
		}
	}
	
	if (ind_count==0) 
	{
		arr_zadan_id = zadan_id;
		arr_operit_id = operit_id;
	}
		
	var req = getXmlHttp();
	req.open('GET', 'zadanres_restzad.php?id='+arr_zadan_id+'&operitems='+arr_operit_id);
	req.send(null);
}

if (document.getElementsByClassName('highlite'))
{
	var trall = document.getElementsByClassName('highlite').length;
	for (var trind = 0; trind < (trall/2); trind++)
	{
		if (document.getElementsByClassName('highlite')[(trind*2)].getElementsByTagName('td')[0])
		{
			var tdval = 1, tdsum;
			tdsum=tdval+trind;
			document.getElementsByClassName('highlite')[(trind*2)].getElementsByTagName('td')[0].innerText = tdsum ;
		}
	}
}

window.onload = function()
{
	document.getElementsByName('links_btn2')[1].innerHTML = document.getElementById('links_btn').innerHTML;
	document.getElementsByName('add_pers')[1].style.margin='0px 0px 0px 0px';
	document.getElementsByName('add_pers')[1].getElementsByTagName('span')[0].setAttribute('style', 'font-size:9pt;');
	document.getElementsByName('add_pers')[1].getElementsByTagName('a')[0].setAttribute('style', 'font-size:9pt;');
	document.getElementsByName('links_btn2')[1].innerHTML = document.getElementsByName('links_btn2')[1].innerHTML + '<a href=\"'+document.getElementById(`smen_dt_sz`).href+'\">'+document.getElementById(`smen_dt_sz`).innerText+'</a> | <a target=\"_blank\" href=\"'+document.getElementById(`prnt_dt_sz2`).href+'\">Сводная</a> | <a target=\"_blank\" href=\"'+document.getElementById(`prnt_dt_sz3`).href+'\">Печать (new)</a> | <a target=\"_blank\" href=\"'+document.getElementById(`prnt_dt_sz`).href+'\">Версия для печати</a>';
	document.getElementById('vpdiv').onscroll = function()
	{
		document.cookie = \"scroll=\"+this.scrollTop+\"x\"+this.scrollLeft+\"; expires=Fri, 31 Dec ".NextYear()." 23:59:59 GMT;\";
		document.getElementsByName('links_btn2')[1].parentNode.parentNode.style.top=\"0px\";
	}
}

$(function () { 
	$(document).on('click', '.dse a img.print', function (e) {
		e.preventDefault();
	 
		var l_zad_res = $('*[name=cur_zad_sel]');
	
		var arr_zadan_id = '';

		l_zad_res.each(function () {
			if ($(this).is(':checked')) {
				arr_zadan_id = arr_zadan_id + $(this).closest('tr').data('dse-id') + ',';

			}
		})

		if (arr_zadan_id == '') {
			arr_zadan_id = $(this).closest('tr').data('dse-id');
		}

		//console.log(arr_zadan_id);

		window.open('/index.php?do=show&formid=226&ids=' + arr_zadan_id, '_blank');
	});
});


// *****************************************************************************************************
// shindax 26.12.2017

var monthNames = ['\u042F\u043D\u0432\u0430\u0440\u044C','\u0424\u0435\u0432\u0440\u0430\u043B\u044C','\u041C\u0430\u0440\u0442','\u0410\u043F\u0440\u0435\u043B\u044C','\u041C\u0430\u0439','\u0418\u044E\u043D\u044C',
        '\u0418\u044E\u043B\u044C','\u0410\u0432\u0433\u0443\u0441\u0442','\u0421\u0435\u043D\u0442\u044F\u0431\u0440\u044C','\u041E\u043A\u0442\u044F\u0431\u0440\u044C','\u041D\u043E\u044F\u0431\u0440\u044C','\u0414\u0435\u043A\u0430\u0431\u0440\u044C'];
var monthNamesShort = ['\u042F\u043D\u0432','\u0424\u0435\u0432','\u041C\u0430\u0440','\u0410\u043F\u0440','\u041C\u0430\u0439','\u0418\u044E\u043D',
        '\u0418\u044E\u043B','\u0410\u0432\u0433','\u0421\u0435\u043D','\u041E\u043A\u0442','\u041D\u043E\u044F','\u0414\u0435\u043A'];
var dayNames = ['\u0432\u043E\u0441\u043A\u0440\u0435\u0441\u0435\u043D\u044C\u0435','\u043F\u043E\u043D\u0435\u0434\u0435\u043B\u044C\u043D\u0438\u043A','\u0432\u0442\u043E\u0440\u043D\u0438\u043A','\u0441\u0440\u0435\u0434\u0430','\u0447\u0435\u0442\u0432\u0435\u0440\u0433','\u043F\u044F\u0442\u043D\u0438\u0446\u0430','\u0441\u0443\u0431\u0431\u043E\u0442\u0430'];
var dayNamesShort = ['\u0432\u0441\u043A','\u043F\u043D\u0434','\u0432\u0442\u0440','\u0441\u0440\u0434','\u0447\u0442\u0432','\u043F\u0442\u043D','\u0441\u0431\u0442'];
var dayNamesMin = ['\u0412\u0441','\u041F\u043D','\u0412\u0442','\u0421\u0440','\u0427\u0442','\u041F\u0442','\u0421\u0431'];


$('.copy_button').bind('click', copy_button_click );
$('#copy_dialog_shift').bind('change', copy_dialog_shift_change );

function copy_dialog_shift_change()
{
	var shift = $( '#copy_dialog_shift option:selected' ).text();
	var date = $( '#copy_dialog_date' ).val() ;

	if( shift && date.length )
              	$.post(
                  'project/zadan/ajax.GetResurs.php',
                  {
                      date : date ,
                      shift : shift
                  },
                  function( data )
                  {
				$( '#copy_dialog_resurs' ).empty().html( data );
                    	$('#new_notify_dialog').dialog('close');
                  }
              );

	get_copy_dialog_values();
}

function copy_button_click()
{
    var id = $( this ).data('id');
    var zadan_id = $( this ).closest('tr[id^=row3_]').attr('id').replace('row3_','');
    $('#copy_dialog').data('id', zadan_id );


    $( '#copy_dialog' ).dialog({
    	  resizable: false,
        modal: true,
        closeOnEscape: true,
        height: 120,
        width: 360,
        position: { my: 'right top', at: 'left top', of: this },
        create : function()
        	{
        		$('div.ui-widget-header').css('background','#AFEEEE'); // Цвет заголовка диалога
        		$('#copy_dialog_copy_button').addClass('ui-state-disabled');
        	},
        buttons:
        [
            {
             id : 'copy_dialog_copy_button',
            text: '\u0421\u043A\u043E\u043F\u0438\u0440\u043E\u0432\u0430\u0442\u044C',
            click : function ()
            {
			var zadan_id = $('#copy_dialog').data('id');
			var zadan_arr = [];
			var checkboxes = $('input:checkbox[id^=item_zad_]:checked');
 			var res = get_copy_dialog_values()

			if( checkboxes.length )
			{
				$.each( checkboxes, function( key, value )
				{
					zadan_arr.push( $( value ).attr( 'id' ).replace('item_zad_','') );
				});
			}
			else
				zadan_arr.push( zadan_id );

              	$.post(
                  'project/zadan/ajax.PutZadanCopy.php',
                  {
                  	  user_id : user_id,
                      date : res.date ,
                      shift : res.shift ,
                      resurs : res.resurs ,
                      zadan_arr : zadan_arr
                  },
                  function( data )
                  {
//                  		alert( data );
                  		$( checkboxes ).prop('checked',false);
                  }
              );


            	   	clear_copy_dialog();
                	$(this).dialog('close');
            }
            },
            {
            text : '\u041E\u0442\u043C\u0435\u043D\u0430',
            click : function ()
            		 {
            		     clear_copy_dialog()
                        $(this).dialog('close');
                    }
            }
        ]
    });

    $( '#copy_dialog_date' ).datepicker(
        {
            closeText: '\u041F\u0440\u0438\u043D\u044F\u0442\u044C', // Принять
            prevText: '&#x3c;\u041F\u0440\u0435\u0434', //
            nextText: '\u0421\u043B\u0435\u0434&#x3e;',
            currentText: '\u0422\u0435\u043A. \u043C\u0435\u0441\u044F\u0446',// тек. месяц
            
            showButtonPanel: false,
            showOtherMonths: true,
            selectOtherMonths: true,
            showButtonPanel: true,
            
            monthNames: monthNames,
            monthNamesShort : monthNamesShort,
            dayNames : dayNames,
            dayNamesShort : dayNamesShort,
            dayNamesMin : dayNamesMin,
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            changeMonth : true,
            changeYear : true,
            closeOnEscape: true,
            isRTL: false,

            beforeShow : function(input, inst) {},

            onSelect: function ()
            {
            		var date = $( this ).val() ;
            		var shift = $( '#copy_dialog_shift option:selected' ).text();

		if( shift && date.length )
	                	$.post(
	                    'project/zadan/ajax.GetResurs.php',
	                    {
	                        date : date ,
	                        shift : shift
	                    },
	                    function( data )
	                    {
					$( '#copy_dialog_resurs' ).empty().html( data );
	                      	get_copy_dialog_values();
	                    }
	                );

            }
        });
}

function clear_copy_dialog()
{
	$( '#copy_dialog_shift option[value=0]' ).prop('selected', true ) ;
	$( '#copy_dialog_date' ).val('') ;
	$( '#copy_dialog_resurs' ).empty() ;
}

function get_copy_dialog_values()
{
      	var shift = $( '#copy_dialog_shift option:selected' ).val();
	var date = $( '#copy_dialog_date' ).val() ;
	var resurs = $( '#copy_dialog_resurs  option:selected' ).val();

	if( shift && date && resurs )
		$('#copy_dialog_copy_button').removeClass('ui-state-disabled');
			else
				$('#copy_dialog_copy_button').addClass('ui-state-disabled');

	return { 'shift' : shift, 'date' : date, 'resurs' : resurs }
}


</script>";

?>

