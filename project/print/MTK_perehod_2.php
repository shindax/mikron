<?php
	if (!defined("MAV_ERP")) { die("Access Denied"); }
Global $user;
// переменные по стилям
$fld = " class='Field'";
$rfld = " class='rwField ntabg'";
$stl_s = " style='";
$stl_e = "'";

// Содержание
echo "<table width='1218px'><tbody>";
$result3 = dbquery("SELECT * FROM okb_db_zakdet where ID='".$render_row['ID_zakdet']."' ");
$name3 = mysql_fetch_array($result3);

$result = dbquery("SELECT * FROM okb_db_mtk_perehod where ID_operitems='".$render_row['ID']."' order by TID ");
while ($name = mysql_fetch_array($result)){ 
	if ($name3['MTK_OK']=='0') {
		if ($name['TID']=='1') { $disbl = "disabled";}
		if ($name['TID']!=='1') { $disbl = "";}
		if ($name['TID']>1){ $del_img = "<img src='uses/del.png' style='cursor:pointer;' onclick='if(confirm(\"Вы действительно хотите удалть переход?\")){ document.getElementById(\"curloading\").style.display=\"block\";delrow_perehod_row(".$name['ID'].");}'>";}else{ $del_img = "";}
		echo "<tr style='border-bottom:1px solid #000;'>
		<td".$rfld.$stl_s."border-right:1px solid #000; width:32px;".$stl_e."><input type='number' ".$disbl." onchange='vote5(\"TID\",".$name['ID'].",this.value); vote2(\"".$name['ID']."\");' value='".$name['TID']."'><br>".$del_img."</td>
		<td".$rfld.$stl_s."border-right:1px solid #000; min-width:300px;".$stl_e."><textarea style='height:54px; resize:none;' onchange='vote5(\"TXT\",".$name['ID'].",this.value); vote2(\"".$name['ID']."\");' value='".$name['TXT']."'>".$name['TXT']."</textarea></td>
		<td".$rfld.$stl_s."border-right:1px solid #000; width:165px;".$stl_e."><textarea style='height:54px; resize:none;' onchange='vote5(\"INSTR_1\",".$name['ID'].",this.value); vote2(\"".$name['ID']."\");' value='".$name['INSTR_1']."'>".$name['INSTR_1']."</textarea></td>
		<td".$rfld.$stl_s."border-right:1px solid #000; width:165px;".$stl_e."><textarea style='height:54px; resize:none;' onchange='vote5(\"INSTR_2\",".$name['ID'].",this.value); vote2(\"".$name['ID']."\");' value='".$name['INSTR_2']."'>".$name['INSTR_2']."</textarea></td>
		<td".$rfld.$stl_s."border-right:1px solid #000; width:165px;".$stl_e."><textarea style='height:54px; resize:none;' onchange='vote5(\"INSTR_3\",".$name['ID'].",this.value); vote2(\"".$name['ID']."\");' value='".$name['INSTR_3']."'>".$name['INSTR_3']."</textarea></td>
		<td".$rfld.$stl_s."border-right:1px solid #000; width:70px;".$stl_e."><input onchange='vote5(\"DIAM_SHIR\",".$name['ID'].",this.value); vote2(\"".$name['ID']."\");' value='".$name['DIAM_SHIR']."'></td>
		<td".$rfld.$stl_s."border-right:1px solid #000; width:70px;".$stl_e."><input onchange='vote5(\"DLINA\",".$name['ID'].",this.value); vote2(\"".$name['ID']."\");' value='".$name['DLINA']."'></td>
		<td".$rfld.$stl_s."border-right:1px solid #000; width:35px;".$stl_e."><input onchange='vote5(\"R_O_S\",".$name['ID'].",this.value); vote2(\"".$name['ID']."\");' value='".$name['R_O_S']."'></td>
		<td".$rfld.$stl_s."border-right:1px solid #000; width:35px;".$stl_e."><input onchange='vote5(\"R_O_N\",".$name['ID'].",this.value); vote2(\"".$name['ID']."\");' value='".$name['R_O_N']."'></td>
		<td".$rfld.$stl_s."border-right:1px solid #000; width:35px;".$stl_e."><input onchange='vote5(\"R_O_V\",".$name['ID'].",this.value); vote2(\"".$name['ID']."\");' value='".$name['R_O_V']."'></td>
		<td".$rfld.$stl_s."border-right:1px solid #000; width:50px;".$stl_e."><input onchange='vote5(\"R_O_TO\",".$name['ID'].",this.value); vote2(\"".$name['ID']."\");' value='".$name['R_O_TO']."'></td>
		<td".$rfld.$stl_s."width:50px;".$stl_e."><input onchange='vote5(\"R_O_TP\",".$name['ID'].",this.value); vote2(\"".$name['ID']."\");' value='".$name['R_O_TP']."'></td>
		</tr>";

	}else{
		
		echo "<tr style='border-bottom:1px solid #000;'>
		<td".$fld.$stl_s."border-right:1px solid #000; width:32px;".$stl_e.">".$name['TID']."</td>
		<td".$fld.$stl_s."border-right:1px solid #000; min-width:300px;".$stl_e.">".$name['TXT']."</td>
		<td".$fld.$stl_s."border-right:1px solid #000; width:165px;".$stl_e.">".$name['INSTR_1']."</td>
		<td".$fld.$stl_s."border-right:1px solid #000; width:165px;".$stl_e.">".$name['INSTR_2']."</td>
		<td".$fld.$stl_s."border-right:1px solid #000; width:165px;".$stl_e.">".$name['INSTR_3']."</td>
		<td".$fld.$stl_s."border-right:1px solid #000; width:70px;".$stl_e.">".$name['DIAM_SHIR']."</td>
		<td".$fld.$stl_s."border-right:1px solid #000; width:70px;".$stl_e.">".$name['DLINA']."</td>
		<td".$fld.$stl_s."border-right:1px solid #000; width:35px;".$stl_e.">".$name['R_O_S']."</td>
		<td".$fld.$stl_s."border-right:1px solid #000; width:35px;".$stl_e.">".$name['R_O_N']."</td>
		<td".$fld.$stl_s."border-right:1px solid #000; width:35px;".$stl_e.">".$name['R_O_V']."</td>
		<td".$fld.$stl_s."border-right:1px solid #000; width:50px;".$stl_e.">".$name['R_O_TO']."</td>
		<td".$fld.$stl_s."width:50px;".$stl_e.">".$name['R_O_TP']."</td>
		</tr>";

	}
}
echo "</tbody></table>";

// запомнить кто делал измененение в переходе операции МТК
echo "<script type='text/javascript'>
function vote2(obj){
	var req = getXmlHttp();
	req.open('GET', 'MTK_perehod_change.php?id='+obj+'&p1=".$user['ID']."');
	req.send(null);
}
function vote5(obj_fld, obj_id, obj_val){
	var req = getXmlHttp();
	req.open('GET', 'MTK_perehod_change_2.php?id='+obj_id+'&value='+TXT(obj_val)+'&field='+obj_fld);
	req.send(null);
}
function delrow_perehod_row(id_per){
	var req = getXmlHttp();

	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if(req.status == 200) {
				location.href = document.location;
			}else{
				alert ('Ошибка удаления перехода');
			}
		}
	}
	req.open('GET', 'MTK_perehod_del_row.php?id='+id_per, true);
	req.send(null);
	
}
</script>";
?>
<script>
function TXT(x) {
	res = x;
	res = res.replace(/\'/g,"@%1@");
	res = res.replace(/\"/g,"@%2@");
	res = res.replace(/\(/g,"@%3@");
	res = res.replace(/\)/g,"@%4@");
	res = res.replace(/\n/g,"@%5@");
	res = res.replace(/\&/g,"@%6@");
	res = res.replace(/\#/g,"@%7@");
	res = res.replace(/\\/g,"@%8@");
	res = res.replace(/\+/g,"@%9@");
	return res;
}
</script>