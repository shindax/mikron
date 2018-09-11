<?php
//error_reporting( 0 );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
//require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DSEOperations.php" );

$start = microtime(true);
$val = $_POST['val'];
$arr = [];
$dse_arr = [];

// Поиск заказов со строкой в имени заказа. Перечень заказов кладется в $arr[0]
            $query = "
                  SELECT
                  okb_db_zak.ID zak_id,
                  okb_db_zak.NAME zak_name,
                  okb_db_zak_type.description zak_type
                  FROM okb_db_zak
                  LEFT JOIN okb_db_zak_type ON okb_db_zak_type.ID = okb_db_zak.TID
                  WHERE
                  okb_db_zak.`NAME` LIKE '%$val%'
                  AND
                  okb_db_zak.PID = 0
                  AND
                  okb_db_zak.EDIT_STATE = 0
                  AND
                  okb_db_zak.INSZ = 1
                  ORDER BY NAME
                  ";

        try
        {
            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while ( $row = $stmt->fetch(PDO::FETCH_LAZY ) )
        {
            $arr[0][] = [ "zak_id" => $row -> zak_id , "zakdet_id" => 0, "zak_type" => $row -> zak_type, "zak_name" => $row -> zak_name ];
        }


// Поиск заказов со строкой в имени ДСЕ. Перечень заказов кладется в $arr[1]
            $query =
                  "
                        SELECT
                        okb_db_zakdet.`ID` zakdet_id,
                        okb_db_zakdet.`ID_zak` zak_id,
                        okb_db_zak.NAME zak_name,
                        okb_db_zak_type.description zak_type

                        FROM `okb_db_zakdet`

                        LEFT JOIN okb_db_zak ON okb_db_zak.ID = `okb_db_zakdet` .ID_zak
                        LEFT JOIN okb_db_zak_type ON okb_db_zak_type.ID = okb_db_zak.TID

                        WHERE
                        (
                        okb_db_zakdet.`NAME` LIKE '%$val%'
                        OR
                        okb_db_zakdet.`OBOZ` LIKE '%$val%'
                        )
                        AND
                        (
                            okb_db_zak.PID = 0
                            AND
                            okb_db_zak.EDIT_STATE = 0
                            AND
                            okb_db_zak.INSZ = 1
                        )

                  ";

        try
        {
            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while ( $row = $stmt->fetch(PDO::FETCH_LAZY ) )
        {
            $zakdet_id = $row -> zakdet_id ;

            $arr[1][] = [ "zak_id" => $row -> zak_id , "zakdet_id" => $zakdet_id, "zak_type" => $row -> zak_type, "zak_name" => $row -> zak_name ];
//            $arr[2][] = $dse_arr ;

        }


$stop = (microtime(true) - $start);
//$arr = [ "val" => $val, "time" => $stop, "query" => $query ];

echo json_encode( $arr );
