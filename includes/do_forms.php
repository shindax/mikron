<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


if ($user["ID"]=="1") {

	function OpenID($item) {
		global $page_url, $db_prefix, $user, $loc;
		
		echo "<tr class='cl_black'>";


		echo "<td class='Field'>".$item["ID"]."</td>\n";

	   // Сортировка
		Field($item,"forms","ORD",true,"","","style='width: 80px;'");

	   // Наименование
		$pic = "<a href='index.php?do=form&id=".$item["ID"]."' title='".$loc["f7"]."'><img style='margin-right: 5px;' src='uses/view.gif'></a> ";
		Field($item,"forms","NAME",true,"",$pic,"");

	   // Группа форм
		Field($item,"forms","ID_formgroups",true,"style='width: 160px;'","","");

	   // Групировка
		Field($item,"forms","GROUPID",true,"","","");

	   // Свободный доступ
		Field($item,"forms","SHOWALL",true,"","","");

	   // Действие
		DelField($item,"forms",true);

		echo "</tr>\n";
	}

	function OpenIDdir($item) {
		global $page_url, $db_prefix, $user, $loc, $opened, $pageurl;
		
		echo "<tr class='cl_black cltreef'>";

		$val = "erp_dbfg_".$item["ID"];

		$isopened = substr_count($opened, "|".$val."|")>0;
		$ischilds = false;
		$xxx = dbquery("SELECT * FROM ".$db_prefix."forms where (ID_formgroups=".$item["ID"].") limit 0,1");
		if (mysql_fetch_array($xxx)) $ischilds = true;

		$ml = $n*10;
		$pic = "<img style='margin-left: ".$ml."px;' src='uses/none.png'>";
		if ((!$isopened) && ($ischilds)) $pic = "<a href='".$pageurl."&open=".$val."'><img style='margin-left: ".$ml."px;' src='uses/collapse.png'></a>";
		if (($isopened) && ($ischilds)) $pic = "<a href='".$pageurl."&close=".$val."'><img style='margin-left: ".$ml."px;' src='uses/expand.png'></a>";


		echo "<td class='Field'></td>\n";
		Field($item,"forms","NAME",false,"",$pic,"colspan='6'");

		if (($isopened) && ($ischilds)) {
			$xxx = dbquery("SELECT * FROM ".$db_prefix."forms where (ID_formgroups=".$item["ID"].") order by ORD");
			while($res = mysql_fetch_array($xxx)) {
				OpenID($res);
			}
		}

		echo "</tr>\n";
	}

   // ЗАГОЛОВОК ///////////////////////////////////////////////////////////////////////
	echo "<h2>".$loc["f1"]."</h2>\n";

   // ДОБАВЛЕНИЕ ///////////////////////////////////////////////////////////////////////
	AddLineLink("forms");


	echo "<form>\n";

   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
	echo "<table class='tbl' style='width: 1100px;' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='40'>formid</td>\n";
	echo "<td width='80'>".$loc["f3"]."</td>\n";
	echo "<td>".$loc["f4"]."</td>\n";
	echo "<td width='160'>".$loc["f5"]."</td>\n";
	echo "<td width='100'>".$loc["f8"]."</td>\n";
	echo "<td width='40'>".$loc["f9"]."</td>\n";
	DelHeader("forms");
	echo "</tr>\n";

   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
	$xxx = dbquery("SELECT * FROM ".$db_prefix."formgroups order by ORD");
	while($res = mysql_fetch_array($xxx)) {
		OpenIDdir($res);
	}

	echo "</table><br>\n";

	echo "<table class='tbl' style='width: 1100px;' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='40'>formid</td>\n";
	echo "<td width='80'>".$loc["f3"]."</td>\n";
	echo "<td>".$loc["f4"]."</td>\n";
	echo "<td width='160'>".$loc["f5"]."</td>\n";
	echo "<td width='100'>".$loc["f8"]."</td>\n";
	echo "<td width='40'>".$loc["f9"]."</td>\n";
	DelHeader("forms");
	echo "</tr>\n";

   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
	$xxx = dbquery("SELECT * FROM ".$db_prefix."forms where (ID_formgroups=0) order by ORD");
	while($res = mysql_fetch_array($xxx)) {
		OpenID($res);
	}

	echo "</table>\n";
	echo "</form>\n";

}

?>