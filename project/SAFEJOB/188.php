<?php
$id_resurs = $_GET[p0];

$s = dbquery("select s.MORE from okb_db_resurs r, okb_db_special s where r.id = '".$id_resurs."' and s.ID = r.ID_special");
$tr = mysql_fetch_array($s);
$s = $tr[0];

if ($s === null)
{
    $s = '';
}

/*
// разбиваем по словам
    $words = preg_split("/;+/s",$s);

// выводим результаты
    print_r($words);
*/
$ss = dbquery("UPDATE okb_db_safety_job s set s.D1_1 = '".$s."' where s.ID_RESURS = ".$id_resurs);

header("Location: http://".$_SERVER['SERVER_NAME']."/index.php?do=show&formid=160&p0=215");

?>