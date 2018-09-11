<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////

	define("MAV_ERP", TRUE);

	include "config.php";
	include "locale/".$lang."/lang.php";
	include "includes/database.php";
	include "includes/config.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);



function db_setup() {
	global $db_cfg, $db_prefix, $newpass, $loc, $db_charset;

	if ($db_cfg["SETUP"]!=="") {
	$db_tables = explode("|",$db_cfg["SETUP"]);
	for ($j=0;$j < count($db_tables);$j++) {
		$sql = "DROP TABLE IF EXISTS ".$db_prefix.$db_tables[$j];
		dbquery($sql);
		echo "==========================================================<br>";
		echo "Delete table \"".$db_tables[$j]."\" - ok<br>";
		$sql = "CREATE TABLE ".$db_prefix.$db_tables[$j]." (ID INTEGER UNSIGNED NOT NULL auto_increment";
		if ($db_cfg[$db_tables[$j]."|TYPE"]=="tree") $sql = $sql.", PID INTEGER UNSIGNED NOT NULL";
		if ($db_cfg[$db_tables[$j]."|TYPE"]=="ltree") $sql = $sql.", PID INTEGER UNSIGNED NOT NULL";
		if ($db_cfg[$db_tables[$j]."|TYPE"]=="ltree") $sql = $sql.", LID INTEGER UNSIGNED NOT NULL";
		$db_fields = explode("|",$db_cfg[$db_tables[$j]."|FIELDS"]);
		for ($i=0;$i < count($db_fields);$i++) {
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="integer") $sql = $sql.", ".$db_fields[$i]." VARCHAR(16) NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="pinteger") $sql = $sql.", ".$db_fields[$i]." INTEGER UNSIGNED NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="real") $sql = $sql.", ".$db_fields[$i]." VARCHAR(16) NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="preal") $sql = $sql.", ".$db_fields[$i]." VARCHAR(16) NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="money") $sql = $sql.", ".$db_fields[$i]." VARCHAR(16) NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="pmoney") $sql = $sql.", ".$db_fields[$i]." VARCHAR(16) NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="boolean") $sql = $sql.", ".$db_fields[$i]." TINYINT UNSIGNED NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="state") $sql = $sql.", ".$db_fields[$i]." TINYINT UNSIGNED NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="tinytext") $sql = $sql.", ".$db_fields[$i]." TINYTEXT NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="text") $sql = $sql.", ".$db_fields[$i]." TEXT NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="longtext") $sql = $sql.", ".$db_fields[$i]." LONGTEXT NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="textarea") $sql = $sql.", ".$db_fields[$i]." TEXT NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="html") $sql = $sql.", ".$db_fields[$i]." TEXT NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="mediumtext") $sql = $sql.", ".$db_fields[$i]." MEDIUMTEXT NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="date") $sql = $sql.", ".$db_fields[$i]." INTEGER UNSIGNED NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="dateplan") $sql = $sql.", ".$db_fields[$i]." TEXT NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="time") $sql = $sql.", ".$db_fields[$i]." INTEGER UNSIGNED NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="time2") $sql = $sql.", ".$db_fields[$i]." INTEGER UNSIGNED NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="list") $sql = $sql.", ".$db_fields[$i]." INTEGER UNSIGNED NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="list2") $sql = $sql.", ".$db_fields[$i]." INTEGER UNSIGNED NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="droplist") $sql = $sql.", ".$db_fields[$i]." INTEGER UNSIGNED NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="searchlist") $sql = $sql.", ".$db_fields[$i]." INTEGER UNSIGNED NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="treelist") $sql = $sql.", ".$db_fields[$i]." INTEGER UNSIGNED NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="grouplist") $sql = $sql.", ".$db_fields[$i]." INTEGER UNSIGNED NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="groupedlist") $sql = $sql.", ".$db_fields[$i]." INTEGER UNSIGNED NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="alist") $sql = $sql.", ".$db_fields[$i]." INTEGER UNSIGNED NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="multilist") $sql = $sql.", ".$db_fields[$i]." TEXT NOT NULL";
			if ($db_cfg[$db_tables[$j]."/".$db_fields[$i]]=="file") $sql = $sql.", ".$db_fields[$i]." TINYTEXT NOT NULL";
			echo $db_fields[$i]." [".$db_cfg[$db_tables[$j]."/".$db_fields[$i]]."] &nbsp &nbsp ";
		}
		echo "<br>";
		$sql = $sql.", PRIMARY KEY  (`ID`)) ENGINE=MyISAM CHARACTER SET=".$db_charset.";";
		if (($db_cfg[$db_tables[$j]."|TYPE"]=="line") or ($db_cfg[$db_tables[$j]."|TYPE"]=="tree") or ($db_cfg[$db_tables[$j]."|TYPE"]=="ltree")) {
			dbquery($sql);
			if ($db_cfg[$db_tables[$j]."|TYPE"]=="ltree") dbquery("INSERT INTO ".$db_prefix.$db_tables[$j]." (ID, PID, LID) VALUES ('1', '0', '0')");
			echo "Create table \"".$db_tables[$j]."\" - ok<br>";
			if ($db_tables[$j]=="users") {
				echo "Add user - ok<br>";
				dbquery("INSERT INTO ".$db_prefix."users (ID, LOGIN, PASS, FIO, IO, USERSEDIT) VALUES ('1', 'admin', '".md5($newpass)."', '".$loc["0"]."', '".$loc["0"]."', '1')");
			}
			
			if ($db_cfg[$db_tables[$j]."|ADDUNIQUE"].""!=="") {
				$uniques = explode("|",$db_cfg[$db_tables[$j]."|ADDUNIQUE"]);
				$uniques = implode(", ",$uniques);
				dbquery("ALTER TABLE ".$db_prefix.$db_tables[$j]." ADD UNIQUE ".$db_tables[$j]."_unique (".$uniques.")");
				echo $db_tables[$j]."_unique - ok<br>";	
			}
			
			if ($db_cfg[$db_tables[$j]."|ADDINDEX"].""!=="") {
				$indexes = explode("|",$db_cfg[$db_tables[$j]."|ADDINDEX"]);
				for ($i=0;$i < count($indexes);$i++) {
					dbquery("CREATE INDEX ".$indexes[$i]."_index ON ".$db_prefix.$db_tables[$j]." (".$indexes[$i].")");
					echo $indexes[$i]."_index - ok<br>";
				}
			}
		}

		echo "==========================================================<br><br><br>";
	}
	echo "Install complete!";
	}
}

db_setup();

?>