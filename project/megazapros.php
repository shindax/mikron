<?php
dbquery("Update ".$db_prefix."db_operitems Set KSZ2_NUM:=''");
dbquery("Update ".$db_prefix."db_operitems Set KSZ2_ID_NUM:=''");
dbquery("Update ".$db_prefix."db_operitems Set KSZ2_ID:=''");
dbquery("Update ".$db_prefix."db_operitems Set KSZ_NUM:=''");
dbquery("Update ".$db_prefix."db_operitems Set KSZ_ID_NUM:=''");
dbquery("Update ".$db_prefix."db_operitems Set KSZ_ID:=''");

dbquery("Update ".$db_prefix."db_operitems Set FACT2_NUM:=''");
dbquery("Update ".$db_prefix."db_operitems Set FACT2_NUM_ID:=''");
dbquery("Update ".$db_prefix."db_operitems Set FACT2_ID_1:=''");
dbquery("Update ".$db_prefix."db_operitems Set FACT2_NORM:=''");
dbquery("Update ".$db_prefix."db_operitems Set FACT2_NORM_ID:=''");
dbquery("Update ".$db_prefix."db_operitems Set FACT2_ID_2:=''");

$res1 = dbquery("SELECT * FROM ".$db_prefix."db_zadan");
while ($res1_1 = mysql_fetch_array($res1)){

if ($res1_1['EDIT_STATE']==0){
// план количество
$change_id = $res1_1['ID'];
$res1_2 = $res1_1['ID_operitems'];
$res1_3 = $res1_1['NUM'];

$res2 = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID='".$res1_2."')");
$res2_1 = mysql_fetch_array($res2);
$res2_2 = $res2_1['KSZ_NUM'];
$res2_3 = explode("|",$res2_1['KSZ_ID']);
$res2_4 = count($res2_3);
if ($res2_4==1) $res2_4 = 2;
$res2_7 = explode("|",$res2_1['KSZ_ID_NUM']);
$res2_8 = "";
$res2_9 = $res2_1['KSZ_ID'];
$res2_10 = $res2_1['KSZ_ID_NUM'];
$res3_1 = 0;
$res_null = 0;

for ($res2_5=0; $res2_5<($res2_4-1); $res2_5++){
	if ($res2_3[$res2_5]!==$change_id){
		if ($res_null == 0)	$res_null = 1;
	}else{
		
		$res_null = 2;
		$res3_1 = $res2_2 - $res2_7[$res2_5];
		$res3_1 += $res1_3;
		for ($res2_6=0; $res2_6<$res2_5; $res2_6++){
			$res2_8 .= $res2_7[$res2_6]."|";
		}
		$res2_8 .= $res1_3."|";
		for ($res2_6=($res2_5+1); $res2_6<($res2_4-1); $res2_6++){
			$res2_8 .= $res2_7[$res2_6]."|";
		}
		dbquery("Update ".$db_prefix."db_operitems Set KSZ_ID_NUM='".$res2_8."' where (ID='".$res1_2."')");
		dbquery("Update ".$db_prefix."db_operitems Set KSZ_NUM='".$res3_1."' where (ID='".$res1_2."')");
	}
}
if ($res_null == 1){
	$res3 = $res2_2+$res1_3;
	dbquery("Update ".$db_prefix."db_operitems Set KSZ_ID='".$res2_9.$change_id."|' where (ID='".$res1_2."')");
	dbquery("Update ".$db_prefix."db_operitems Set KSZ_ID_NUM='".$res2_10.$res1_3."|' where (ID='".$res1_2."')");
	dbquery("Update ".$db_prefix."db_operitems Set KSZ_NUM='".$res3."' where (ID='".$res1_2."')");
}
// план Н/Ч
$change_id = $res1_1['ID'];
$res1_2 = $res1_1['ID_operitems'];
$res1_3 = $res1_1['NORM'];

$res2 = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID='".$res1_2."')");
$res2_1 = mysql_fetch_array($res2);
$res2_2 = $res2_1['KSZ2_NUM'];
$res2_3 = explode("|",$res2_1['KSZ2_ID']);
$res2_4 = count($res2_3);
if ($res2_4==1) $res2_4 = 2;
$res2_7 = explode("|",$res2_1['KSZ2_ID_NUM']);
$res2_8 = "";
$res2_9 = $res2_1['KSZ2_ID'];
$res2_10 = $res2_1['KSZ2_ID_NUM'];
$res3_1 = 0;
$res_null = 0;

for ($res2_5=0; $res2_5<($res2_4-1); $res2_5++){
	if ($res2_3[$res2_5]!==$change_id){
		if ($res_null == 0)	$res_null = 1;
	}else{
		
		$res_null = 2;
		$res3_1 = $res2_2 - $res2_7[$res2_5];
		$res3_1 += $res1_3;
		for ($res2_6=0; $res2_6<$res2_5; $res2_6++){
			$res2_8 .= $res2_7[$res2_6]."|";
		}
		$res2_8 = $res2_8.$res1_3."|";
		for ($res2_6=($res2_5+1); $res2_6<($res2_4-1); $res2_6++){
			$res2_8 .= $res2_7[$res2_6]."|";
		}
		dbquery("Update ".$db_prefix."db_operitems Set KSZ2_ID_NUM='".$res2_8."' where (ID='".$res1_2."')");
		dbquery("Update ".$db_prefix."db_operitems Set KSZ2_NUM='".$res3_1."' where (ID='".$res1_2."')");
	}
}
if ($res_null == 1){
	$res3 = $res2_2+$res1_3;
	dbquery("Update ".$db_prefix."db_operitems Set KSZ2_ID='".$res2_9.$change_id."|' where (ID='".$res1_2."')");
	dbquery("Update ".$db_prefix."db_operitems Set KSZ2_ID_NUM='".$res2_10.$res1_3."|' where (ID='".$res1_2."')");
	dbquery("Update ".$db_prefix."db_operitems Set KSZ2_NUM='".$res3."' where (ID='".$res1_2."')");
}
}

if ($res1_1['EDIT_STATE']==1){
// факт количество
$change_id = $res1_1['ID'];
$res1_2 = $res1_1['ID_operitems'];
$res1_3 = $res1_1['NUM_FACT'];

$res2 = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID='".$res1_2."')");
$res2_1 = mysql_fetch_array($res2);
$res2_2 = $res2_1['FACT2_NUM'];
$res2_3 = explode("|",$res2_1['FACT2_ID_1']);
$res2_4 = count($res2_3);
if ($res2_4==1) $res2_4 = 2;
$res2_7 = explode("|",$res2_1['FACT2_NUM_ID']);
$res2_8 = "";
$res2_9 = $res2_1['FACT2_ID_1'];
$res2_10 = $res2_1['FACT2_NUM_ID'];
$res3_1 = 0;
$res_null = 0;

for ($res2_5=0; $res2_5<($res2_4-1); $res2_5++){
	if ($res2_3[$res2_5]!==$change_id){
		if ($res_null == 0)	$res_null = 1;
	}else{
		
		$res_null = 2;
		$res3_1 = $res2_2 - $res2_7[$res2_5];
		$res3_1 += $res1_3;
		for ($res2_6=0; $res2_6<$res2_5; $res2_6++){
			$res2_8 .= $res2_7[$res2_6]."|";
		}
		$res2_8 = $res2_8.$res1_3."|";
		for ($res2_6=($res2_5+1); $res2_6<($res2_4-1); $res2_6++){
			$res2_8 .= $res2_7[$res2_6]."|";
		}
		dbquery("Update ".$db_prefix."db_operitems Set FACT2_NUM_ID='".$res2_8."' where (ID='".$res1_2."')");
		dbquery("Update ".$db_prefix."db_operitems Set FACT2_NUM='".$res3_1."' where (ID='".$res1_2."')");
	}
}
if ($res_null == 1){
	$res3 = $res2_2+$res1_3;
	dbquery("Update ".$db_prefix."db_operitems Set FACT2_ID_1='".$res2_9.$change_id."|' where (ID='".$res1_2."')");
	dbquery("Update ".$db_prefix."db_operitems Set FACT2_NUM_ID='".$res2_10.$res1_3."|' where (ID='".$res1_2."')");
	dbquery("Update ".$db_prefix."db_operitems Set FACT2_NUM='".$res3."' where (ID='".$res1_2."')");
}
// факт Н/Ч
$change_id = $res1_1['ID'];
$res1_2 = $res1_1['ID_operitems'];
$res1_3 = $res1_1['NORM_FACT'];

$res2 = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID='".$res1_2."')");
$res2_1 = mysql_fetch_array($res2);
$res2_2 = $res2_1['FACT2_NORM'];
$res2_3 = explode("|",$res2_1['FACT2_ID_2']);
$res2_4 = count($res2_3);
if ($res2_4==1) $res2_4 = 2;
$res2_7 = explode("|",$res2_1['FACT2_NORM_ID']);
$res2_8 = "";
$res2_9 = $res2_1['FACT2_ID_2'];
$res2_10 = $res2_1['FACT2_NORM_ID'];
$res3_1 = 0;
$res_null = 0;

for ($res2_5=0; $res2_5<($res2_4-1); $res2_5++){
	if ($res2_3[$res2_5]!==$change_id){
		if ($res_null == 0)	$res_null = 1;
	}else{
		
		$res_null = 2;
		$res3_1 = $res2_2 - $res2_7[$res2_5];
		$res3_1 += $res1_3;
		for ($res2_6=0; $res2_6<$res2_5; $res2_6++){
			$res2_8 .= $res2_7[$res2_6]."|";
		}
		$res2_8 = $res2_8.$res1_3."|";
		for ($res2_6=($res2_5+1); $res2_6<($res2_4-1); $res2_6++){
			$res2_8 .= $res2_7[$res2_6]."|";
		}
		dbquery("Update ".$db_prefix."db_operitems Set FACT2_NORM_ID='".$res2_8."' where (ID='".$res1_2."')");
		dbquery("Update ".$db_prefix."db_operitems Set FACT2_NORM='".$res3_1."' where (ID='".$res1_2."')");
	}
}
if ($res_null == 1){
	$res3 = $res2_2+$res1_3;
	dbquery("Update ".$db_prefix."db_operitems Set FACT2_ID_2='".$res2_9.$change_id."|' where (ID='".$res1_2."')");
	dbquery("Update ".$db_prefix."db_operitems Set FACT2_NORM_ID='".$res2_10.$res1_3."|' where (ID='".$res1_2."')");
	dbquery("Update ".$db_prefix."db_operitems Set FACT2_NORM='".$res3."' where (ID='".$res1_2."')");
}
}

}
?>