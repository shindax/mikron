<?php
$res1 = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (ID='".$change_id."')");
$res1_1 = mysql_fetch_array($res1);
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

		//dbquery("Update ".$db_prefix."db_operitems Set KSZ_NUM='".($res2_4-1)." = ".$res2_3[$res2_5]." = ".$res1_3."' where (ID='4866')");

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
?>