<?php
// $change_id - ID элемента у которого изменился статус
  
  $result2 = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID='".$change_id."') ");
  $name2 = mysql_fetch_array($result2);
  
  dbquery("UPDATE ".$db_prefix."db_contacts SET TID='".$name2['TID']."' WHERE (ID_resurs='".$change_id."')");
  
  $res_2 = dbquery("SELECT ID, ID_users, ID_users3 FROM okb_db_itrzadan where (ID_users3='".$change_id."') and (STATUS!='Завершено') and (STATUS!='Аннулировано') ");
  while ($nam_2 = mysql_fetch_array($res_2)){
	dbquery("Update okb_db_itrzadan Set ID_users3='".$nam_2['ID_users']."' where (ID='".$nam_2['ID']."')");
  }

  $res_3 = dbquery("SELECT BOSS, ID_otdel FROM okb_db_shtat where (ID_resurs='".$change_id."') and (NOTTAB='0') ");
  while ($nam_3 = mysql_fetch_array($res_3)){
	  if ($nam_3['BOSS']=='1'){
		  $res_4 = dbquery("SELECT PID FROM okb_db_otdel where (ID='".$nam_3['ID_otdel']."') ");
		  $nam_4 = mysql_fetch_array($res_4);
		  $res_5 = dbquery("SELECT ID_resurs FROM okb_db_shtat where (ID_otdel='".$nam_4['PID']."') and (NOTTAB='0') and (BOSS='1') ");
		  $nam_5 = mysql_fetch_array($res_5);
		  dbquery("Update okb_db_itrzadan Set ID_users='".$nam_5['ID_resurs']."' where (ID_users='".$change_id."') and (STATUS!='Завершено') and (STATUS!='Аннулировано') ");
	  }
	  if ($nam_3['BOSS']=='0'){
		  $res_5 = dbquery("SELECT ID_resurs FROM okb_db_shtat where (ID_otdel='".$nam_3['ID_otdel']."') and (NOTTAB='0') and (BOSS='1') ");
		  $nam_5 = mysql_fetch_array($res_5);
		  dbquery("Update okb_db_itrzadan Set ID_users='".$nam_5['ID_resurs']."' where (ID_users='".$change_id."') and (STATUS!='Завершено') and (STATUS!='Аннулировано') ");
	  }
  }
?>