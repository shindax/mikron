<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once( "db.php" );

class OrdersCalendar
{
    private $dblocation ;
    private $pdo ;
    private $orders ;
    private $projects ;

    private $user_id ;
    private $user_name ;
    private $date ;
    private $imageName ;

   function GetColor( $index )
   {
      return "#ddd";
   }

    public function __construct( $dblocation, $dbname, $dbuser, $dbpasswd, $user_id )
    {
        $charset = 'utf8';
        $dsn = "mysql:host=$dblocation;dbname=$dbname;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try{
            $this -> pdo = new PDO($dsn,$dbuser, $dbpasswd, $opt);
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, at ".__LINE__." line. Can't connect : " . $e->getMessage());
        }

        $this -> user_id = $user_id ;
        $this -> imageName = "work_cal_".$user_id ;

        if( $user_id )
        {

        try
        {
            $query ="
                        SELECT
                        NAME
                        FROM `okb_db_resurs`
                        WHERE ID=".$this -> user_id;

            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }


        if ( $row = $stmt->fetch( PDO::FETCH_OBJ ))
                $this -> user_name = $row -> NAME ;

        }
        else
          $this -> user_name = "Администратор";

     }

    public function GetData( $date = '' )
    {
        $this -> date = $date ;

        try
        {
            $query ="
                        SELECT
                        okb_db_projects.`name` AS project_name,

                        okb_db_zak_type.description AS zak_type,
                        okb_db_zak.NAME AS zak_name,
                        okb_db_zak.DSE_NAME AS zak_dse_name,
                        okb_db_zak.DSE_OBOZ AS zak_dse_draw,

                        okb_db_itrzadan.TXT AS order_name,
                        okb_db_itrzadan.ID_users AS id_creator,
                        okb_db_itrzadan.ID_users3 AS id_checker,
                        okb_db_itrzadan.STARTDATE AS begin_date,
                        okb_db_itrzadan.`STATUS` AS `status`,
                        okb_db_itrzadan.comp_perc AS comp_perc,
                        okb_db_itrzadan.ID_proj AS project_id,
                        okb_db_itrzadan.ID_zak AS zak_id,

                        okb_db_itrzadan.ID AS order_id
                        FROM
                        okb_db_itrzadan
                        LEFT JOIN okb_db_projects ON okb_db_itrzadan.ID_proj = okb_db_projects.ID
                        LEFT JOIN okb_db_zak ON okb_db_itrzadan.ID_zak = okb_db_zak.ID
                        LEFT JOIN okb_db_zak_type ON okb_db_zak.TID = okb_db_zak_type.ID
                        WHERE
                        okb_db_itrzadan.ID_users2 = ".$this -> user_id."
                        #AND
                        #okb_db_itrzadan.STATUS <> 'Завершено'
                        AND
                        okb_db_itrzadan.STATUS <> 'Аннулировано'

                        ORDER BY project_name
                        ";

            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while ( $row = $stmt->fetch( PDO::FETCH_OBJ ))
        {

            $this->orders [] =
                [
                    'project_id' => $row -> project_id ,
                    'zak_id' => $row -> zak_id,

                    'project_name' => conv( $row -> project_name ),

                    'zak_type' => conv( $row -> zak_type ),
                    'zak_name' => $row -> zak_name,
                    'zak_dse_name' => conv( $row -> zak_dse_name ),
                    'zak_dse_draw' => conv( $row -> zak_dse_draw ),

                    'descr' => conv( $row -> order_name ) ,

                    'order_id' => $row -> order_id,
                    'order_name' => conv( $row -> order_name ),
                    'begin_date' => $row -> begin_date,
                    'id_creator' => $row -> id_creator,
                    'id_checker' => $row -> id_checker,
                    'status' => conv( $row -> status ),
                    'comp_perc' => $row -> comp_perc,
                    'hour_count' => 0
                ];
        }

    if( count( $this->orders ) )
    foreach( $this->orders AS $key => & $val )
    {
        $order_id = $val['order_id'];

        try
        {
            $query ="
                        SELECT
                        SUM( hour_count ) hour_count
                        FROM
                        okb_db_working_calendar
                        WHERE
                        order_id = $order_id
                        ";

            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();

            $row = $stmt->fetch( PDO::FETCH_OBJ );
            $val['hour_count'] = $row -> hour_count ? $row -> hour_count : 0 ;
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }
    }

        return $this->orders ;
    }

    public function GetUserName()
    {
      return $this -> user_name;
    }

    private function getTableRow()
    {
        $str = "";
        $line_num = 1 ;
     if( count( $this -> orders ) )
        foreach( $this -> orders AS $order )
        {

            $color = $this -> GetColor( $line_num - 1 );
            $str .= "<tr data-id='".$order['order_id']."'><td class='field name'>".( $line_num ) .". ".$order['descr']."</td>

            <td class='field legend'>
                <div style='background:$color;'></div>
            </td>

            <td class='field  spinner'>
                <input id='spinner-prev-".$order['order_id']."'>
            </td>
            <td class='field spinner'>
                <input id='spinner-now-".$order['order_id']."'>
            </td>

            <td class='field slider'>
                <div>
                    <div data-perc='".$order['comp_perc']."' id='slider-".$order['order_id']."'></div>
                    <div><input type='text' id='amount-".$order['order_id']."' readonly></div>
                </div>
            </td>
            <td class='field AC'><span>".$order['hour_count']."</span></td>
            </tr>";

            $line_num ++;
        }

        return $str;
    }

    public function getStatistics()
    {
        $arr = [];

        foreach( $this->orders AS $order )
        {
            $project_id = $order['project_id'];
            $hour_count = $order ['hour_count'] ;

            $arr [ $project_id ]['project_id'] = $project_id ;
            $arr [ $project_id ]['project_name'] = conv( $order['project_name'] );
            if( isset( $arr [ $project_id ]['hour_count'] ) )
                $arr [ $project_id ]['hour_count'] +=  $hour_count ;
                  else
                    $arr [ $project_id ]['hour_count'] =  $hour_count ;
        }

        $this -> projects = array_values( $arr );
        return $this -> projects;
    }


private function GetTableBegin()
{
return "<div class='row'>
                      <table id='ord_table' class='tbl table-striped'>
                      <col width='45%'><col width='2%'><col width='5%'><col width='5%'><col width='15%'><col width='5%'>
                      <tr class='first'>
                          <td rowspan='2' colspan='2'>".conv("Проект / задание")."</td>
                          <td colspan='2'><span>".conv("Затрачено часов")."</span></td>
                          <td rowspan='2'>".conv("% выполнения")."</td>
                          <td rowspan='2'>".conv("Часов всего")."</td>
                      </tr>
                      <tr class='first'>
                      <td><span class='prev_date_span'></span></td>
                      <td><span class='cur_date_span'></span></td>
                      </tr>
                      ";
}

public function GetMonthTableBegin( $number )
{

  $str = "<div class='row'>
                      <table id='ord_table' class='tbl table-striped'>
                      <col width='40%'><col width='2%'>
                      <tr class='first'>
                      <td colspan='2'>".conv("Проект / задание")."</td>
                      ";
                      for( $i = 1 ; $i <= $number ; $i++ )
                          $str .= "<td class='field AC' >$i</td>";

                      $str .= "<td class='AC'>".conv("М")."</td>";
                      $str .= "<td class='AC'>".conv("В")."</td>";

                      $str .= "</tr>";
    return $str ;
}

    public function getMonthTableRow( $month , $year )
    {

//      $number = cal_days_in_month(CAL_GREGORIAN, $month , $year );
      $number = date('t', strtotime( "$year-$month-01" ) );
      $str = '';

     $arr = $this -> SplitOrders();

    if( isset( $arr['project'] ) )
      foreach( $arr['project'] AS $project )
      {
          $project_name = $project['project_name'];
          $project_id  = $project['project_id'];
          $hour_count  = $project['hour_count'];

                 $str .= "<tr class='proj_row' data-proj-id='$project_id'>
                      <td class='field AL' colspan='". ( $number + 2 )."'><div><img data-show='0' class='coll_exp' src='/uses/collapse.png'/><span>".conv("Проект ").$project_name."</span><span> ( ".count( $project['orders'])." )</span></div></td>
                      ";
                      $hour_count = $hour_count ? $hour_count : "-";
                      $total_month_hrs = $this -> GetHoursInMonthViewer( $project['orders'], $month, $year ) ;
                      $total_month_hrs = $total_month_hrs ? $total_month_hrs : "-";

                      $str .= "<td class='field hours AC'>$total_month_hrs</td>";
                      $str .= "<td class='field hours AC'>$hour_count</td>";
                      $str .= "</tr>";

          $str .= $this -> GetMonthViewerHtml( $project['orders'], $month, $year, $project_id, 0 );
      }

    if( isset( $arr['zak'] ) )
      foreach( $arr['zak'] AS $zak )
      {
          $zak_name = $zak['zak_name'];
          $zak_id  = $zak['zak_id'];
          $zak_dse_name = $zak['zak_dse_name'];
          $hour_count  = $zak['hour_count'];

                 $str .= "<tr class='zak_row' data-zak-id='$zak_id'>
                      <td class='field AL' colspan='". ( $number + 2 )."'><div><img data-show='0' class='coll_exp' src='/uses/collapse.png'/><span>".conv("Заказ ").$zak_name." ".$zak_dse_name."</span><span> ( ".count( $zak['orders'])." )</span></div></td>
                      ";
                      $hour_count = $hour_count ? $hour_count : "-";
                      $total_month_hrs = $this -> GetHoursInMonthViewer( $zak['orders'], $month, $year ) ;
                      $total_month_hrs = $total_month_hrs ? $total_month_hrs : "-";

                      $str .= "<td class='field hours AC'>$total_month_hrs</td>";
                      $str .= "<td class='field hours AC'>$hour_count</td>";
                      $str .= "</tr>";
          $str .= $this -> GetMonthViewerHtml( $zak['orders'], $month, $year, 0, $zak_id);
      }

       if( isset( $arr['unlinked'] ) )
       {
            $hour_count  = $arr['unlinked']['hour_count'];
            $str .= "<tr class='unlinked_row'>
                         <td class='field AL' colspan='". ( $number + 2 )."'><div><img data-show='0' class='coll_exp' src='/uses/collapse.png'/><span>".conv("Задания вне проектов и заказов ")." ( ".count( $arr['unlinked']['orders'])." )</span></div></td>
                              ";
            $hour_count = $hour_count ? $hour_count : "-";
            $total_month_hrs = $this -> GetHoursInMonthViewer( $arr['unlinked']['orders'], $month, $year) ;
            $total_month_hrs = $total_month_hrs ? $total_month_hrs : "-";

            $str .= "<td class='field hours AC'>$total_month_hrs</td>";
            $str .= "<td class='field hours AC'>$hour_count</td>";
            $str .= "</tr>";

             $str .= $this -> GetMonthViewerHtml( $arr['unlinked']['orders'], $month, $year, 0, 0);
      }

      return $str;
    }





public function GetWorkingCalendar()
{
      $str =  $this -> GetTableBegin();
      $str .= $this -> GetTableRow();
      $str .= $this -> GetTableEnd();
      return $str ;
}

public function GetMonthWorkingCalendar( $month, $year )
{
//      $number = cal_days_in_month(CAL_GREGORIAN, $month , $year );
      $number = date('t', strtotime( "$year-$month-01" ) );

      $str  =  $this -> GetMonthTableBegin( $number );
      $str .=  $this -> GetMonthTableRow( $month , $year );
      $str .=  $this -> GetTableEnd();
      return $str ;
}


private function GetTableEnd()
{
    return "</table></div></div>";
}

public function GetHeadTitle()
{

    return $head_title = "<div class='head'>
                      <div>
                        <div><h2>".conv( "Редактирование рабочего календаря")."</h2><h3>".conv( "Сотрудник : ").conv($this -> GetUserName())."</h3></div>
                        <div><span class='label'>".conv( "Дата").":</span><input type='text' id='datepicker' readonly='readonly'></div>
                      </div>
                      <div id='pie_div'>
                      </div>

                      </div>";
}

public function GetProjects()
{
    return $this -> projects;
}

public function GetOrders()
{
    return $this -> orders;
}

    private function removeUnnecessaryFields( $order )
    {
            unset( $order['project_id'] );
            unset( $order['project_name'] );

            unset( $order['zak_id'] );
            unset( $order['zak_type'] );
            unset( $order['zak_name'] );
            unset( $order['zak_dse_name'] );
            unset( $order['zak_dse_draw'] );

            return $order;
    }


public function SplitOrders()
{
    $arr = [];

  if( count( $this -> orders ) )
    foreach( $this -> orders AS $order )
    {
        $zak_id = $order['zak_id'];
        $project_id = $order['project_id'];

        if( $zak_id )
        {
            $arr['zak'][ $zak_id ]['zak_id'] = $order['zak_id'];
            $arr['zak'][ $zak_id ]['zak_type'] = $order['zak_type'];
            $arr['zak'][ $zak_id ]['zak_name'] = $order['zak_name'];
            $arr['zak'][ $zak_id ]['zak_dse_name'] = $order['zak_dse_name'];
            $arr['zak'][ $zak_id ]['zak_dse_draw'] = $order['zak_dse_draw'];

            if( isset( $arr['zak'][ $zak_id ]['hour_count'] ) )
              $arr['zak'][ $zak_id ]['hour_count'] += $order['hour_count'];
                else
                    $arr['zak'][ $zak_id ]['hour_count'] = $order['hour_count'];

            $arr['zak'][ $zak_id ]['orders'][ $order['order_id'] ] = $this -> removeUnnecessaryFields( $order );
        }
        if( $project_id )
        {
            $arr['project'][ $project_id ]['project_id'] = $order['project_id'];
            $arr['project'][ $project_id ]['project_name'] = $order['project_name'];

            if( isset( $arr['project'][ $project_id ]['hour_count'] ) )
                $arr['project'][ $project_id ]['hour_count'] += $order['hour_count'];
                    else
                        $arr['project'][ $project_id ]['hour_count'] = $order['hour_count'];

            $arr['project'][ $project_id ]['orders'][ $order['order_id'] ] = $this -> removeUnnecessaryFields( $order );
        }

        if( $zak_id == 0 && $project_id == 0 )
        {

            if( isset( $arr['unlinked']['hour_count'] ) )
                $arr['unlinked']['hour_count'] += $order['hour_count'];
                    else
                        $arr['unlinked']['hour_count'] = $order['hour_count'];

             $arr['unlinked']['orders'][ $order['order_id'] ] = $this -> removeUnnecessaryFields( $order );
        }
    }

    return $arr;
}


public function GetMonthViewerData( $order_id, $month, $year )
{
  $user_id = $this -> user_id;
  $total_month_hrs = 0 ;
  $dates = [] ;

            try
                {
                    $query ="
                                SELECT
                                DAY( date ) day,
                                hour_count
                                FROM
                                okb_db_working_calendar
                                WHERE
                                order_id = $order_id
                                AND
                                user_id = $user_id
                                AND
                                MONTH( date ) = $month
                                AND
                                YEAR( date ) = $year
                                ";

                    $stmt = $this -> pdo->prepare( $query );
                    $stmt->execute();
                }
                catch (PDOException $e)
                {
                    die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                }

                while ( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
                {
                      $hrs = $row -> hour_count;
                      $total_month_hrs += $hrs ;
                      $dates[ $row -> day ] = $hrs ? $hrs : "-";
                }

        $dates[0] = $total_month_hrs ;
        return $dates;
}

public function GetHoursInMonthViewer( $orders, $month, $year )
{
    $user_id = $this -> user_id;
    $total_month_hrs = 0 ;

      foreach( $orders AS $order )
        {
            $order_id = $order['order_id'];

            try
                {
                    $query ="
                                SELECT SUM( hour_count) AS hours
                                FROM
                                okb_db_working_calendar
                                WHERE
                                order_id = $order_id
                                AND
                                user_id = $user_id
                                AND
                                MONTH( date ) = $month
                                AND
                                YEAR( date ) = $year
                                ";

                    $stmt = $this -> pdo->prepare( $query );
                    $stmt->execute();
                }
                catch (PDOException $e)
                {
                    die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                }

        $row = $stmt->fetch( PDO::FETCH_OBJ ) ;
        $total_month_hrs += $row -> hours ;
      }

        return $total_month_hrs ;
}


public function GetMonthViewerHtml( $orders, $month, $year, $project_id, $zak_id )
 {
        $user_id = $this -> user_id;
        $line_num = 1 ;
        $str = '';
        $number = date('t', strtotime( "$year-$month-01" ) );

        foreach( $orders AS $order )
        {
            $order_id = $order['order_id'];
            $order_name = $order['order_name'];
            $total_hour = $order['hour_count'];

            $dates = $this -> GetMonthViewerData( $order_id, $month, $year );

            $color = "#e6e6fa" ; //$this -> GetColor( $line_num - 1 );

            $str .= "<tr class='hidden' data-id='$order_id' data-proj-id='$project_id' data-zak-id='$zak_id'>
                        <td class='field name'>$order_name</td>

            <td class='field legend'>
                <div style='background:$color;'></div>
            </td>";

             for( $i = 1 ; $i <= $number ; $i++ )
                  {
                    $class = "";
                    $hours =  "-";
                      if( isset( $dates[ $i ] ) && $dates[ $i ] != 0 )
                      {
                          $class = "hours";
                          $hours =  $dates[ $i ] ;
                      }
                       $str .= "<td class='field AC $class cal_date'><a title='$i'>$hours</a></td>";
                  }

              $total_month_hrs = $dates[0] ? $dates[0] : "-";

             $str .= "<td class='field hours AC total_month_hrs'><span>$total_month_hrs</span></td>";
             $str .= "<td class='field hours AC total_hrs'><span>".( $total_hour ? $total_hour : "-")."</span></td>";
             $str .= "</tr>";

            $line_num ++;
        }

        return $str ;
  }

} //class OrdersCalendar
