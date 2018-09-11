<?php
///////////////////////////////////////////////////////////////////////
// Редактирование запланированных часов Нп
///////////////////////////////////////////////////////////////////////

	include "includes.php";



// Если есть доступ
if (db_adcheck("db_planzad")) {

$id = $_GET["oid"];
$ID_operitems = $id;
$date = $_GET["date"];
$val = $_GET["val"]*1;

	$result = dbquery("SELECT DATE, NORM FROM ".$db_prefix."db_planzad where (ID_operitems = '".$id."') and (DATE='".$date."')");
	if ($zad = mysql_fetch_array($result)) {
	// Если есть

		if ($val>0) {
		// Изменить
			dbquery("Update ".$db_prefix."db_planzad Set NORM:='".$val."' where (ID_operitems = '".$id."') and (DATE='".$date."')");
		} else {
		// Удалить
			dbquery("DELETE from ".$db_prefix."db_planzad where (ID_operitems = '".$id."') and (DATE='".$date."')");
		}

	} else {
	// Если нет

		if ($val>0) {
		// Создать

			$zak_id = 0;
			$izd_id = 0;

			$result = dbquery("SELECT ID_zakdet, ID FROM ".$db_prefix."db_operitems where (ID = '".$id."')");
			if ($operitem = mysql_fetch_array($result)) {
				$izd_id = $operitem["ID_zakdet"];
				$result = dbquery("SELECT ID_zak, ID FROM ".$db_prefix."db_zakdet where (ID = '".$izd_id."')");
				if ($izd = mysql_fetch_array($result)) {
					$zak_id = $izd["ID_zak"];
				}
			}

			if ($zak_id>0) dbquery("INSERT INTO ".$db_prefix."db_planzad (DATE, ID_zak, ID_zakdet, ID_operitems, NORM) VALUES ('".$date."', '".$zak_id."', '".$izd_id."', '".$id."', '".$val."')");

		}

	}

include "calczak.php";

}
?>