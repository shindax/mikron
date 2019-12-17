<link rel='stylesheet' href='/project/plan_mismatch/css/style.css'>
<?php
error_reporting( E_ERROR );
// error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( "functions.php" );
require_once( "getZakInfo.php" );
require_once( "getKrz2Info.php" );

function conv( $str )
{
	return iconv( "UTF-8", "Windows-1251",  $str );
}

function mismatchSort($a, $b) 
{
	if( $a['edit_state'] == $b['edit_state'] )
	{
		if( $a['percent'] == $b['percent'] )	
			return 0;

		return $a['percent'] < $b['percent'] ? 1 : -1 ;
	}

 	return $a['edit_state'] > $b['edit_state'] ?  1 :  -1 ;
}

$data = [];

try
{
    $query = "	SELECT 
				zak.ID AS zak_id, 
				zak.NAME AS zak_name, 
				zak.DSE_NAME AS dse_name,
				zak.EDIT_STATE AS edit_state, 
				zak_type.description AS zak_type,
				krz2.ID AS krz2_id
				FROM okb_db_zak AS zak 
				LEFT JOIN okb_db_krz2 AS krz2 ON krz2.ZAKNUM = zak.ID
				LEFT JOIN okb_db_zak_type AS zak_type ON zak_type.ID = zak.TID
				WHERE 
				krz2.ID <> 0
				AND
				zak.EDIT_STATE <> 2
				ORDER BY zak.EDIT_STATE
				#LIMIT 50
				";
    $stmt = $pdo -> prepare( $query );
    $stmt -> execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
}

while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
	$data[] = [ 
				'zak_id' => $row -> zak_id , 
				'zak_name' => $row -> zak_name,
				'zak_type' => conv( $row -> zak_type ),
				'dse_name' => conv( $row -> dse_name ),
				'edit_state' => $row -> edit_state,
				'zak_info' => 0,
				'krz2_id' => $row -> krz2_id,
				'krz2_info' => 0,
				'mismatch' => 0,
			];

foreach( $data AS $key => $value )
{
	$det_array = [];
	$count_array = [];

	$zak_info = GetZakInfo( $value['zak_id'] );
	$krz2_info = GetKrz2Info( $value['krz2_id'] );

	if( $zak_info >$krz2_info )
	{
		$data[ $key ][ 'zak_info' ] = $zak_info ;
		$data[ $key ][ 'krz2_info' ] = $krz2_info;
		$data[ $key ][ 'mismatch' ] = $zak_info - $krz2_info;
		$data[ $key ][ 'percent' ] = ( $zak_info - $krz2_info ) * 100 / $zak_info ;
	}
		else
			unset( $data[ $key ]);
}

usort( $data, "mismatchSort" );
// _debug( $data );

$str = "<h2>".conv("Несоответствие плановых нормо-часов КРЗ с данными в заказах")."</h2>";

$str .= "<table class='tbl result_table'>";
$str .= "<col width='3%'>";
$str .= "<col width='6%'>";
$str .= "<col width='40%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";
$str .= "<col width='5%'>";

$str .= "<tr class='first'>";
$str .= "<td class='field'>#</td>";
$str .= "<td class='field'>".conv("Заказ")."</td>";
$str .= "<td class='field'>".conv("ДСЕ")."</td>";    
$str .= "<td class='field'>".conv("Нормо-часы по заказу")."</td>";
$str .= "<td class='field'>".conv("Нормо-часы по КРЗ2")."</td>";
$str .= "<td class='field'>".conv("Разница")."</td>";
$str .= "<td class='field'>%</td>";
$str .= "<td class='field'>".conv("Состояние заказа")."</td>";

$str .= "</tr>";

$line = 1 ;
$states = [ conv("В работе"), conv("Выполнено"), conv("Аннулировано"), conv("На складе") ];
$classes = ["even", "odd"];
foreach ($data AS $key => $value) 
{
	$zak_a = "<a target='_blank' href='index.php?do=show&formid=39&id={$value['zak_id']}'>{$value['zak_type']} {$value['zak_name']}</a>";

	$krz2_a = "<a target='_blank' href='index.php?do=show&formid=33&id={$value['krz2_id']}'>{$value['krz2_info']}</a>";
	$zak_info_a = "<a target='_blank' href='index.php?do=show&formid=39&id={$value['zak_id']}'>{$value['zak_info']}</a>";

	$str .= "<tr class='{$classes[$line%2]}'>";
	$str .= "<td class='field AC'>$line</td>";
	$str .= "<td class='field AC'>$zak_a</td>";
	$str .= "<td class='field'><span class='dse'>{$value['dse_name']}</span></td>";    
	$str .= "<td class='field AC'>$zak_info_a</td>";
	$str .= "<td class='field AC'>$krz2_a</td>";
	$str .= "<td class='field AC'>".number_format( $value['mismatch'],2)."</td>";
	$str .= "<td class='field AC'>".number_format( $value['percent'],0)."%</td>";
	$str .= "<td class='field AC'>{$states[$value['edit_state']]}</td>";
	$str .= "</tr>";
	$line ++;
}

$str .= "</table>";

echo $str;

