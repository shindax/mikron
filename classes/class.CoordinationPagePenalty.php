<?php

class CoordinationPagePenalty
{
  protected $pdo ;  
  protected $page_id ;  
  protected $creation_date ;

  protected $krz_id ;
  protected $krz_name ;  
  protected $krz_det_name ;    
 
  protected $coop = false;
  protected $special_activity = false ;

  protected $data = [];

  public function __construct( $pdo, $page_id )
  {
    $this -> pdo = $pdo ;
    $this -> page_id = $page_id ;
    $this -> CollectData();
  }

  private function CollectData()
  {
       $page_id = $this -> page_id; 
       $ord = 0 ;          
       $coordinated = 0 ;
       $minutes_to_penalty = 0;


          try
          {
                  $query = "SELECT krz2_detitems.TID AS tid
                            FROM okb_db_krz2 AS krz2
                            LEFT JOIN okb_db_krz2det AS krz2_det ON krz2_det.ID_krz2 = krz2.ID
                            LEFT JOIN okb_db_krz2detitems AS krz2_detitems ON krz2_detitems.ID_krz2det = krz2_det.ID
                            WHERE 
                            krz2.ID = $page_id" ;
                  $stmt = $this -> pdo ->prepare( $query );
                  $stmt -> execute();
              }
              catch (PDOException $e)
              {
                die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
              }

             while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
             {
              if( $row ->  tid == 2 )
                $this -> coop = true ;

              if( $row ->  tid == 5 )
                $this -> special_activity = true ;
             }


          try
          {
                  $query = "SELECT DISTINCT

                            pr.ord AS ord, 
                            pt.id AS task_id,

                            krz.NAME AS krz_name,
                            krz.ID AS krz_id,

                            krz_det.NAME AS krz_det_name,
                            

                            pr.caption AS row_caption, 
                            pr.minutes_to_penalty AS minutes_to_penalty,

                            pr.penalty AS penalty,
                            pr.penalty2 AS penalty2,
                            pr.penalty3 AS penalty3,
                           

                            pt.caption, 
                            pt.agreed_flag AS agreed_flag,                            

                            pi.ins_time, 
                            pi.timestamp, 
                            pi.id,

                            cp.timestamp AS creation_date,

                            pi.frozen_by AS frozen_by,
                            DATE_FORMAT( pi.frozen_at, '%d.%m.%Y %H:%i') as frozen_at,
                            
                            cp.coordinated

                            FROM coordination_pages AS cp
                            LEFT JOIN coordination_page_items AS pi ON pi.page_id = cp.id
                            LEFT JOIN coordination_pages_task AS pt ON pt.id = pi.task_id
                            LEFT JOIN coordination_pages_rows AS pr ON pr.id = pi.row_id
                            LEFT JOIN okb_db_krz2 AS krz ON krz.ID = cp.krz2_id
                            LEFT JOIN okb_db_krz2det AS krz_det ON krz_det.ID_krz2 = krz.ID
                            LEFT JOIN okb_db_krz2detitems AS krz_detitems ON krz_detitems.ID_krz2det = krz_det.ID
                            WHERE 
                            cp.krz2_id = $page_id
                            AND
                            pi.ignored = 0
                            ORDER BY pr.ord, pi.task_id" ;
                  $stmt = $this -> pdo ->prepare( $query );
                  $stmt -> execute();

                  // echo $query;

              }
              catch (PDOException $e)
              {
                die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
              }

             while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
                {
                  $this -> krz_name = $row -> krz_name;
                  $this -> krz_det_name = $row -> krz_det_name;
                  $this -> creation_date = $row -> creation_date;
                  $this -> krz_id = $row -> krz_id;

                  $data = $this -> data;
                  $ord = $row -> ord ;
                  $task_id = $row -> task_id ;
                  $coordinated = $row -> coordinated;
                  $from = "";
                  $to = "";
                 
                  $minutes_to_penalty = $row -> minutes_to_penalty;

                  $penalty_rate = $row -> penalty;
                  $penalty_rate2 = $row -> penalty2;
                  $penalty_rate3 = $row -> penalty3;                                    
                  $frozen_by = $row -> frozen_by;
                  $frozen_at = $row -> frozen_at;

                  switch( $ord )
                  {
                    case 1 : // Инициатор заказа
                            if( $task_id == $ord )
                              {
                                $from = $row -> timestamp ;
                                $to = $row -> ins_time ;
                              }
                              else
                                $ord = 0 ;
                            break ;

                    case 2 : // Коммерческий директор
                                $from = $data[ $ord - 1 ]['to'];
                                $to = $row -> ins_time ;
                            break ;

                    case 3 : // Технический директор

                              $from = $data[ $ord - 1 ]['to'];
                              if( $task_id == 18 )
                                  $to = $row -> ins_time ;
                                    // else
                                    //   $ord = 0 ;

                                $to = $row -> ins_time ;
                            break ;

                    case 4 : // Главный инженер
                                $from = $data[3]['to'];
                                $to = $row -> ins_time ;

                              if( $this -> special_activity )
                                $minutes_to_penalty = 60;

                              break ;

                    case 5 : // Начальник ОМТС
                              $from = $data[4]['to'];
                              if( $task_id == 5 || $task_id == 6 || $task_id == 7 )
                                  $to = $row -> ins_time ;
                                    else
                                      $ord = 0 ;

                                $to = $row -> ins_time ;
                              break ;

                    case 6 : // Начальник ОВК
                              $from = $data[5]['to'];

                              if( $task_id == 5 || $task_id == 6 || $task_id == 7 )
                                  $to = $row -> ins_time ;
                                    else
                                      $ord = 0 ;

                              if( $this -> coop )
                                $minutes_to_penalty = 180;
                              $to = $row -> ins_time ;
                              break ;

                    case 7 : // Начальник ПДО
                              $from = $data[6]['to'];
                              if( $task_id == 13 || $task_id == 14 || $task_id == 15 )
                                  $to = $row -> ins_time ;
                                    else
                                      $ord = 0 ;
                              break ;

                    case 8 : // Начальник производства
                              $from = $data[7]['to'];
                              $to = $row -> ins_time ;
                              break ;

                    default : 
                  }

                  if( $ord )
                  {
                    self :: AdjustFrom( $from, $to );
                    $int = $this -> GetDateTimeDiff( $from, $to, $ord );
                    $total = $int[ 'total' ];

                    $row_caption = $row -> row_caption;
                    $krz_name = $row -> krz_name;
                    $krz_id = $row -> krz_id;

                    $penalty = 0;
                    $penalty2 = 0 ;
                    $penalty3 = 0 ;                    

                    $this -> CalcPenalty( $total, $penalty, $penalty2, $penalty3, $penalty_rate, $penalty_rate2, $penalty_rate3, $minutes_to_penalty );

                    $this -> data[ $ord ] = 
                    [ 
                      'row_caption' => $row_caption, 
                      'krz_name' => $krz_name,
                      'krz_id' => $krz_id,
                      'ord' => $ord,
                      'from' => $from , 
                      'to' => $to, 
                      'diff' => $int, 
                      'minutes_to_penalty' => $minutes_to_penalty , 
                      'penalty_rate' => $penalty_rate, 
                      'penalty_rate2' => $penalty_rate2, 
                      'penalty_rate3' => $penalty_rate3,

                      'frozen_by' => $frozen_by,
                      'frozen_at' => $frozen_at,

                      'penalty' => $penalty,
                      'penalty2' => $penalty2,
                      'penalty3' => $penalty3,
                    ];

                    // if( $frozen_by )
                    // {
                    //   $this -> data[ $ord ]['frozen_by'] = $frozen_by;
                    //   $this -> data[ $ord ]['frozen_at'] = $frozen_at;
                    // }

                  }
                }

          $ord += 1;

          try
          {
                  $query = "SELECT 
                            caption, penalty, penalty2, penalty3, minutes_to_penalty
                            FROM coordination_pages_rows
                            WHERE ord = $ord " ;
                  $stmt = $this -> pdo ->prepare( $query );
                  $stmt -> execute();

                  // echo $query;

              }
              catch (PDOException $e)
              {
                die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
              }

      if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
      {
        $row_caption = $row -> caption;
        $minutes_to_penalty = $row -> minutes_to_penalty;
        $penalty_rate = $row -> penalty;
        $penalty_rate2 = $row -> penalty2;
        $penalty_rate3 = $row -> penalty3;        
      }

      $int = $this -> GetDateTimeDiff( $to, $coordinated, $ord );
      $total = $int[ 'total' ];
      $penalty = 0;
      $penalty2 = 0 ;
      $penalty3 = 0 ;                    

      $this -> CalcPenalty( $total, $penalty, $penalty2, $penalty3, $penalty_rate, $penalty_rate2, $penalty_rate3, $minutes_to_penalty );

      $this -> data[ $ord ] = [ 'row_caption' => $row_caption, 'krz_name' => $krz_name,'krz_id' => $krz_id,'ord' => $ord,'from' => $to , 'to' => $coordinated, 'diff' => $int,'minutes_to_penalty' => $minutes_to_penalty,'penalty_rate' => $penalty_rate, 'penalty_rate2' => $penalty_rate2, 'penalty_rate3' => $penalty_rate3, 'penalty' => $penalty , 'penalty2' => $penalty2 , 'penalty3' => $penalty3, ];

  } // private function CollectData()

  public function GetData()
  {
    return $this -> data ;
  }

  public function GetName()
  {
    return $this -> krz_name ;
  }

  private function GetDateTimeDiff( $from, $to, $ord = 0 )
  {
      $datetime1 = new DateTime( $from );
      $datetime2 = new DateTime( $to );

      $yearfrom = $datetime1->format('Y');
      $monthfrom = $datetime1 -> format('m');
      $dayfrom = $datetime1 -> format('d');

      $interval = $datetime1 -> diff( $datetime2 );

      $hour_from = + $datetime1->format('H') ;
      $hour_to = + $datetime2->format('H') ;
      $cond = 0 ;

// До 10 часов
      if( $interval -> y == 0 && $interval -> m == 0 && $interval -> d == 0 && $hour_from < 10 && $hour_to < 10 )
          {
              $datetime2 = $datetime1 ;
              $interval = $datetime1 -> diff( $datetime2 );
              $cond = "1.1" ;
          }

// После 10 часов
      if( 
          $interval -> y == 0 
          && $interval -> m == 0 
          && $interval -> d == 0 
          && $hour_from < 10 
          && $hour_to >= 10  
          && $hour_to <= 12 
        )
          {
              $datetime1 = new DateTime( "$yearfrom-$monthfrom-$dayfrom 10:00" );
              $interval = $datetime1 -> diff( $datetime2 );
              $cond = "1.2" ;
          }

// Если не прошли сутки до начала этапа
      if( $interval -> y == 0 && $interval -> m == 0 && $interval -> d == 0 ) 
      {
        $cond = "2" ;

// В первой половине дня, этап принят до 10 часов
      if( $hour_from >= 10 && $hour_from <= 12 && $hour_to < 10 )
          {
              $datetime2 = new DateTime( "$yearfrom-$monthfrom-$dayfrom 10:00" );
              // $datetime2 -> add(new DateInterval('PT6H'));
              $cond = "2.1" ;
          }

// Во второй половине дня
          if( $hour_from >= 13 && $hour_from <= 17 &&  $hour_to >= 17  )
          {
              $datetime2 = new DateTime( "$yearfrom-$monthfrom-$dayfrom 13:00" );
              $datetime2 -> add(new DateInterval('PT4H'));
              $cond = "2.2" ;
          }
           
// Без времени : 00:00:00
          if( $hour_from >= 13 && $hour_from <= 17 &&  $hour_to == 0 && $min_to == 0 )
          {
              $datetime2 = new DateTime( "$yearfrom-$monthfrom-$dayfrom 13:00" );
              // $datetime2 -> add(new DateInterval('PT4H'));
              $cond = "2.3" ;
          }

          $interval = $datetime1 -> diff( $datetime2 );              
      }

      if( $to != "0000-00-00 00:00:00" )
      {
        $int = [ 
                  'y' => $interval -> y, 
                  'm' => $interval -> m, 
                  'd' => $interval -> d, 
                  'hour' => $interval -> h, 
                  'min' => $interval -> i,
                  'total' => 
                      $interval -> y * 365 * 6 * 60 + // В сутках берется 6 рабочих часов, а не 24
                      $interval -> m * 30 * 6 * 60 +  // В сутках берется 6 рабочих часов, а не 24
                      $interval -> d * 6 * 60 +       // В сутках берется 6 рабочих часов, а не 24
                      $interval -> h * 60 +
                      $interval -> i,
                    'cond' => $cond
                ];
      }
                    else
                      $int = [];                   
      return $int ;
  }


  protected static function DecodeDateTime( $val )
  {
    $date = new DateTime( $val );

    $year = $date->format('Y');
    $month = $date->format('m');
    $day = $date->format('d');
    $hour = $date->format('H');
    $min = $date->format('i');
    return [ 'day' => $day , 'month' => $month, 'year' => $year, 'hour' => $hour, 'min' => $min ];
  }

  protected static function AdjustFrom( &$from, &$to )
  {
    $datefrom = new DateTime( $from );
    $dateto = new DateTime( $to );

    $yearfrom = $datefrom->format('Y');
    $yearto = $dateto->format('Y');

    $monthfrom = $datefrom -> format('m');
    $monthto = $dateto -> format('m');    

    $dayfrom = $datefrom -> format('d');
    $dayto = $dateto -> format('d');

    $hourfrom = $datefrom -> format('H');
    $hourto = $dateto -> format('H');

    $minfrom = $datefrom -> format('i');
    $minto = $dateto -> format('i');

    $day_type_from = $datefrom -> format('w');
    $day_type_to = $dateto -> format('w');

    if( ( ( $hourfrom >= 16 && $minfrom > 30 ) || $hourfrom >= 17 ) && $dayfrom != $dayto ) 
    {
      $date = new DateTime( "$yearfrom-$monthfrom-$dayfrom 10:00" );

// Учет выходных дней
      switch( $day_type_from )
      {
        case 0 :
        case 1 :
        case 2 :
        case 3 :
        case 4 : $date -> add(new DateInterval('P1D')); break;
        case 5 : $date -> add(new DateInterval('P3D')); break;
        case 6 : $date -> add(new DateInterval('P2D')); break;
      }

      $from = $date -> format('Y-m-d H:i:s');
    }

    if( $hourfrom >= 12 && $hourfrom < 13 && $dayfrom == $dayto )
    {
      $date = new DateTime( "$yearfrom-$monthfrom-$dayfrom 13:00" );
      $from = $date -> format('Y-m-d H:i:s');
    }
    
    if( $hourfrom >= 13 && $hourto >= 17 && $dayfrom == $dayto )
    {
      $date = new DateTime( "$yearfrom-$monthfrom-$dayfrom $hourfrom:$minfrom" );
      $from = $date -> format('Y-m-d H:i:s');
    }

  } // protected static function AdjustFrom( &$from, &$to )


  private function CalcPenalty( &$total, &$penalty, &$penalty2, &$penalty3, &$penalty_rate, &$penalty_rate2, &$penalty_rate3, &$minutes_to_penalty )
  {
      if( ( $total - $minutes_to_penalty >= 1 ) && ( $total - $minutes_to_penalty <= 360 ))
          $penalty = $penalty_rate;

      if( ( $total - $minutes_to_penalty >= 361 ) && ( $total - $minutes_to_penalty <= 720 ))
      {
          $penalty = 0;
          $penalty2 = $penalty_rate2;
      }

      if( $total - $minutes_to_penalty >= 721 )
      {
          $penalty = 0;
          $penalty2 = 0;
          $penalty3 = $penalty_rate3;
      }
  }


  private function CalcPenalty2( &$total, &$penalty, &$penalty2, &$penalty3, &$penalty_rate, &$penalty_rate2, &$penalty_rate3, &$minutes_to_penalty )
  {
      if( ( $total - $minutes_to_penalty >= 1 ) && ( $total - $minutes_to_penalty <= 360 ))
          $penalty = ( $total - $minutes_to_penalty ) * $penalty_rate;

      if( ( $total - $minutes_to_penalty >= 361 ) && ( $total - $minutes_to_penalty <= 720 ))
      {
          $penalty = 0;
          $penalty2 = ( $total - $minutes_to_penalty ) * $penalty_rate2;
      }

      if( $total - $minutes_to_penalty >= 721 )
      {
          $penalty = 0;
          $penalty2 = 0;
          $penalty3 = ( $total - $minutes_to_penalty ) * $penalty_rate3;
      }
  }
}

