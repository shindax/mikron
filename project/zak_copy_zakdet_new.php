<style>
.replace_root
{
	font-size: 16px;
	font-weight: bold;
	color:red;
}
</style>

<?php	
require_once( $_SERVER['DOCUMENT_ROOT']."/project/zakdet_remove.php" );

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
if ($_GET['p5']) 
	if ((in_array("1", $usrght)) or (in_array("20", $usrght))) 
		check_all_tree_dse($na_m1['ID'], $na_m1['PID'], 1);

////////////////////////////////////////////////////////////////////////////

function CopyIzdIzd($from_zakdet_ID,$to_zakdet_ID) {
	global $db_prefix, $to_zakdet, $NEW_ID_array;
	
	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (PID = '".$from_zakdet_ID."') order by ORD");
	while($res = mysql_fetch_array($xxx)) {
		dbquery("INSERT INTO ".$db_prefix."db_zakdet (ID_zak, PID, NAME, ORD, OBOZ, COUNT, RCOUNT, TID, LID, MTK_OK, NORM_OK) VALUES ('".$to_zakdet["ID_zak"]."', '".$to_zakdet_ID."', '".$res["NAME"]."', '".$res["ORD"]."', '".$res["OBOZ"]."', '".$res["COUNT"]."', '".$res["RCOUNT"]."', '".$res["TID"]."', '".$res["LID"]."', '".$res["MTK_OK"]."', '".$res["NORM_OK"]."')");
		$new_zakdet_ID = mysql_insert_id();
		CopyIzdOperitems($res["ID"], $new_zakdet_ID);
	}
}

////////////////////////////////////////////////////////////////////////////

function check_all_tree_dse($id_par_dse, $pid_par_dse, $child_n)
{
	global $cook_open_all, $child_n_ar, $total_all_dse, $total_all_pardse, $zak_id;

	$re_s2 = dbquery("SELECT * FROM okb_db_zakdet where (ID_zak='".$zak_id."') AND (PID='".$id_par_dse."') ");
	if ($na_m2 = mysql_fetch_array($re_s2)) 
		$plus = "+";
			else
				$plus = "";

	$total_all_dse = $total_all_dse.$id_par_dse."|";
	$total_all_pardse = $total_all_pardse.$pid_par_dse."|";
	$re_s2 = dbquery("SELECT * FROM okb_db_zakdet where (ID_zak='".$zak_id."') AND (PID='".$id_par_dse."') ");
	while ($na_m2 = mysql_fetch_array($re_s2))
	{
		if ($na_m2['PID'] == $id_par_dse)
			$child_n_ar[$child_n] = $child_n+1;

		check_all_tree_dse($na_m2['ID'], $na_m2['PID'], $child_n_ar[$child_n], $child_n_ar[$child_n_pr]);
	}
} // function check_all_tree_dse($id_par_dse, $pid_par_dse, $child_n)

$new_ids_arr = array();
$go_ids_arr = array();
$check_arr = array();
$total_all_dse_expl = explode("|", $total_all_dse);
$total_all_dse_expl2 = explode("|", $total_all_pardse);

// shindax 22.04.2019
$link_additional_arr = [];
$link_additional_arr['src'] = $total_all_dse_expl;

foreach ($total_all_dse_expl as $kk1 => $vv1)
	if ($vv1!=="")
	{
		dbquery("INSERT INTO ".$db_prefix."db_zakdet (ID_zak) VALUES ('0')");
		$new_ids_arr[$kk1]= mysql_insert_id();
		$go_ids_arr[$kk1] = $vv1;
		if (!$check_arr[$total_all_dse_expl2[$kk1]]) $check_arr[$total_all_dse_expl2[$kk1]] = $kk1;
	}

$pid_new = 0;
$first_id = 0;
echo "<br>";

// shindax 22.04.2019
$link_additional_arr['dest'] = $new_ids_arr;

foreach ($new_ids_arr as $kk2 => $vv2)
{
	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID = '".$go_ids_arr[$kk2]."') ");
	$res = mysql_fetch_array($xxx);
	$xxx22 = dbquery("SELECT PID FROM ".$db_prefix."db_zakdet where (ID = '".$_GET['p0']."') ");
	$res22 = mysql_fetch_array($xxx22);

	$val_copy = "";
	$pid_new = $new_ids_arr[($check_arr[$res['PID']]-1)];
	if ($kk2==0) 
		{ 
			$pid_new = $_GET['p0']; 
			$val_copy = " - копия"; $first_id = $vv2; 
		}

// shindax 22.04.2019
	$link_additional_arr['lid'][] = $res['LID'];

	dbquery("
			UPDATE ".$db_prefix."db_zakdet 
			SET ID_zak={$txt_q_1['ID_zak']},
			PID = $pid_new,
			NAME='".$res['NAME'].$val_copy."' ,
			ORD='{$res['ORD']}',
			OBOZ='{$res['OBOZ']}' ,
			COUNT={$res['COUNT']},
			RCOUNT={$res['RCOUNT']},
			TID='{$res['TID']}',
			LID='{$res['LID']}',
			MTK_OK='{$res['MTK_OK']}',
			NORM_OK='{$res['NORM_OK']}'
			WHERE ID=$vv2");

	CopyIzdOperitems($res["ID"], $vv2, $user['ID']);
	
} // foreach ($new_ids_arr as $kk2 => $vv2)

if ( $first_id > 0 && count($new_ids_arr) > 1 ) 
{
	$first_id_pid = mysql_result(dbquery("SELECT PID FROM okb_db_zakdet WHERE ID = " . $first_id), 0);

	$first_id_childs_query = dbquery("SELECT * FROM okb_db_zakdet WHERE PID = " . $first_id);


	while ($row = mysql_fetch_assoc($first_id_childs_query)) 
	{
		dbquery("
					UPDATE okb_db_zakdet 
					SET PID = " . $first_id . ", 
					NAME = '" . mysql_real_escape_string($row['NAME']) . " - копия' 
					WHERE ID = {$row['ID']}");
	}
	
	dbquery("UPDATE okb_db_zakdet SET PID = " . $to_id . " WHERE ID = " . $first_id);

}

////////////////////////////////////////////////////////////////////////////

// shindax 29.03.2019
// alert( count( $new_ids_arr ) );

if( isset( $_GET['p0'] ) && isset( $_GET['p5'] ) && count( $new_ids_arr ) > 1 )
{
	$result = dbquery("SELECT PID FROM ".$db_prefix."db_zakdet WHERE ID = $to_id");
	$result_arr = mysql_fetch_array( $result );
	$pid = $result_arr['PID'];
	dbquery("UPDATE okb_db_zakdet SET PID = $to_id WHERE ID = $first_id");

	if( !$pid )
	{
		if( $_GET['p6'] )
		{
			dbquery("UPDATE okb_db_zakdet SET PID = 0 WHERE ID = $first_id");
			ZakdetRemove( $to_id );
		}
			else
				dbquery("UPDATE okb_db_zakdet SET PID = $to_id WHERE ID = $first_id");
	}

} // if( isset( $_GET['p0'] ) && isset( $_GET['p5'] ) && count( $new_ids_arr ) > 1 )

// shindax 22.04.2019
UpdateLinks( $link_additional_arr );

////////////////////////////////////////////////////////////////////////////

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
function UpdateLinks( $link_additional_arr ) 
{
	global $pdo;
	$make_log = true ;
	$str = null;
	$now = new DateTime();
	$now = $now -> format('Y-m-d H:i').PHP_EOL;

	if( $make_log )
		$str = $now."Src id. Count : ".count($link_additional_arr['src'])." : ".join(",", $link_additional_arr['src']).PHP_EOL."Dest id. Count : ".count($link_additional_arr['dest'])." : ".join(",", $link_additional_arr['dest']).PHP_EOL.join(",", $link_additional_arr['lid']).PHP_EOL;

	foreach( $link_additional_arr['lid'] AS $key => $val )
	{
		if( $val )
		{
			if( $make_log )
			{
				$str .= "$key-th element is link. Old tied element id is $val".PHP_EOL;
				$str .= "searched id : $val".PHP_EOL;				
			}

			$res = array_search( $val, $link_additional_arr['src']);
			$link_id = $link_additional_arr['dest'][ $key ];

			if( $res === false )
			{
				$tied_id = 0;
				$str .= "old link not found".PHP_EOL;
			}
					else
					{
						$str .= "old found at position $res".PHP_EOL;
						$tied_id = $link_additional_arr['dest'][ $res ];
					}

			if( $make_log )
			{
				$str .= "old link position in tree is $res".PHP_EOL;
		 	 	$str .= "Modified link value id is $link_id".PHP_EOL;
		 	 	$str .= "New tied link value id is $tied_id".PHP_EOL;
	 	 	}
			try
            {
            	$query = "UPDATE `okb_db_zakdet` SET ";

            	if( $tied_id )
                	$query .= "LID = $tied_id " ;
                    	else
                    		$query .= "LID = 1, NAME='' " ;

                $query .= "WHERE id = $link_id" ;

              $stmt =  $pdo->prepare( $query );
              $stmt->execute();
            }
            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }

		} // if( $val )
	} // 	foreach( $link_additional_arr['lid'] AS $key => $val )
	
	if( $make_log )
	{
		$file = 'log.txt';
		$current = file_get_contents($file);
		file_put_contents($file, $current.$str);
	}

} // function UpdateLinks( $link_additional_arr ) 

////////////////////////////////////////////////////////////////////////////

function CopyIzdOperitems($from_zakdet_ID,$to_zakdet_ID, $us_id) 
{
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
		while($res5 = mysql_fetch_array($xxx5))
			dbquery("INSERT INTO okb_db_mtk_perehod (ETIME, EUSER, ID_zak, ID_zakdet, ID_operitems, TXT, INSTR_1, INSTR_2, INSTR_3, DIAM_SHIR, DLINA, R_O_S, R_O_N, R_O_V, R_O_TO, R_O_TP, TID) VALUES ('".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))."', '".$us_id."', '".$res4['ID_zak']."','".$res4['ID']."','".$ins_msql_id."', '".$res5["TXT"]."', '".$res5['INSTR_1']."', '".$res5['INSTR_2']."', '".$res5['INSTR_3']."', '".$res5['DIAM_SHIR']."', '".$res5['DLINA']."', '".$res5['R_O_S']."', '".$res5['R_O_N']."', '".$res5['R_O_V']."', '".$res5['R_O_TO']."', '".$res5['R_O_TP']."', '".$res5['TID']."')");				
	}

	// Копируем НР
	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zn_zag where (ID_zakdet = '".$from_zakdet_ID."') order by ID");

	while($res = mysql_fetch_array($xxx)) 
		dbquery("INSERT INTO ".$db_prefix."db_zn_zag (ID_zakdet, ID_mat, ID_sort, WW, HH, LL, RCOEF, KDZ, MORE, NORM, NORMZAK, RCOUNT, ID_user, ETIME) VALUES ('".$to_zakdet_ID."', '".$res["ID_mat"]."', '".$res["ID_sort"]."', '".$res["WW"]."', '".$res["HH"]."', '".$res["LL"]."', '".$res["RCOEF"]."', '".$res["KDZ"]."', '".$res["MORE"]."', '".$res["NORM"]."', '".$res["NORM_ZAK"]."', '".$res["RCOUNT"]."', '".$res["ID_user"]."', '".$res["ETIME"]."')");

	$xxx = dbquery("SELECT * FROM ".$db_prefix."db_zn_pok where (ID_zakdet = '".$from_zakdet_ID."') order by ID");
	while($res = mysql_fetch_array($xxx)) 
		dbquery("INSERT INTO ".$db_prefix."db_zn_pok (ID_zakdet, ID_mat, WW, HH, LL, KDZ, MORE, NORM, NORMZAK, RCOUNT, ID_user, ETIME) VALUES ('".$to_zakdet_ID."', '".$res["ID_mat"]."', '".$res["WW"]."', '".$res["HH"]."', '".$res["LL"]."', '".$res["KDZ"]."', '".$res["MORE"]."', '".$res["NORM"]."', '".$res["NORM_ZAK"]."', '".$res["RCOUNT"]."', '".$res["ID_user"]."', '".$res["ETIME"]."')");
}

////////////////////////////////////////////////////////////////////////////

if ((in_array("1", $usrght)) or (in_array("20", $usrght))) 
{

$base_id = $_GET['p0'];
$result = dbquery("SELECT PID FROM ".$db_prefix."db_zakdet where ID = $base_id ");
$result = mysql_fetch_array($result);
$pid = $result['PID'];

echo "<h2>Копирование в ДСЕ<br>".$arr_tid[$txt_q_2['TID']]."&nbsp;&nbsp;".$txt_q_2['NAME']."&nbsp;&nbsp;&nbsp;".$txt_q_1['NAME']." - ".$txt_q_1['OBOZ']."</h2>";

echo "<input style='width:400px;' onkeyup='find_anothers_dse(this.value);'>";

if( !$pid )
	echo "<input type='checkbox' id='replace_root' /><span class='replace_root'>Заменить корневой элемент</span>";

echo "<br><br>";
echo "<table class='rdtbl tbl'><tbody id='tbody_dseses'>";
echo "<tr class='First'><td style='width:150px;'>№ Заказа</td><td style='width:450px;'>Наименование ДСЕ</td><td style='width:275px;'>№ чертежа ДСЕ</td><td style='width:100px;'>№ Заказа</td></tr>";
echo "</tbody></table>";
}
	else
		echo "доступ запрещён";

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
function copy_dsetodse(id_dse_otkyda, id_dse_kyda)
{
	let replace_root_state = $('#replace_root').prop('checked') ? 1 : 0

	let message = replace_root_state ? \"Вы уверены что хотите скопировать ДСЕ с заменой корневого элемента?\" : \"Вы уверены что хотите скопировать ДСЕ?\"

	if (confirm( message ))
	{
		location.href='index.php?do=show&formid=208&p0=' + id_dse_kyda+'&p5='+id_dse_otkyda +'&p6=' + replace_root_state;
 	}
}
var pp_5 = 0".$_GET['p5'].";
if (pp_5>0) location.href='index.php?do=show&formid=39&id=".$txt_q_1['ID_zak']."';
</script>";
?>
