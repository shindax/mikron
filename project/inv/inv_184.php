<?php
//error_reporting(E_ERROR);
$id_r = $_GET[id_resurs];

$ss = dbquery("SELECT v.* FROM okb_db_v_inventory_for_system_admin v WHERE v.ID_RESURS = '" . $id_r . "'");
$name = mysql_fetch_array($ss);
?>

<h2 class="text_center margin15">Карточка учёта ТМЦ
      <?php echo "<a class='a_button prnt' href='print.php?do=show&formid=184&id_resurs=" . $id_r . "' target='_blank'> &#9997;  </a>"; ?></h2>
<div class='text_left bold'>             Я, <?php echo $name['FIO']; ?>, принял для использования в работе:</div>

<table class=my_table>
    <?php
    $s = dbquery("SELECT v.* FROM okb_db_v_inventory_for_system_admin v WHERE v.ID_RESURS = '" . $id_r . "'");
    $current_group = 0;
    $last_group = 0;
    $x = 0;
    while ($tr = mysql_fetch_array($s)) {
        $current_group = $tr['TYPE'];
        if ($current_group != $last_group && $current_group != 6) {
            echo "<tr  class='my_type'> <td colspan=5><div class='text_left bold'>" . $tr['TYPE_NAME'] . "</div></td> </tr>";
        }
        $last_group = $tr['TYPE'];
        if ($tr['TYPE'] == 1) {
            # Системный блок
            echo "<tr>
                        <td rowspan=4>                
                        </td>
                        <td class=my_td>
                            <div class='text_right bold'>Инвентарный №: </div>
                        </td>
                        <td class=my_td>
                            <div class='text_center text'> " . $tr['S_INV_N'] . "</div>
                        </td>
                        <td class=my_td>
                            <div class='text_right bold'>Процессор: </div>
                        </td>
                        <td class=my_td>
                            <div class='text_center text'> " . $tr['MODEL_AND_PROCESSOR'] . "</div>
                        </td>
                    </tr>
                    <tr>
                        <td class=my_td>
                            <div class='text_right bold'>RAM: </div>
                        </td>
                        <td class=my_td>
                            <div class='text_center text'> " . $tr['OZU'] . "</div>
                        </td>
                        <td class=my_td>
                            <div class='text_right bold'>Видеокарта: </div>
                        </td>
                        <td class=my_td>
                            <div class='text_center text'> " . $tr['VIDEO'] . "</div>
                        </td>
                    </tr>
                    <tr>
                        <td class=my_td>
                            <div class='text_right bold'>HDD: </div>
                        </td>
                        <td class=my_td>
                            <div class='text_center text'> " . $tr['HDD'] . "</div>
                        </td>
                        <td class=my_td>
                            <div class='text_right bold'>Пломба №: </div>
                        </td>
                        <td class=my_td>
                            <div class='text_center text'> " . $tr['S_PLOMB'] . "</div>
                        </td>
                    </tr>
                    <tr>
                        <td class=my_td>
                            <div class='text_right bold'>Дата последнего ТО: </div>
                        </td>
                        <td class=my_td>
                            <div class='text_center text'> " . (($tr['DT_LAST_TO'] == 0) ? " -" : $tr['S_DT_LAST_TO']) . "</div>
                        </td>
                        <td class=my_td>
                            <div class='text_right bold'>Дата инвентаризации: </div>
                        </td>
                        <td class=my_td>
                            <div class='text_center text'> " . (($tr['DT_INVENTORY'] == 0) ? " -" : $tr['S_DT_INVENTORY']) . "</div>
                        </td>
                    </tr>";
        }
        if ($current_group == 7 && $x == 0) {
            $x = 1;
            if ($name['ID_CARD'] > 0) {
                echo "<tr>
                        <td>        
                        </td>
                        <td class=my_td>
                            <div class='text_right bold'>Карточка СКД №: </div>
                        </td>
                        <td class=my_td colspan='3'>
                            <div class='text_center text'> " . $name['ID_CARD'] . "</div>
                        </td>
                    </tr>";
            }
        }
        if ($tr['TYPE'] != 6 && $tr['TYPE'] != 1) {
            # Пропустить ПО а для всех остальных "Название + инв номер"
            echo "<tr>
                        <td>        
                        </td>
                        <td class=my_td>
                            <div class='text_right bold'>" . $tr['NAME'] . ": </div>
                        </td>
                        <td class=my_td>
                            <div class='text_center text'> " . $tr['MODEL_AND_PROCESSOR'] . "</div>
                        </td>
                        <td class=my_td>
                            <div class='text_right bold'>Инвентарный №: </div>
                        </td>
                        <td class=my_td>
                            <div class='text_center text'> " . $tr['S_INV_N'] . "</div>
                        </td>
                    </tr>";
        }
    }
    ?>
</table>
<table class=my_table>
    <tr class='my_type'>
        <td colspan=7>
            <div class='text_left bold'>Программное обеспечение:</div>
        </td>
    </tr>
    <tr>
        <td rowspan=7>        
        </td>
        <td colspan="2">
            <div class='text_left text'><a class='a_button'> </a> MS Windows</div>
        </td>
        <td colspan="2">
            <div class='text_left text'><a class='a_button'> </a> Adobe reader</div>
        </td>
        <td colspan="2">
            <div class="my_td td_underline">                         </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class='text_left text'><a class='a_button'> </a> MS Office</div>
        </td>
        <td colspan="2">
            <div class='text_left text'><a class='a_button'> </a> 2Gis</div>
        </td>
        <td colspan="2">
            <div class="my_td td_underline"> </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class='text_left text'><a class='a_button'> </a> MS Project</div>
        </td>
        <td colspan="2">
            <div class='text_left text'><a class='a_button'> </a> Спрут-ТП</div>
        </td>
        <td colspan="2">
            <div class="my_td td_underline"> </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class='text_left text'><a class='a_button'> </a> MS Visio</div>
        </td>
        <td colspan="2">
            <div class='text_left text'><a class='a_button'> </a> 3D Max</div>
        </td>
        <td colspan="2">
            <div class="my_td td_underline"> </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class='text_left text'><a class='a_button'> </a> T-flex</div>
        </td>
        <td colspan="2">
            <div class='text_left text'><a class='a_button'> </a> Photoshop CC</div>
        </td>
        <td colspan="2">
            <div class="my_td td_underline"> </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class='text_left text'><a class='a_button'> </a> Компас 3D</div>
        </td>
        <td colspan="2">
            <div class='text_left text'><a class='a_button'> </a> T-flex viewer</div>
        </td>
        <td colspan="2">
            <div class="my_td td_underline"> </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class='text_left text'><a class='a_button'> </a> SolidWorks</div>
        </td>
        <td colspan="2">
            <div class='text_left text'><a class='a_button'> </a> SVN-клиент</div>
        </td>
        <td colspan="2">
            <div class="my_td td_underline"> </div>
        </td>
    </tr>
    <tr class='my_type'>
        <td colspan=7>
            <div class='text_left bold'>Примечание</div>
        </td>
    </tr>
    <tr>
        <td rowspan=4>     
        </td>
        <td colspan=6 class="my_td td_underline"> 
        </td>
    </tr>
    <tr>
        <td colspan=6 class="my_td td_underline"> 
        </td>
    </tr>
    <tr>
        <td colspan=6 class="my_td td_underline"> 
        </td>
    </tr>
    <tr>
        <td colspan=6 class="my_td td_underline"> 
        </td>
    </tr>
    <tr>
        <td> </td>
    </tr>
    <tr>
        <td> </td>
    </tr>
    <tr>
        <td> </td>
    </tr>
    <tr>
        <td> </td>
    </tr>
    <tr>
        <td colspan=6> 
            <div class='text_right text'>Подпись: </div>
        </td>
        <td class="my_td td_underline"> 
        </td>
    </tr>
    <tr>
        <td colspan=6> 
            <div class='text_right text'>Дата: </div>
        </td>
        <td class="my_td td_underline"> 
        </td>
    </tr>
</table>

<style>
    .my_table {
        width: 100%;
    }

    .text {
        font-size: 1.3em;
    }

    .prnt {
        font-size: 0.9em;
    }

    .bold {
        font-size: 1.5em;
        font-weight: bold;
    }

    .td_underline {
        border: 0;
        border-bottom: 1px solid;
    }

    .my_type {
        background: #98b8e2;
    }
</style>