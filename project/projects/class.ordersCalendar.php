<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once( "db.php" );
require_once( "makeChart.php" );

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
    private $Palette = [] ;

   function GetColor( $index )
   {
        $r = dechex( $this->Palette[$index]["R"] );
        $r = strlen( $r ) == 1 ? "0".$r : $r ;

        $g = dechex( $this->Palette[$index]["G"] );
        $g = strlen( $g ) == 1 ? "0".$g : $g ;

        $b = dechex( $this->Palette[$index]["B"] );
        $b = strlen( $b ) == 1 ? "0".$b : $b ;


      return "#$r$g$b";
   }

   function loadColorPalette($FileName,$Delimiter=",")
    {
     $handle  = @fopen($FileName,"r");
     $ColorID = 0;
     if ($handle)
      {
       while (!feof($handle))
        {
         $buffer = fgets($handle, 4096);
         $buffer = str_replace(chr(10),"",$buffer);
         $buffer = str_replace(chr(13),"",$buffer);
//         $Values = split($Delimiter,$buffer);
         $Values = explode($Delimiter,$buffer);
         if ( count($Values) == 3 )
          {
           $this->Palette[$ColorID]["R"] = $Values[0];
           $this->Palette[$ColorID]["G"] = $Values[1];
           $this->Palette[$ColorID]["B"] = $Values[2];
           $ColorID++;
          }
        }
      }
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
            die("Can't connect: " . $e->getMessage());
        }

            $this -> user_id = $user_id ;
            $this -> imageName = "work_cal_".$user_id ;

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
            die("Can't get data: " . $e->getMessage());
        }

        if ( $row = $stmt->fetch( PDO::FETCH_OBJ ))
                $this -> user_name = $row -> NAME ;

            $this->loadColorPalette( $_SERVER['DOCUMENT_ROOT']."/project/working_calendar/softtones.pal");

     }

    public function GetData( $date = '' )
    {
        $this -> date = $date ;

        try
        {
            $query ="
                        SELECT
                        okb_db_projects.`name` AS project_name,
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
                        INNER JOIN okb_db_projects ON okb_db_itrzadan.ID_proj = okb_db_projects.ID
                        WHERE
                        okb_db_itrzadan.ID_users2 = ".$this -> user_id."
                        ORDER BY project_name
                        ";

            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Can't get data: " . $e->getMessage());
        }

        while ( $row = $stmt->fetch( PDO::FETCH_OBJ ))
        {
            $this->orders [] =
                [
                    'project_id' => $row -> project_id,
                    'project_name' => $row -> project_name,
                    'order_id' => $row -> order_id,
                    'order_name' => $row -> order_name,
                    'begin_date' => $row -> begin_date,
                    'id_creator' => $row -> id_creator,
                    'id_checker' => $row -> id_checker,
                    'status' => $row -> status,
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
                        order_id = $order_id ;
                        ";

            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();

            $row = $stmt->fetch( PDO::FETCH_OBJ );
            $val['hour_count'] = $row -> hour_count ? $row -> hour_count : 0 ;
        }
        catch (PDOException $e)
        {
            die("Can't get data: " . $e->getMessage());
        }
    }

        return $this->orders ;
    }

    public function makeChart()
    {
    
           MakeChart(
                                            $_SERVER['DOCUMENT_ROOT']."/uses/working_calendar_img/".$this -> imageName ,
                                            $this ->orders
                            );
    }

    public function GetImageName()
    {
      return $this -> imageName;
    }

    public function GetUserName()
    {
      return $this -> user_name;
    }

    public function getTableRow()
    {
        $str = "";
        $line_num = 1 ;
        foreach( $this -> orders AS $order )
        {
            $color = $this -> GetColor( $line_num - 1 );
            $str .= "<tr data-id='".$order['order_id']."'><td class='field name'>".( $line_num ) .". ".conv( $order['project_name'])." / ".conv( $order['order_name'])."</td>

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
            $arr [ $project_id ]['project_id'] = $project_id ;
            $arr [ $project_id ]['project_name'] = $order['project_name'];
            $arr [ $project_id ]['hour_count'] += $order ['hour_count'] ;
        }

        $this -> projects = array_values( $arr );
        return $this -> projects;
    }


public function GetTableBegin()
{
return "<div class='row'>
                      <table id='ord_table' class='tbl'>
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

public function GetTableEnd()
{
    return "</table></div></div>";
}

public function GetHeadTitle()
{

    return $head_title = "<div class='head'>
                      <div>
                        <div><h2>".conv( "Рабочий календарь")."</h2><h3>".conv( "Сотрудник : ").conv($this -> GetUserName())."</h3></div>
                        <div><span class='label'>".conv( "Дата").":</span><input type='text' id='datepicker' readonly='readonly'></div>
                      </div>
                      <div class='pie_div'>
                        <img class='chart' src='/uses/working_calendar_img/work_cal_".$this -> user_id.".png'>                        
                      </div>

                      </div>";
}

} //class OrdersCalendar



