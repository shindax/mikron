<?php
  $resxx7 = dbquery("SELECT * FROM ".$db_prefix."db_edo_inout_files_vrem where (ID='".$i_d."') ");
  $last_4 = mysql_fetch_array($resxx7);

  $rexs_3 = dbquery("SELECT NAME FROM ".$db_prefix."db_clients where (ID='".$last_4['ID_clients']."') ");
  $ltas_3 = mysql_fetch_row($rexs_3);
  
  $resxxx5 = dbquery("SELECT MAX(ID) FROM ".$db_prefix."db_edo_inout_files where (TIP_FAIL='1') ");
  $last5 = mysql_fetch_row($resxxx5);
  $num13 = $last5[0];
		
  $resxxx = dbquery("SELECT NAME_IN FROM ".$db_prefix."db_edo_inout_files where (ID='".$num13."') ");
  $last = mysql_fetch_array($resxxx);
  $last33 = $last['NAME_IN'];
  $last33 = explode("-",$last33);
  $last33 = $last33[1]*1;
  $last33 = $last33 + 1;
			
  if ($last33<10) $last33 = "0".$last33;
  if ($last33<100) $last33 = "0".$last33;
  if ($last33<1000) $last33 = "0".$last33;
		
  $oboz2 = date("Ymd");
  dbquery("INSERT INTO ".$db_prefix."db_edo_inout_files (KOMM, CONTRAGENT, OTVET_INOUT, TIP_FAIL, ID_clients, ID_clients_contacts, NAME_OUT, NAME_IN, DATA, DATE_START, VID_FAIL, MORE, ID_krz, 
  ID_krz2, ID_zak, ID_files_3, ID_files_1, FILENAME, ID_resurs, EDITTIME, ID_users, ID_contacts) VALUES 
  ('".$last_4['KOMM']."', '".$ltas_3[0]."', '".$last_4['OTVET_INOUT']."', '1', '".$last_4['ID_clients']."', '".$last_4['ID_clients_contacts']."', '".$last_4['NAME_OUT']."', 
  '".date("y")."-".$last33."', '".$oboz2."', '".$last_4['DATE_START']."', '".$last_4['VID_FAIL']."', '".$last_4['MORE']."', '".$last_4['ID_krz']."', '".$last_4['ID_krz2']."', '".$last_4['ID_zak']."', 
  '".$last_4['ID_files_3']."', '".$last_4['ID_files_1']."', '".$last_4['FILENAME']."', '".$last_4['ID_resurs']."', '".$last_4['EDITTIME']."', '".$last_4['ID_users']."', '".$last_4['ID_contacts']."')");
  $cur_new_ins_id = mysql_insert_id();
    
  dbquery("INSERT INTO ".$db_prefix."db_edo_vremitr (ID_contacts) VALUES ('".$cur_new_ins_id."')");
   
  copy("project/63gu88s920hb045e/db_edo_inout_files_vrem@FILENAME/".$last_4['FILENAME'], "project/63gu88s920hb045e/db_edo_inout_files@FILENAME/".$last_4['FILENAME']);

echo "<script type='text/javascript'>
location.href='index.php?do=show&formid=111&id=".$cur_new_ins_id."';
</script>";
?>