<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	� 2012 ���������� �.�.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


if ($user["ID"]=="1") {


	function OpenID($item) {
		global $page_url, $user;
		
	   // ����
		echo "<tr class='cl_black'>";

	   // ����������
		Field($item,"formgroups","ORD",true,"","","style='width: 80px;'");

	   // ������������
		Field($item,"formgroups","NAME",true,"","","");

	   // ����� ������
		Field($item,"formgroups","BARID",true,"","","");

	   // ��������
		DelField($item,"formgroups",true);

		echo "</tr>\n";
	}

   // ��������� ///////////////////////////////////////////////////////////////////////
	echo "<h2>".$loc["gf1"]."</h2>\n";

   // ���������� ///////////////////////////////////////////////////////////////////////
	AddLineLink("formgroups");

   // ����� ///////////////////////////////////////////////////////////////////////
	echo "<form>\n";

   // ����� ������� ///////////////////////////////////////////////////////////////
	echo "<table class='tbl' style='width: 100%;' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='80'>".$loc["gf3"]."</td>\n";
	echo "<td>".$loc["gf4"]."</td>\n";
	echo "<td width='100'>".$loc["gf5"]."</td>\n";
	DelHeader("formgroups");
	echo "</tr>\n";


   // ���� ������� ///////////////////////////////////////////////////////////////
	$xxx = dbquery("SELECT * FROM ".$db_prefix."formgroups order by BARID, ORD");
	while($res = mysql_fetch_array($xxx)) {
		OpenID($res);
	}	

	echo "</table>\n";
	echo "</form>\n";

}

?>