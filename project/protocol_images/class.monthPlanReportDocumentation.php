<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once( "functions.php" );

class MonthPlanReportDocumentation
{
    private $dblocation ;
    private $pdo ;
    private $krzs ;
    private $view_image = "uses/film2.png" ;
    private $view_image_dis = "uses/film2_dis.png" ;
    private $load_image = "uses/addf_img2.png" ;
    private $load_image_dis = "uses/addf_img2_dis.png" ;

    private $but_load = "/project/protocol_images/css/but_load.png";
    private $but_load_dis = "/project/protocol_images/css/but_load_dis.png";
    private $but_del = "/project/protocol_images/css/but_del.png";
    private $but_del_dis = "/project/protocol_images/css/but_del_dis.png";


    private $root ;
    private $ajax ;
    private $date ;

    private function MakeClickImage( $krz, $what, $date_str )
    {
        $result = '';
        $id = $krz['id'];
        $date = $krz['rec_date'];
        $dep_name = $this -> conv( $krz['dep_name']);

        switch( $what )
        {
            case 'project_plan' : $images = $krz['project_plan_images'];  break ;
            case 'plan' : $images = $krz['plan_images'];  break ;
            case 'report' : $images = $krz['report_images'];  break ;
        }

        if( strlen( $date ) )
            $result =      "<img data-what='$what' data-id='$id'
                            src='". ( count( $images ) ? $this -> view_image : ( strlen( $date_str ) ? $this -> load_image : $this -> load_image_dis ) ) ."'
                            data-total_images='". count( $images ) ."'
                            title='".$this -> conv("Всего изображений :").count( $images )."'
                            data-current_image='1' data-dep_name='$dep_name'
                            >";

        // Вывод переменных для JavaScript
        echo "<script>  var view_image = '".$this -> view_image."';
                        var view_image_dis = '".$this -> view_image_dis."';
                        var load_image = '".$this -> load_image."';
                        var load_image_dis = '".$this -> load_image_dis."';
                        var but_load = '".$this -> but_load."';
                        var but_load_dis = '".$this -> but_load_dis."';
                        var but_del = '".$this -> but_del."';
                        var but_del_dis = '".$this -> but_del_dis."';



               </script>" ;

        return $result ;
    }


    private function conv( $str )
    {
        if( $this -> dblocation == "127.0.0.1" )
            return iconv("UTF-8", "Windows-1251", $str );

        if( $this -> ajax )
            return $str ;
        return iconv("UTF-8", "Windows-1251", $str );
    }

    public function __construct( $dblocation, $dbname, $dbuser, $dbpasswd, $ajax = 0 )
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
        $this -> root = $_SERVER['DOCUMENT_ROOT'];
        $this -> ajax = $ajax ;
        $this -> dblocation = $dblocation;
    }

    public function addRecords( )
    {
        $dep_arr = [];

        try
        {
            $query ="SELECT ID FROM okb_db_protocol_departments WHERE 1 ORDER BY ID";
            $stmt = $this -> pdo -> prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Can't get data: " . $e->getMessage());
        }

        while ( $row = $stmt->fetch( PDO::FETCH_OBJ ))
            $dep_arr[] = $row -> ID;

        $date = DateConvert( $this -> date );

        foreach( $dep_arr AS $dep_id )
        {
            try {
                $query = "INSERT INTO okb_db_protocol_images VALUES(NULL,'$date', $dep_id, NULL,'[]',NULL,'[]',NULL,'[]')";
               $stmt = $this->pdo->prepare($query);
               $stmt->execute();
            }
            catch (PDOException $e)
            {
                die("Can't get data: " . $e->getMessage());
            }
        }

    }

    public function getMaxMinDate( $date )
    {
        $timestamp  = strtotime ( $date );
        $maxdate = date('Y', $timestamp )."-".date('m', $timestamp )."-".date( 't', $timestamp );
        $mindate = date('Y', $timestamp )."-".date('m', $timestamp )."-01";
        return [ 'maxdate' => $maxdate , 'mindate' => $mindate ];
    }

    public function GetData( $date = null )
    {
        $this -> date = $date ;
        $max_min_date = $this -> getMaxMinDate( $date );

        $maxdate = $max_min_date[ 'maxdate'] ;
        $mindate = $max_min_date[ 'mindate'] ;

        try
        {
            $query ="
                        SELECT COUNT(*) count
                        FROM
                        okb_db_protocol_images
                        WHERE okb_db_protocol_images.rec_date BETWEEN '$mindate' AND '$maxdate'
            ";

            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Can't get data: " . $e->getMessage());
        }

        $row = $stmt->fetch( PDO::FETCH_OBJ );

         if( $row->count == 0 && $this -> date )
             $this -> addRecords();

        try
        {
            $query ="SET @CNT := 0";
            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();

            $query ="
                        SELECT #@CNT := @CNT + 1 line,
                        okb_db_protocol_images.ID,
                        okb_db_protocol_images.rec_date,

                        DATE_FORMAT( okb_db_protocol_images.project_plan_date_fact, '%d.%m.%Y') project_plan_date_fact,
                        okb_db_protocol_images.project_plan_images,

                        DATE_FORMAT( okb_db_protocol_images.plan_date_fact, '%d.%m.%Y') plan_date_fact,
                        okb_db_protocol_images.plan_images,

                        DATE_FORMAT( okb_db_protocol_images.report_date_fact, '%d.%m.%Y') report_date_fact,
                        okb_db_protocol_images.report_images,

#                        okb_db_protocol_images.project_plan_comments,

                        okb_db_protocol_departments.department_name dep_name,
                        okb_db_protocol_departments.id dep_id

                        FROM
                        okb_db_protocol_images
                        INNER JOIN okb_db_protocol_departments ON okb_db_protocol_images.department_id = okb_db_protocol_departments.ID
                        WHERE okb_db_protocol_images.rec_date BETWEEN '$mindate' AND '$maxdate'
                        ORDER BY okb_db_protocol_departments.id
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
            $this->krzs [] =
                [
                    'line' => $row -> line,
                    'id' => $row -> ID,
                    'rec_date' => $row -> rec_date,

                    'dep_name' => $row -> dep_name,
                    'dep_id' => $row -> dep_id,

                    'project_plan_date_fact' => $row -> project_plan_date_fact,
                    'project_plan_images' => json_decode( $row -> project_plan_images ),

                    'plan_date_fact' => $row -> plan_date_fact,
                    'plan_images' => json_decode( $row -> plan_images ),

                    'report_date_fact' => $row -> report_date_fact,
                    'report_images' => json_decode( $row -> report_images ) ,

                    'project_plan_comments' => json_decode( $row -> project_plan_comments ) ,

                ];
        }

        $str ="
                <div id='krz_table_div'><table id='krz_table' class='tbl' data-date='".$this -> date ."' data-row_count='".$stmt->rowCount()."'>
                <col class='num_field'>
                <col class='dep_name_field'>
                <col class='date_field'>
                <col class='img_field'>
                <col class='date_field'>
                <col class='img_field'>
                <col class='date_field'>
                <col class='img_field'>

                <tr class='first'>
                <td rowspan='2'>".$this -> conv("№")."</td>
                <td rowspan='2'>".$this -> conv("Наименование отдела ")."</td>
                <td colspan='2'>".$this -> conv("Проект плана")."
                <div>
                <input id='project_plan_date' class='datepicker' type='text' id='' title='".$this -> conv("Выберите месяц и год")."' />
                </div></td>
                <td colspan='2'>".$this -> conv("План")."<div>
                <input id='plan_date' class='datepicker' type='text' id='' title='".$this -> conv("Выберите месяц и год")."' /></div></td>
                <td colspan='2'>".$this -> conv("Отчет")."<div>
                <input id='report_date' class='datepicker' type='text' id='' title='".$this -> conv("Выберите месяц и год")."' /></div></td>
                </tr>

                <tr class='first head_second_line'>
                <td>".$this -> conv("Дата сдачи<br>фактическая")."</td>
                <td></td>

                <td>".$this -> conv("Дата сдачи<br>фактическая")."</td>
                <td></td>

                <td>".$this -> conv("Дата сдачи<br>фактическая")."</td>
                <td></td>
                </tr>";


        foreach( $this->krzs AS $krz )
        {

            $plan_poject_image = $this -> MakeClickImage( $krz, "project_plan", $krz['project_plan_date_fact']);
            $plan_image = $this -> MakeClickImage( $krz, "plan", $krz['plan_date_fact']);
            $report_image = $this -> MakeClickImage( $krz, "report", $krz['report_date_fact']);

            $str .= "
            <tr>
            <td>{$krz['line']}</td>
            <td class='left'>".$this -> conv( $krz['dep_name'] )."</td>

            <td>
                    <div class='input-group'>

                      <span class='input-group-addon'>
                                <button class='glyphicon glyphicon-question-sign ".
                                ( 1 * count( $krz['project_plan_comments']  ) ? 'btn-susp' : 'btn-susp btn-empty' )
                                ." btn-dis'  disabled></button>
                      </span>

                      <input data-id='{$krz['id']}' data-what='project_plan' type='text' class='form-control datepicker td_date' value='{$krz['project_plan_date_fact']}'/>


                      <span class='input-group-addon'>
                                <button class='glyphicon glyphicon-circle-arrow-right btn-ok btn-dis' disabled></button>
                      </span>
                    </div>
            </td>

            <td class='td_img'>$plan_poject_image</td>

            <td class='middle_col'><input data-id='{$krz['id']}' data-what='plan' type='text' class='form-control _datepicker_ td_date' value='{$krz['plan_date_fact']}'/></td>
            <td class='middle_col td_img'>$plan_image</td>

            <td><input data-id='{$krz['id']}' data-what='report' type='text' class='form-control datepicker td_date' value='{$krz['report_date_fact']}' /></td>
            <td class='td_img'>$report_image</td>

            </tr>
            ";
        }

        $str = "";

        $str .= "</table></div>";
        return $str;
    }

    public function getPrintData( $date )
    {
        $this -> date = $date ;
        $max_min_date = $this -> getMaxMinDate( $date );
        $maxdate = $max_min_date[ 'maxdate'] ;
        $mindate = $max_min_date[ 'mindate'] ;

        $date = MakeDateWithDash( $date, "01");

        try
        {
            $query ="
                SELECT
                *
                FROM
                okb_db_protocol_images_ref_dates
                WHERE
                ref_date = '$date'
            ";

            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Can't get data: " . $e->getMessage());
        }

        $row = $stmt->fetch(PDO::FETCH_OBJ);

        $project_plan_date = MakeDateWithDot( $row->project_plan_date );
        $plan_date = MakeDateWithDot( $row->plan_date );
        $report_date = MakeDateWithDot( $row->report_date );

        try
        {
            $query ="
                        SELECT COUNT(*) count
                        FROM
                        okb_db_protocol_images
                        WHERE okb_db_protocol_images.rec_date BETWEEN '$mindate' AND '$maxdate'
            ";

            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Can't get data: " . $e->getMessage());
        }

        $row = $stmt->fetch( PDO::FETCH_OBJ );

        if( $row->count == 0 && $this -> date )
            $this -> addRecords();

        try
        {
            $query ="SET @CNT := 0";
            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();

            $query ="
                        SELECT @CNT := @CNT + 1 line,
                        okb_db_protocol_images.ID,
                        okb_db_protocol_images.rec_date,
                        DATE_FORMAT( okb_db_protocol_images.project_plan_date_fact, '%d.%m.%Y') project_plan_date_fact,
                        DATE_FORMAT( okb_db_protocol_images.plan_date_fact, '%d.%m.%Y') plan_date_fact,
                        DATE_FORMAT( okb_db_protocol_images.report_date_fact, '%d.%m.%Y') report_date_fact,

                        okb_db_protocol_departments.department_name dep_name,
                        okb_db_protocol_departments.id dep_id
                        FROM
                        okb_db_protocol_images
                        INNER JOIN okb_db_protocol_departments ON okb_db_protocol_images.department_id = okb_db_protocol_departments.ID
                        WHERE okb_db_protocol_images.rec_date BETWEEN '$mindate' AND '$maxdate'
                        ORDER BY okb_db_protocol_departments.id
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
            $this->krzs [] =
                [
                    'line' => $row -> line,
                    'id' => $row -> ID,
                    'rec_date' => $row -> rec_date,

                    'dep_name' => $row -> dep_name,
                    'dep_id' => $row -> dep_id,

                    'project_plan_date_fact' => $row -> project_plan_date_fact,
                    'plan_date_fact' => $row -> plan_date_fact,
                    'report_date_fact' => $row -> report_date_fact,
                ];
        }

        $timestamp  = strtotime ( $date );
        $month_names = 	['январь','февраль','март','апрель','май','июнь','июль','август','сентябрь','октябрь','ноябрь','декабрь'];
        $month = 1 * date('m', $timestamp ) - 1 ;
        $year = 1 * date('Y', $timestamp );

        $h1 = "";

        if( $month == 11 )
        {
                $h1 = conv( "План-отчет за " ).conv( $month_names[ $month ])." ".$year.conv("г.")." - ".conv( $month_names[ 0 ])." ".($year + 1 ).conv("г.");
        }
        else
                $h1 = conv( "План-отчет за " ).conv( $month_names[ $month ])." - ".conv( $month_names[ $month + 1])." ".$year.conv("г.");




                // <p>".conv("Дата проекта плана : ").$project_plan_date."</p>
                // <p>".conv("Дата плана : ").$plan_date."</p>
                // <p>".conv("Дата отчета : ").$report_date."</p><br>


        $str =
               "<div id='krz_table_div'>
                <h1 class='prn'>$h1</h1>
                <br>
                <table id='krz_table_prn' class='tbl' data-date='".$this -> date ."' data-row_count='".$stmt->rowCount()."'>
                <col class='num_field_prn'>
                <col class='dep_name_field_prn'>
                <col class='date_field_prn'>
                <col class='date_field_prn'>
                <col class='date_field_prn'>

                <tr class='first'>
                <td rowspan='2'>".$this -> conv("№")."</td>
                <td rowspan='2'>".$this -> conv("Наименование отдела ")."</td>
                <td>".$this -> conv("Проект плана")."<br>$project_plan_date</td>
                <td>".$this -> conv("План")."<br>$plan_date</td>
                <td>".$this -> conv("Отчет")."<br>$report_date</td>
                </tr>
                <tr class='first head_second_line'>
                <td>".$this -> conv("Дата сдачи<br>фактическая")."</td>
                <td>".$this -> conv("Дата сдачи<br>фактическая")."</td>
                <td>".$this -> conv("Дата сдачи<br>фактическая")."</td>
                </tr>";

        foreach( $this->krzs AS $krz )
        {
            $str .= "
            <tr>
            <td>{$krz['line']}</td>
            <td class='left'>".$this -> conv( $krz['dep_name'] )."</td>

            <td>{$krz['project_plan_date_fact']}</td>
            <td>{$krz['plan_date_fact']}</td>
            <td>{$krz['report_date_fact']}</td>

            </tr>
            ";
        }
        $str .= "</table></div>";
        return $str;
    }
}


