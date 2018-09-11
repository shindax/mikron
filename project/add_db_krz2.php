<?php
// $insert_id - ID нового элемента

	if (!defined("MAV_ERP")) { die("Access Denied"); }

$result = dbquery("SELECT ID, ID_krz FROM ".$db_prefix."db_krz2 where (ID='".$insert_id."') ");
if ($row = mysql_fetch_array($result)) {

		$rxx = dbquery("SELECT * FROM ".$db_prefix."db_krz where (ID='".$row["ID_krz"]."') ");
		if ($krz = mysql_fetch_array($rxx)) {
			$name = $krz["NAME"] . "-" . mysql_result(dbquery("SELECT COUNT(NAME) FROM ".$db_prefix."db_krz2 where (ID_krz='".$row["ID_krz"]."') "), 0);

			dbquery("Update ".$db_prefix."db_krz2 Set NAME:='".$name."' where (ID='".$insert_id."')");

			dbquery("Update ".$db_prefix."db_krz2 Set ID_users:='".$krz["ID_users"]."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_krz2 Set ID_clients:='".$krz["ID_clients"]."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_krz2 Set ID_postavshik:='".$krz["ID_postavshik"]."' where (ID='".$insert_id."')");
			//dbquery("Update ".$db_prefix."db_krz2 Set DOGOVOR:='".$krz["DOGOVOR"]."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_krz2 Set MORE:='".$krz["MORE"]."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_krz2 Set MORE_EXPERT:='".$krz["MORE_EXPERT"]."' where (ID='".$insert_id."')");
			dbquery("Update ".$db_prefix."db_krz2 Set DATE_PLAN:='".$krz["DATE_PLAN"]."' where (ID='".$insert_id."')");


		   // Копируем из КРЗ

			function CopyInnerDSE($det_krz_id,$det_krz2_id) {
				global $db_prefix, $insert_id;

				// Копируем входящие krzdetitems
					$xxx = dbquery("SELECT * FROM ".$db_prefix."db_krzdetitems where (ID_krzdet='".$det_krz_id."')");
					while ($item=mysql_fetch_array($xxx)) {
						dbquery("INSERT INTO ".$db_prefix."db_krz2detitems (ID_krz2det, NAME, ID_users, TID, PRICE, COUNT) VALUES ('".$det_krz2_id."', '".$item["NAME"]."', '".$item["ID_users"]."', '".$item["TID"]."', '".$item["PRICE"]."', '".$item["COUNT"]."')");
					}

				// Копируем входящие krzdet
					$xxx = dbquery("SELECT * FROM ".$db_prefix."db_krzdet where (PID='".$det_krz_id."')");
					while ($det = mysql_fetch_array($xxx)) {
						dbquery("INSERT INTO ".$db_prefix."db_krz2det (ID_krz2, PID, OBOZ, COUNT, D1, D2, D3, D4, D5, D6, D7, D8, D9, D10, D11, VES, NAME) VALUES ('".$insert_id."', '".$det_krz2_id."', '".$det["OBOZ"]."', '".$det["COUNT"]."', '".$det["D1"]."', '".$det["D2"]."', '".$det["D3"]."', '".$det["D4"]."', '".$det["D5"]."', '".$det["D6"]."', '".$det["D7"]."', '".$det["D8"]."', '".$det["D9"]."', '".$det["D10"]."', '".$det["D11"]."', '".$det["VES"]."', '".$det["NAME"]."')");
						$id = mysql_insert_id();
						CopyInnerDSE($det["ID"],$id);
					}
			}

			$xxx = dbquery("SELECT * FROM ".$db_prefix."db_krzdet where (ID_krz='".$krz["ID"]."') and (PID='0')");
			$det = mysql_fetch_array($xxx);
			dbquery("INSERT INTO ".$db_prefix."db_krz2det (ID_krz2, PID, OBOZ, COUNT, D1, D2, D3, D4, D5, D6, D7, D8, D9, D10, D11, VES, NAME) VALUES ('".$insert_id."', '0', '".$det["OBOZ"]."', '".$det["COUNT"]."', '".$det["D1"]."', '".$det["D2"]."', '".$det["D3"]."', '".$det["D4"]."', '".$det["D5"]."', '".$det["D6"]."', '".$det["D7"]."', '".$det["D8"]."', '".$det["D9"]."', '".$det["D10"]."', '".$det["D11"]."', '".$det["VES"]."', '".$det["NAME"]."')");
			$id = mysql_insert_id();
			CopyInnerDSE($det["ID"],$id);
		}
}


?>