<?php
$d1 = $_GET[dt_1];
$d2 = $_GET[dt_2];
if ($d1 === null || $d1 === null) {
    $source_date_from = strtotime($_POST['date_from']);
    $source_date_to = strtotime($_POST['date_to']);
    $id_resurs = $_POST['resurs_name'];
} else {
    $source_date_from = $d1;
    $source_date_to = $d2;
    $id_resurs = $id_r;
}

$date_from = date("Ymd", $source_date_from);
$date_to = date("Ymd", $source_date_to);
$d1 = date("d.m.Y", $source_date_from);
$d2 = date("d.m.Y", $source_date_to);
if ($d1 == '01.01.1970') {
    $d1 = '';
    $d2 = '';
}
?>

<h2 class="text_center margin15">Отчёт о рабочем времени за период c <?php echo $d1; ?> по <?php echo $d2; ?></h2>

<form action="index.php?do=show&formid=181" method="post">
    <div class=text_center><label for="date-from">С: </label>
        <input type=date name="date_from" id="date-from">
        <label for="date-to"> По: </label>
        <input type=date name="date_to" id="date-to">
        <input type=submit value="Построить">
        <?php if ($d2 != '') {
            echo "<a class=a_button href='print.php?do=show&formid=181&dt_1=" . $source_date_from . "&dt_2=" . $source_date_to . "' target='_blank'> Распечатать результат </a></div>";
        } ?>
    </div>
</form>

<br>

<table class=my_table>
    <?php
    $n = 0;
    if ($date_from != $date_to) {
        ?>
        <tr>
            <td class=my_td>
                <div class=text_center>Дата</div>
            </td>
            <td class=my_td>
                <div class=text_center>Количество<br>человек</div>
            </td>
            <td class=my_td>
                <div class=text_center>Фонд раб.<br>времени, час.</div>
            </td>
            <td class=my_td>
                <div class=text_center>Факт. раб время,<br>час.</div>
            </td>
            <td class=my_td>
                <div class=text_center>Опоздания,<br>час.</div>
            </td>
            <td class=my_td>
                <div class=text_center>Переработки,<br>час.</div>
            </td>
            <td class=my_td>
                <div class=text_center>Недоработки,<br>час.</div>
            </td>
            <td class=my_td>
                <div class=text_center>ФактРВ/ФондРВ</div>
            </td>
        </tr>
        <?php
        $s = dbquery("SELECT
            v.*, get_s_from_hours (v.LATENESS) AS S_LATENESS,
            get_s_from_hours (v.PROCESSING) AS S_PROCESSING,
            get_s_from_hours (v.SHORTCOMING) AS s_SHORTCOMING
        FROM
            okb_db_v_sca_work_time_on_period v
        WHERE
            v.DATE BETWEEN " . $date_from . " AND " . $date_to);
        while ($tr = mysql_fetch_array($s)) {
            echo "<tr><td class=my_td><div class=text_center><a class=a_button href='index.php?do=show&formid=182&p0=" . strtotime($tr[1]) . "' target='_blank'>" . $tr[1] . "</a></div></td><td class=my_td><div class=text_center>" . $tr[2] . "</div></td><td class=my_td><div class=text_center>" . $tr[3] . "</div></td><td class=my_td><div class=text_center>" . $tr[4] . "</div></td><td class=my_td><div class=text_center>" . $tr[5] . " (" . $tr[10] . ")</div></td><td class=my_td><div class=text_center>" . $tr[6] . " (" . $tr[11] . ")</div></td><td class=my_td><div class=text_center>" . $tr[7] . " (" . $tr[12] . ")</div></td><td class=my_td><div class=text_center>" . $tr[8] . "</div></td></tr>";
            $n = 1;
        }
        if ($n == 1) {
            $s = dbquery("SELECT
            1,
            round(avg(v.RESURS_COUNT),0) AS AVG_RES,
            SUM(v.FOND_TIME) AS SUM_FOND,
            SUM(v.FACT_TIME) AS SUM_FACT,
            SUM(v.LATENESS) AS SUM_LATENES,
            SUM(v.PROCESSING) AS SUM_PROCESSING,
            SUM(v.SHORTCOMING) AS SUM_SHORTCOMING,
            round((avg(v.FACT_DIV_FOND)),2) AS AVG_F_D_F
        FROM
            okb_db_v_sca_work_time_on_period v
        WHERE
            v.DATE BETWEEN " . $date_from . " AND " . $date_to . " GROUP BY 1");
            $tr = mysql_fetch_array($s);
            echo "<tr><td class=my_td><div class=text_center>Итог</div></td><td class=my_td><div class=text_center>" . $tr[1] . "</div></td><td class=my_td><div class=text_center>" . $tr[2] . "</div></td><td class=my_td><div class=text_center>" . $tr[3] . "</div></td><td class=my_td><div class=text_center>" . $tr[4] . "</div></td><td class=my_td><div class=text_center>" . $tr[5] . "</div></td><td class=my_td><div class=text_center>" . $tr[6] . "</div></td><td class=my_td><div class=text_center>" . $tr[7] . "</div></td></tr>";
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
</style>