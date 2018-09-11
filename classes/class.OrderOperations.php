<?php
// класс OrderOperations
// Конструктор получает id заказа и строит дерево ДСЕ с видами операций обработки

class OrderOperations
{
    private $debug = 0;
    private $data_processed = 0 ;

    private $pdo;
    private $order_id;
    private $arr;

    private $zak_type;
    private $zak_name;
    private $dse_name;

    public function __construct( $id_zak, $pdo )
    {
        $this -> order_id = $id_zak ;
        $this -> pdo = $pdo ;
        $this -> GetZakInfo();
    }

              private function GetZakInfo( )
              {
                  $arr = [];

                  $query = "
                                      SELECT
                                      okb_db_zak_type.description,
                                      okb_db_zak.DSE_NAME AS dse_name,
                                      okb_db_zak.`NAME` AS zak_name
                                      FROM
                                      okb_db_zak
                                      INNER JOIN okb_db_zak_type ON okb_db_zak.TID = okb_db_zak_type.id
                                      WHERE
                                      okb_db_zak.PID = 0
                                      AND
                                      okb_db_zak.EDIT_STATE = 0
                                      AND
                                      okb_db_zak.INSZ = 1
                                      AND
                                      okb_db_zak.ID = ". $this -> order_id ;
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

                          $this -> zak_type = $this -> conv( $row['description'] );
                          $this -> zak_name = $row['zak_name'] ;
                          $this -> dse_name = $this -> conv( $row['dse_name'] );
              }


    public function getMainQuery()
    {
            $query = "
                  SELECT

                  okb_db_zakdet.ID,
                  okb_db_operitems.ORD ORD,
                  okb_db_zakdet.PID,

                  okb_db_operitems.ID oper_item_id,
                  okb_db_operitems.MORE operation_description,
                  okb_db_operitems.NORM_ZAK oper_norm_zak,
                  okb_db_operitems.NORM_FACT oper_norm_fact,
                  okb_db_operitems.NUM_ZAK oper_num_zak,
                  okb_db_operitems.NUM_FACT oper_num_fact,
                  okb_db_operitems.FACT2_NUM oper_num_fact2,
                  okb_db_operitems.MSG_INFO msg_info,

                  okb_db_zakdet.OBOZ,
                  okb_db_zakdet.COUNT,
                  okb_db_zakdet.`NAME` dse_name,

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
                  okb_db_zakdet.ID_zak = ".$this -> order_id ;

             return $query ;
    }

    public function getOrderID()
          {
            return $this -> order_id ;
          }


    public function getData( $debug = 0 )
    {
        $this -> debug = $debug;

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
            $this -> arr [] =
                [
                    'id' => $row['ID'],
                    'parent_id' => $row['PID'],
                    'dse_name' =>  $this -> conv( $row['dse_name'] ),
                    'count' => $row['COUNT'],
                    'draw' => $this -> conv( $row['OBOZ'] ),
                    'oper_item_id' => $row['oper_item_id'],

                    'operation_description' => $this -> conv( $row['operation_description'] ),
                    'oper_norm_zak' => $row['oper_norm_zak'],
                    'oper_norm_fact' => $row['oper_norm_fact'],
                    'oper_num_zak' => $row['oper_num_zak'],
                    'oper_num_fact' => $row['oper_num_fact'],
                    'oper_num_fact2' => $row['oper_num_fact2'],
                    'msg_info' => $this -> conv( $row['msg_info'] ),

                    'ord' => $this -> conv( $row['ORD'] ),
                    'operation_class' => $this -> conv( $row['oper_class_name']),
                    'operation_name' => $this -> conv( $row['oper_name'] ),
                    'park_name' => $this -> conv( $row['park_name'] ),
                    'mark_name' => $this -> conv( $row['mark_name'] ),
                ];
        $this -> arrayProcess();
        return $this -> arr ;
    }


    private function getMTKDescrption( $oper_item_id )
    {
        $str = '';
        $query = "SELECT TXT FROM `okb_db_mtk_perehod` WHERE `ID_operitems`= $oper_item_id ORDER BY TID";

        if( $oper_item_id ) {
            try {
                $stmt = $this->pdo->prepare($query);
                $stmt->execute();
            } catch (PDOException $e) {
                die("Error in :" . __FILE__ . " file, at " . __LINE__ . " line. Query is : $query.Can't get data : " . $e->getMessage());
            }

            while ($row = $stmt->fetch(PDO::FETCH_LAZY))
                $str .= $row['TXT'];
        }

        return $this -> conv ( $str );
    }


    private function arrayProcess()
    {
        $outArr = [];

        foreach( $this -> arr AS $item )
        {
            $key = $item['id'];

            if( !array_key_exists( $key, $outArr ) )
                    $outArr [ $key ] =
                        [
                            'id' => $item['id'],
                            'parent_id' => $item['parent_id'],
                            'dse_name' => $item['dse_name'],
                            'draw' => $item['draw'],
                            'count' => $item['count'],
                            'childs' => []
                        ];

            $operation =
                [
                    'ord' => $item['ord'],
                    'oper_item_id' => $item['oper_item_id'],
                    'operation_description' => $item['operation_description'],
                    'oper_item_description' => $this -> getMTKDescrption( $item['oper_item_id'] ),

                    'operation_class'   => $item['operation_class'],
                    'operation_name'    => $item['operation_name'],
                    'oper_norm_zak'     => $item['oper_norm_zak'],
                    'oper_norm_fact'    => $item['oper_norm_fact'],
                    'oper_num_zak'      => $item['oper_num_zak'],
                    'oper_num_fact'     => $item['oper_num_fact'],
                    'oper_num_fact2'    => $item['oper_num_fact2'],
                    'msg_info'    => $item['msg_info'],

                    'park_name'         => $item['park_name'],
                    'mark_name'         => $item['mark_name']
                ];

            $outArr [ $key ]['operations'][] = $operation ;
        }

        foreach( $outArr AS $key => $value )
            uasort( $outArr[ $key ]['operations'], array($this, "cmp_ord") );

        uasort( $outArr, array($this, "cmp_draw") );

        $this -> arr = $this -> map_tree( $outArr );
    }

    private function conv( $str )
    {
        if( ! $this -> debug )
            $str = iconv("UTF-8", "Windows-1251", $str );

        return $str ;
    }

    public function __toString()
    {
        return "OrderOperations";
    }

    private function cmp_ord( $a , $b )
    {
        if ( $a['ord'] == $b['ord'] )
            return 0;

        return ( $a['ord'] < $b['ord'] ) ? -1 : 1;
    }

    private function cmp_draw( $a , $b )
    {
        if ( $a['draw'] == $b['draw'] )
            return 0;

        return ( $a['draw'] < $b['draw'] ) ? -1 : 1;
    }

    function map_tree( $dataset )
    {
        $tree = array();

        foreach ( $dataset as $id => &$node )
          if( isset( $node['parent_id'] ) )
              if (!$node['parent_id'])
                  $tree[$id] = &$node;
              else
                  $dataset[$node['parent_id']]['childs'][$id] = &$node;

        return $tree;
    }

                public function getHtmlTree( )
                {
                        $str = "" ;
                        $arr = $this -> getData();

                        foreach(  $arr AS $child )
                            $str .= $this ->  getRawHtmlTree( $child ) ;

                        return $str;
                }

                private function normalizeNum( $num )
                {
                        if( ! strlen( $num  ))
                                $num = 0 ;

                        return $num ;
                }

                private function getRawHtmlTree( $row, $level = 0 )
                        {

                                     $id = $row['id'];
                                     $parent_id = $row['parent_id'];
                                     $name = $row['dse_name'];
                                     $draw = $row['draw'];
                                     $count = $row['count'];
                                     $operations = $row['operations'];
                                     $childs = $row['childs'];
                                     $str = '';

                                    if( count( $operations )  >= 1  )
                                    {
                                     $substr = "<tr class='dse_row' data-id='$id' data-pid='$parent_id'><td colspan='8'><span class='l_$level'> {$this -> zak_type} {$this -> zak_name} $draw $name</span></td></tr>";

                                        foreach ($operations AS $operation)
                                        {
                                            $zak_count_norm = $this -> normalizeNum( $operation['oper_num_zak'] );
                                            $zak_norm = $this -> normalizeNum( $operation['oper_norm_zak'] );

                                            $zak_count_fact = $this -> normalizeNum( $operation['oper_num_fact2'] );
                                            $zak_norm_fact = $this -> normalizeNum( $operation['oper_norm_fact'] );

                                            $zak_count_fact_diff = $zak_count_norm - $zak_count_fact ;
                                            $zak_norm_diff = $zak_norm - $zak_norm_fact ;

                                            $msg_info = $operation['msg_info'];

                                                  $substr .= "<tr  class='operation_row' data-oper-item-id='{$operation['oper_item_id']}'>
                                                       <td class='AC'>{$operation['ord']}</td>
                                                       <td><b>{$operation['operation_name']} : {$operation['operation_class']}</b><br>{$operation['operation_description']}{$operation['oper_item_description']}</td>
                                                       <td>{$operation['park_name']} : {$operation['mark_name']}</td>
                                                       <td class='AC'>$zak_count_norm<br>$zak_norm</td>
                                                       <td class='AC'>$zak_count_fact<br>$zak_norm_fact</td>
                                                       <td class='AC'>$zak_count_fact_diff<br>$zak_norm_diff</td>
                                                       <td><div class='wide'><input   ".( strlen( $msg_info ) ? 'disabled' : '')." class='inp' value='$msg_info' />
                                                       <button class='note_btn' ".( strlen($msg_info) ? '' : 'disabled') ."disabled>OK</button></div></td>
                                                       <td class='AC'><a class='alink'><b>>>></b></a></td>
                                                       </tr>";
                                        }
                                          if(   ( count( $operations )  == 1 && strlen( $operation['ord'] ) ) || count( $operations )  > 1 )
                                              $str .= $substr ;
                                    }

                                    $level ++ ;
                                    foreach( $childs AS $child )
                                         $str .= $this -> getRawHtmlTree( $child, $level );

                        return $str ;
                     }
}
