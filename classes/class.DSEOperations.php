<?php
// класс OrderOperations
// Конструктор получает id заказа и строит дерево ДСЕ с видами операций обработки

// 11.16621.28.05.01.01.00.00.000 СБ

class DSEOperations
{
    private $debug;
    private $pdo;

    private $data_processed = 0 ;

    private $dse_id;
    private $zak_id;

    private $count;

    private $zak_type;
    private $zak_name;
    private $zak_dse_name;

    private $dse_name;
    private $dse_pid;
    private $dse_prefix = '/';

    private $draw;
    private $dse_draw;

    private $operations = [];

    public function __construct( $dse_id, $pdo )
    {
        $this -> dse_id = $dse_id ;
        $this -> pdo = $pdo ;
        $this -> GetZakInfo();
    }

              private function GetZakInfo( $debug = 0 )
              {
                  $this -> debug = 0;

                  $query = "
                                    SELECT
                                    okb_db_zak.ID AS zak_id,
                                    okb_db_zak_type.description AS zak_type,
                                    okb_db_zak.DSE_NAME AS zak_dse_name,
                                    okb_db_zak.DSE_OBOZ AS zak_dse_draw,
                                    okb_db_zak.`NAME` AS zak_name,
                                    okb_db_zakdet.`NAME` AS dse_name,
                                    okb_db_zakdet.OBOZ AS draw,
                                    okb_db_zakdet.PID AS dse_pid,
                                    okb_db_zakdet.COUNT AS count
                                    FROM
                                    okb_db_zak
                                    INNER JOIN okb_db_zakdet ON okb_db_zakdet.ID_zak = okb_db_zak.ID
                                    INNER JOIN okb_db_zak_type ON okb_db_zak.TID = okb_db_zak_type.id

                                    WHERE

                                    (
                                        okb_db_zak.PID = 0
                                        AND
                                        okb_db_zak.EDIT_STATE = 0
                                        AND
                                        okb_db_zak.INSZ = 1
                                    )
                                    AND
                                    okb_db_zakdet.ID = ". $this -> dse_id ;

                      try
                      {
                          $stmt = $this -> pdo->prepare( $query );
                          $stmt->execute();
                      }
                      catch (PDOException $e)
                      {
                        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                      }

                          $row = $stmt->fetch(PDO::FETCH_LAZY );

                          $this -> zak_type = $this -> conv( $row['zak_type'] );
                          $this -> zak_name = $this -> conv( $row['zak_name'] );
                          $this -> zak_dse_name = $this -> conv( $row['zak_dse_name'] );
                          $this -> dse_name = $this -> conv( $row['dse_name'] );
                          $this -> dse_pid = $this -> conv( $row['dse_pid'] );
                          $this -> zak_id = $row['zak_id'] ;

                          $this -> draw = $this -> conv( $row['draw'] );
                          $this -> dse_draw = $this -> conv( $row['zak_dse_draw'] );
              }


    public function getMainQuery()
    {
            $query = "
                  SELECT

                  okb_db_operitems.ORD ORD,

                  okb_db_operitems.ID oper_item_id,
                  okb_db_operitems.MORE operation_description,
                  okb_db_operitems.NORM_ZAK oper_norm_zak,
                  okb_db_operitems.NORM_FACT oper_norm_fact,
                  okb_db_operitems.NUM_ZAK oper_num_zak,
                  okb_db_operitems.NUM_FACT oper_num_fact,
                  okb_db_operitems.FACT2_NUM oper_num_fact2,
                  okb_db_operitems.MSG_INFO msg_info,

                  okb_db_zakdet.COUNT,
                  okb_db_zakdet.PID,

                  okb_db_oper_class.description oper_class_name,
                  okb_db_oper.`NAME` oper_name,
                  okb_db_park.`NAME` park_name,
                  okb_db_park.MARK mark_name

                  FROM
                  okb_db_zakdet
                  LEFT JOIN okb_db_operitems ON okb_db_operitems.ID_zakdet = okb_db_zakdet.ID
                  LEFT JOIN okb_db_oper ON okb_db_oper.ID = okb_db_operitems.ID_oper
                  LEFT JOIN okb_db_oper_class ON okb_db_oper.TID = okb_db_oper_class.ID
                  LEFT JOIN okb_db_park ON okb_db_operitems.ID_park = okb_db_park.ID
                  WHERE
                  okb_db_zakdet.ID = ".$this -> dse_id ;

             return $query ;
    }

    public function getData( $debug = 0 )
    {
        $this -> debug = $debug;

        $this -> getPrefix();

        if( $this -> dse_prefix == 'error' )
             return ['error' => "Parent record with id : ". $this -> dse_pid." is absent"];

        if( $this -> data_processed )
              return $this -> arr ;

        $this -> data_processed  = 1 ;

        try
        {
            $stmt = $this -> pdo->prepare( $this -> getMainQuery() );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while ( $row = $stmt->fetch(PDO::FETCH_LAZY ) )
            $this -> operations [] =
                [
                    "count" => $row["count"],
                    "oper_item_id" => $this -> conv( $row["oper_item_id"] ),

                    "operation_description" => $this -> conv( $row["operation_description"] ),
                    "oper_norm_zak" => $row["oper_norm_zak"],
                    "oper_norm_fact" => $row["oper_norm_fact"],
                    "oper_num_zak" => $row["oper_num_zak"],
                    "oper_num_fact" => $row["oper_num_fact"],
                    "oper_num_fact2" => $row["oper_num_fact2"],
                    "msg_info" => $this -> conv( $row["msg_info"] ),

                    "ord" => $this -> conv( $row["ORD"] ),
                    "operation_class" => $this -> conv( $row["oper_class_name"]),
                    "operation_name" => $this -> conv( $row["oper_name"] ),
                    "park_name" => $this -> conv( $row["park_name"] ),
                    "mark_name" => $this -> conv( $row["mark_name"] ),
                ];

        uasort( $this -> operations, array($this, "cmp_ord") );
        return $this -> operations ;
    }

    private function conv( $str )
    {
        if( ! $this -> debug )
            $str = iconv("UTF-8", "Windows-1251", $str );

        return $str ;
    }

    public function __toString()
    {
        return "DSEOperations";
    }

    private function cmp_ord( $a , $b )
    {
        if ( $a['ord'] == $b['ord'] )
            return 0;

        return ( $a['ord'] < $b['ord'] ) ? -1 : 1;
    }

                private function normalizeNum( $num )
                {
                        if( ! strlen( $num  ))
                                $num = 0 ;

                        return $num ;
                }

                public function getHtmlTableRow()
                        {
                                    if( $this -> dse_prefix == 'error' )
                                        return '';

                                     $id = $this -> dse_id ;
                                     $name = $this -> dse_name;
                                     $draw = $this -> draw;
                                     $count = $this -> count;
                                     $operations = $this -> operations;

                                    $prefix = $this -> dse_prefix ;

                                     $str = '';

                                    if( count( $operations )  >= 1 )
                                    {
                                     $str .= "<tr class='dse_row' data-id='$id'><td colspan='8'><span>$prefix {$this -> zak_type} {$this -> zak_name} $draw $name </span></td></tr>";

                                        foreach ($operations AS $operation)
                                        {
                                            $zak_count_norm = $this -> normalizeNum( $operation['oper_num_zak'] );
                                            $zak_norm = $this -> normalizeNum( $operation['oper_norm_zak'] );

                                            $zak_count_fact = $this -> normalizeNum( $operation['oper_num_fact2'] );
                                            $zak_norm_fact = $this -> normalizeNum( $operation['oper_norm_fact'] );

                                            $zak_count_fact_diff = $zak_count_norm - $zak_count_fact ;
                                            $zak_norm_diff = $zak_norm - $zak_norm_fact ;

                                            $msg_info = $operation['msg_info'];

                                            $str .= "<tr  class='operation_row' data-oper-item-id='{$operation['oper_item_id']}'>
                                                 <td class='AC'>{$operation['ord']}</td>
                                                 <td><b>{$operation['operation_name']} : {$operation['operation_class']}</b><br>{$operation['operation_description']}</td>
                                                 <td>{$operation['park_name']} : {$operation['mark_name']}</td>
                                                 <td class='AC'>$zak_count_norm<br>$zak_norm</td>
                                                 <td class='AC'>$zak_count_fact<br>$zak_norm_fact</td>
                                                 <td class='AC'>$zak_count_fact_diff<br>$zak_norm_diff</td>
                                                 <td><div class='wide'><input   ".( strlen( $msg_info ) ? 'disabled' : '')." class='inp' value='$msg_info' />
                                                 <button class='note_btn' ".( strlen($msg_info) ? '' : 'disabled') ."disabled>OK</button></div></td>
                                                 <td class='AC'><a class='alink'><b>>>></b></a></td>
                                                 </tr>";
                                        }
                                    }

                        return $str ;
                     }

    public function getPrefix()
    {
      if( $this -> dse_pid )
      {
          $this -> dse_prefix = '/..';
          $this -> getRecursivePrefix( $this -> dse_pid );
      }

        return $this -> dse_prefix;
    }

    private function getRecursivePrefix( $pid )
    {
       try
        {
            $stmt = $this -> pdo->prepare( "SELECT PID FROM `okb_db_zakdet` WHERE ID = $pid" );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        if( $stmt -> rowCount() )
        {
            $row = $stmt->fetch(PDO::FETCH_LAZY ) ;
            $pid = $row['PID'];
            if( $pid == 0 )
            {
              $this -> dse_prefix .= '/';
              return ;
            }
            $this -> dse_prefix .= '/..';
            $this -> getRecursivePrefix( $pid );
        }
            else
                    $this -> dse_prefix = 'error';
    }

}

