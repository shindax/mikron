<?php
error_reporting( E_ALL );
//error_reporting( 0 );


class BaseOrdersCalendar
{
    protected $pdo ;
    protected $user_id ;
    protected $user_id_arr = null;
    protected $user_name ;

    protected $projects = [];
    protected $orders = [];
    protected $unlinked = [];

    function __construct( $pdo, $user_id_arr )
    {
        $this -> pdo = $pdo ;
        $this -> user_id = $user_id_arr[0] ;
        $this -> user_id_arr = $user_id_arr ;

        if( $this -> user_id )
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

            $this -> CollectRawData();
     }

    public function GetUserName()
    {
      return $this -> user_name;
    }

    public function GetUserID()
    {
        return $this -> user_id;
    }

    public function GetData()
    {
        return [ "projects" => $this -> projects, "orders" => $this -> orders, "unlinked" => $this -> unlinked ];
    }


    protected function CollectRawData()
    {
        $orders = [];
        $users_arr = [];

        foreach( $this -> user_id_arr AS $user_id )
            $users_arr [] = $user_id ;

        $users_arr = join(',', $users_arr );

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
                        okb_db_itrzadan.ID_users2 IN ( $users_arr )
                        AND
                        okb_db_itrzadan.STATUS <> 'Завершено'
                        AND
                        okb_db_itrzadan.STATUS <> 'Аннулировано'
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
            $orders [] =
                [
                    'project_id' => $row -> project_id ,
                    'project_name' => $row -> project_name ,

                    'zak_id' => $row -> zak_id,
                    'zak_type' => $row -> zak_type ,
                    'zak_name' => $row -> zak_name,
                    'zak_dse_name' =>  $row -> zak_dse_name ,
                    'zak_dse_draw' =>  $row -> zak_dse_draw ,

                    'order_id' => $row -> order_id,
                    'order_name' =>  $row -> order_name ,
                    'begin_date' => $row -> begin_date,
                    'id_creator' => $row -> id_creator,
                    'id_checker' => $row -> id_checker,
                    'status' =>  $row -> status ,
                    'comp_perc' => $row -> comp_perc,
                    'hour_count' => 0
                ];
        }

        if( count( $orders ) )
        {
            foreach ($orders AS $key => & $val) {
                $order_id = $val['order_id'];

                try {
                    $query = "
                        SELECT
                        SUM( hour_count ) hour_count
                        FROM
                        okb_db_working_calendar
                        WHERE
                        order_id = $order_id
                        ";

                    $stmt = $this->pdo->prepare($query);
                    $stmt->execute();

                    $row = $stmt->fetch(PDO::FETCH_OBJ);
                    $val['hour_count'] = $row->hour_count ? $row->hour_count : 0;
                } catch (PDOException $e) {
                    die("Error in :" . __FILE__ . " file, at " . __LINE__ . " line. Can't get data : " . $e->getMessage());
                }
            }

            foreach ($orders AS $key => & $val)
            {
                $project_id = $val['project_id'];
                $zak_id = $val['zak_id'];
                $order_id = $val['order_id'];

                if( $project_id == 0 && $zak_id == 0 )
                {
                    $this->unlinked[0]['month_hour_count'] = "month_unlinked";

                    if( isset( $this-> unlinked[0]['total_hour_count'] ) )
                        $this->unlinked[0]['total_hour_count'] += $val['hour_count'];
                            else
                                $this->unlinked[0]['total_hour_count'] = $val['hour_count'];

                    $this -> UnsetProjectData( $val );
                    $this -> UnsetZakData( $val );
                    $this -> unlinked[0]["items"][ $order_id ] = $val ;
                }

                if( $project_id != 0 && $zak_id == 0 )
                {
                    $this-> projects[$project_id]['project_name'] =  $val['project_name'];
                    $this-> projects[$project_id]['project_id'] =  $val['project_id'];
                    $this-> projects[$project_id]['month_hour_count'] = "month_project";

                    if( isset( $this-> projects[$project_id]['total_hour_count'] ) )
                        $this-> projects[$project_id]['total_hour_count'] += $val['hour_count'];
                        else
                            $this-> projects[$project_id]['total_hour_count'] =  $val['hour_count'];

                    $this -> UnsetZakData( $val );
                    $this-> projects[$project_id]['items'][ $order_id ] = $val;
                }

                if( $project_id == 0 && $zak_id != 0 )
                {

                    $this-> orders[$zak_id]['zak_id'] =  $val['zak_id'];
                    $this-> orders[$zak_id]['zak_type'] =  $val['zak_type'];
                    $this-> orders[$zak_id]['zak_name'] =  $val['zak_name'];
                    $this-> orders[$zak_id]['zak_dse_name'] =  $val['zak_dse_name'];
                    $this-> orders[$zak_id]['zak_dse_draw'] =  $val['zak_dse_draw'];
                    $this-> orders[$zak_id]['month_hour_count'] = "month_zak";

                    if( isset( $this-> orders[$zak_id]['total_hour_count'] ) )
                        $this-> orders[$zak_id]['total_hour_count'] += $val['hour_count'];
                    else
                        $this-> orders[$zak_id]['total_hour_count'] =  $val['hour_count'];

                    $this -> UnsetProjectData( $val );
                    $this->orders[ $zak_id ]["items"][ $order_id ] = $val;
                }
            }
        }

        return $this -> GetData();
    }

    private function UnsetZakData( &$val )
    {
        unset( $val['zak_id'] );
        unset( $val['zak_type'] );
        unset( $val['zak_name'] );
        unset( $val['zak_dse_name'] );
        unset( $val['zak_dse_draw'] );

        unset( $val['project_name'] );
        unset( $val['project_id'] );
    }

    private function UnsetProjectData( &$val )
    {
        unset( $val['project_id'] );
        unset( $val['project_name'] );

        unset( $val['zak_id'] );
        unset( $val['zak_type'] );
        unset( $val['zak_name'] );
        unset( $val['zak_dse_name'] );
        unset( $val['zak_dse_draw'] );
    }
    public function GetChartData( $month, $year )
    {
        $data = [];
//        $this -> GetChartSectionData( $data, $this-> projects, $month, $year );
        $this -> GetChartSectionData( $data, $this-> orders, $month, $year );
//        $this -> GetChartSectionData( $data, $this-> unlinked, $month, $year );

        $total_hours = 0 ;

        foreach( $data AS $item )
            $total_hours  += $item["hours"];

        foreach( $data AS & $item )
        {
            $perc = round( $item["hours"]  /  $total_hours * 100 , 1 );
            $item["y"] = $perc ;

            foreach( $item['orders_data'] AS & $subitem )
                $subitem["y"] = round( $subitem["hours"]  /  $item["hours"] * $perc , 1 );

        }

        return $data ;
    }

    private function GetChartSectionData( &$data, $in_section, $month, $year )
    {
        foreach( $in_section AS $section )
        {
            $sect_hours = 0 ;
            $orders_data = [];

            foreach( $section['items'] AS $item )
            {
                $order_id = $item['order_id'];
                $hours = $this -> GetOrderChartHours( $order_id, $month, $year );
                if( $hours )
                    $orders_data[] = [ "name" => $item["order_name"], "row_id" => $order_id, "hours" => $hours ];

                $sect_hours += $hours ;
            }
            if(  $sect_hours )
            {
                if( isset( $section["project_id"] ))
                {
                    $id = "project_".$section["project_id"];
                    $name = $section["project_name"];
                }
                else
                {
                    if( isset( $section["zak_id"] ))
                    {
                        $id = "zak_".$section["zak_id"];
                        $name = $section["zak_type"]." ".$section["zak_name"]." ".$section["zak_dse_name"];
                    }
                    else
                    {
                        $id = "unlinked";
                        $name = "Задания вне проектов и заказов";
                    }
               }

                $data[] = [ "id" => $id, "name" => $name, "hours" => $sect_hours, "y" => 0, "orders_data" => $orders_data ];
            }
        }

        return $data ;
    }

    protected function GetOrderChartHours( $order_id, $month, $year )
    {
        $hour_count = 0 ;
        $user_id_str = join(',', $this -> user_id_arr );
        try
        {
            $query ="
                                SELECT
                                SUM(`hour_count`) hour_count
                                FROM `okb_db_working_calendar`
                                WHERE
                                MONTH( date ) = $month
                                AND
                                YEAR( date ) = $year
                                AND
                                `order_id` = $order_id
                                AND
                                `user_id` IN ( $user_id_str )";

            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        if ( $row = $stmt->fetch( PDO::FETCH_OBJ ))
            $hour_count = $row -> hour_count ;

        return $hour_count;
    }


} //class BaseOrdersCalendar




