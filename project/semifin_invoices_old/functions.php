<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
        $result = iconv("UTF-8", "Windows-1251", $str );
//        $result = $str ;
  return $result;
}

function debug($arr)
{
    echo '<pre>' . print_r($arr, true) . '</pre>';
}

function GetSemifinishedStoreType( $id )
{
    global $pdo;
    $option = '';

    if( $id == 'option' )
         {
            $option = "<option value='0'>...</option>";

                       try
                    {
                        $query = "SELECT * FROM `okb_db_semifinished_store_type` WHERE 1 ORDER BY id";
                        $stmt = $pdo->prepare( $query );
                        $stmt->execute();
                    }
                    catch (PDOException $e)
                    {
                      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                    }
                    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
                        $option .= "<option value='".$row->id."'>".conv($row->description)."</option>";
         }
            else
            {
                if( $id == 0 )
                     $option = "...";
                 else
                {
                   try
                    {
                        $query = "SELECT * FROM `okb_db_semifinished_store_type` WHERE id = $id ";
                        $stmt = $pdo->prepare( $query );
                        $stmt->execute();
                    }
                    catch (PDOException $e)
                    {
                      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                    }
                        $row = $stmt->fetch(PDO::FETCH_OBJ ) ;
                        $option = conv($row->description);
                }
            }

    return $option;
}


function GetSemifinishedStoreUsedDate( $cur_year )
{
    global $pdo;
    $option = '';

                    try
                    {
                        $query = "SELECT
                                        DISTINCT YEAR(`create_date`) date
                                        FROM `okb_db_semifinished_store_invoices`
                                        WHERE 1
                                        order by date";
                        $stmt = $pdo->prepare( $query );
                        $stmt->execute();
                    }
                    catch (PDOException $e)
                    {
                      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                    }
                    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
                        $option .= "<option value='".$row->date."' ".( $cur_year == $row->date ? 'selected' : '' ).">".$row->date."</option>";

    return $option;
}

function GetSemifinishedStoreInvoicesNumber( $cur_year )
{
    global $pdo;
    $option = "<option value='0'>".conv("Все накладные")."</option>";

                    try
                    {
                        $query = "SELECT DISTINCT( inv_num ) num
                                        FROM `okb_db_semifinished_store_invoices`
                                        WHERE YEAR(`create_date`) = '$cur_year'
                                        order by num";
                        $stmt = $pdo->prepare( $query );
                        $stmt->execute();
                    }
                    catch (PDOException $e)
                    {
                      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                    }
                    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
                    {
                        $option .= "<option value='".$row->num."'>".$row->num."</option>";
                    }

    return $option;
}

function GetSemifinishedStoreStartInvoicesNumber( $cur_year )
{
    global $pdo;
    $option = '';

                    try
                    {
                        $query = "SELECT MIN( inv_num ) num
                                        FROM `okb_db_semifinished_store_invoices`
                                        WHERE YEAR(`create_date`) = '$cur_year'";
                        $stmt = $pdo->prepare( $query );
                        $stmt->execute();
                    }
                    catch (PDOException $e)
                    {
                        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                    }
                        $row = $stmt->fetch(PDO::FETCH_OBJ ) ;
                        return $row->num;

}

function GetSemifinishedStoreData2( $year, $inv_num )
{
global $pdo ;

$order_arr = [];

    try
            {
                $query = "
                SELECT
                okb_db_semifinished_store_invoices.id,
                okb_db_semifinished_store_invoices.inv_num,
                okb_db_zak.`NAME` AS zak_name,
                okb_db_zak_type.description AS zak_type,
                okb_db_zak.DSE_NAME AS zak_dse_name,
                okb_db_zak.DSE_OBOZ AS zak_dse_draw,
                okb_db_semifinished_store_invoices.part_num,
                okb_db_semifinished_store_invoices.count,
                okb_db_semifinished_store_invoices.transfer_place,
                okb_db_zakdet.`NAME` AS zakdet_name,
                okb_db_zakdet.OBOZ AS draw_name,
                okb_db_semifinished_store_invoices.id_zadan,
                okb_db_semifinished_store_type.description AS storage_type,
                okb_db_semifinished_store_invoices.storage_time AS storage_type_bin,
                okb_db_semifinished_store_invoices.note AS note
                FROM
                okb_db_semifinished_store_invoices
                LEFT JOIN okb_db_zadan ON okb_db_semifinished_store_invoices.id_zadan = okb_db_zadan.ID
                LEFT JOIN okb_db_zak ON okb_db_zadan.ID_zak = okb_db_zak.ID
                LEFT JOIN okb_db_zakdet ON okb_db_zadan.ID_zakdet = okb_db_zakdet.ID
                LEFT JOIN okb_db_zak_type ON okb_db_zak.TID = okb_db_zak_type.id
                LEFT JOIN okb_db_semifinished_store_type ON okb_db_semifinished_store_invoices.storage_time = okb_db_semifinished_store_type.id
                WHERE
                YEAR(okb_db_semifinished_store_invoices.create_date ) = $year
                AND
                okb_db_semifinished_store_invoices.inv_num = $inv_num
                ORDER BY
                zakdet_name ASC
                " ;
                $stmt = $pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
                die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }

            $line = 1 ;
            while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            {
              $id = $row -> id_zadan;
              $inv_num = $row -> inv_num ;

              $zak_type = conv( $row -> zak_type );
              $zak_name = conv( $row -> zak_name );

              $zak_dse_name = conv( $row -> zak_dse_name );
              $zak_dse_draw = conv( $row -> zak_dse_draw );

              $zakdet_name = conv( $row -> zakdet_name );
              $draw_name = conv( $row -> draw_name );
              $storage_type = conv( $row -> storage_type );
              $storage_type_bin = conv( $row -> storage_type_bin );
              $note = conv( $row -> note );

                $order_arr [] =
                [
                    'line' => $line ++,
                    'id' => $id,
                    'inv_num' => $inv_num,
                    'count' => $row -> count,
                    'zak_name' => $zak_type." ".$zak_name,
                    'draw' => $draw_name ? $draw_name : $zak_dse_draw,
                    'name' => $zakdet_name ? $zakdet_name : $zak_dse_name,
                    'part_num' => $row -> part_num,
                    'transfer_place' => $row -> transfer_place,
                    'storage_type' => $storage_type,
                    'storage_type_bin' => $storage_type_bin,
                    'note' => $note
                ];
            }

    return $order_arr ;
}

function RestoreData()
{
    global $pdo ;
    $order_arr = [];
    $data = [];

    try
            {
                $query = "SELECT * FROM `okb_db_semifinished_store_invoices` WHERE dse_name = ''";

                $stmt = $pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
                die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }

            while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            {
                $order_arr [ $row -> id ] = ["id_zadan" => $row -> id_zadan ];
            }

            foreach( $order_arr AS $key => $order )
            {
                $data[] = GetIncompletedSemifinishedStoreData( $key );
            }

//            debug( $data );

            foreach( $data AS $rec )
            {
                $id = $rec['rec_id'];
                $order_name = $rec['order_name'];
                $dse_name = $rec['dse_name'];
                $draw_name = $rec['draw_name'];

                    try
                        {
                                 $sql = "UPDATE `okb_db_semifinished_store_invoices`
                                      SET
                                                `order_name` = :order_name,
                                                `dse_name` =:dse_name ,
                                                `draw_name`=:draw_name

                                      WHERE id = $id
                                      ";

                                 $statement = $pdo->prepare($sql);
                                 $statement->bindValue(":order_name", $order_name);
                                 $statement->bindValue(":dse_name", $dse_name);
                                 $statement->bindValue(":draw_name", $draw_name);
                                 $count = $statement->execute();
                        }
                        catch (PDOException $e)
                        {
                            die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                        }
            }

    return $order_arr ;
}

function GetIncompletedSemifinishedStoreData( $inv_num )
{
global $pdo ;

$order_arr = [];

    try
            {
                $query = "
                SELECT
                okb_db_semifinished_store_invoices.id,
                okb_db_semifinished_store_invoices.inv_num,
                okb_db_zak.`NAME` AS zak_name,
                okb_db_zak_type.description AS zak_type,
                okb_db_zak.DSE_NAME AS zak_dse_name,
                okb_db_zak.DSE_OBOZ AS zak_dse_draw,
                okb_db_semifinished_store_invoices.part_num,
                okb_db_semifinished_store_invoices.count,
                okb_db_semifinished_store_invoices.transfer_place,
                okb_db_zakdet.`NAME` AS zakdet_name,
                okb_db_zakdet.OBOZ AS draw_name,
                okb_db_semifinished_store_invoices.id_zadan
                FROM
                okb_db_semifinished_store_invoices
                LEFT JOIN okb_db_zadan ON okb_db_semifinished_store_invoices.id_zadan = okb_db_zadan.ID
                LEFT JOIN okb_db_zak ON okb_db_zadan.ID_zak = okb_db_zak.ID
                LEFT JOIN okb_db_zakdet ON okb_db_zadan.ID_zakdet = okb_db_zakdet.ID
                LEFT JOIN okb_db_zak_type ON okb_db_zak.TID = okb_db_zak_type.id

                WHERE
                okb_db_semifinished_store_invoices.id = $inv_num
                ORDER BY
                zakdet_name ASC
                " ;
                $stmt = $pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
                die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }

            $line = 1 ;
            while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            {
              $id = $row -> id_zadan;

              $zak_type = conv( $row -> zak_type );
              $zak_name = conv( $row -> zak_name );

              $zak_dse_name = conv( $row -> zak_dse_name );
              $zak_dse_draw = conv( $row -> zak_dse_draw );

              $zakdet_name = conv( $row -> zakdet_name );
              $draw_name = conv( $row -> draw_name );

                $order_arr  =
                [
                    'id' => $id,
                    'rec_id' => $row -> id,
                    'order_name' => $zak_type." ".$zak_name,
                    'draw_name' => $draw_name ? $draw_name : $zak_dse_draw,
                    'dse_name' => $zakdet_name ? $zakdet_name : $zak_dse_name,
                ];
            }
    return $order_arr ;
}

function GetSemifinishedStoreData( $year, $inv_num )
{
global $pdo ;

$order_arr = [];

    try
            {
                $query = "
                SELECT
                okb_db_semifinished_store_invoices.id,

                okb_db_semifinished_store_invoices.dse_name,
                okb_db_semifinished_store_invoices.order_name,
                okb_db_semifinished_store_invoices.draw_name,

                okb_db_semifinished_store_invoices.inv_num,
                okb_db_semifinished_store_invoices.part_num,
                okb_db_semifinished_store_invoices.count,
                okb_db_semifinished_store_invoices.transfer_place,
                okb_db_semifinished_store_invoices.id_zadan,
                okb_db_semifinished_store_type.description AS storage_type,
                okb_db_semifinished_store_invoices.storage_time AS storage_type_bin,
                okb_db_semifinished_store_invoices.note AS note
                FROM
                okb_db_semifinished_store_invoices
                LEFT JOIN okb_db_semifinished_store_type ON okb_db_semifinished_store_invoices.storage_time = okb_db_semifinished_store_type.id
                WHERE
                YEAR(okb_db_semifinished_store_invoices.create_date ) = $year
                AND
                okb_db_semifinished_store_invoices.inv_num = $inv_num
                ORDER BY
                order_name ASC
                " ;
                $stmt = $pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
                die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }

            $line = 1 ;
            while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            {
              $id = $row -> id_zadan;
              $inv_num = $row -> inv_num ;

              $order_name = conv( $row -> order_name );
              $dse_name = conv( $row -> dse_name );
              $draw_name = conv( $row -> draw_name );

              $draw_name = conv( $row -> draw_name );

              $storage_type = conv( $row -> storage_type );
              $storage_type_bin = conv( $row -> storage_type_bin );
              $note = conv( $row -> note );

                $order_arr [] =
                [
                    'line' => $line ++,
                    'id' => $id,
                    'inv_num' => $inv_num,
                    'count' => $row -> count,

                    'zak_name' => $order_name,
                    'draw' => $draw_name ,
                    'name' => $dse_name,

                    'part_num' => $row -> part_num,
                    'transfer_place' => $row -> transfer_place,
                    'storage_type' => $storage_type,
                    'storage_type_bin' => $storage_type_bin,
                    'note' => $note
                ];
            }

    return $order_arr ;
}

function GetSemifinishedHTMLData( $order_arr )
{
    $str = '';
    foreach ( $order_arr AS $val )
        {
              $line = $val[ 'line' ];
              $id = $val[ 'id' ];
              $inv_num = $val['inv_num'];
              $name = $val[ 'name' ];
              $zak_name = $val['zak_name'];
              $draw = $val['draw'];
              $count = $val['count'];
              $part_num = conv( $val['part_num'] );
              $transfer_place = conv( $val['transfer_place'] );
              $storage_type = $val['storage_type'];
              $storage_type_bin = $val['storage_type_bin'];
              $note = $val['note'];

              $row_style = $line %2 ? "active" : "warning";

               $str .= "<tr class='order_row $row_style'  data-id='$id' data-inv-num='$inv_num'>
                            <td class='AC'>$line</td>
                            <td>$name</td>
                            <td class='AC'>$zak_name</td>
                            <td class='AC'>$draw</td>
                            <td><span class='part_num'>$part_num</span></td>
                            <td class='AC'><span class='count'>$count</span></td>
                            <td class='AC'><span class='transfer_place'>$transfer_place</span></td>
                            <td class='AC'><span class='storage_type' data-bin='$storage_type_bin'>$storage_type</span></td>
                            <td><span class='note'>$note</span></td>
                            </tr>";
        }

        return $str ;
}

function GetSemifinishedInvoices( $cur_year )
{
    global $pdo;
    $arr = [];

                    try
                    {
                        $query = "
                                        SELECT DISTINCT inv_num
                                        FROM `okb_db_semifinished_store_invoices`
                                        WHERE YEAR(`create_date`) = '$cur_year'
                                        ";
                        $stmt = $pdo->prepare( $query );
                        $stmt->execute();
                    }
                    catch (PDOException $e)
                    {
                        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                    }
                        while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
                            $arr[] = $row->inv_num;
        return $arr ;
}
