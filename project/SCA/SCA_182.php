<?php
$link = $_GET[p0];
if ($link === null || $_POST['date_to'] != null ) {
    $source_date = strtotime($_POST['date_to']);
} else {
    $source_date = $link;
}
$date_to = date("Ymd", $source_date);
$d2 = date("d.m.Y", $source_date);
if ($d2 == '01.01.1970') {
    $d2 = '';
}
?>

<h2 class="text_center margin15">Отчёт о рабочем времени за <?php echo $d2; ?></h2>

<form action="index.php?do=show&formid=181" method="post">
    <div class=text_center>
        <input type=date name="date_to" id="date-to">
        <input type=submit value="Показать">
        <?php if ($d2 != '') {
            echo "<a class=a_button href='print.php?do=show&formid=182&p0=" . $source_date . "' target='_blank'> Распечатать результат </a>";
        } ?>
    </div>
</form>

<br>
<table class=my_table>
    <?php
    $n = 0;
    if ($d2 != '') {
        ?>
        <tr>
            <td class=my_td>
                <div class=text_center>№<br>п.п.</div>
            </td>
            <td class=my_td>
                <div class=text_center>Фамилия И.О.</div>
            </td>
            <td class=my_td>
                <div class=text_center>Факт. раб.<br>время, час.</div>
            </td>
            <td class=my_td>
                <div class=text_center>Вход</div>
            </td>
            <td class=my_td>
                <div class=text_center>Выход</div>
            </td>
            <td class=my_td>
                <div class=text_center>Опоздание,<br>час.</div>
            </td>
            <td class=my_td>
                <div class=text_center>Переработка,<br>час.</div>
            </td>
            <td class=my_td>
                <div class=text_center>Недоработка,<br>час.</div>
            </td>
            <td class=my_td>
                <div class=text_center>Факт РВ/<br>Фонд РВ</div>
            </td>
        </tr>
        <?php
        $s = dbquery("SELECT
                            v.*, get_s_from_hours (v.WORK_TIME) AS S_WORK_TIME,
                            get_s_from_hours (v.LATENESS) AS S_LATENESS,
                            get_s_from_hours (v.PROCESSING) AS S_PROCESSING,
                            get_s_from_hours (v.SHORTCOMING) AS s_SHORTCOMING
                        FROM okb_db_v_sca_time_report v where v.date = " . $date_to);
        $i = 0;
        while ($tr = mysql_fetch_array($s)) {
            $i ++;
            #<div class=text_center>" . $tr[4] . " (" . $tr[11] . ")</div>
            echo "<tr>
                    <td class=my_td>
                        <div class=text_center>".$i."</div>
                    </td>
                    <td class=my_td>
                        <div class=text_left>" . $tr[0] . "</div>
                    </td>
                    <td class=my_td>
                        <div class='text_center ".(($tr[4] < $tr[11]) ? "attention" : " ")."'>" . $tr[4] . " (" . $tr[12] . ")</div>
                    </td>
                    <td class=my_td>
                        <div class='text_center ".(($tr[5] == 'нет') ? "attention" : " ")."'>" . $tr[5] . "</div>
                    </td>
                    <td class=my_td>
                        <div class='text_center ".(($tr[6] == 'нет') ? "attention" : " ")."'>" . $tr[6] . "</div>
                    </td>
                    <td class=my_td>
                        <div class='text_center ".(($tr[7] > 0) ? "attention" : " ")."'>" . $tr[7] . " (" . $tr[13] . ")</div>
                    </td>
                    <td class=my_td>
                        <div class='text_center ".(($tr[8] > 0) ? "good_boy" : " ")."'>" . $tr[8] . " (" . $tr[14] . ")</div>
                    </td>
                    <td class=my_td>
                        <div class='text_center ".(($tr[9] > 0) ? "attention" : " ")."'>" . $tr[9] . " (" . $tr[15] . ")</div>
                    </td>
                    <td class=my_td>
                        <div class=text_center>" . $tr[10] . "</div>
                    </td>
                </tr>";
            $n = 1;
        }
        if ($n == 1) {
            # отдельно вычисляется итоговый коэфициент, т.к. в ведомости нет тех кто не отмечается
            $k = dbquery("SELECT v.FACT_DIV_FOND FROM okb_db_v_sca_work_time_on_period v WHERE v.DATE = ".$date_to);
            $k = mysql_fetch_array($k);
            $k = $k[0];

            $s = dbquery("SELECT 1, sum(v.work_time), sum(v.lateness), sum(v.processing), sum(v.shortcoming), round(avg(v.fact_div_8),2) FROM okb_db_v_sca_time_report v WHERE v.DATE = " . $date_to . " GROUP BY 1");
            $tr = mysql_fetch_array($s);
            echo "<tr>
                    <td class=my_td colspan='2'>
                        <div class=text_center>Итог</div>
                    </td>
                    <td class=my_td>
                        <div class=text_center>" . $tr[1] . "</div>
                    </td>
                    <td class=my_td colspan='2'>
                        <div class=text_center></div>
                    </td>
                    <td class=my_td>
                        <div class=text_center>" . $tr[2] . "</div>
                    </td>
                    <td class=my_td>
                        <div class=text_center>" . $tr[3] . "</div>
                    </td>
                    <td class=my_td>
                        <div class=text_center>" . $tr[4] . "</div>
                    </td>
                    <td class=my_td>
                        <div class=text_center>" . $k . "</div>
                    </td>
                </tr>";
        }

    }
    ?>
</table>

<script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
<script>
    webshims.setOptions('forms-ext', {types: 'date'});
    webshims.polyfill('forms forms-ext');
</script>
<style>
    .my_table {
        width: 100%;
    }

    .my_table tr:last-child {
        background: #98b8e2;
    }

    .attention { background: coral; font-weight: bold;}
    .good_boy {background: palegreen; font-weight: bold;}
</style>