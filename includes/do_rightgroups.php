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
		global $pageurl, $user;
		
	   // Цвет
		echo "<tr class='cl_black'>";

		$pic = "<a href='".$pageurl."&id=".$item["ID"]."&p0=edit' title='".$loc["u11"]."'><img style='margin-right: 5px;' src='uses/view.gif'></a> ";

	   // Сортировка
		Field($item,"rightgroups","ID",false,"","","");

	   // Наименование
		Field($item,"rightgroups","NAME",true,"",$pic,"");

	   // Права доступа
		//Field($item,"rightgroups","RIGHTS",true,"","","");

	   // Действие
		DelField($item,"rightgroups",true);

		echo "</tr>\n";
	}

	function OpenID2($item) {
		global $pageurl, $user;
		
	   // Цвет
		echo "<tr class='cl_black'>";

		$pic = "<a href='".$pageurl."&id=".$item["ID"]."&p0=view' title='".$loc["u11"]."'><img style='margin-right: 5px;' src='uses/view.gif'></a> ";

	   // Сортировка
		Field($item,"viewgroups","ID",false,"","","");

	   // Наименование
		Field($item,"viewgroups","NAME",true,"",$pic,"");

	   // Права доступа
		//Field($item,"viewgroups","RIGHTS",true,"","","");

	   // Действие
		DelField($item,"viewgroups",true);

		echo "</tr>\n";
	}

   // ФОРМА ///////////////////////////////////////////////////////////////////////
	echo "<form>\n";

if (!isset($_GET["id"])) {
////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////

	echo "<table class='tbl' style='width: 1200px; background: none;' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr>\n";
	echo "<td style='width: 50%; background: none; vertical-align: top;'>\n";


   // ЗАГОЛОВОК ///////////////////////////////////////////////////////////////////////
	echo "<h2>".$loc["rg5"]."</h2>\n";

   // ДОБАВЛЕНИЕ ///////////////////////////////////////////////////////////////////////
	AddLineLink("viewgroups");

   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
	echo "<table class='tbl' style='width: 100%;' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='30'>".$loc["rg2"]."</td>\n";
	echo "<td>".$loc["rg3"]."</td>\n";
	//echo "<td width='250'>".$loc["rg4"]."</td>\n";
	DelHeader("viewgroups");
	echo "</tr>\n";


   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
	$xxx = dbquery("SELECT * FROM ".$db_prefix."viewgroups order by ID desc");
	while($res = mysql_fetch_array($xxx)) {
		OpenID2($res);
	}	

	echo "</table>\n";

	echo "</td><td style='vertical-align: top; background: none;'>\n";


   // ЗАГОЛОВОК ///////////////////////////////////////////////////////////////////////
	echo "<h2>".$loc["rg1"]."</h2>\n";

   // ДОБАВЛЕНИЕ ///////////////////////////////////////////////////////////////////////
	AddLineLink("rightgroups");

   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
	echo "<table class='tbl' style='width: 100%;' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='30'>".$loc["rg2"]."</td>\n";
	echo "<td>".$loc["rg3"]."</td>\n";
	//echo "<td width='250'>".$loc["rg4"]."</td>\n";
	DelHeader("rightgroups");
	echo "</tr>\n";


   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
	$xxx = dbquery("SELECT * FROM ".$db_prefix."rightgroups order by ID desc");
	while($res = mysql_fetch_array($xxx)) {
		OpenID($res);
	}	

	echo "</table>\n";

	echo "</td></tr></table>\n";

////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
} else {

	if ($_GET["p0"]=="edit") {

		echo "<table class='tbl' style='width: 1200px; background: none;' border='0' cellpadding='0' cellspacing='0'>\n";
		echo "<tr>\n";
		echo "<td style='width: 50%; background: none; vertical-align: top;'>\n";

		$xxx = dbquery("SELECT * FROM ".$db_prefix."rightgroups where (ID='".$_GET["id"]."')");
		if ($item = mysql_fetch_array($xxx)) {

			echo "<h2>".$loc["rg1"]."</h2>\n";
			echo "<h4>".$item["ID"]." - ".$item["NAME"]."</h4>\n";
			echo "<table class='tbl' style='width: 100%;' border='0' cellpadding='0' cellspacing='0'>\n";
			echo "<tr class='first'>\n";
			echo "<td width='30'>".$loc["rg4"]."</td>\n";
			echo "</tr>";

			echo "<tr class='cl_black'>\n";
			Field($item,"rightgroups","RIGHTS",true,"","","");
			echo "</tr>\n";

			echo "<tr class='first'>\n";
			echo "<td width='30'>".$loc["rg6"]."</td>\n";
			echo "</tr>";
			echo "<tr class='cl_black'><td class='Field'>\n";
			$xxx = dbquery("SELECT ID, FIO, LOGIN FROM ".$db_prefix."users where (ID_rightgroups like '%|".$item["ID"]."|%') order by binary(FIO)");
			while($res = mysql_fetch_array($xxx)) {
				echo "<b>".$res["LOGIN"]."</b> - ".$res["FIO"]."<br>";
			}
			echo "</td></tr>\n";

			echo "</table>\n";

		}

		echo "</td><td style='vertical-align: top; background: none;'>\n";		
		echo out_db_cfg();
		echo "</td></tr></table>\n";

	}

	if ($_GET["p0"]=="view") {

		$xxx = dbquery("SELECT * FROM ".$db_prefix."viewgroups where (ID='".$_GET["id"]."')");
		if ($item = mysql_fetch_array($xxx)) {

			echo "<h2>".$loc["rg5"]."</h2>\n";
			echo "<h4>".$item["ID"]." - ".$item["NAME"]."</h4>\n";
			echo "<table class='tbl' style='width: 700px;' border='0' cellpadding='0' cellspacing='0'>\n";
			echo "<tr class='first'>\n";
			echo "<td width='30'>".$loc["rg4"]."</td>\n";
			echo "</tr>";

			echo "<tr class='cl_black'>\n";
			Field($item,"viewgroups","RIGHTS",true,"","","");
			echo "</tr>\n";

			echo "<tr class='first'>\n";
			echo "<td width='30'>".$loc["rg6"]."</td>\n";
			echo "</tr>";
			echo "<tr class='cl_black'><td class='Field'>\n";
			$xxx = dbquery("SELECT ID, FIO, LOGIN FROM ".$db_prefix."users where (ID_forms like '%|".$item["ID"]."|%') order by binary(FIO)");
			while($res = mysql_fetch_array($xxx)) {
				echo "<b>".$res["LOGIN"]."</b> - ".$res["FIO"]."<br>";
			}
			echo "</td></tr>\n";

			echo "</table>\n";

		}

	}


////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
}


	echo "</form>\n";


}

?>