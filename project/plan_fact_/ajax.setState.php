<?php
error_reporting( 0 );

require_once( "functions.php" );

date_default_timezone_set("Asia/Krasnoyarsk");

$id = $_POST['id'];
$field = $_POST['field'];
$state = $_POST['state'] == 'true' ? 1 : 0 ;
$user_id = $_POST['user_id'];
$today = date("d.m.Y");

try
{
    $query = "SELECT $field FROM okb_db_zak
    WHERE
    ID=$id" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}
$row = $stmt->fetch(PDO::FETCH_OBJ );
$data = $row -> $field ;

if( strlen( $data ) )
{
    $temp_arr = explode('|', $data );
    $temp_arr[0] = $state ;
    $out_str = join( '|', $temp_arr );
    $out_str .= "|$today#$user_id#$today";
}
 else
     $out_str = "$state|$today#$user_id#$today";

try
{
    $query = "UPDATE okb_db_zak
    SET $field = '$out_str'
    WHERE
    ID=$id" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
}

try
{
    $query = "
    SELECT
    PD1,
    PD2,
    PD3,
    PD4,
    PD7,
    PD8,
    PD9,
    PD10,
    PD11,
    PD12,
    PD13
    FROM okb_db_zak
    WHERE
    ID=$id" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}

$row = $stmt->fetch(PDO::FETCH_OBJ );

                    $pd1 = getBreakApartPD( $row -> PD1 );
                    $pd2 = getBreakApartPD( $row -> PD2 );
                    $pd3 = getBreakApartPD( $row -> PD3 );
                    $pd4 = getBreakApartPD( $row -> PD4 );
                    $pd7 = getBreakApartPD( $row -> PD7 );
                    $pd8 = getBreakApartPD( $row -> PD8 );
                    $pd9 = getBreakApartPD( $row -> PD9 );
                    $pd10 = getBreakApartPD( $row -> PD10 );
                    $pd11 = getBreakApartPD( $row -> PD11 );
                    $pd12 = getBreakApartPD( $row -> PD12 );
                    $pd13 = getBreakApartPD( $row -> PD13 );

                    $stage_prepare  = MakeLogicData( $pd1['log_state'] , $pd2['log_state'] , $pd3['log_state'] );
                    $stage_equipment   = MakeLogicData( 1, $pd4['log_state'] , $pd7['log_state'] );
                    $stage_production   = MakeLogicData( $pd8['log_state'] , $pd12['log_state'] , $pd13['log_state'] );
                    $stage_commertion  = MakeLogicData( $pd9['log_state'] , $pd10['log_state'] , $pd11['log_state'] );

            $stage = stageLogic
                      (
                        $stage_prepare,
                        $stage_equipment,
                        $stage_production,
                        $stage_commertion
                      );


 if (($pd9['log_state'] * $pd10['log_state'] * $pd11['log_state']) == 1 ) {
	 $stage = READY;
 }
try
{
    $query = "UPDATE okb_db_zak
    SET ID_stage = $stage
    WHERE
    ID=$id" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
}


$index = count( explode('|', $out_str ) ) - 1 ;
$state_str = "Изменение состояния этапа : ";
$state_str .= $state ? "Выполнено" : "Не выполнено";

try
{
    $query = "INSERT INTO okb_db_zak_ch_date_history ( id, zak_id, pd, date_index, date_string, cause, comment, user_id, timestamp ) 
              VALUES ( NULL, $id, '$field', $index, '$today', 0, '$state_str', $user_id, NOW() )" ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't insert data : " . $e->getMessage());
}



echo $stage;
