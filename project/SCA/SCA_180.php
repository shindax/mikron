<?php
$link = $_GET[p0];
if ($link === null) {
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

<h2 class="text_center margin15">Ежедневная сводка СКД за <?php echo $d2; ?></h2>

<form action="index.php?do=show&formid=181" method="post">
    <div class=text_center>
        <input type=date name="date_to" id="date-to">
        <input type=submit value="Показать">
        <?php if ($d2 != '') {
            echo "<a class=a_button href='print.php?do=show&formid=180&p0=" . $source_date . "' target='_blank'> Распечатать результат </a>";
        } ?>
    </div>
</form>

<?php
$n = 0;
if ($d2 != '') {
?>
<br>
<table>
    <tr><td class=table_align_top>
<h2 class="text_left margin15">Опоздания</h2>
<table class=my_table>
    <tr>
        <td class=my_td>
            <div class=text_center>№<br>п.п.</div>
        </td>
        <td class=my_td>
            <div class=text_center>Фамилия И.О.</div>
        </td>
        <td class=my_td>
            <div class=text_center>Время опоздания,<br>час.</div>
        </td>
    </tr>
    <?php
    $n = 1;
    $s = dbquery("SELECT r.NAME, s.LATENESS, get_s_from_hours(s.LATENESS) FROM okb_db_sca_tabel s, okb_db_resurs r WHERE s.DATE = " . $date_to . " AND s.LATENESS != 0 AND r.id = s.ID_RESURS ORDER BY 1");
    while ($tr = mysql_fetch_array($s)) {
        echo "<tr><td class=my_td><div class=text_center>" . $n . "</div></td><td class=my_td><div class=text_left>" . $tr[0] . "</div></td><td class=my_td><div class=text_center>" . $tr[1] . " (" . $tr[2] . ")</div></td></tr>";
        $n++;
    }
    ?>
</table>
</td>
        <td>  </td>
<td class=table_align_top>

<h2 class="text_left margin15">Переработки</h2>
<table class=my_table>
    <tr>
        <td class=my_td>
            <div class=text_center>№<br>п.п.</div>
        </td>
        <td class=my_td>
            <div class=text_center>Фамилия И.О.</div>
        </td>
        <td class=my_td>
            <div class=text_center>Время переработки,<br>час.</div>
        </td>
    </tr>
    <?php
    $n = 1;
    $s = dbquery("SELECT r.NAME, s.PROCESSING, get_s_from_hours(s.PROCESSING) FROM okb_db_sca_tabel s, okb_db_resurs r WHERE s.DATE = " . $date_to . " AND s.PROCESSING != 0 AND r.id = s.ID_RESURS ORDER BY 1");
    while ($tr = mysql_fetch_array($s)) {
        echo "<tr><td class=my_td><div class=text_center>" . $n . "</div></td><td class=my_td><div class=text_left>" . $tr[0] . "</div></td><td class=my_td><div class=text_center>" . $tr[1] . " (" . $tr[2] . ")</div></td></tr>";
        $n++;
    }
    ?>
</table>
</td>
    <td>  </td>
<td class=table_align_top>

<h2 class="text_left margin15">Недоработки</h2>
<table class=my_table>
    <tr>
        <td class=my_td>
            <div class=text_center>№<br>п.п.</div>
        </td>
        <td class=my_td>
            <div class=text_center>Фамилия И.О.</div>
        </td>
        <td class=my_td>
            <div class=text_center>Время недоработки,<br>час.</div>
        </td>
    </tr>
    <?php
    $n = 1;
    $s = dbquery("SELECT r.NAME, s.SHORTCOMING, get_s_from_hours(s.SHORTCOMING) FROM okb_db_sca_tabel s, okb_db_resurs r WHERE s.DATE = " . $date_to . " AND s.SHORTCOMING != 0 AND r.id = s.ID_RESURS ORDER BY 1");
    while ($tr = mysql_fetch_array($s)) {
        echo "<tr><td class=my_td><div class=text_center>" . $n . "</div></td><td class=my_td><div class=text_left>" . $tr[0] . "</div></td><td class=my_td><div class=text_center>" . $tr[1] . " (" . $tr[2] . ")</div></td></tr>";
        $n++;
    }
    ?>
</table>
</td></tr>



    <tr><td class=table_align_top>
<h2 class="text_left margin15">Не отмечен "Вход"</h2>
<table class=my_table>
    <tr>
        <td class=my_td>
            <div class=text_center>№<br>п.п.</div>
        </td>
        <td class=my_td>
            <div class=text_center>Фамилия И.О.</div>
        </td>
    </tr>
    <?php
    $n = 1;
    $s = dbquery("SELECT r.NAME FROM okb_db_sca_tabel s, okb_db_resurs r WHERE s.DATE = " . $date_to . " AND s.TIME_IN = 'нет' AND r.id = s.ID_RESURS ORDER BY 1");
    while ($tr = mysql_fetch_array($s)) {
        echo "<tr><td class=my_td><div class=text_center>" . $n . "</div></td><td class=my_td><div class=text_left>" . $tr[0] . "</div></td></tr>";
        $n++;
    }
    ?>
</table>
        </td>
        <td>  </td>
        <td class=table_align_top>
<h2 class="text_left margin15">Не отмечен "Выход"</h2>
<table class=my_table>
    <tr>
        <td class=my_td>
            <div class=text_center>№<br>п.п.</div>
        </td>
        <td class=my_td>
            <div class=text_center>Фамилия И.О.</div>
        </td>
    </tr>
    <?php
    $n = 1;
    $s = dbquery("SELECT r.NAME FROM okb_db_sca_tabel s, okb_db_resurs r WHERE s.DATE = " . $date_to . " AND s.TIME_OUT = 'нет' AND r.id = s.ID_RESURS ORDER BY 1");
    while ($tr = mysql_fetch_array($s)) {
        echo "<tr><td class=my_td><div class=text_center>" . $n . "</div></td><td class=my_td><div class=text_left>" . $tr[0] . "</div></td></tr>";
        $n++;
    }
    ?>
</table>
        </td>
        <td>  </td>
        <td class=table_align_top>
<h2 class="text_left margin15">Не отметились</h2>
<table class=my_table>
    <tr>
        <td class=my_td>
            <div class=text_center>№<br>п.п.</div>
        </td>
        <td class=my_td>
            <div class=text_center>Фамилия И.О.</div>
        </td>
    </tr>
    <?php $n = 1;
    $s = dbquery("SELECT
                    r. NAME
                FROM
                    okb_db_resurs r
                WHERE
                    r.ID_CARD > 0
                AND " . $date_to . " BETWEEN r.DATE_FROM AND IF ( r.DATE_TO = 0, 20180125, r.DATE_TO )
                AND r.ID NOT IN (
                    SELECT
                        s.ID_RESURS
                    FROM
                        okb_db_sca_tabel s
                    WHERE
                        s.DATE = " . $date_to . "
                )
                AND r.ID IN (
                    SELECT
                        rr.id
                    FROM
                        okb_db_tabel tt,
                        okb_db_resurs rr
                    WHERE
                        tt.ID_resurs = rr.id
                    AND rr.ID_CARD > 0
                    AND tt.DATE = " . $date_to . "
                    AND tt.PLAN > 0
                )
                ORDER BY
                    1");
    while ($tr = mysql_fetch_array($s)) {
        echo "<tr><td class=my_td><div class=text_center>" . $n . "</div></td><td class=my_td><div class=text_left>" . $tr[0] . "</div></td></tr>";
        $n++;
    } ?>
</table>

</table>
</td></tr>
</table>

<?php } ?>

<script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
<script>
    webshims.setOptions('forms-ext', {types: 'date'});
    webshims.polyfill('forms forms-ext');
</script>