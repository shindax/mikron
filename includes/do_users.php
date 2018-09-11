<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


if (($user["USERSEDIT"]=="1") && ($_GET["p0"]!=="rights")) {

	function OpenID($item) {
		global $pageurl, $user, $loc;
		
		$edit = $user["ID"]!==$item["ID"];
	   // Цвет
		echo "<tr class='cl_black'>";

		Field($item,"users","ID",false,"","","");

	   // Логин
		$pic = "<a href='index.php?do=rights&id=".$item["ID"]."' title='".$loc["u11"]."'><img style='margin-right: 5px;' src='uses/view.gif'></a> ";
		echo "<td class='Field'>".$pic."<b>".FVal($item,"users","LOGIN")."</b></td>";

	   // Ф.И.О.
		Field($item,"users","FIO",true,"","","");

	   // Имя
		Field($item,"users","IO",true,"","","");

	   // Статус
		Field($item,"users","STATE",$edit,"","","");

	   // Редактор польз.
		if ($user["ID"]=="1") Field($item,"users","USERSEDIT",$edit,"","","style='text-align: center;'");

	   // Сброс пароля
		if ($item["ID"]!=="1") {
			echo "<td class='Field AC'><a href='javascript:void(0);' title='".$loc["u12"]."' onclick='if (confirm(\"".$loc["u13"]." - ".$item["LOGIN"]." ?\")) parent.location=\"$pageurl&nullpass=".$item["ID"]."\";'><img src='uses/key.png' alt='".$loc["u12"]."'></a></td>";
		} else {
			echo "<td class='Field'></td>";
		}

		if ($user["ID"]=="1") {
			if ($item["ID"]!=="1") {
				echo "<td class='Field AC'><a href='$pageurl&loginunder=".$item["ID"]."'><img src='style/login.png'></a></td>";
			} else {
				echo "<td class='Field'></td>";
			}
		}

	   // Действие
		DelField($item,"users",$edit);

		echo "</tr>\n";
	}

   // ЗАГОЛОВОК ///////////////////////////////////////////////////////////////////////
	echo "<h2>".$loc["u1"]."</h2>\n";

   // ДОБАВЛЕНИЕ ///////////////////////////////////////////////////////////////////////
	echo "<table style='width: 100%;'><tr><td>";
	echo "<span class='popup' onClick='chClass(this,\"hpopup\",\"popup\");'>".$loc["u4"];
		echo "<br><div class='popup' onClick='window.event.cancelBubble = true;'>";
		echo "<form method='post' style='padding: 0px; margin: 0px;'>";
		echo "<input type='hidden' name='AddUSER' value='OK'>";
		echo "<input type='text' style='width: 250px;' name='Login' value=''>";
		echo "<input type='submit' value='".$loc["u5"]."'>";
		echo "</form>";
		echo "</div>";
	echo "</span></td><td style='text-align: right;'><a href='$pageurl&p0=rights'>".$loc["u11"]."</a></td></tr></table><br>\n";

   // ФОРМА ///////////////////////////////////////////////////////////////////////
	echo "<form>\n";

   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
	echo "<table class='tbl' style='width: 100%;' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='30'>ID</td>\n";
	echo "<td width='120'>".$loc["u6"]."</td>\n";
	echo "<td>".$loc["u8"]."</td>\n";
	echo "<td width='220'>".$loc["u7"]."</td>\n";
	echo "<td width='100'>".$loc["u9"]."</td>\n";
	if ($user["ID"]=="1") echo "<td width='80'>".$loc["u10"]."</td>\n";
	echo "<td width='40'>".$loc["u12"]."</td>\n";
	if ($user["ID"]=="1") echo "<td width='25'></td>\n";
	DelHeader("users");
	echo "</tr>\n";


   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
	$where = "";
	if ($user["ID"]!=="1") $where = "where (ID>'1')";
	$xxx = dbquery("SELECT * FROM ".$db_prefix."users ".$where." order by binary(FIO)");
	while($res = mysql_fetch_array($xxx)) {
		OpenID($res);
	}	

	echo "</table>\n";
	echo "</form>\n";
}


if (($user["USERSEDIT"]=="1") && ($_GET["p0"]=="rights")) {


	function OpenID2($item) {
		global $pageurl, $user, $loc;
		
		$edit = $user["ID"]!==$item["ID"];
	   // Цвет
		echo "<tr class='cl_black'>";

		Field($item,"users","ID",false,"","","");

	   // Логин
		echo "<td class='Field'><b>".FVal($item,"users","LOGIN")."</b></td>";

	   // Ф.И.О.
		Field($item,"users","FIO",false,"","","");

	   // Права просмотра
		Field($item,"users","ID_forms",false,"","","");

	   // Права редактирования
		Field($item,"users","ID_rightgroups",false,"","","");

		echo "</tr>\n";
	}


   // ЗАГОЛОВОК ///////////////////////////////////////////////////////////////////////
	echo "<h2>".$loc["u11"]."</h2>\n";

   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
	echo "<table class='tbl' style='width: 100%;' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='30'>ID</td>\n";
	echo "<td width='120'>".$loc["u6"]."</td>\n";
	echo "<td>".$loc["u8"]."</td>\n";
	echo "<td>".$loc["r3"]."</td>\n";
	echo "<td>".$loc["r4"]."</td>\n";
	echo "</tr>\n";


   // САМА ТАБЛИЦА ///////////////////////////////////////////////////////////////
	$where = "";
	if ($user["ID"]!=="1") $where = "where (ID>'1')";
	$xxx = dbquery("SELECT * FROM ".$db_prefix."users ".$where." order by binary(FIO)");
	while($res = mysql_fetch_array($xxx)) {
		OpenID2($res);
	}	

	echo "</table>\n";

}

?>