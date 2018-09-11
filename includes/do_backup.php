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


	$now_time = mktime();

	function DeltaTime($time) {
		global $loc, $now_time;

		$res = "";

		$dt = $now_time-$time;

		$ddd = floor($dt/86400);
		if ($ddd>0) $res = $res."<b>".$ddd."</b> ".$loc["bckp8"]." ";
		$dt = $dt - (86400*$ddd);

		if ($ddd<30) {
		$hhh = floor($dt/3600);
		if ($hhh>0) $res = $res."<b>".$hhh."</b> ".$loc["bckp9"]." ";
		$dt = $dt - (3600*$hhh);

		if ($ddd==0) {

			$mmm = floor($dt/60);
			if ($mmm>0) $res = $res."<b>".$mmm."</b> ".$loc["bckp10"]." ";
			$dt = $dt - (60*$mmm);

			if ($hhh==0) {
				$res = $res."<b>".$dt."</b> ".$loc["bckp11"]." ";
			}
		}
		}

		return $res;		
	}

	if (isset($_POST["create_backup"])) Create_BACKUP();
	if (isset($_POST["do_restore_backup"])) Restore_BACKUP($_POST["do_restore_backup"]);



	function OutID($time) {
		global $loc, $backup_path;

		$filename = $time."-".date("d_m_Y",$time).".sql";
		
	   // Цвет
		echo "<tr class='cl_black'>";
		echo "<td class='Field' style='text-align: left; vertical-align: middle;'><a href='get_backup.php?filename=".$filename."' title='".$loc["bckp12"]."'><img src='uses/addf.png'></a><b style='margin-left: 15px; margin-right: 15px; font-size: 14px;'>".date("Y - m - d",$time)."</b>".date("H : i : s",$time)."</td>\n";
		echo "<td class='Field' style='text-align: center; vertical-align: middle;'>".DeltaTime($time)."</td>\n";
		echo "<td class='Field' style='text-align: center; vertical-align: middle;'>";
			echo "<form method='post' style='padding: 0px; margin: 0px;'>";
			echo "<input type='hidden' name='restore_backup' value='".$time."'>";
			echo "<input type='submit' value='".$loc["bckp5"]."'>";
			echo "</form>";
		echo "</td>\n";
		echo "</tr>";
	}



	//////////////////////////////////////////////////////////////////
	//
	//	Вывод информации
	//
	//////////////////////////////////////////////////////////////////


	echo "<h2>".$loc["bckp1"]."</h2>";

if (!isset($_POST["restore_backup"])) {

		echo "<form method='post' style='padding: 0px; margin: 0px 0px 0px 100px;'>";
		echo "<input type='hidden' name='create_backup' value='OK'>";
		echo "<input type='submit' value='".$loc["bckp2"]."'>";
		echo "</form>";

	echo "<br><br>";

   // ШАПКА ТАБЛИЦЫ ///////////////////////////////////////////////////////////////
	echo "<table class='tbl' style='width: 740px;' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td>".$loc["bckp3"]."</td>\n";
	echo "<td width='120'>".$loc["bckp4"]."</td>\n";
	echo "<td width='180'></td>\n";
	echo "</tr>\n";

	$files = makefilelist("./project/".$backup_path."/", ".sql", false, true);
	for ($j=0;$j < count($files);$j++) {
		$time_txt = explode("-",$files[$j]);
		if (count($time_txt)==2) {
			$time=$time_txt[0];
			if ($time*1==$time) OutID($time);
		}
	}

	echo "</table>\n";

}

if (isset($_POST["restore_backup"])) {

	$time = $_POST["restore_backup"]*1;
	$filename = $time."-".date("d_m_Y",$time).".sql";

	echo "<div class='edivwin'>";
	echo "<h4 style='color: Red;'>".$loc["bckp7"]." ".date("Y-m-d H:i:s",$time)." ?</h4> ";
			echo "<form method='post' style='padding: 0px; margin: 40px 0px 0px 150px;'>";
			echo "<input type='hidden' name='do_restore_backup' value='".$filename."'>";
			echo "<input type='submit' value='".$loc["bckp5"]."' style='width: 100px; color: red;'>";
			echo "</form>";
			echo "<form method='post' style='padding: 0px; margin: 50px 0px 0px 150px;'>";
			echo "<input type='hidden' name='chancell' value='".$filename."'>";
			echo "<input type='submit' value='".$loc["bckp6"]."' style='width: 100px; color: green;'>";
			echo "</form>";
	echo "</div>";
}

}

?>