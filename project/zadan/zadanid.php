<?php

// ПОЕХАЛИ

	define("MAV_ERP", TRUE);

	include "../../config.php";
	include "../db_cfg.php";
	include "../../db_func.php";
	include "../../includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function utftxt($str) {
		echo iconv("windows-1251","UTF-8",$str);
	}

		function TreeIzd($pid, $arr_txt, $arr_ID, $arr_PID, $opers, $n, $prefix = "") {

			for ($i=0;$i < count($arr_ID);$i++) {
				if ($arr_PID[$i]==$pid) {
					$izd = str_replace("{{ml}}",(10*$n),$arr_txt[$arr_ID[$i]]);
					$izd = str_replace("{{pf}}",$prefix,$izd);
					utftxt ($izd);
					utftxt (str_replace("{{ml}}",(10*$n+15),$opers[$arr_ID[$i]]));
					TreeIzd($arr_ID[$i], $arr_txt, $arr_ID, $arr_PID, $opers, $n+1, $prefix.".. / ");
				}
			}
		}



	// ОБРАБОТКА ЗАКАЗА
	///////////////////////////////////////////

	function OpenZakID($item) {	// На один заказ
		global $db_prefix;

		utftxt (OutZakID($item));

		if (isset($_GET["opened"])) {

		$zakdet_IDs = Array();	// которые надо вывести

		$vis_IDs = Array();	// видимые ДСЕ
		$vis_PIDs = Array();	// PID видимых ДСЕ

		$zakdet_txt = Array();	// все ДСЕ

		$zakdet_IDPID = Array();// ID -> PID

		$operitems = Array();	// Строки операций

		// Забор операций по заказу
		//////////////////////////////////////////////

			$result = dbquery("SELECT ID, NORM_FACT, STATE, ID_oper, ORD, MORE, ID_park, NUM_ZAK, NUM_ZADEL, BRAK, NORM_ZAK, NORM_FACT, STATE, ID_zakdet FROM ".$db_prefix."db_operitems where (ID_zak = '".$item["ID"]."') and (STATE = '0') and (CHANCEL = '0') order by ORD");
			while($res = mysql_fetch_array($result)) {
				$operitems[$res["ID_zakdet"]*1] = "".$operitems[$res["ID_zakdet"]*1].OutOperitemsID($res)."\n";
				if (!in_array($res["ID_zakdet"]*1,$zakdet_IDs)) $zakdet_IDs[] = $res["ID_zakdet"]*1;
			}

		// Забор всех ДСЕ по заказу
		//////////////////////////////////////////////
			$result = dbquery("SELECT ID, PID, OBOZ, NAME FROM ".$db_prefix."db_zakdet where (ID_zak = '".$item["ID"]."') order by ORD");
			while($res = mysql_fetch_array($result)) {
				$zakdet_txt[$res["ID"]*1] = OutIzdID($res);
				$zakdet_IDPID[$res["ID"]*1] = $res["PID"]*1;
			}

		// Проброс видимых parent
		//////////////////////////////////////////////
			for ($i=0;$i < count($zakdet_IDs);$i++) {
				$vis_IDs[] = $zakdet_IDs[$i];
				$pid = $zakdet_IDPID[$zakdet_IDs[$i]];
				$vis_PIDs[] = $pid;
			}
			for ($i=0;$i < count($zakdet_IDs);$i++) {
				$pid = $zakdet_IDPID[$zakdet_IDs[$i]];
				while ($pid>0) {
					if (!in_array($pid,$vis_IDs)) {
						$vis_IDs[] = $pid;
						$pid = $zakdet_IDPID[$pid];
						$vis_PIDs[] = $pid;
					} else {
						$pid = 0;
					}
				}
			}

		// Цикл вывода
		//////////////////////////////////////////////
			TreeIzd(0, $zakdet_txt, $vis_IDs, $vis_PIDs, $operitems, 1);


		// Unset
		/////////////////////////////////////////////

			unset($zakdet_IDs);
			unset($vis_IDs);
			unset($vis_PIDs);
			unset($zakdet_txt);
			unset($zakdet_IDPID);
		}
	}





	// СТРОКА ЗАКАЗА
	///////////////////////////////////////////

	function OutZakID($item) {
		global $pageurl, $db_prefix, $zak_name;

		$restxt = "";
		
	   // Цвет
		$restxt = $restxt."<tr class='highlite' style='height: 30px;'>";

	   // №
		$restxt = $restxt."<td class='Field' width='30'></td>";

	   // Заказ / ДСЕ / Операция
		$pic = "<img src='uses/collapse.png' style='margin-left: 10px; margin-top: 2px; cursor: hand;' onClick='loaddata(\"zak_".$item["ID"]."\", ".$item["ID"].", \"&opened\");'>";
		if (isset($_GET["opened"])) $pic = "<img src='uses/expand.png' style='margin-left: 10px; margin-top: 2px; cursor: hand;' onClick='loaddata(\"zak_".$item["ID"]."\", ".$item["ID"].", \"\");'>";

		$restxt = $restxt."<td class='Field' style='text-align: left;' colspan='4'>".$pic."<b style='margin-right: 10px;'>".FVal($item,"db_zak","TID")." ".$item["NAME"]."</b> ".$item["DSE_NAME"]."</td>";

	   // Действие
		$restxt = $restxt."<td class='Field' width='30'></td>";

		$restxt = $restxt."</tr>\n";

		$zak_name = FVal($item,"db_zak","TID")." ".$item["NAME"];
		
		Return $restxt;
	}




	// СТРОКА ДСЕ
	///////////////////////////////////////////

	function OutIzdID($item) {
		global $pageurl, $db_prefix, $izd, $opened, $zak_name;

		$restxt = "";

	   // Цвет
		$restxt = $restxt."<tr class='highlite2'>";

	   // №
		$restxt = $restxt."<td class='Field'></td>";

	   // Заказ / ДСЕ / Операция
		$pic = "<img style='margin: 3px 5px 0px {{ml}}px;' src='uses/expand.png'> ";
		$restxt = $restxt."<td class='Field' style='text-align: left;'><b>".$zak_name." / ".$pic."{{pf}}".$item["OBOZ"]."</b> ".$item["NAME"]."</td>";

	   // Оборудование
		$restxt = $restxt."<td class='Field' width='120'></td>";

	   // На заказ
		$restxt = $restxt."<td class='Field' width='120'></td>";

	   // Сообщение ПП
		$restxt = $restxt."<td class='Field' width='170'></td>";

	   // Действие
		$restxt = $restxt."<td class='Field' width='30'></td>";

		$restxt = $restxt."</tr>\n";

		Return $restxt;
	}




	// СТРОКА ОПЕРАЦИИ
	///////////////////////////////////////////

	function OutOperitemsID($item) {
		global $pageurl, $dboper, $dbpark, $prioritet, $inwork;
		
		$restxt = "";

	   // Цвет
	   if ($_GET['p4']==1){
	   if ((in_array($item["ID_oper"],$prioritet)) or ($item["STATE"]*1>0) or ($item["NORM_FACT"]*1>0)) {
		$cl = "f0";
		if ($item["NORM_FACT"]*1>0) $cl = "f1";
		if ($item["STATE"]*1>0) $cl = "f2";
		$cl2 = "";
		if (in_array($item["ID_oper"],$prioritet)) $cl2 = " pr";
		$restxt = $restxt."<tr class='$cl$cl2'>";

	   // №
		$restxt = $restxt."<td class='Field'>".$item["ORD"]."</td>";

		$per_7_txt = '';
		$result_7 = dbquery("SELECT TXT FROM okb_db_mtk_perehod where (ID_operitems = '".$item["ID"]."')");
		while($per_7 = mysql_fetch_row($result_7)){
			$per_7_txt = $per_7_txt.$per_7[0]."\\n";
		}

		$txt_2 = "<img id='col_".$item["ID"]."' src='uses/collapse.png' style='cursor:pointer;' onclick='this.style.display = \"none\"; document.getElementById(\"exp_".$item["ID"]."\").style.display = \"block\"; document.getElementById(\"spa_".$item["ID"]."\").innerText=\"".$per_7_txt."\";'>
		<img id='exp_".$item["ID"]."' src='uses/expand.png' style='display:none; cursor:pointer;' onclick='this.style.display = \"none\"; document.getElementById(\"col_".$item["ID"]."\").style.display = \"block\"; document.getElementById(\"spa_".$item["ID"]."\").innerText=\" \";'>
		<span id='per_".$item["ID"]."'></b>";
	   // Заказ / ДСЕ / Операция
		$restxt = $restxt."<td class=\"Field AL\" style = \"padding-left: {{ml}}px;\"><b>".$dboper[$item["ID_oper"]]."</b> ".$txt_2."<br><span id='spa_".$item["ID"]."'></span></td>";

	   // Оборудование
		$restxt = $restxt."<td class='Field'>".$dbpark[$item["ID_park"]]."</td>";

	   // На заказ
		$rcount = $item["NUM_ZAK"]*1 - $item["NUM_ZADEL"]*1;
		if ($item["BRAK"]*1==1) $rcount = $item["NUM_ZAK"]*1;
		$ost = 0;
		if ($item["NORM_ZAK"]>0) $ost = $rcount*(($item["NORM_ZAK"]-$item["NORM_FACT"])/$item["NORM_ZAK"]);
		$ost = number_format( $ost, 0, '.', ' ');
		$brak = "";
		if ($item["BRAK"]*1==1) $brak = " brak";
		$restxt = $restxt."<td class='Field".$brak."'><b>".($item["NORM_ZAK"]-$item["NORM_FACT"])." (".$ost.")</b><br>".$item["NORM_ZAK"]." (".$rcount.")</td>";

		// Сообщение ПП
		$result_9 = dbquery("SELECT ID_zak FROM okb_db_zakdet where ID = '".$item["ID_zakdet"]."'");
		$per_9 = mysql_fetch_row($result_9);

		$restxt = $restxt."<td class='Field'><textarea style=\"width:205px; resize:none;\"></textarea><input type=\"button\" style=\"float:right; margin-right:10px; border:1px solid #444; background:#bbb; height:25px; width:25px; font-size:80%;\" value=\"ok\" onclick=\"zapr_pp(this,".$per_9[0].",".$item['ID_zakdet'].",".$item['ID_oper'].");\"></td>";
		
	   // Действие
		$restxt = $restxt."<td class='Field xx' id='oi_".$item["ID"]."'>";
		if ($item["STATE"]*1==0) {
			if (!in_array($item["ID"],$inwork)) {
				$restxt = $restxt."<a href='javascript:void(0);' onClick='addzadan(\"oi_".$item["ID"]."\",".$item["ID"].");'>>>></a>";
			} else {
				$restxt = $restxt."<img src='uses/ok.png' style='margin-top: 5px; cursor:pointer;' onClick='if (confirm(\"Вернуть?\")) { delzada(\"oi_".$item["ID"]."\",".$item["ID"].");}'>";
			}
		}
		$restxt = $restxt."</td>";

		$restxt = $restxt."</tr>\n";
	   }
	   }else{
		$cl = "f0";
		if ($item["NORM_FACT"]*1>0) $cl = "f1";
		if ($item["STATE"]*1>0) $cl = "f2";
		$cl2 = "";
		if (in_array($item["ID_oper"],$prioritet)) $cl2 = " pr";
		$restxt = $restxt."<tr class='$cl$cl2'>";

	   // №
		$restxt = $restxt."<td class='Field'>".$item["ORD"]."</td>";

	   // Заказ / ДСЕ / Операция
		$restxt = $restxt."<td class=\"Field AL\" style = \"padding-left: {{ml}}px;\"><b>".$dboper[$item["ID_oper"]]."</b> <i>".$item["MORE"]."</i></td>";

	   // Оборудование
		$restxt = $restxt."<td class='Field'>".$dbpark[$item["ID_park"]]."</td>";

	   // На заказ
		$rcount = $item["NUM_ZAK"]*1 - $item["NUM_ZADEL"]*1;
		if ($item["BRAK"]*1==1) $rcount = $item["NUM_ZAK"]*1;
		$ost = 0;
		if ($item["NORM_ZAK"]>0) $ost = $rcount*(($item["NORM_ZAK"]-$item["NORM_FACT"])/$item["NORM_ZAK"]);
		$ost = number_format( $ost, 0, '.', ' ');
		$brak = "";
		if ($item["BRAK"]*1==1) $brak = " brak";
		$restxt = $restxt."<td class='Field".$brak."'><b>".($item["NORM_ZAK"]-$item["NORM_FACT"])." (".$ost.")</b><br>".$item["NORM_ZAK"]." (".$rcount.")</td>";

		// Сообщение ПП
		
		$res11 = dbquery("SELECT ID, ID_zak, ID_zakdet, MSG_INFO FROM okb_db_operitems where (ID = '".$item["ID"]."') ");
		$nam11 = mysql_fetch_array($res11);
		$msg_pp = explode("||", $nam11["MSG_INFO"]);
		if ($msg_pp[1]) { $disabl_1 = "disabled"; $disabl_2 = "none"; $disabl_3=$msg_pp[0]; $disabl_4="Field";}else{ $disabl_1 = ""; $disabl_2 = "display"; $disabl_3=$nam11["MSG_INFO"]; $disabl_4="rwField ntabg";}
		$restxt = $restxt."<td class='Field'><table><tbody><tr><td><textarea ".$disabl_1." style='width:165px; resize:none;' onchange='vote(this,\"db_edit.php?db=db_operitems&field=MSG_INFO&id=".$nam11["ID"]."&value=\"+TXT(this.value));' value='".$disabl_3."'>".$disabl_3."</textarea></td>
		<td><input type='button' style='display:".$disabl_2."; border:1px solid #444; background:#bbb; height:25px; width:25px; font-size:80%;' value='ok' onclick='if(this.value==\"ok\") {if(confirm(\"Послать запрос в КТО?\")){ this.parentNode.parentNode.parentNode.parentNode.parentNode.className = \"Field\"; this.parentNode.parentNode.getElementsByTagName(\"textarea\")[0].disabled = true; this.style.display = \"none\"; vote(this,\"db_edit.php?db=db_operitems&field=MSG_INFO&id=".$nam11["ID"]."&value=\"+this.parentNode.parentNode.getElementsByTagName(\"textarea\")[0].value+\"||ok\"); vote(this,\"zapros_MTK_PP.php?p1=".$nam11["ID"]."&p2=".$nam11["ID_zakdet"]."&p3=".$nam11["ID_zak"]."\");}}'></td></tr></tbody></table></td>";
		
	   // Действие
		$restxt = $restxt."<td class='Field xx' id='oi_".$item["ID"]."'>";
		if ($item["STATE"]*1==0) {
			if (!in_array($item["ID"],$inwork)) {
				$restxt = $restxt."<a href='javascript:void(0);' onClick='addzadan(\"oi_".$item["ID"]."\",".$item["ID"].");'>>>></a>";
			} else {
				$restxt = $restxt."<img src='uses/ok.png' style='margin-top: 5px; cursor:pointer;' onClick='if (confirm(\"Вернуть?\")) { delzada(\"oi_".$item["ID"]."\",".$item["ID"].");}'>";
			}
		}
		$restxt = $restxt."</td>";

		$restxt = $restxt."</tr>\n";
	   }
		Return $restxt;
	}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


   ///// ЗАДАНИЯ ИЗ ОПЕРАЦИЙ К ДСЕ ЗАКАЗА

	   // ПРИГОТОВЛЕНИЯ ///////////////////////////////////////////////////////////////

		$prioritet = Array();
		$resurs = dbquery("SELECT ID, OPER_IDS FROM ".$db_prefix."db_resurs where (ID = '".$_GET["resurs"]."')");
		if ($resurs = mysql_fetch_array($resurs)) {
			$prioritet = explode("|",$resurs["OPER_IDS"]);
		}

		$inwork = Array();
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE = '".$_GET["date"]."') and (SMEN = '".$_GET["smen"]."') and (ID_resurs = '".$_GET["resurs"]."')");
		while($res = mysql_fetch_array($result)) {
			$inwork[] = $res["ID_operitems"];
		}

		$dboper = Array();
		$result = dbquery("SELECT * FROM ".$db_prefix."db_oper");
		while($res = mysql_fetch_array($result)) {
			$dboper[$res["ID"]] = FVal($res,"db_oper","NAME")." - ".FVal($res,"db_oper","TID");
		}

		$dbpark = Array();
		$result = dbquery("SELECT * FROM ".$db_prefix."db_park");
		while($res = mysql_fetch_array($result)) {
			$dbpark[$res["ID"]] = FVal($res,"db_park","MARK");
		}

	   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
		utftxt ("<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1100px;' border='1' cellpadding='0' cellspacing='0'>\n");

	   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
		$result = dbquery("SELECT ID, TID, NAME, DSE_NAME FROM ".$db_prefix."db_zak where (ID='".$_GET["idzak"]."')");
		while($res = mysql_fetch_array($result)) {
			OpenZakID($res);
		}

		utftxt ("</table>\n");

?>