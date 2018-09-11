<?php
//require_once( "db.php" );
require_once( "functions.php" );

class OrderTree
{
    private $pdo;
    private $orders = [];

    public function __construct( $pdo  )
    {
        $this -> pdo = $pdo ;
        $this -> getData();
    }

    public function getOrderCount()
    {
        return count( $this -> orders );
    }

    private function getData()
    {
        try
        {
            $query ="
                    SELECT
                    okb_db_zak_type.description,
                    okb_db_zak.`NAME`,
                    okb_db_zak.DSE_NAME,
                    okb_db_zak.ID
                    FROM
                    okb_db_zak
                    INNER JOIN okb_db_zak_type ON okb_db_zak.TID = okb_db_zak_type.ID
                    WHERE
                    (
                    ( okb_db_zak.EDIT_STATE = 0 AND okb_db_zak.PID = 0 )
                    OR
                    ( okb_db_zak.EDIT_STATE = 0 AND okb_db_zak_type.ID = 6 AND okb_db_zak.PID = 0 )
                    )
                    AND
                      okb_db_zak.CDATE >= 20140201
                    ORDER BY
                    okb_db_zak.`NAME` ASC
            ";

            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Can't get data: " . $e->getMessage());
        }

        while ( $row = $stmt->fetch(PDO::FETCH_LAZY ))
            $this->orders [] =
            [
                'id' => $row['ID'],
                'descr' => $row['description'],
                'name' => $row['NAME'],
                'dse_name' => $row['DSE_NAME'],
            ];

        foreach( $this -> orders AS &$order )
        {
            $id = $order['id'] ;
            try
            {
                $query ="
                    SELECT COUNT( * ) count
                    FROM
                    okb_db_zakdet
                    WHERE ID_zak = $id
            ";
                $stmt = $this -> pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
                die("Can't get data: " . $e->getMessage());
            }

            $row = $stmt->fetch(PDO::FETCH_LAZY );
            $order['child_count'] = $row['count'];
            $order['childs'] = 0;
        }
    }

    public function getHtml()
    {
        $table = conv("<table id='order_table'>
            <col width='1%'></col>
            <col width='1%'></col>
            <col width='5%'></col>
            <col width='5%'></col>
            <col width='1%'></col>
            <col width='1%'></col>
            <col width='1%'></col>
            <col width='1%'></col>
            <col width='1%'></col>
            <col width='1%'></col>

            ");

        foreach( $this -> orders AS $order )
        {
            $id = $order['id'];
            $descr =  $order['descr'];
            $name = $order['name'];
            $dse_name = $order['dse_name'];
            $child_count = $order['child_count'];

            if( $child_count )
            {

                $table .= conv("<tr id='$id' name='$name'><td colspan='10'>

                <img data-state='0' class='coll_image' src='uses/collapse.png' />");
//                $table .= conv("$id $descr $name $dse_name ( $child_count )");
                $table .= conv("$descr $name $dse_name");
                $table .= "</td></tr>";
            }
        }
        $table .= "</table>";

        return $table ;
    }

}
