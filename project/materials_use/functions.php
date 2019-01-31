<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
  global $dbpasswd;
  if( strlen( $dbpasswd ) )
    return $str;
      else
        return iconv("UTF-8", "Windows-1251", $str );
}

function get_mat_options()
{
  global $pdo;
  $options = "<option value='0'>...</option>";

      try
        {
            $query = "SELECT ID, NAME
                      FROM okb_db_mat 
                      WHERE ID IN (
                      SELECT DISTINCT( ID_mat )
                      FROM okb_db_zn_zag zag
                      WHERE zag.ID_zakdet IN (
                                  SELECT zakdet.ID 
                                  FROM `okb_db_zak` zak
                                  LEFT JOIN `okb_db_zakdet` zakdet ON zakdet.ID_zak = zak.ID
                                  WHERE EDIT_STATE = 0
                                 )
                      )
                      AND 
                      NAME <> ''
                      ORDER BY NAME";

            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());

        }

        // Multiple record
        while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
          $options .= "<option value='". ( $row -> ID )."'>".conv( $row -> NAME )."</option>";

  return $options;
}

function get_sort_options()
{
  global $pdo;
  $options = "<option value='0'>...</option>";

      try
        {
            $query = "SELECT ID, NAME
                      FROM okb_db_sort 
                      WHERE ID IN (
                      SELECT DISTINCT( ID_sort )
                      FROM okb_db_zn_zag zag
                      WHERE zag.ID_zakdet IN (
                                  SELECT zakdet.ID 
                                  FROM `okb_db_zak` zak
                                  LEFT JOIN `okb_db_zakdet` zakdet ON zakdet.ID_zak = zak.ID
                                  WHERE EDIT_STATE = 0
                                 )
                      )
                      AND 
                      NAME <> ''
                      ORDER BY NAME";

            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());

        }

        // Multiple record
        while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
          $options .= "<option value='". ( $row -> ID )."'>".conv( $row -> NAME )."</option>";

  return $options;
}


function get_data( $mat, $sort )
{
  global $pdo;
  $data = [];

      try
        {
            $query = "
                      SELECT 
                        zakdet.ID zakdet_id, 
                        zakdet.NAME zakdet_name, 
                        zakdet.OBOZ zakdet_draw, 

                        zak.ID zak_id, 
                        zak.NAME zak_name,
                        zak_type.description zak_type,
                        
                        oper.NAME oper_name,
                        oper_kind.name oper_kind_name,

                        opitems.ID operitems_id,
                        opitems.ORD operitems_ord,
                        opitems.FACT2_NUM operitems_fact,
                        opitems.NUM_ZAK operitems_num_zak,
                        opitems.NORM_ZAK operitems_norm_zak,
                        opitems.FACT2_NORM operitems_norm_fact,
                        opitems.MORE operitems_more,
                        
                        park.NAME park_name,
                        park.MARK park_mark,
                        per.TXT per_descr

                        FROM okb_db_zakdet zakdet
                        LEFT JOIN okb_db_zak zak ON zak.ID = zakdet.ID_zak
                        LEFT JOIN okb_db_zak_type zak_type ON zak_type.id = zak.TID
                        LEFT JOIN okb_db_operitems opitems ON opitems.ID_zakdet = zakdet.ID
                        LEFT JOIN okb_db_oper oper ON oper.ID = opitems.ID_oper
                        LEFT JOIN okb_db_park park ON park.ID = opitems.ID_park
                        LEFT JOIN okb_db_oper_kind oper_kind ON oper_kind.id = oper.TID
                        LEFT JOIN okb_db_mtk_perehod per ON per.ID_operitems = opitems.ID
                        WHERE zakdet.ID IN(
                                SELECT 
                                ID_zakdet
                                FROM `okb_db_zn_zag` 
                                WHERE 
                                ID_mat= $mat
                                OR 
                                ID_sort = $sort
                              )
                        AND
                        zak.EDIT_STATE = 0
                        AND
                        zak.INSZ = 1
                        #GROUP BY operitems_ord
                        ORDER BY zak_name, operitems_ord, zakdet_name
                      ";

            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());

        }

        while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        {
          $zak_id = $row -> zak_id;
          $zak_name = conv( $row -> zak_name );
          $zak_type = conv( $row -> zak_type );
          
          $zakdet_id = $row -> zakdet_id;
          $zakdet_name = conv( $row -> zakdet_name );
          $zakdet_draw = conv( $row -> zakdet_draw );

          $oper_name = conv( $row -> oper_name );
          $oper_kind_name = conv( $row -> oper_kind_name );
          $ord = $row -> operitems_ord;
          $operitems_fact = $row -> operitems_fact;
          $operitems_num_zak = $row -> operitems_num_zak;
          $operitems_norm_zak = $row -> operitems_norm_zak;
          $operitems_norm_fact = $row -> operitems_norm_fact;
          $operitems_more = conv( $row -> operitems_more );
          $operitems_id = $row -> operitems_id ;
          $park_name = conv( $row -> park_name );
          $park_mark = conv( $row -> park_mark );
          $per_descr = conv( $row -> per_descr );

          $data[ $zak_id ]['zak_name'] = $zak_name;
          $data[ $zak_id ]['zak_type'] = $zak_type;

          if( ! isset( $data[ $zak_id ]['childs'][ $zakdet_id ] ))
            $data[ $zak_id ]['childs'][ $zakdet_id ] = [];

          $data[ $zak_id ]['childs'][ $zakdet_id ]['name'] = $zakdet_name;
          $data[ $zak_id ]['childs'][ $zakdet_id ]['draw'] = $zakdet_draw;

          if( ! isset( $data[ $zak_id ]['childs'][ $zakdet_id ]['ord'][ $ord ] ) && $ord )
            $data[ $zak_id ]['childs'][ $zakdet_id ]['ord'][ $ord ] = 
            [
              'operitem_id' => $operitems_id,
              'oper_name' => $oper_name,
              'oper_kind_name' => $oper_kind_name,
              'park_name' => $park_name,
              'park_mark' => $park_mark,
              'count' => $operitems_num_zak,
              'norm_zak' => $operitems_norm_zak,
              'norm_fact' => $operitems_norm_fact,
              'fact' => $operitems_fact,
              'more' => $operitems_more,
              'per_descr' => $per_descr
            ];

        }
        return $data;
}

function get_table( $data )
{
  $str = "<table class='tbl' id='mat_table'>";
  $str .= "<col width='5%'>";
  $str .= "<col width='45%'>";
  $str .= "<col width='40%'>";
  $str .= "<col width='5%'>";
  $str .= "<col width='5%'>";
          
  foreach( $data AS $key => $val )
  {
    $zak_name = $val['zak_name'];
    $zak_type = $val['zak_type'];
    $arr = $val['childs'];
      foreach( $arr AS $skey => $sval ) 
      {
        $name = $sval['name'];
        $draw = $sval['draw'];
        $ord = $sval['ord'];

        if( count( $ord ) == 0 )
          continue ;

        $str .= "<tr class='first' data-zakdet_id='$skey'>";
        $str .= "<td class='Field AL' colspan = '5'>
                 <span class='zak_span'>$zak_type $zak_name</span>
                 <span class='draw_span'>$draw</span>
                 <span class='name_span'>$name</span>
                 </td></tr>";
        
        foreach( $ord AS $okey => $oval )
        {
          $oper_name = $oval['oper_name'];
          $oper_kind_name = $oval['oper_kind_name'];
          $park_name = $oval['park_name'];
          $park_mark = $oval['park_mark'];
          $per_descr = $oval['per_descr'];

          if( strlen( $oval['park_mark'] ) )
            $park_name .= " : ".$oval['park_mark'];

          if( strlen( $oval['more'] ) )
            $park_name .= " : ".$oval['more'];

          $count = $oval['count'];
          $norm_zak = number_format( $oval['norm_zak'], 1 );
          $fact = $oval['fact'];
          $norm_fact = number_format($oval['norm_fact'], 1 );

          $str .= "<tr>";
          $str .= "<td class='Field AC'>$okey</td>";
          $str .= "<td class='Field AL'>$oper_name-$oper_kind_name<br><span class='per_descr'>$per_descr</span></td>";
          $str .= "<td class='Field AL'>$park_name</td>";
          $str .= "<td class='Field AC'>$count<br>$norm_zak </td>";
          $str .= "<td class='Field AC'>$fact<br>$norm_fact</td>";
          $str .= "</tr>";
        }
      }
  }
  $str .= "</tr>";
  $str .= "</table>";

  return $str ;
}
