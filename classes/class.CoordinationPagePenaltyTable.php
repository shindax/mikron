<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.CoordinationPagePenalty.php" );

class CoordinationPagePenaltyTable extends CoordinationPagePenalty
{
  public function __construct( $pdo, $page_id )
  {
    parent::__construct( $pdo, $page_id );
  }

  public function GetDetailTableCaption()
  {
    $str = "<table class='tbl coordination-page-penalty-table'>";

    $str .= "<col width='10'>";
    $str .= "<col width='10'>";
    $str .= "<col width='7'>";            
    $str .= "<col width='10'>";
    $str .= "<col width='8'>";
    $str .= "<col width='8'>";        
    $str .= "<col width='8'>";    
    $str .= "<col width='5'>";    
    $str .= "<col width='5'>";    
    $str .= "<col width='5'>";            
    $str .= "<col width='5'>";        
    $str .= "<col width='5'>";    

    $str .= "<tr class='first'>";
    $str .= "<td class='Field AC'>".conv("№ КРЗ")."</td>";
    $str .= "<td class='Field AC'>".conv("Наименование")."</td>";    
    $str .= "<td class='Field AC'>".conv("Дата")."</td>";
    $str .= "<td class='Field AC'>".conv("Исполнитель")."</td>";
    $str .= "<td class='Field AC'>".conv("Время на обработку")."</td>";
    $str .= "<td class='Field AC'>".conv("Время обработки")."</td>";
    $str .= "<td class='Field AC'>".conv("Время просрочки")."</td>";    
    $str .= "<td class='Field AC'>".conv("Ставки штрафов")."</td>";    
    $str .= "<td class='Field AC'>".conv("Штраф 1")."</td>";
    $str .= "<td class='Field AC'>".conv("Штраф 2")."</td>";
    $str .= "<td class='Field AC'>".conv("Штраф 3")."</td>";        
    $str .= "<td class='Field AC'>".conv("Штраф итого")."</td>";    
    $str .= "</tr>";
    return $str;
  }

  public static function GetFinalTableCaption()
  {
    $str = "<h3>Итого по предприятию за период</h3>";
    $str .= "<table class='tbl coordination-page-penalty-table'>";

    $str .= "<col width='50'>";
    $str .= "<col width='10'>";
    $str .= "<col width='10'>";
    $str .= "<col width='10'>";
    $str .= "<col width='10'>";
    $str .= "<col width='10'>";
    $str .= "<col width='10'>";    

    $str .= "<tr class='first'>";
    $str .= "<td class='Field AC'>".conv("Исполнитель")."</td>";
    $str .= "<td class='Field AC'>".conv("Время просрочки")."</td>";    
    $str .= "<td class='Field AC'>".conv("Ставки штрафов")."</td>";    
    $str .= "<td class='Field AC'>".conv("Штраф 1")."</td>";
    $str .= "<td class='Field AC'>".conv("Штраф 2")."</td>";
    $str .= "<td class='Field AC'>".conv("Штраф 3")."</td>";
    $str .= "<td class='Field AC'>".conv("Штраф итого")."</td>";    
    $str .= "</tr>";
    return $str;
  }

  public static function GetFinalTable( $data )
  {
    $str = self :: GetFinalTableCaption();
    $classes = ["even","odd"];

    foreach ( $data as $key => $value ) 
    {
      $penalty_rate = $value['penalty_rate'];
      $penalty_rate2 = $value['penalty_rate2'];
      $penalty_rate3 = $value['penalty_rate3'];            

      $str .= "<tr class='".( $classes[ $key % 2 ] )."'>";
      $str .= "<td class='Field AC'>".conv( $value['caption'] )."</td>";
      $total_minutes = $value['total_minutes'];

      $minutes = self :: DecodeMinutes( $total_minutes );
      $str .= "<td class='Field AC'>".conv( $minutes )."</td>";
      
      $minutes_to_penalty = $value['total_minutes'];
      
      $str .= "<td class='Field AC'>".
      ( $value['penalty_rate'] ).conv(" руб.")."<br>".
      ( $value['penalty_rate2'] ).conv(" руб.")."<br>".
      ( $value['penalty_rate3'] ).conv(" руб.").
      "</td>";

      $penalty = $value['penalty'] ;
      $penalty2 = $value['penalty2'] ;
      $penalty3 = $value['penalty3'] ;
      $pages = $value['pages'] ;      

      $str .= "<td class='Field AC'>".( $penalty ? number_format( $penalty, 0, "", " ").conv(" руб.") : '-')."</td>";    

      $str .= "<td class='Field AC'>".( $penalty2 ? number_format( $penalty2, 0, "", " ").conv(" руб.") : '-')."</td>";    

      $str .= "<td class='Field AC'>".( $penalty3 ? number_format( $penalty3, 0, "", " ").conv(" руб.") : '-')."</td>";    

      $total = $penalty + $penalty2 + $penalty3;
      $str .= "<td class='Field AC'><span class='final_span' title='".conv("Нажмите, чтобы посмотреть листы с нарушениями")."'>".( $total ? number_format( $total, 0, "", " ").conv(" руб.") : '-')."</span><div class='hidden penalty_pages'>
      ".join('<br>', $pages )."</div>
      </td>";    

      $str .= "</tr>";      
    }

    $str .= "</table>";
    return $str;  
  }

  public function GetTable()
  {
    $data = $this -> data;
    $str = $this -> GetDetailTableCaption();
    $classes = ["even","odd"];

    $cnt = count( $data );
    
    $date = new DateTime( $this -> creation_date );
    $creation_date = $date->format('m.d.Y');

    $krz_name = conv( $this -> krz_name );
    $krz_id = $this -> krz_id;

    $str .= "<tr>";
    $str .= "<td class='Field AC' rowspan='$cnt'><a target = '_blank' href='index.php?do=show&formid=30&id=$krz_id'> $krz_name</a></td>";
    $str .= "<td class='Field AC' rowspan='$cnt'>".conv( $this -> krz_det_name )."</td>";    
    $str .= "<td class='Field AC' rowspan='$cnt'>$creation_date</td>";        

    foreach ( $data AS $key => $val ) 
    {
      $from = $val['from'];
      $to = $val['to'];
      $delay = $val['diff']['total'] - $val['minutes_to_penalty'];

      if( $val['penalty'] <= 0 )
        $val['penalty'] = 0;
      $delay = $delay <= 0 ? "-" : $delay." мин.";

      if( $key != 1 )
        $str .= "<tr class='".( $classes[ $key % 2 ] )."'>";
      
      $delay_min = self :: GetReadableDateTime( $val['diff'] );

      $delay_class = '';
      if( $from == "0000-00-00 00:00:00" && $to == "0000-00-00 00:00:00" )
      {
          $delay_class = 'not-begun';
          $delay_min = "Этап не начат";
          $delay = "-";
          $val['diff']['total'] = 0 ;
      }

      if( $from != "0000-00-00 00:00:00" && $to == "0000-00-00 00:00:00" )
      {
          $delay_class = "expired $from $to";
          $date_time = self :: DecodeDateTime( $from );
          $delay_min = "Этап не завершен.<br>Начат ".$date_time['day'].".".$date_time['month'].".".$date_time['year']." ".$date_time['hour'].":".$date_time['min'];
          
          if( $this -> frozen_by ) 
          {
              $delay_min = "Этап заморожен ".$this-> frozen_at;
              $delay_class = "frozen $from $to";
          }

          $delay = "-";
          $val['penalty'] = 0 ;
          $val['penalty2'] = 0 ;
          $val['penalty3'] = 0 ;                    
          $val['diff']['total'] = 0 ;
          $val['diff']['y'] = 0 ;
      }

      $str .= "<td class='Field AC'>".conv( $val['row_caption'] )."</td>";
      $str .= "<td class='Field AC'>".conv( $val['minutes_to_penalty']." мин." )."</td>";      
      $str .= "<td class='Field AC $delay_class'>".conv( $delay_min )."</td>";
      $str .= "<td class='Field AC $delay_class'>".conv( $delay )."</td>";
      $str .= "<td class='Field AC $delay_class'>".( $val['penalty_rate'] ).conv(" руб.")."<br>".
      ( $val['penalty_rate2'] ).conv(" руб.")."<br>".
      ( $val['penalty_rate3'] ).conv(" руб.").      
      "</td>";

      $total = $val['penalty'] + $val['penalty2'] + $val['penalty3'];

      $str .= "<td class='Field AC $delay_class'>".( $val['penalty'] ? number_format( $val['penalty'], 0, "", " ").conv(" руб.") : '-')."</td>";

      $str .= "<td class='Field AC $delay_class'>".( $val['penalty2'] ? number_format( $val['penalty2'], 0, "", " ").conv(" руб.") : '-')."</td>";

      $str .= "<td class='Field AC $delay_class'>".( $val['penalty3'] ? number_format( $val['penalty3'], 0, "", " ").conv(" руб.") : '-')."</td>";

      $str .= "<td class='Field AC $delay_class'>".( $total ? number_format( $total, 0, "", " ").conv(" руб.") : '-')."</td>";

      if( $key == 1 )
       $str .= "</tr>";
    }

    $str .= "</table>";
    return $str;
  }

  private static function GetReadableDateTime( $val )
  {
    $str = "";
    if( $val['y'] )
      $str .= $val['y']."г. ";
    
    if( $val['m'] )
      $str .= $val['m']."мес. ";
    
    if( $val['d'] )
      $str .= $val['d']."дн. ";
    
    if( $val['hour'] )
      $str .= $val['hour']."час. ";
    
    if( $val['min'] )
      $str .= $val['min']."мин. ";
    
    if( $val['total'] )
      $str .= "( всего : ".$val['total']." мин. )";

    return $str;
  }

  private static function seconds2times( $seconds )
  {
    $times = array();
    
    // считать нули в значениях
    $count_zero = false;
    $periods = array(60, 3600, 86400, 31536000);
    
    for ($i = 3; $i >= 0; $i--)
    {
      $period = floor($seconds/$periods[$i]);
      if (($period > 0) || ($period == 0 && $count_zero))
      {
        $times[$i+1] = $period;
        $seconds -= $period * $periods[$i];
        
        $count_zero = true;
      }
    }
    
    $times[0] = $seconds;
    return $times;
  }

  public static function DecodeMinutes( $val )
  {
    if( $val )
    {
      $str = "";
      $times_values = array('сек.','мин.','час.','д.','лет');
      $times = self :: seconds2times( $val * 60 );
      for ( $i = count( $times ) - 1; $i >= 0; $i-- )
      {
        if( $i )
        {
          if( $times[$i] )
            $str .= $times[$i] . ' ' . $times_values[$i] . ' ';
        }
      }

    $str .= "( всего : $val мин. )";    
    }
    else
        $str = "-";  

    return $str ;    
  }


} // class CoordinationPagePenaltyTable extends CoordinationPagePenalty




