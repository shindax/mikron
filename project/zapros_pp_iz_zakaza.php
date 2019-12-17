<?php

if (db_check("db_zadan","MEGA_REDACTOR")) $editing = true;
if ($editing) {
	$id_zak_sql = dbquery("SELECT ID_zak FROM okb_db_zakdet where ID='".$render_row['ID_zakdet']."' ");
	$id_zak_val = mysql_fetch_row($id_zak_sql);
	
	echo "<b id='btn_soob_pp_".$render_row['ID']."' style='color:#0000ff; cursor:pointer;' onclick='check_soob_pp(".$render_row['ID'].",this.innerText)'>Отправить сообщение ПП</b>
	<div id='soobshenie_pp_".$render_row['ID']."' style='display:none; position:relative;'><div style='padding:10px; border:1px solid #8ba2c2; box-shadow:3px 4px 20px #555555; background:#c6d9f1; left:90px; top:-60px; width:400px; position:absolute;'>
	<b>Операция №".$render_row['ORD']."</b><br>
	<textarea style=\"width:355px; resize:none;\" onchange=vote9(this,".$render_row['ID'].",this.value); value=\"".$render_row['MSG_INFO']."\">".$render_row['MSG_INFO']."</textarea>
	<input type=\"button\" style=\"float:right; margin-top:3px; margin-right:10px; border:1px solid #444; background:#bbb; height:25px; width:25px; font-size:80%;\" value=\"ok\" onclick=\"zapr_pp(this,".$id_zak_val[0].",".$render_row['ID_zakdet'].",".$render_row['ID'].");\"></div></div>";

	echo "<script language='javascript'>
	function check_soob_pp(id_op, val){
		if(val==\"Отправить сообщение ПП\"){ document.getElementById(\"soobshenie_pp_\"+id_op).style.display=\"block\"; document.getElementById(\"btn_soob_pp_\"+id_op).innerText=\"Не отправлять\"};
		if(val==\"Не отправлять\"){ document.getElementById(\"soobshenie_pp_\"+id_op).style.display=\"none\"; document.getElementById(\"btn_soob_pp_\"+id_op).innerText=\"Отправить сообщение ПП\"};
	}
	function vote9(obj, id_oper, val_oper){
		var req = getXmlHttp();
		req.open('GET', 'MSG_INFO_operitems.php?id='+id_oper+'&value='+val_oper);
		req.send(null);
	}
	function zapr_pp(obj, id_zak, id_dse, id_op){
		if(obj.value==\"ok\"){
			if(confirm(\"Послать запрос в КТО?\")){
				obj.parentNode.parentNode.parentNode.parentNode.parentNode.className='Field';
				obj.parentNode.parentNode.getElementsByTagName('textarea')[0].disabled=true;
				obj.style.display='none';
				vote(obj,'MSG_INFO_operitems.php?id='+id_op+'&value='+obj.parentNode.parentNode.getElementsByTagName('textarea')[0].value);
				vote(obj,'zapros_MTK_PP.php?p1='+id_op+'&p2='+id_dse+'&p3='+id_zak);
			}
		}	
	}	
	</script>";
}else{
	echo "";
}



$result = mysql_fetch_assoc(dbquery("SELECT u.FIO as NAME,m.ETIME as TIME FROM okb_db_mtk_perehod m LEFT JOIN okb_users u ON u.id = m.EUSER where ID_operitems='".$render_row['ID']."' order by m.TID "));

echo '<span style="float:right;font-weight:bold">Обновлено: ' . $result['NAME'] . ' — ' . date('d-m-Y H:i:s', $result['TIME']) . '</span>';


?>
