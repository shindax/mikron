<?php 
$idcur = $_GET['id'];
$curme = 0;

$resu1 = dbquery("SELECT * FROM okb_db_shtat where ((ID_resurs='".$render_row['ID_users']."') and (BOSS='1') and (NOTTAB='0')) ");
$na1 = mysql_fetch_array($resu1);
$na1_1 = $na1['ID_otdel'];
$na1_2 = $na1['BOSS'];

$resu9 = dbquery("SELECT * FROM okb_db_shtat where ((ID_resurs='".$render_row['ID_users']."') and (BOSS='1') and (NOTTAB='1')) ");
$na9 = mysql_fetch_array($resu9);
$na9_1 = $na9['ID_otdel'];
$na9_2 = $na9['BOSS'];
$na9_3 = $na9['NOTTAB'];

$div_nam_id = array();
$div_nam_name = array();

echo "<a href='javascript:void(0);' onclick='showlist(); setTimeout(showlistall, 400);'>
	<img src='uses/link.png'>
</a>
<span class='ltpopup'>
<div id='itrres_div' class='ltpopup' style='min-width: 220px; display: none;'>
<img class='limg' onclick='showlist();' src='uses/line.png'>
<input id='itrres_inp' type='text' class='lid_input' onkeyup='setTimeout(showlistall, 800);' onblur='setTimeout(showlist, 800);'>
<div class='lid_res' id='itrreslist_div'>";
	global $user;


$query ="
                          SELECT
                          okb_db_shtat.ID_otdel, okb_db_shtat.NAME, okb_db_shtat.ID_resurs
                          FROM
                          okb_db_resurs
                          INNER JOIN okb_db_shtat ON okb_db_resurs.ID = okb_db_shtat.ID_resurs
                          WHERE
                          okb_db_resurs.ID_users = ".$user['ID'];

$result = dbquery( $query );
$row = mysql_fetch_assoc( $result );
$department_id = $row['ID_otdel'];
$resurs_id = $row['ID_resurs'];


//if ($na1_2 == '1' || $user['ID'] == 169) 
//if ($na1_2 == '1' || $user['ID'] == 169 ||  $user['ID'] == 132 || $department_id == 91 )
//{
$curme = 1;

	/*if ($user['ID'] == 43) {
		
		$resu3 = dbquery("SELECT * FROM okb_db_shtat where (((ID_otdel='103') OR (ID_otdel='8') OR (ID_otdel='9') OR ID_special = 1 OR ID_special = 2 OR ID_special = 97) and (ID_resurs!='0')) ");
	} else if ($user['ID'] == 31) {
		
		$resu3 = dbquery("SELECT * FROM okb_db_shtat where ((ID_otdel='104') OR (ID_otdel='".$na1_1."') AND (ID_resurs!='0')) ");
	} else if ($user['ID'] == 169) {

		$resu3 = dbquery("SELECT * FROM okb_db_shtat where (((ID_otdel='103') OR (ID_otdel='8') OR (ID_otdel='9') OR ID_special = 1 OR ID_special = 2 OR ID_special = 97) and (ID_resurs!='0')) ");
	} else if ($user['ID'] == 132) {

		$resu3 = dbquery("SELECT * FROM okb_db_shtat where ID_special = 101 and (ID_resurs!='0') ");
	} else {
			$resu3 = dbquery("SELECT * FROM okb_db_shtat where ((ID_otdel='".$na1_1."') and (ID_resurs!='0')) ");
	}
	
		if ( $department_id == 91 && $na1_2 != '1' )
	{
*/
		$resu3 = dbquery("	SELECT * FROM okb_db_shtat
							/*where
							ID_otdel='91'
							AND
							ID_resurs=$resurs_id*/" );
	//}

	
	while($na3 = mysql_fetch_array($resu3)){

		$na3_1 = $na3['ID_resurs'];
		$na3_2 = $na3['ID'];
		
//		$resu4 = dbquery("SELECT * FROM okb_db_resurs where (ID='".$na3_1."') ");
		
$resu4 = dbquery("
					SELECT okb_db_resurs.NAME NAME, okb_db_resurs.ID ID, okb_db_resurs.TID TID
					FROM okb_db_resurs
					LEFT JOIN okb_db_shtat ON okb_db_shtat.ID_resurs=okb_db_resurs.ID
					LEFT JOIN okb_db_otdel ON okb_db_shtat.ID_otdel=okb_db_otdel.ID		
					where 
					(okb_db_resurs.ID='".$na3_1."')
					AND okb_db_otdel.ID IN ( 91,104,118,141,147,148,149,150,151,152,103 )
					"
				 );
				 				 				 				
		$na4 = mysql_fetch_array($resu4);
		$na4_1 = $na4['NAME'];
		$na4_2 = $na4['ID'];
		$na4_3 = $na4['TID'];
		
		if ($na4_3 !== '1'){
			$div_nam_id[] = $na4_2;
			$div_nam_name[] = $na4_1;
		}
	}

	if ( ! ( $department_id == 91 && $na1_2 != '1' ) )
  {
    $resu6 = dbquery("SELECT * FROM okb_db_otdel where (PID='".$na1_1."') ");
    while($na6 = mysql_fetch_array($resu6))
	{
		$na6_1 = $na6['ID'];
		
		$resu7 = dbquery("SELECT * FROM okb_db_shtat where ((ID_resurs!='0') and (ID_otdel='".$na6_1."') and (BOSS='1')) ");
		$na7 = mysql_fetch_array($resu7);
		$na7_1 = $na7['ID_resurs'];
		
		$resu8 = dbquery("SELECT * FROM okb_db_resurs where (ID='".$na7_1."') ");
		$na8 = mysql_fetch_array($resu8);
		$na8_1 = $na8['NAME'];
		$na8_2 = $na8['ID'];
		$na8_3 = $na8['TID'];
		
		if ($na8_3 !== '1'){		
		if (($na8_2 !== $render_row['ID_users'])){
			$div_nam_id[] = $na8_2;
			$div_nam_name[] = $na8_1;
		}
		}
	 }
	}
	
//}

if (($na9_2 == '1') and ($na9_3 == '1')) {
	
	$resu3 = dbquery("SELECT * FROM okb_db_shtat where ((ID_otdel='".$na9_1."') and (ID_resurs!='0')) ");
	while($na3 = mysql_fetch_array($resu3)){
		$na3_1 = $na3['ID_resurs'];
		$na3_2 = $na3['ID'];
		
		$resu4 = dbquery("SELECT * FROM okb_db_resurs where (ID='".$na3_1."') ");
		$na4 = mysql_fetch_array($resu4);
		$na4_1 = $na4['NAME'];
		$na4_2 = $na4['ID'];
		$na4_3 = $na4['TID'];
		
		if ($na4_3 !== '1'){		
		if ($na4_2 !== $render_row['ID_users']){
			$div_nam_id[] = $na4_2;
			$div_nam_name[] = $na4_1;
		}else{
			if ($curme == 0) {
				$div_nam_id[] = $na4_2;
				$div_nam_name[] = $na4_1;
			}
		}
		}
	}

	$resu6 = dbquery("SELECT * FROM okb_db_otdel where (PID='".$na9_1."') ");
	while($na6 = mysql_fetch_array($resu6)){
		$na6_1 = $na6['ID'];
		
		$resu7 = dbquery("SELECT * FROM okb_db_shtat where ((ID_resurs!='0') and (ID_otdel='".$na6_1."') and (BOSS='1')) ");
		$na7 = mysql_fetch_array($resu7);
		$na7_1 = $na7['ID_resurs'];

		$resu8 = dbquery("SELECT * FROM okb_db_resurs where (ID='".$na7_1."') ");
		$na8 = mysql_fetch_array($resu8);
		$na8_1 = $na8['NAME'];
		$na8_2 = $na8['ID'];
		$na8_3 = $na8['TID'];
		
		if ($na8_3 !== '1'){		
		if ($na8_2 !== $render_row['ID_users']){
			$div_nam_id[] = $na8_2;
			$div_nam_name[] = $na8_1;
		}
		}
	}
}
array_multisort($div_nam_name, $div_nam_id);

foreach($div_nam_id as $keey_1 => $vaal_1){
	
	if (strlen($div_nam_name[$keey_1])>1){
		if ($vaal_1!==$cur_id){
			echo "<div class='hr'></div><a href='javascript:void(0)' onclick='parent.location=\"index.php?do=show&formid=124&id=".$idcur."&edit_list=db_itr_vremitr|".$idcur."|ID_users2|".$vaal_1."\";'>".$div_nam_name[$keey_1]."</a>";
			$cur_id = $vaal_1;
		}
	}
}

echo "</div>
</div>
</span>

<script type='text/javascript'>
if (document.getElementById('itrreslist_div').getElementsByTagName('div')) {
	var divcont = document.getElementById('itrreslist_div').getElementsByTagName('div').length;
		
	if (divcont > 0) {
		for (var divind = 0; divind < divcont; divind++){
			document.getElementById('itrreslist_div').getElementsByTagName('div')[divind].style.display='none';
			document.getElementById('itrreslist_div').getElementsByTagName('a')[divind].style.display='none';
		}
	}

	function showlist(){
		if (document.getElementById('itrres_div').style.display=='block'){
			document.getElementById('itrres_div').style.display='none';		
		}else{
			document.getElementById('itrres_div').style.display='block';
			document.getElementById('itrres_inp').focus();	
		}
	}
	function showlistall(){
		var divcont = document.getElementById('itrreslist_div').getElementsByTagName('div').length;
		if (divcont > 0) {
			for (var divind = 0; divind < divcont; divind++){
				var itrinp = document.getElementById('itrres_inp').value;
				var regex;
				regex = new RegExp (itrinp, 'i');
				var itrinp5 = document.getElementById('itrreslist_div').getElementsByTagName('a')[divind].innerText;
				var itrinp3 = itrinp5.match(regex);
				if (itrinp3 !== null) {
					document.getElementById('itrreslist_div').getElementsByTagName('div')[divind].style.display='block';
					document.getElementById('itrreslist_div').getElementsByTagName('a')[divind].style.display='block';
				}
				if (itrinp3 == null) {
					document.getElementById('itrreslist_div').getElementsByTagName('div')[divind].style.display='none';
					document.getElementById('itrreslist_div').getElementsByTagName('a')[divind].style.display='none';
				}
			}
		}
	}
}
</script>";

$resu15 = dbquery("SELECT * FROM okb_db_resurs where (ID='".$render_row['ID_users2']."') ");
$na15 = mysql_fetch_array($resu15);
$na15_1 = $na15['NAME'];
echo $na15_1;
?>