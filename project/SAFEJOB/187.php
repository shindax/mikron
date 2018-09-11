<<h2 class="text_center margin15">Протоколы проверки знаний </h2>

<table class=my_table>
        <tr>
            <td class=my_td>
                <div class=text_center>№<br>п.п.</div>
            </td>
            <td class=my_td>
                <div class=text_center>ФИО</div>
            </td> 
            <td class=my_td>
                <div class=text_center>Должность</div>
            </td> 
            <td class=my_td>
                <div class=text_center>ОТ</div>
            </td>
            <td class=my_td>
                <div class=text_center>Дата</div>
            </td>
            <td class=my_td>
                <div class=text_center>ПБ</div>
            </td>
            <td class=my_td>
                <div class=text_center>Дата</div>
            </td>
            <td class=my_td>
                <div class=text_center>ЭЛ</div>
            </td>
            <td class=my_td>
                <div class=text_center>Дата</div>
            </td>
            <td class=my_td>
                <div class=text_center>Группа допуска</div>
            </td>
            <td class=my_td>
                <div class=text_center>Дата выдачи</div>
            </td>
        </tr>
    <?php
    // shindax 28.02.2018
    // was okb_db_safety_job.F4_1 should be okb_db_safety_job.F3_1
    $s = dbquery("SELECT (SELECT concat_ws(' ', okb_db_resurs.FF, okb_db_resurs.II, okb_db_resurs.OO) FROM okb_db_resurs WHERE okb_db_safety_job.ID_RESURS=okb_db_resurs.ID), 
okb_db_safety_job.A8_1, okb_db_safety_job.A8_2, okb_db_safety_job.B7_1, okb_db_safety_job.B7_2, okb_db_safety_job.F3_1, 
okb_db_safety_job.F3_2,
(SELECT okb_db_resurs.TID FROM okb_db_resurs WHERE okb_db_safety_job.ID_RESURS=okb_db_resurs.ID),
F5_1,F5_2 , okb_db_special.NAME
 FROM okb_db_safety_job
LEFT JOIN okb_db_resurs ON okb_db_safety_job.ID_RESURS = okb_db_resurs.ID
LEFT JOIN okb_db_special ON okb_db_special.ID = okb_db_resurs.ID_special
 order by (SELECT okb_db_resurs.NAME FROM okb_db_resurs WHERE okb_db_safety_job.ID_RESURS=okb_db_resurs.ID)");
	$max = mysql_result(dbquery("SELECT CAST(okb_db_safety_job.A8_1 as UNSIGNED) as x FROM okb_db_safety_job WHERE A8_1 != ''  ORDER BY x DESC LIMIT 1"), 0);

    $i = 0;
    while ($tr = mysql_fetch_row($s)) {
		if ($tr[7] == '1') {
			continue;
		}
		
		$m = substr($tr[9], 4, 2);
		$d = substr($tr[9], 6, 2);
		$y = substr($tr[9], 0, 4);
		
        $i ++;
		if ($tr[2]!=="0") $tr[2]=substr($tr[2],6,2).".".substr($tr[2],4,2).".".substr($tr[2],0,4);
		if ($tr[4]!=="0") $tr[4]=substr($tr[4],6,2).".".substr($tr[4],4,2).".".substr($tr[4],0,4);
		if ($tr[6]!=="0") $tr[6]=substr($tr[6],6,2).".".substr($tr[6],4,2).".".substr($tr[6],0,4);
		if ($tr[2]=="0") $tr[2]="";
		if ($tr[4]=="0") $tr[4]="";
		if ($tr[6]=="0") $tr[6]="";
		
		
        echo "<tr " . ((int) $tr[3] == $max ? ' style="background-color:#ccc;color:red"' : '') . ">
            <td class=my_td>
                <div class=text_center>".$i."</div>
            </td>
            <td class=my_td>
                <div class=text_left>".$tr[0]."</div>
            </td>
            <td class=my_td>
                <div class=text_left>".$tr[10]."</div>
            </td>
            <td class=my_td>
                <div class=text_center>".$tr[1]."</div>
            </td>
            <td class=my_td>
                <div class=text_center>".$tr[2]."</div>
            </td>
            <td class=my_td >
                <div class=text_center>".$tr[3]."</div>
            </td>
            <td class=my_td>
                <div class=text_center>".$tr[4]."</div>
            </td>
            <td class=my_td>
                <div class=text_center>".str_replace('эл', '-эл', $tr[5])."</div>
            </td>
            <td class=my_td>
                <div class=text_center>".$tr[6]."</div>
            </td>
            <td class=my_td>
                <div class=text_center>".$tr[8]."</div>
            </td>
            <td class=my_td>
                <div class=text_center>". ($tr[9] != 0 ? $d . '.' . $m . '.' . $y : '') . "</div>
            </td>
        </tr>";
    }
    ?>
</table>

<style>
    .my_table {
        width: 100%;
    }
</style>