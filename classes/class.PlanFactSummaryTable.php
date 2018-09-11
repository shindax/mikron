<?php
error_reporting( E_ALL );
error_reporting( E_ERROR );

class PlanFactSummaryTable
  {
      protected $pdo ;
      protected $direction ;
      protected $responible ;
      protected $field_names = [] ;
      protected $field_arr  = [] ;
      protected $unread_messages ;
      protected $head_id ;
      protected $penalty_rate ;

      protected $penalty_str ;
      protected $sections ;

      protected $filter  = "" ;
      protected $date_from  = "" ;
      protected $date_to  = "" ;
      protected $total_expired = 0 ;

      function __construct( $pdo, $direction, $penalty_rate, $filter = 0, $date_from=0, $date_to=0 )
      {
          $this -> sections = [
          conv("В работе"), conv( "Выполнено"), conv("Просрочено этапов / сумма"), conv( "3 дня до окончания срока")
      ];

          $this -> penalty_str = conv( "Количество штрафов по переносам сроков / сумма" );
          $this -> pdo = $pdo ;
          $this -> penalty_rate = $penalty_rate ;

         $this -> getUnreadNotifications( $direction );

          if( $filter )
              $this -> filter = $filter ;
                else
                  $this -> filter = " EDIT_STATE = 0";

          if( $date_from )
              $this -> date_from = new DateTime("00:00:00 $date_from");
                else
                   $this -> date_from = new DateTime("00:00:00 "."1900");

          if( $date_to )
              $this -> date_to = new DateTime("00:00:00 $date_to");
                else
                   $this -> date_to = new DateTime("00:00:00 now");

        try
                {
                    $query = "SELECT
                                    responsible_persons.persons AS persons,
                                    responsible_persons.fields AS fields,
                                    responsible_persons.note direction,
                                    users.FIO responsible
                                    FROM okb_db_responsible_persons AS responsible_persons
                                    LEFT JOIN okb_users AS users ON users.id = responsible_persons.persons->'$[0]'
                                    WHERE responsible_persons.id = $direction";
                    $stmt = $pdo -> prepare( $query );
                    $stmt -> execute();
                }
                catch (PDOException $e)
                {
                  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
                }

                $row = $stmt->fetch( PDO::FETCH_OBJ ); // One record
                $this -> direction = conv( $row ->  direction );
                $this -> responsible = conv( $row ->  responsible );

                $fields = json_decode( $row ->  fields );

                foreach( $fields AS $key => $value )
                {
                    $this -> field_names[] = conv( $key );
                    $this -> field_arr[] = $value ;
                }
      }


      protected function GetTableBegin()
      {
        if( $this -> unread_messages )
          $unread_messages = " &#128276; ". ( $this -> unread_messages ) ;
          else
            $unread_messages = '';

        $str =   "<div class='plan_fact_summary_div'><table class='table tbl plan_fact_summary_table'>
                    <col width='40%'>
                    <tr class='first'>
                    <td class='field'><span class='direction_name'>".$this -> direction."</span><span class='unread_notes' data-to-id='".( $this -> head_id )."'>$unread_messages</span></td>";
          foreach( $this -> field_names AS $name )
              $str .= "<td class='field'><span>$name</span></td>"  ;

          $str .= "</tr>"  ;
          return $str ;
      }

      protected function GetData()
      {
            $result_arr = [];
            $zak_arr = [];
            $penalties_arr = [];

            foreach( $this -> field_arr AS $key => $value )
              {
                  $result_arr[ 0 ][ $key ] = 0 ;
                  $result_arr[ 1 ][ $key ] = 0 ;
                  $result_arr[ 2 ][ $key ] = 0 ;
                  $result_arr[ 3 ][ $key ] = 0 ;
                  $result_arr[ 4 ][ $key ] = 0 ;

                  $zak_arr[ 0 ][ $key ] = "" ;
                  $zak_arr[ 1 ][ $key ] = "" ;
                  $zak_arr[ 2 ][ $key ] = "" ;
                  $zak_arr[ 3 ][ $key ] = "" ;
                  $zak_arr[ 4 ][ $key ] = "" ;
              }//foreach( $this -> field_arr AS $key => $value )

            $field_list = join(",", $this -> field_arr );
            $query = "SELECT ID,  $field_list FROM `okb_db_zak` WHERE ".( $this -> filter );

            try
              {
                  $stmt = $this -> pdo -> prepare( $query );
                  $stmt -> execute();
              }
              catch (PDOException $e)
              {
                die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage(). ". Query is : ". $query );
              }
              while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
                {
                    $zak_id = $row -> ID;

                        foreach( $this -> field_arr AS $key => $field )
                        {
                            $val_str = $row -> $field ;
                            $arr = $this -> getBreakApartPD( $val_str );

                            if( 1 * $arr['state'] ) // Если выполнено
                            {
                                if( $arr['date_changes_count'] == 1 )
                                {
                                      $result_arr[1][ $key ] ++ ; // Выполнено без переносов сроков
                                      $zak_arr[1][ $key ] .= "$zak_id,";
                                }
                                else
                                {
                                    $penalties = $this -> getShiftCauses( $zak_id, $field );

                                    if( isset( $penalties_arr[ $key ]['total'] ) )
                                        $penalties_arr[ $key ]['total'] += $penalties['total'];
                                        else
                                            $penalties_arr[ $key ]['total'] = $penalties['total'];

                                    if( isset( $penalties_arr[ $key ]['count'] ) )
                                        $penalties_arr[ $key ]['count'] += $penalties['count'];
                                    else
                                        $penalties_arr[ $key ]['count'] = $penalties['count'];

                                    if( ! isset( $penalties_arr[ $key ]['orders'] ) )
                                        $penalties_arr[ $key ]['orders'] = [];

                                    foreach( $penalties ['causes'] AS $ckey => $cvalue )
                                        if( isset( $penalties_arr[ $key ]['orders'][$zak_id][$field][$ckey] ))
                                            $penalties_arr[ $key ]['orders'][$zak_id][$field][$ckey] += $cvalue;
                                                else
                                                    $penalties_arr[ $key ]['orders'][$zak_id][$field][$ckey] = $cvalue;

                                    $result_arr[2][ $key ] ++ ; // Выполнено с переносами сроков
                                    $zak_arr[2][ $key ] .= "$zak_id,";
                                }
                            }// if( 1 * $arr['state'] ) // Если выполнено
                              else // Если ещё не выполнено
                              {
                                  if( $arr['date_changes_count'] == 1 ) // Не было переносов дат
                                    {
                                        $zak_arr[0][ $key ] .= "$zak_id,";
                                    }
                              else { // Были переносы дат
                                  $penalties = $this->getShiftCauses($zak_id, $field);

                                  if( isset( $penalties_arr[ $key ]['total'] ) )
                                      $penalties_arr[ $key ]['total'] += $penalties['total'];
                                  else
                                      $penalties_arr[ $key ]['total'] = $penalties['total'];

                                  if( isset( $penalties_arr[ $key ]['count'] ) )
                                      $penalties_arr[ $key ]['count'] += $penalties['count'];
                                  else
                                      $penalties_arr[ $key ]['count'] = $penalties['count'];

                                  if( ! isset( $penalties_arr[ $key ]['orders'] ) )
                                      $penalties_arr[ $key ]['orders'] = [];

                                  foreach( $penalties ['causes'] AS $ckey => $cvalue )
                                      if( isset( $penalties_arr[ $key ]['orders'][$zak_id][$field][$ckey] ))
                                          $penalties_arr[ $key ]['orders'][$zak_id][$field][$ckey] += $cvalue;
                                      else
                                          $penalties_arr[ $key ]['orders'][$zak_id][$field][$ckey] = $cvalue;
                              }// else Были переносы дат

                                  $date = $arr['last_date'];
                                  $date_from = $this->date_from;
                                  $date_to = $this->date_to;
                                  $date_diff_from = $this->getDayDiff($date_from, $date);
                                  $date_diff_to = $this->getDayDiff($date_to, $date);

                                  if ($date_diff_from >= 0)
                                  {
                                      if ( $date_diff_to < 0 && strlen($date) )
                                      {
                                          $result_arr[3][$key]++; // Просрочка
                                          $zak_arr[3][$key] .= $row->ID . ",";
                                      } // if ( $date_diff_to < 0 && strlen($date))
                                      else
                                          {
                                          if ( $date_diff_to < 3 && strlen($date))
                                          {
                                              $result_arr[4][$key]++; // Осталось три дня
                                              $zak_arr[4][$key] .= $row->ID . ",";
                                          } //if ( $date_diff_to < 3 && strlen($date))
                                          else
                                            {
                                              $result_arr[0][$key]++; // В работе
                                              $zak_arr[0][$key] .= $row->ID . ",";
                                            }// else if ( $date_diff_to < 3 && strlen($date))
                                      } // else if ( $date_diff_to < 0 && strlen($date))
                                  } // if ($date_diff_from >= 0)
                              } // else // Если ещё не выполнено
                        }// foreach( $this -> field_arr AS $key => $field )
                } // while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )

              return [ "res_arr" => $result_arr, "zak_arr" => $zak_arr, "penalties_arr" => $penalties_arr ];
      }



      protected function GetTableContent()
      {
          $res = $this -> GetData();
          $result_arr = $res['res_arr'];
          $zak_arr = $res['zak_arr'];
          $penalties_arr = $res['penalties_arr'];

// Jointing 1-st and 2-nd elements of $res array
          foreach( $result_arr[1] AS $key => $val  )
              $result_arr[1][$key] += $result_arr[2][$key];

          unset( $result_arr[2] );
          $result_arr = array_values($result_arr);

          foreach( $zak_arr[1] AS $key => $val  )
              $zak_arr[1][$key] .= ",".$zak_arr[2][$key];

          unset( $zak_arr[2] );
          $zak_arr = array_values($zak_arr);
//

          $str = "";
          $class = "";

          foreach( $result_arr AS $key => $value )
          {
             if( $key == 3 )
                  $class = "coming_soon";


            if( $key == 2 )
              $class = "expired";

            $str .= "<tr class='data_row'><td class='field AL'><span>".( $this -> sections[ $key ] )."</span></td>";
              foreach( $value AS $tdkey => $td )
              {
                $zak_str = $zak_arr[ $key ][ $tdkey ];

                          if( $key == 2 )
                          {
                              $penalty = $td * $this -> penalty_rate ;
                              $td .= " / $penalty";
                              $this -> total_expired += $penalty ;
                          }
                            if( $td == 0 )
                              $str .= "<td class='field AC'><span class='zero_span'>-</span></td>";
                                else
                                    $str .= "<td class='field AC'><span class='value_span $class' data-id='$zak_str'>$td</span></td>";
                }
            $str .= "</tr>";
          } // foreach( $result_arr AS $key => $value )

// Строка со штрафами
            $str .= "<tr class='data_row penalties'><td class='field AL'><span>".( $this -> penalty_str )."</span></td>";
              foreach( $value AS $pkey => $pval )
              {
                  $penalty_arr = [];
                  $pd_arr = [];

                  $orders = [];
                  if( isset( $penalties_arr[$pkey]['orders']) )
                    $orders = $penalties_arr[$pkey]['orders'];

                  foreach( $orders  AS $key => $order )
                  {
                      $penalty_arr[] = $key ;
                      foreach( $order AS $pdkey => $pd )
                          foreach( $pd AS $pdsubkey => $val )
                          $pd_arr[] = $key.":".$pdkey.":".$pdsubkey ;
                  }


                  $penalty_list = join(",", $penalty_arr );
                  $pd_list = join(",", $pd_arr );

                  if( $penalties_arr[$pkey]['count'] == 0 || $penalties_arr[$pkey]['total'] == 0 )
                        $str .= "<td class='field AC'><span class='zero_span'>- / -</span></td>";
                    else
                        $str .= "<td class='field AC'><span class='penalties_count_span value_span' data-id='$penalty_list' data-penalties-list='$pd_list'>".$penalties_arr[$pkey]['count']." / <summ>".$penalties_arr[$pkey]['total']."</summ></span></td>";

                }// foreach( $value AS $pkey => $pval )
            $str .= "</tr>";

          return $str ;
      }

      protected function GetTableEnd()
      {
          return "</table><div class='plan_fact_summary_expired_span_div'>
            <span class='plan_fact_summary_span'>".conv( "Штраф " )."</span>
            <span class='plan_fact_summary_span summ' data-summ='".( $this -> total_expired  )."'>".( $this -> total_expired  )."</span>
            <span class='plan_fact_summary_span'>".conv( "&nbsp;руб." )."</span><br><span class='expire_responsible'>".conv( "Ответственный&nbsp;:&nbsp;" ).( $this -> responsible )."</span></div></div><hr>"  ;
      }

      public function GetTable()
      {
        return $this -> GetTableBegin(). $this -> GetTableContent(). $this -> GetTableEnd() ;
      }

    private function getBreakApartPD( $str )
    {
        // Получаем начало PD : состояние и первая дата
        $state_and_dates_str = explode('#', $str ) ;
        $state_and_first_date = explode('|', $state_and_dates_str[0] );
        $log_state = $state_and_first_date[0] ;
        $state = $log_state ? '1' : '0';

        if( isset( $state_and_first_date[1] ))
          $first_date = $state_and_first_date[1] ;
            else
              $first_date = $state_and_first_date[1] ='';

        $last_date = $state_and_dates_str[ count( $state_and_dates_str ) - 1 ] ;
        $arr = ['state' => $state, 'first_date' => $this -> extractDate( $first_date ), 'last_date' => $this -> extractDate( $last_date ), 'date_changes_count' => ( count( $state_and_dates_str ) - 1 ) / 2 ];
        return $arr ;
    }

    private function extractDate( $val )
    {
      return explode(' ', $val )[0];
    }

    private function getDayDiff( $ref_date, $date )
    {
          $datetime2 = new DateTime("00:00:00 $date");
          $interval = $ref_date->diff( $datetime2 );
          return 1 * $interval->format('%R%a');
    }

    public function getShiftCauses( $zak_id, $field )
    {
        $field = mb_strtolower( $field );

         try
                 {
                    $query = "
                                  SELECT
                                  okb_db_plan_fact_carry_causes.rate rate,
                                  okb_db_plan_fact_carry_causes.id
                                  FROM
                                  okb_db_zak_ch_date_history
                                  INNER JOIN okb_db_plan_fact_carry_causes ON okb_db_zak_ch_date_history.cause = okb_db_plan_fact_carry_causes.id
                                   WHERE 
                                   pd='$field' 
                                   AND 
                                   `zak_id` = $zak_id
                                   AND
                                   `date_index` <> 0
                                   AND
                                   `rate` <> 0
                                   ";

                      $stmt = $this -> pdo -> prepare( $query );
                      $stmt -> execute();
                }
                catch (PDOException $e)
                {
                  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
                }

                $rate = 0 ;
                $count = 0 ;
                $result = [];
                $result [ 'count' ] = 0 ;
                $result [ 'total' ] = 0 ;
                $result [ 'causes' ] = [] ;

               while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
               {
                   $id = $row -> id ;

                  if( isset( $result['causes'][ $id ] ) )
                    $result ['causes'][ $id ] += 1 ;
                      else
                        $result ['causes'][ $id ] = 1 ;

                  $rate += $row -> rate ;
                  $count ++ ;
               }

                $result[ 'total' ] = $rate;
                $result[ 'count' ] = $count;
               return $result;
    }

    public function getUnreadNotifications( $direction )
    {

        try
                {
                    $query =       "SELECT responsible_persons.persons AS persons 
                                    FROM okb_db_responsible_persons AS responsible_persons
                                    WHERE responsible_persons.id = $direction";
                    $stmt = $this -> pdo -> prepare( $query );
                    $stmt -> execute();
                }
                catch (PDOException $e)
                {
                  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
                }

                $row = $stmt->fetch( PDO::FETCH_OBJ ); // One record
                $head_id = json_decode( $row -> persons )[0] ;
                $this -> head_id = $head_id ;

        try
                {
                    $query = "
                          SELECT COUNT( * ) count 
                          FROM `okb_db_plan_fact_notification` 
                          WHERE 
                          `to_user` = $head_id 
                          AND
                          ack = 0 
                          ";
                    $stmt = $this -> pdo -> prepare( $query );
                    $stmt -> execute();
                }
                catch (PDOException $e)
                {
                  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
                }

                $row = $stmt->fetch( PDO::FETCH_OBJ ); // One record
                $this -> unread_messages = $row -> count ;
    }
}