<?php

function GetZakInfo( $id )
{
	global $db_cfg;

	$result = dbquery("SELECT * FROM okb_db_zak where ID = $id");
	
	if ($zak = mysql_fetch_array($result)) 
	{

		$result = dbquery("SELECT * FROM okb_db_zakdet where ID_zak = $id and PID=0");
		$zakdet = mysql_fetch_array($result);
		$count = $zak["DSE_COUNT"];
		$oper_N_arr = [];

		$operitems = dbquery("SELECT * FROM okb_db_operitems where ID_zak=$id");
		while ($oper = mysql_fetch_array($operitems)) 
			$oper_N_arr[$oper["ID_oper"]] += $oper["NORM_ZAK"];

		$summ_N = [];
		
		$opers = dbquery("SELECT * FROM okb_db_oper");
		while ($oper = mysql_fetch_array($opers)) 
			$summ_N[$oper["TID"]] = $summ_N[$oper["TID"]] + $oper_N_arr[$oper["ID"]];

		$tids = explode("|","|".$db_cfg["db_oper/TID|LIST"]);

		$sn = 0;
		$tids_count = count($tids);

		for ( $i=0; $i < $tids_count; $i ++ )
				$sn += $summ_N[$i];
	}

	return $sn;
}
