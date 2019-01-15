<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

//error_reporting( 0 );
//ini_set('display_errors', false );

function GetMonthTableBegin( $month , $year )
{
  $day_names = [ "", conv("Пн"), conv("Вт"), conv("Ср"), conv("Чт"), conv("Пт"), conv("Сб"), conv("Вс") ];
  $days_in_month = date('t', strtotime( "$year-$month-01" ) );

  $perc = 86 / ( $days_in_month + 4 );

  $str =          "<div class='row'><div class='col-sm-12'><table id='month_table' class='tbl table-striped'>
                      <col width='10%'>";

    for( $i = 1 ; $i <= $days_in_month + 2 ; $i ++ )
          $str .= "<col width='$perc%'>";

    $str .= "<col width='4%'>";

  $str .= "<tr class='first'>

                      <td rowspan='2'>".conv("Ресурс")."</td>
                      ";
                      for( $i = 1 ; $i <= $days_in_month ; $i++ )
                          $str .= "<td class='field AC' data-date='$i'>$i</td>";

                      $str .= "<td class='AC' colspan='2'>".conv("По мн-ст.")."</td>";
                      $str .= "<td rowspan='2' class='AC'>".conv("Итого")."</td></tr>";
                      $str .= "<tr class='first'>";

                      for( $i = 1 ; $i <= $days_in_month ; $i++ )
                      {
                          $cur_day = date('N', strtotime( "$year-$month-$i" ) );
                          $str .= "<td class='field AC' data-date='$i'>".($day_names[ $cur_day ])."</td>";
                      }

                      $str .= "<td class='field AC'>".conv("кол.")."</td>";
                      $str .= "<td class='field AC'>".conv("ч.")."</td>";

                      $str .= "</tr>";
    return $str ;
}

function GetMonthTableRow( $month , $year )
{
	global $pdo;
	
  $data = GetMonthTableData( $month , $year );
  $days_in_month = date('t', strtotime( "$year-$month-01" ) );
  $str = ''  ;

  foreach( $data AS $emp )
  {
      $str .= "<tr class='data-row'>" ;
      $items = $emp['items'];
      $name = $emp['res_name'];
      $str .= "<td class='field AL'>$name</td>";
      $shift1 = 0 ;
      $shift2 = 0 ;
      $shift3 = 0 ;
      $mul_tool_cnt = 0 ;
      $mul_tool_hours = 0 ;
      for( $i = 1; $i <=  $days_in_month ; $i ++ )
        {
          $total_shift = 0 ;
          $multimachine_fact = 0 ;

          if( isset( $items[ $i ]['day_type'] ) )
            $day_type = $items[ $i ]['day_type'];

          if( isset( $items[ $i ]['shift'] ) )
              $shift = $items[ $i ]['shift'];

          if( isset( $items[ $i ]['hours'] ) )
              $hours = $items[ $i ]['hours'];

          if( isset( $items[ $i ]['multy_tool'] ) )
            $multy_tool = $items[ $i ]['multy_tool'];

          if( isset( $items[ $i ]['multimachine_fact'] ) )
            $multimachine_fact = $items[ $i ]['multimachine_fact'];

        if( $multy_tool )
        {
          $class = 'multi';
          $mul_tool_cnt ++ ;
          if( $multimachine_fact )
             $mul_tool_hours += $multimachine_fact ;
               else
                  $mul_tool_hours += $hours ;
        }
            else
              $class = '';

          $cell_val = $day_type ;

          if( $multimachine_fact )
              $hours = $multimachine_fact;

          if( $hours && $shift )          
          {
              if( $day_type )
                $cell_val = "<span class='vac'>$day_type</span><br>$hours/$shift";
                  else
                    $cell_val = "$hours<br>$shift";
          }
            else
            {
              $cell_val = "$hours<br>$shift";
              if( $day_type )
                  $cell_val = "<span class='vac'>$day_type</span>";
            }

        if( $shift )
            switch( $shift )
            {
                case 1 : $shift1 += $hours ; break;
                case 2 : $shift2 += $hours ; break;
                case 3 : $shift3 += $hours ; break;
            }

          $str .= "<td class='field AC $class'>$cell_val</td>";
        }
          $total_shift = $shift1 + $shift2 + $shift3 ;
          $total_shift = $total_shift ? $total_shift : '-';

          $shift1 = $shift1 ? $shift1 : '-' ;
          $shift2 = $shift2 ? $shift2 : '-' ;
          $shift3 = $shift3 ? $shift3 : '-' ;

          $mul_tool_cnt = $mul_tool_cnt ? $mul_tool_cnt : '-';
          $mul_tool_hours = $mul_tool_hours ? $mul_tool_hours : '-';

		  // Костыль на проверку пустого значения.
		//  $tabel_hours = $pdo->prepare("SELECT FACT FROM okb_db_tabel WHERE ID_resurs = " . $emp['res_id'] . " AND ");
		  
          $str .= "<td class='field AC'>$mul_tool_cnt</td>";
          $str .= "<td class='field AC'>$mul_tool_hours</td>";
          $str .= "<td class='field AC'>$total_shift</td>";

      $str .= '</tr>'  ;
  }

  return $str;
}

function GetMonthTableData( $month , $year )
{
  global $pdo ;

  $month = strlen( $month ) == 1 ? "0$month" : $month ;
  $from = $year.$month."01";
  $to = $year.$month."31";
  $emp_list = [];
  $table = [];

      try
      {
          $query ="
                        SELECT DISTINCT okb_db_resurs.ID res_id
                        FROM
                        okb_db_tabel
                        LEFT JOIN okb_db_tabel_day_type ON okb_db_tabel.TID = okb_db_tabel_day_type.day_type_id
                        LEFT JOIN okb_db_resurs ON okb_db_tabel.ID_resurs = okb_db_resurs.ID
                        LEFT JOIN okb_db_zadanres ON okb_db_zadanres.DATE = okb_db_tabel.DATE AND okb_db_zadanres.ID_resurs = okb_db_tabel.ID_resurs AND okb_db_zadanres.SMEN = okb_db_tabel.SMEN
                        WHERE
                        okb_db_tabel.DATE >= $from AND
                        okb_db_tabel.DATE <= $to AND
                        okb_db_zadanres.is_multimachine = 1
                      ";

          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }
           while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            $emp_list[] = $row -> res_id;

      if( count( $emp_list ) )
    {
      try
      {
          $query ="
                          SELECT
                          okb_db_zadanres.ID,
                          okb_db_tabel_day_type.day_type_short AS day_type,
                          okb_db_resurs.`NAME` AS res_name,

                          #okb_db_tabel.SMEN AS shift,
                          okb_db_zadanres.SMEN AS shift,

                          okb_db_tabel.FACT AS hours,
                          okb_db_tabel.DATE AS shift_date,
                          okb_db_tabel.ID AS rec_id,
                          okb_db_resurs.ID AS res_id,
                          okb_db_zadanres.is_multimachine,
                          okb_db_zadanres.multimachine_fact
                          FROM
                          okb_db_tabel
                          LEFT JOIN okb_db_tabel_day_type ON okb_db_tabel.TID = okb_db_tabel_day_type.day_type_id
                          LEFT JOIN okb_db_resurs ON okb_db_tabel.ID_resurs = okb_db_resurs.ID
                          LEFT JOIN okb_db_zadanres ON okb_db_zadanres.DATE = okb_db_tabel.DATE AND okb_db_zadanres.ID_resurs = okb_db_tabel.ID_resurs 
                          #AND okb_db_zadanres.SMEN = okb_db_tabel.SMEN
                        WHERE
                        okb_db_tabel.DATE >= $from
                        AND
                        okb_db_tabel.DATE <= $to 
                        AND
                        okb_db_tabel.`ID_resurs` IN ( ". ( join(",", $emp_list )) ."
                        )";

//          echo $query;

          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }
           while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
           {
              $res_id = $row-> res_id ;
              $rec_id = $row-> rec_id ;
              $res_name = conv( $row-> res_name );
              $shift = $row-> shift ;
              $multimachine_fact = $row-> multimachine_fact ;

              if( is_null( $row-> day_type ))
                $day_type = '';
                  else
                    $day_type = conv(   $row-> day_type );

              $hours = $row-> hours ;
              $shift_date = 1 * substr( $row-> shift_date, 6 );
              $multy_tool = $row-> is_multimachine ?  $row-> is_multimachine : 0 ;

              $val = [ 'rec_id' => $rec_id, 'date' => $row-> shift_date, 'shift' => $shift, 'day_type' => $day_type, 'hours' => $hours, 'multy_tool' => $multy_tool, 'multimachine_fact' => $multimachine_fact ];

              if( isset( $table[ $res_id ] ) )
                $table[ $res_id ]['items'][ $shift_date ] = $val ;
                else
                   $table[ $res_id ] = [ 'res_id' => $res_id , 'res_name' => $res_name, 'items' => [ $shift_date => $val ] ];
           }
    } //   if( count( $emp_list ) )

		return $table ;
}
