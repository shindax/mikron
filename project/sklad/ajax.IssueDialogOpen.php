<?php
error_reporting( 0 );
require_once( "functions.php" );
global $pdo;

$id = $_POST['id'];
$count = $_POST['count'];
$pattern = trim( $_POST['pattern'] );
$arr = [];
$id_zakdet = $_POST['id_zakdet'];
$operation_id = $_POST['operation_id'];

$wh_struct = get_warehouse_structure();
$wh = $wh_struct["wh"];
$cells = $wh_struct["cells"];
$tiers = $wh_struct["tiers"];
$arr = getLocationOfDSEAtWarehouse( $id_zakdet, $operation_id, $id, $pattern);

$line = 1 ;

$str = "<h3>".conv( "Необходимо выдать детали в количестве $count шт. Данные детали хранятся на складе на следующих позициях:" )."</h3>";
$str .= "<table class='tbl reserve' data-count='$count'>";

$str .= "<col width='2%' />";
$str .= "<col width='10%' />";
$str .= "<col width='5%' />";
$str .= "<col width='5%' />";
$str .= "<col width='5%' />";
$str .= "<col width='5%' />";


$str .= "<tr class='first'>
        <td class = 'AC'>".conv("№")."</td>
        <td class = 'AC'>".conv("Склад")."</td>
        <td class = 'AC'>".conv("Ячейка")."</td>
        <td class = 'AC'>".conv("Ярус")."</td>
        <td class = 'AC'>".conv("Кол.")."</td>        
        <td class = 'AC'>".conv("Выдать шт.")."</td>
        </tr>";


foreach( $arr AS $key => $value )
{
 foreach( $value['storage_place'] AS $svalue )
 {
    $str .= "<tr data-inv-id='$key' 
                data-id-zakdet='".$value['id_zakdet']."' 
                data-warehouse-detiitem-record-id='".$svalue['id']."'>
            <td class = 'AC field'>".$line ++."</td>
            <td class = 'AC field'><span class='wh' data-id='".$svalue['wh']."'>".$wh[ $svalue['wh']]."</span></td>
            <td class = 'AC field'><span class='cell' data-id='".$svalue['cell']."'>".$cells[ $svalue['cell']]."</span></td>
            <td class = 'AC field'><span class='tier' data-id='".$svalue['tier']."'>".$tiers[ $svalue['tier']]."</span></td>
            <td class = 'AC field'><span class='count'>".$svalue['count']."</span></td>
            <td class = 'AC field'><input class='give_out_input' type='number' /></td>
            </tr>";
 }
}

$str .= "<tr>
        <td class = 'AC field' colspan='5'>".conv("Итого :")."</td>
        <td class = 'AC field'><span class='total_give_out'>0</span></td>
        </tr>";

$str .= "</table>";

echo $str;

