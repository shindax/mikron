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
		
	   // Цвет
		echo "<tr class='cl_black'>";


		echo "<td class='Field'>".$item["ID"]."</td>\n";

	   // Наименование
		$pic = "<a href='index.php?do=formsitem&id=".$item["ID"]."' title='".$loc["fi4"]."'><img style='margin-right: 5px;' src='uses/view.gif'></a> ";
		Field($item,"formsitem","NAME",true,"",$pic,"");

	   // Описание
		Field($item,"formsitem","MORE",true,"","","");

	   // Группа форм
		Field($item,"formsitem","ID_formgroups",true,"style='width: 160px;'","","");

	   // Действие
		DelField($item,"formsitem",true);

		echo "</tr>\n";
	}

	function OpenIDdir($item) {
		global $page_url, $db_prefix, $user, $loc, $opened, $pageurl;
		
		echo "<tr class='cl_black cltreef'>";

		$val = "erp_dbfg_".$item["ID"];

		$isopened = substr_count($opened, "|".$val."|")>0;
		$ischilds = false;
		$xxx = dbquery("SELECT * FROM ".$db_prefix."formsitem where (ID_formgroups=".$item["ID"].") limit 0,1");
		if (mysql_fetch_array($xxx)) $ischilds = true;

		$ml = $n*10;
		$pic = "<img style='margin-left: ".$ml."px;' src='uses/none.png'>";
		if ((!$isopened) && ($ischilds)) $pic = "<a href='".$pageurl."&open=".$val."'><img style='margin-left: ".$ml."px;' src='uses/collapse.png'></a>";
		if (($isopened) && ($ischilds)) $pic = "<a href='".$pageurl."&close=".$val."'><img style='margin-left: ".$ml."px;' src='uses/expand.png'></a>";


		echo "<td class='Field'></td>\n";
		Field($item,"forms","NAME",false,"",$pic,"colspan='4'");

		if (($isopened) && ($ischilds)) {
			$xxx = dbquery("SELECT * FROM ".$db_prefix."formsitem where (ID_formgroups=".$item["ID"].") order by ID");
			while($res = mysql_fetch_array($xxx)) {
				OpenID($res);
			}
		}

		echo "</tr>\n";
	}

   // ЗАГОЛОВОК ///////////////////////////////////////////////////////////////////////
	echo "<h2>".$loc["fi1"]."</h2>\n";

   // ДОБАВЛЕНИЕ ///////////////////////////////////////////////////////////////////////
	AddLineLink("formsitem");


	echo "<form>\n";

   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
	echo "<table class='tbl' style='width: 1100px;' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='40'>item</td>\n";
	echo "<td>".$loc["fi2"]."</td>\n";
	echo "<td width='650'>".$loc["fi3"]."</td>\n";
	echo "<td width='150'>".$loc["f5"]."</td>\n";
	DelHeader("formsitem");
	echo "</tr>\n";

   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
	$xxx = dbquery("SELECT * FROM ".$db_prefix."formgroups order by ORD");
	while($res = mysql_fetch_array($xxx)) {
		OpenIDdir($res);
	}

	echo "</table><br>\n";

	echo "<table class='tbl' style='width: 1100px;' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='40'>item</td>\n";
	echo "<td>".$loc["fi2"]."</td>\n";
	echo "<td width='650'>".$loc["fi3"]."</td>\n";
	echo "<td width='150'>".$loc["f5"]."</td>\n";
	DelHeader("formsitem");
	echo "</tr>\n";

   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
	$xxx = dbquery("SELECT * FROM ".$db_prefix."formsitem where (ID_formgroups=0) order by ID");
	while($res = mysql_fetch_array($xxx)) {
		OpenID($res);
	}	


	echo "</table>\n";
	echo "</form>\n";

}

?>