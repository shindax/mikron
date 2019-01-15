<?php	
$usrght = explode("|", $user['ID_rightgroups']);
			
$db_prefix = "okb_";
$to_id = $_GET['p0'];
$from_id = $_GET['p5'];

$arr_tid = explode("|","|ОЗ|КР|СП|БЗ|ХЗ|ВЗ");
$sql_q_1 = dbquery("SELECT ID_zak, NAME, OBOZ FROM ".$db_prefix."db_zakdet where (ID = '".$to_id."') ");
$txt_q_1 = mysql_fetch_array($sql_q_1);
$sql_q_2 = dbquery("SELECT ID, TID, NAME FROM ".$db_prefix."db_zak where (ID = '".$txt_q_1['ID_zak']."') ");
$txt_q_2 = mysql_fetch_array($sql_q_2);

////////////////////////////////////////////////////////////////////////////

$re_s1 = dbquery("SELECT * FROM okb_db_zakdet where (ID='".$from_id."') ");
$na_m1 = mysql_fetch_array($re_s1);
$zak_id = $na_m1['ID_zak'];

$child_n_ar = array();
$child_n_ar[0] = 1;
$cook_open_all = "";
if ($_GET['p5']) {
	if ((in_array("1", $usrght)) or (in_array("20", $usrght))) {
		check_all_tree_dse($na_m1['ID'], $na_m1['PID'], 1);
	}
}

function check_all_tree_dse($id_par_dse, $pid_par_dse, $child_n){
Global $cook_open_all, $child_n_ar, $total_all_dse, $total_all_pardse, $zak_id;
	$re_s2 = dbquery("SELECT * FROM okb_db_zakdet where (ID_zak='".$zak_id."') AND (PID='".$id_par_dse."') ");
	if ($na_m2 = mysql_fetch_array($re_s2)) { 
		$plus = "+";
		//$cook_open_all = $cook_open_all."|db_zakdet_39_".$id_par_dse."|";
	}else{ 
		$plus = "";
	}
	$total_all_dse = $total_all_dse.$id_par_dse."|";
	$total_all_pardse = $total_all_pardse.$pid_par_dse."|";
	//echo $id_par_dse." = ".$pid_par_dse." = ".$child_n." = ".$plus."<br>";
	//echo $id_par_dse." = ".$child_n." = ".$plus."<br>";
	$re_s2 = dbquery("SELECT * FROM okb_db_zakdet where (ID_zak='".$zak_id."') AND (PID='".$id_par_dse."') ");
	while ($na_m2 = mysql_fetch_array($re_s2)){
		if ($na_m2['PID'] == $id_par_dse){
			$child_n_ar[$child_n] = $child_n+1;
		}
		check_all_tree_dse($na_m2['ID'], $na_m2['PID'], $child_n_ar[$child_n], $child_n_ar[$child_n_pr]);
	}
}

//echo $cook_open_all;
//echo $total_all_dse."<br>";

$new_ids_arr = array();
$go_ids_arr = array();
$check_arr = array();
$total_all_dse_expl = explode("|", $total_all_dse);
$total_all_dse_expl2 = explode("|", $total_all_pardse);
foreach ($total_all_dse_expl as $kk1 => $vv1)
{
	if ($vv1!==""){
		dbquery("INSERT INTO ".$db_prefix."db_zakdet (ID_zak) VALUES ('0')");
		$new_ids_arr[$kk1]= mysql_insert_id();
		$go_ids_arr[$kk1] = $vv1;
		if (!$check_arr[$total_all_dse_expl2[$kk1]]) $check_arr[$total_all_dse_expl2[$kk1]] = $kk1;
	}
}
$pid_new = 0;

$first_id = 0;
echo "<br>";
foreach ($new_ids_arr as $kk2 => $vv2)
{
	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID = '".$go_ids_arr[$kk2]."') ");
	$res = mysql_fetch_array($xxx);
	$xxx22 = dbquery("SELECT PID FROM ".$db_prefix."db_zakdet where (ID = '".$_GET['p0']."') ");
	$res22 = mysql_fetch_array($xxx22);
		//dbquery("INSERT INTO ".$db_prefix."db_zakdet (ID_zak, PID, NAME, ORD, OBOZ, COUNT, RCOUNT, TID, LID, MTK_OK, NORM_OK) VALUES ('".$to_zakdet["ID_zak"]."', '".$to_zakdet_ID."', '".$res["NAME"]."', '".$res["ORD"]."', '".$res["OBOZ"]."', '".$res["COUNT"]."', '".$res["RCOUNT"]."', '".$res["TID"]."', '".$res["LID"]."', '".$res["MTK_OK"]."', '".$res["NORM_OK"]."')");
		//$new_zakdet_ID = mysql_insert_id();
	//}	
	$val_copy = "";
	$pid_new = $new_ids_arr[($check_arr[$res['PID']]-1)];
	if ($kk2==0) 
		{ 
			$pid_new = $_GET['p0']; 
			$val_copy = " - копия"; 
			$first_id = $vv2; 
		}



	dbquery("UPDATE okb_db_zakdet 
			 SET 
			 ID_zak={$txt_q_1['ID_zak']},
	`		 PID=$pid_new,
			 NAME='{$res['NAME']}$val_copy',
			 ORD={$res['ORD']},
			 OBOZ='{$res['OBOZ']}',
			 COUNT={$res['COUNT']},
			 RCOUNT={$res['RCOUNT']},			 
			 TID={$res['TID']},
			 LID={$res['LID']},
			 MTK_OK={$res['MTK_OK']},
			 NORM_OK={$res['NORM_OK']}
			 WHERE ID=$vv2");

	// dbquery("Update ".$db_prefix."db_zakdet Set ID_zak='".$txt_q_1['ID_zak']."' where (ID='".$vv2."')");
	// dbquery("Update ".$db_prefix."db_zakdet Set PID='".$pid_new."' where (ID='".$vv2."')");
	// dbquery("Update ".$db_prefix."db_zakdet Set NAME='".$res['NAME'].$val_copy."' where (ID='".$vv2."')");
	// dbquery("Update ".$db_prefix."db_zakdet Set ORD='".$res['ORD']."' where (ID='".$vv2."')");
	// dbquery("Update ".$db_prefix."db_zakdet Set OBOZ='".$res['OBOZ']."' where (ID='".$vv2."')");
	// dbquery("Update ".$db_prefix."db_zakdet Set COUNT='".$res['COUNT']."' where (ID='".$vv2."')");
	// dbquery("Update ".$db_prefix."db_zakdet Set RCOUNT='".$res['RCOUNT']."' where (ID='".$vv2."')");
	// dbquery("Update ".$db_prefix."db_zakdet Set TID='".$res['TID']."' where (ID='".$vv2."')");
	// dbquery("Update ".$db_prefix."db_zakdet Set LID='".$res['LID']."' where (ID='".$vv2."')");
	// dbquery("Update ".$db_prefix."db_zakdet Set MTK_OK='".$res['MTK_OK']."' where (ID='".$vv2."')");
	// dbquery("Update ".$db_prefix."db_zakdet Set NORM_OK='".$res['NORM_OK']."' where (ID='".$vv2."')");

	CopyIzdOperitems($res["ID"], $vv2, $user['ID']);
	
	//echo $pid_new."|";
}

if ($first_id > 0 && count($new_ids_arr) > 1) 
{
	$first_id_pid = mysql_result(dbquery("SELECT PID FROM okb_db_zakdet WHERE ID = " . $first_id), 0);

	$first_id_childs_query = dbquery("SELECT * FROM okb_db_zakdet WHERE PID = " . $first_id);


	while ($row = mysql_fetch_assoc($first_id_childs_query)) {
		dbquery("UPDATE okb_db_zakdet SET PID = " . $first_id . ", NAME = '" . mysql_real_escape_string($row['NAME']) . " - копия' WHERE ID = " . $row['ID']);
	}
	
	dbquery("UPDATE okb_db_zakdet SET PID = " . $to_id . " WHERE ID = " . $first_id);

	//CopyIzdOperitems ($first_id, $first_id_pid, $user['ID']);
	
//	dbquery("DELETE FROM okb_db_zakdet WHERE ID = " . $first_id);
}

//echo "<br>".implode("|",$new_ids_arr)." = ".implode("|",$go_ids_arr)." = ".implode("|",$check_arr);

////////////////////////////////////////////////////////////////////////////

		function CopyIzdOperitems($from_zakdet_ID,$to_zakdet_ID, $us_id) {
			global $db_prefix;

			// Копируем МТК
			dbquery("DELETE from okb_db_operitems where (ID_zakdet='".$to_zakdet_ID."')");
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID_zakdet = '".$from_zakdet_ID."') order by ID");
			while($res = mysql_fetch_array($xxx)) {
				$xxx4 = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID = '".$to_zakdet_ID."') order by ID");
				$res4 = mysql_fetch_array($xxx4);
				dbquery("INSERT INTO ".$db_prefix."db_operitems (ETIME, ID_user, ID_zak, ID_zakdet, ORD, ID_oper, ID_park, NORM, NORM_2, NORM_ZAK, MORE) VALUES ('".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$us_id."', '".$res4["ID_zak"]."', '".$to_zakdet_ID."', '".$res["ORD"]."', '".$res["ID_oper"]."', '".$res["ID_park"]."', '".$res["NORM"]."', '".$res["NORM_2"]."', '".$res["NORM_ZAK"]."', '".$res["MORE"]."')");
				$ins_msql_id = mysql_insert_id();
				$xxx5 = dbquery("SELECT * FROM okb_db_mtk_perehod where (ID_operitems = '".$res['ID']."') order by TID");
				while($res5 = mysql_fetch_array($xxx5)){
					dbquery("INSERT INTO okb_db_mtk_perehod (ETIME, EUSER, ID_zak, ID_zakdet, ID_operitems, TXT, INSTR_1, INSTR_2, INSTR_3, DIAM_SHIR, DLINA, R_O_S, R_O_N, R_O_V, R_O_TO, R_O_TP, TID) VALUES ('".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$us_id."', '".$res4['ID_zak']."','".$res4['ID']."','".$ins_msql_id."', '".$res5["TXT"]."', '".$res5['INSTR_1']."', '".$res5['INSTR_2']."', '".$res5['INSTR_3']."', '".$res5['DIAM_SHIR']."', '".$res5['DLINA']."', '".$res5['R_O_S']."', '".$res5['R_O_N']."', '".$res5['R_O_V']."', '".$res5['R_O_TO']."', '".$res5['R_O_TP']."', '".$res5['TID']."')");				
				}
			}

			// Копируем НР
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zn_zag where (ID_zakdet = '".$from_zakdet_ID."') order by ID");
			while($res = mysql_fetch_array($xxx)) {
				dbquery("INSERT INTO ".$db_prefix."db_zn_zag (ID_zakdet, ID_mat, ID_sort, WW, HH, LL, RCOEF, KDZ, MORE, NORM, NORMZAK, RCOUNT, ID_user, ETIME) VALUES ('".$to_zakdet_ID."', '".$res["ID_mat"]."', '".$res["ID_sort"]."', '".$res["WW"]."', '".$res["HH"]."', '".$res["LL"]."', '".$res["RCOEF"]."', '".$res["KDZ"]."', '".$res["MORE"]."', '".$res["NORM"]."', '".$res["NORM_ZAK"]."', '".$res["RCOUNT"]."', '".$res["ID_user"]."', '".$res["ETIME"]."')");
			}
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zn_pok where (ID_zakdet = '".$from_zakdet_ID."') order by ID");
			while($res = mysql_fetch_array($xxx)) {
				dbquery("INSERT INTO ".$db_prefix."db_zn_pok (ID_zakdet, ID_mat, WW, HH, LL, KDZ, MORE, NORM, NORMZAK, RCOUNT, ID_user, ETIME) VALUES ('".$to_zakdet_ID."', '".$res["ID_mat"]."', '".$res["WW"]."', '".$res["HH"]."', '".$res["LL"]."', '".$res["KDZ"]."', '".$res["MORE"]."', '".$res["NORM"]."', '".$res["NORM_ZAK"]."', '".$res["RCOUNT"]."', '".$res["ID_user"]."', '".$res["ETIME"]."')");
			}

		}

		function CopyIzdIzd($from_zakdet_ID,$to_zakdet_ID) {
			global $db_prefix, $to_zakdet, $NEW_ID_array;
			
			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (PID = '".$from_zakdet_ID."') order by ORD");
			while($res = mysql_fetch_array($xxx)) {
				dbquery("INSERT INTO ".$db_prefix."db_zakdet (ID_zak, PID, NAME, ORD, OBOZ, COUNT, RCOUNT, TID, LID, MTK_OK, NORM_OK) VALUES ('".$to_zakdet["ID_zak"]."', '".$to_zakdet_ID."', '".$res["NAME"]."', '".$res["ORD"]."', '".$res["OBOZ"]."', '".$res["COUNT"]."', '".$res["RCOUNT"]."', '".$res["TID"]."', '".$res["LID"]."', '".$res["MTK_OK"]."', '".$res["NORM_OK"]."')");
				$new_zakdet_ID = mysql_insert_id();
				CopyIzdOperitems($res["ID"], $new_zakdet_ID);
			}
		}

			// ПОЕХАЛИ
			//$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where  (ID = '".$to_id."')");
			//if ($to_zakdet = mysql_fetch_array($xxx)) {
			//$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where  (ID = '".$from_id."')");
			//if ($from_zakdet = mysql_fetch_array($xxx)) {

				// Копируем МТК основной сборки ////////////////////////////

				//CopyIzdOperitems($from_id, $to_id, $user['ID']);

				// Копируем МТК_OK, NAME, OBOZ основной сборки /////////////

				//dbquery("Update ".$db_prefix."db_zakdet Set MTK_OK:='".$from_zakdet["MTK_OK"]."' where (ID='".$to_id."')");
				//dbquery("Update ".$db_prefix."db_zakdet Set NAME:='".$from_zakdet["NAME"]." - копия' where (ID='".$to_id."')");
				//dbquery("Update ".$db_prefix."db_zakdet Set OBOZ:='".$from_zakdet["OBOZ"]."' where (ID='".$to_id."')");

				// Копируем ДСЕ основной сборки ////////////////////////////

				//CopyIzdIzd($from_id, $to_id);

			//}
			//}

if ((in_array("1", $usrght)) or (in_array("20", $usrght))) {
echo "<h2>Копирование в ДСЕ<br>".$arr_tid[$txt_q_2['TID']]."&nbsp;&nbsp;".$txt_q_2['NAME']."&nbsp;&nbsp;&nbsp;".$txt_q_1['NAME']." - ".$txt_q_1['OBOZ']."</h2>";
echo "<input style='width:400px;' onkeyup='find_anothers_dse(this.value);'><br><br>";
echo "<table class='rdtbl tbl'><tbody id='tbody_dseses'>";
echo "<tr class='First'><td style='width:150px;'>№ Заказа</td><td style='width:450px;'>Наименование ДСЕ</td><td style='width:275px;'>№ чертежа ДСЕ</td><td style='width:100px;'>№ Заказа</td></tr>";
echo "</tbody></table>";
}else{
	echo "доступ запрещён";
}
	//if ((in_array("1", $usrght)) or (in_array("20", $usrght))) {
		//dbquery("DELETE FROM okb_db_zakdet where ID='".$_GET['p0']."'");
	//}

echo "<script type='text/javascript'>
function find_anothers_dse(val){
	if (val.length>2){
		document.getElementById('tbody_dseses').innerHTML = '<tr class=\"First\"><td style=\"width:150px;\">№ Заказа</td><td style=\"width:450px;\">Наименование ДСЕ</td><td style=\"width:275px;\">№ чертежа ДСЕ</td><td style=\"width:100px;\">№ Заказа</td></tr><tr><td class=Field colspan=4>Получение списка найденных совпадений</td></tr>';
		var req = getXmlHttp();
			req.onreadystatechange = function (){
                if(req.readyState == 4){
					document.getElementById('tbody_dseses').innerHTML = '<tr class=First><td style=width:150px;>№ Заказа</td><td style=width:450px;>Наименование ДСЕ</td><td style=width:275px;>№ чертежа ДСЕ</td><td style=width:100px;>№ Заказа</td></tr>'+req.responseText;
				}
			}
		req.open('GET', 'project/zak_copy_zakdet_new_list.php?p1='+val+'&p2=".$to_id."');
		req.send(null);
	}
}
function copy_dsetodse(id_dse_otkyda, id_dse_kyda){
	if (confirm(\"Вы уверены что хотите скопировать ДСЕ?\")){
		location.href='index.php?do=show&formid=208&p0=' + id_dse_kyda+'&p5='+id_dse_otkyda;
 
	}
}
var pp_5 = 0".$_GET['p5'].";
if (pp_5>0) location.href='index.php?do=show&formid=39&id=".$txt_q_1['ID_zak']."';
</script>";
?>
