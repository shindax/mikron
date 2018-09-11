<?php
$re_s1 = dbquery("SELECT * FROM okb_db_zakdet where (ID_zak='".$_GET['id']."') AND (PID='0') ");
$na_m1 = mysql_fetch_array($re_s1);

$child_n_ar = array();
$child_n_ar[0] = 1;
$cook_open_all = "";
check_all_tree_dse($na_m1['ID'], $na_m1['PID'], 1);

function check_all_tree_dse($id_par_dse, $pid_par_dse, $child_n){
Global $cook_open_all, $child_n_ar, $total_all_dse;
	$re_s2 = dbquery("SELECT * FROM okb_db_zakdet where (ID_zak='".$_GET['id']."') AND (PID='".$id_par_dse."') ");
	if ($na_m2 = mysql_fetch_array($re_s2)) { 
		$plus = "+";
		$cook_open_all .= "|db_zakdet_39_".$id_par_dse."|";
	}else{ 
		$plus = "";
	}
	$total_all_dse .= $id_par_dse."|";
	//echo $id_par_dse." = ".$pid_par_dse." = ".$child_n." = ".$plus."<br>";
	$re_s2 = dbquery("SELECT * FROM okb_db_zakdet where (ID_zak='".$_GET['id']."') AND (PID='".$id_par_dse."') ");
	while ($na_m2 = mysql_fetch_array($re_s2)){
		if ($na_m2['PID'] == $id_par_dse){
			$child_n_ar[$child_n] = $child_n+1;
		}
		check_all_tree_dse($na_m2['ID'], $na_m2['PID'], $child_n_ar[$child_n], $child_n_ar[$child_n_pr]);
	}
}

//echo $cook_open_all;
//echo $total_all_dse;
echo "<a href='index.php?do=show&formid=39&id=".$_GET['id']."&dse_view=".$cook_open_all."&openall'>Показать всё</a>";
echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href='index.php?do=show&formid=39&id=".$_GET['id']."&dse_view=||&closeall'>Закрыть всё</a>";
echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a target='_blank' href='print.php?do=show&formid=189&id=".$_GET['id']."&p1=".$total_all_dse."'>Печать техпроцесса</a> (чем больше заказ, тем дольше загрузка)<br><br>";
//echo "<script language='javascript'>
//alert (document.cookie);
//</script>";
//setcookie("O_show39", "||", time()+(60*60*24*30));
?>