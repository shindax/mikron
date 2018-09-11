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
		global $page_url, $user;
		
	   // Цвет
		echo "<tr class='cl_black'>";

	   // Сортировка
		Field($item,"formgroups","ORD",true,"","","style='width: 80px;'");

	   // Наименование
		Field($item,"formgroups","NAME",true,"","","");

	   // Номер панели
		Field($item,"formgroups","BARID",true,"","","");

	   // Действие
		DelField($item,"formgroups",true);

		echo "</tr>\n";
	}

   // ЗАГОЛОВОК ///////////////////////////////////////////////////////////////////////
	echo "<h2>".$loc["gf1"]."</h2>\n";

   // ДОБАВЛЕНИЕ ///////////////////////////////////////////////////////////////////////
	AddLineLink("formgroups");

   // ФОРМА ///////////////////////////////////////////////////////////////////////
	echo "<form>\n";

   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
	echo "<table class='tbl' style='width: 100%;' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='80'>".$loc["gf3"]."</td>\n";
	echo "<td>".$loc["gf4"]."</td>\n";
	echo "<td width='100'>".$loc["gf5"]."</td>\n";
	DelHeader("formgroups");
	echo "</tr>\n";


   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
	$xxx = dbquery("SELECT * FROM ".$db_prefix."formgroups order by BARID, ORD");
	while($res = mysql_fetch_array($xxx)) {
		OpenID($res);
	}	

	echo "</table>\n";
	echo "</form>\n";

}

?>