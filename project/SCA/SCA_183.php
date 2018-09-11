<?php
$id_r = $_GET[id_r];
$d1 = $_GET[dt_1];
$d2 = $_GET[dt_2];
if ($id_r === null) {
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

if ($id_resurs == 0 && $id_resurs === null) {
    $name = "Фамилия И.О.";
} else {
    $s = dbquery("SELECT r.NAME FROM okb_db_resurs r WHERE r.ID = ".$id_resurs);
    $tr = mysql_fetch_array($s);
    $name = $tr[0];
}

?>

<h2 class="text_center margin15">Табель (факт) <?php echo $name; ?> за период с <?php echo $d1; ?>
    по <?php echo $d2; ?></h2>

<form action="index.php?do=show&formid=181" method="post">
    <div class=text_center>
        <select name="resurs_name">
            <option selected value="0">Сотрудник</option>
            <?php
            $s = dbquery("SELECT r.ID, r.NAME FROM okb_db_resurs r WHERE r.ID_CARD > 0 ORDER BY 2");
            while ($tr = mysql_fetch_array($s)) {
                echo "<option value=" . $tr[0] . ">" . $tr[1] . "</option>";
            }
            ?>
        </select>
        <label for="date-from">С: </label>
        <input type=date name="date_from" id="date-from" value="<?php echo $_POST['date_from'];?>">
        <label for="date-to"> По: </label>
        <input type=date name="date_to" id="date-to" value="<?php echo $_POST['date_to'];?>">
        <input type=submit value="Показать">
        <?php if ($d2 != '') {
            echo "<a class=a_button href='print.php?do=show&formid=183&id_r=" . $id_resurs . "&dt_1=" . $source_date_from . "&dt_2=" . $source_date_to . "' target='_blank'> Распечатать результат </a></div>";
        } ?>
    </div>
</form>

<br>
<table class=my_table>
    <?php
    $n = 0;
    if ($d1 != '') {
        ?>
        <tr>
            <td class=my_td>
                <div class=text_center>Дата</div>
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
                <div class=text_center>Факт РВ/8</div>
            </td>
        </tr>
        <?php
        $s = dbquery("SELECT v.*,
                        get_s_from_hours (v.WORK_TIME) AS S_WORK_TIME,
                        get_s_from_hours (v.LATENESS) AS S_LATENESS,
                        get_s_from_hours (v.PROCESSING) AS S_PROCESSING,
                        get_s_from_hours (v.SHORTCOMING) AS s_SHORTCOMING
                      FROM okb_db_v_sca_time_report v WHERE v.ID_RESURS = " . $id_resurs . " AND v.DATE BETWEEN " . $date_from . " AND " . $date_to);
        while ($tr = mysql_fetch_array($s)) {
            echo "<tr>
                    <td class=my_td>
                        <div class=text_center>" . $tr[3] . "</div>
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
            $s = dbquery("SELECT 1, sum(v.work_time), sum(v.lateness), sum(v.processing), sum(v.shortcoming), round(avg(v.fact_div_8),2) FROM okb_db_v_sca_time_report v WHERE v.ID_RESURS = " . $id_resurs . " AND v.DATE BETWEEN " . $date_from . " AND " . $date_to . " GROUP BY 1");
            $tr = mysql_fetch_array($s);
            echo "<tr>
                    <td class=my_td>
                        <div class=text_center>Итог</div>
                    </td>
                    <td class=my_td>
                        <div class=text_center>" . $tr[1] . "</div>
                    </td>
                    <td class=my_td>
                        <div class=text_center></div>
                    </td>
                    <td class=my_td>
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
                        <div class=text_center>" . $tr[5] . "</div>
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