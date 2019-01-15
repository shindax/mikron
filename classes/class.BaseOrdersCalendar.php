<?php
error_reporting( E_ALL );
error_reporting( 0 );

class BaseOrdersCalendar
{
    protected $pdo ;
    protected $user_id ;
    protected $user_id_arr = null;
    protected $user_name ;

    protected $day ;
    protected $month ;
    protected $year ;
    protected $second_date ;

    protected $day_types ;

    protected $projects = [];
    protected $orders = [];
    protected $unlinked = [];

    public function GetSecondDate()
    {
        return $this -> second_date ;
    }

    public function __construct( $pdo, $user_id_arr, $year = 0, $month = 0, $day = 0, $second_date = [] )
    {
        $this -> pdo = $pdo ;
        $this -> user_id = $user_id_arr[0] ;
        $this -> user_id_arr = $user_id_arr ;

        $this -> day = 1 * $day ;
        $this -> year = 1 * $year ;
        $this -> month = 1 * $month ;

        if( empty($second_date) )
            $this -> second_date = $second_date ;
                else
                    $this -> second_date = ['year' => $second_date['year'], 'month' => 1 * $second_date['month'], 'day' => 1 * $second_date['day']];
                

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

         $this -> CollectDayTypes();
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
        $date_plan = 0;
        $add_where = '';

        if( $this -> year && $this -> month )
        {
            $date_plan = $this -> year * 10000 + $this -> month * 100 + 01 ;
       //     $add_where = " AND STARTDATE < ". ( $this -> year ).( $this -> month > 9 ? $this -> month : "0".( $this -> month ))."31";
        }

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
                       # okb_db_itrzadan.DATE_PLAN >= $date_plan 
                       # AND
                        okb_db_itrzadan.ID_users2 IN ( $users_arr )
                        #AND # Восстановлено по заказу Роева 2.-7.2018
                        #okb_db_itrzadan.STATUS <> 'Завершено'  # Восстановлено по заказу Роева 2.-7.2018
                        AND
                        okb_db_itrzadan.STATUS <> 'Аннулировано' 
                        ";

            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query is : $query");
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
                    $val['hour_count_by_order'] = $row->hour_count ? $row->hour_count : 0;

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

    protected function UnsetZakData( &$val )
    {
        unset( $val['zak_id'] );
        unset( $val['zak_type'] );
        unset( $val['zak_name'] );
        unset( $val['zak_dse_name'] );
        unset( $val['zak_dse_draw'] );

        unset( $val['project_name'] );
        unset( $val['project_id'] );
    }

    protected function UnsetProjectData( &$val )
    {
        unset( $val['project_id'] );
        unset( $val['project_name'] );

        unset( $val['zak_id'] );
        unset( $val['zak_type'] );
        unset( $val['zak_name'] );
        unset( $val['zak_dse_name'] );
        unset( $val['zak_dse_draw'] );
    }

    public function GetChartData()
    {
        $data = [];
        $this -> GetChartSectionData( $data, $this-> projects );
        $this -> GetChartSectionData( $data, $this-> orders );
        $this -> GetChartSectionData( $data, $this-> unlinked );

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

    protected function GetChartSectionData( &$data, $in_section )
    {

        foreach( $in_section AS $section )
        {
            $orders_data = [];        
            $sect_hours = 0 ;

            foreach( $section['items'] AS $item )
            {
                $order_id = $item['order_id'];
                $hours = $this -> GetOrderChartHours( $order_id );
               
                if( $hours )
                    $orders_data[] = [ "name" => $item["order_name"], "row_id" => $order_id, "hours" => $hours , "hour_count_by_order" =>  $item['hour_count_by_order'] ];

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

                $data[] = [ "id" => $id, "name" => $name, "hours" => $sect_hours, "y" => 0, "orders_data" => $orders_data ];
            }
        }
        
        return $data ;
    }

    protected function GetOrderChartHours( $order_id )
    {
        $hour_count = 0 ;
        $user_id_str = join(',', $this -> user_id_arr );

        try
        {
			if(!empty($this->second_date) and ($this->month !== '' and $this->year !== '' and $this->day !== ''))
				$query ="
                                SELECT
                                SUM(`hour_count`) hour_count
                                FROM `okb_db_working_calendar`
                                WHERE
								`date` BETWEEN '".$this -> year."-".$this -> month."-".$this -> day."' AND '".$this->second_date['year']."-".$this->second_date['month']."-".$this->second_date['day']."'
                                AND
                                `order_id` = $order_id
                                AND
                                `user_id` IN ( $user_id_str )";
			elseif(!empty($this->second_date))
				$query ="
                                SELECT
                                SUM(`hour_count`) hour_count
                                FROM `okb_db_working_calendar`
                                WHERE
                                MONTH( date ) <= ".( $this->second_date['month'] )."
                                AND
                                YEAR( date ) <= ".( $this->second_date['year'] )."
								AND
								DAY( date ) <= ".( $this->second_date['day'] )."
                                AND
                                `order_id` = $order_id
                                AND
                                `user_id` IN ( $user_id_str )";
			else
				$query ="
                                SELECT
                                SUM(`hour_count`) hour_count
                                FROM `okb_db_working_calendar`
                                WHERE
                                MONTH( date ) >= ".( $this->month )."
                                AND
                                YEAR( date ) >= ".( $this->year )."
								AND
								DAY( date ) >= ".( $this->day )."
                                AND
                                `order_id` = $order_id
                                AND
                                `user_id` IN ( $user_id_str )";

            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query is : $query");
        }

        if ( $row = $stmt->fetch( PDO::FETCH_OBJ ))
            $hour_count = $row -> hour_count ;

        return $hour_count;
    }


    public function GetDayHourData()
    {
        $user_name = $this -> GetUserName();
        $day_types = $this -> GetDayTypes();
        $user_id = $this -> GetUserID();
        $task_arr = $this -> GetChartData();
        $data = [];
		
		$bonus_payment = $this->GetBonusPayment( $user_id );
		
		foreach( $task_arr AS $task )
        {
            $caption = "";
            $id = $task['id'];
            $order_name = $task['name'];
            $orders_data = $task['orders_data'];

            if( strpos( $id, 'project_') !== FALSE )
                $caption = " Проект ";
            if( strpos( $id, 'zak_') !== FALSE )
                $caption = " Заказ ";
            // if( strpos( $id, 'unlinked') !== FALSE )
            //     $caption = " Задание вне проектов и заказов. ";

            $caption .= "$order_name. ";

            foreach( $orders_data AS $order )
                $data[] = ['name' => "$caption ".$order['name'], 'hours' => $order['hours'], 'hour_count_by_order' => $order['hour_count_by_order'], 'row_id' => $order['row_id']];
        }

        $arr = [ 'name' => $user_name, 'id' => $user_id, 'day_types' => $day_types, 'data' => $data, 'bonus_payment' => $bonus_payment ];
        
       return $arr;
    }
	
	
	private function GetBonusPayment($user_id)
	{
		try
        {
			if(!empty($this->second_date) and ($this->month !== '' and $this->year !== '' and $this->day !== ''))
			{
				$month = '';
				
				if($this->month < 10)
					$month = '0'.$this->month;
				else
					$month = $this->month;
				
				$second_month = '';
				
				if($this->second_date['month'] < 10)
					$second_month = '0'.$this->second_date['month'];
				else
					$second_month = $this->second_date['month'];
				
				$query ="
                                SELECT
                                `bonus`, DATE_FORMAT(`date`, '%m.%Y') as date
                                FROM `okb_bonuspayment`
                                WHERE
								`date` BETWEEN '".$this -> year."-".$month."-".$this -> day."' AND '".$this->second_date['year']."-".$second_month."-01'
                                AND
                                `user_id` = $user_id";
			}
			elseif(!empty($this->second_date))
			{
				$second_month = '';
				
				if($this->second_date['month'] < 10)
					$second_month = '0'.$this->second_date['month'];
				else
					$second_month = $this->second_date['month'];
				
				$query ="
                                SELECT
                                `bonus`, DATE_FORMAT(`date`, '%m.%Y') as date
                                FROM `okb_bonuspayment`
                                WHERE
                                `date` <= ".$this->second_date['year']."-".$second_month."-01
								AND
                                `user_id` = $user_id";
			}
			else
			{
				$month = '';
				
				if($this->month < 10)
					$month = '0'.$this->month;
				else
					$month = $this->month;
				
				$query ="
                                SELECT
                                `bonus`, DATE_FORMAT(`date`, '%m.%Y') as date
                                FROM `okb_bonuspayment`
                                WHERE
                                `date` >= ".$this->year."-".$month."-01
								AND
                                `user_id` = $user_id";
			}
			
            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query is : $query");
        }
		
		$bonus_list = array();
		
		while ( $row = $stmt->fetch( PDO::FETCH_OBJ ))
		{
			$bonus_list[] = array('bonus'=>$row->bonus, 'date'=>$row->date);
		}
        
        return $bonus_list;
	}

    public function GetDayTypes()
    {
        $arr = [];

        foreach ( $this -> day_types as $value ) 
        {
            if( $value['count'] )
                $arr[] = "{$value['type']}:{$value['count']}. часов:{$value['hours']}";
        }

        return join(", ", $arr ) ;
    }

    private function CollectDayTypes()
    {
        $day_types = [];
        $res_id = $this -> user_id ;
        $from_day = $this -> day < 10 ? '0'.$this -> day : $this -> day ;
        $from_month = $this -> month < 10 ? '0'.$this -> month : $this -> month ;
        $from_year = $this -> year;
        $from = $from_year.$from_month.$from_day;

        $to_day = $this -> second_date ['day'] < 10 ? '0'.$this -> second_date['day'] : $this -> second_date ['day'];
        $to_month = $this -> second_date['month'] < 10 ? '0'.$this -> second_date['month'] : $this -> second_date ['month'] ;
        $to_year = $this -> second_date ['year'] ;
        $to = $to_year.$to_month.$to_day;

        try
        {
            $query = "  SELECT 
                        tabel.TID `tid`, 
                        tabel.FACT `fact`, 
                        day_type.day_type_description `day_type`

                        FROM okb_db_tabel tabel
                        LEFT JOIN okb_db_tabel_day_type day_type ON day_type.day_type_id = tabel.TID
                        WHERE 
                        tabel.ID_resurs = $res_id
                        AND
                        tabel.DATE >= $from
                        AND
                        tabel.DATE <= $to
                        ORDER BY `tid`
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
            $tid = $row -> tid ;
            $day_type = strlen( $row -> day_type ) ? $row -> day_type : "Раб. дней ";
            $fact = $row -> fact ;

            if( isset( $day_types[ $tid ] ) )
            {
                $day_types[ $tid ]["type"] = $day_type ;
                if( $fact )
                {
                    if( isset( $day_types[ $tid ]["count"] ) )
                        $day_types[ $tid ]["count"] ++;
                            else
                                $day_types[ $tid ]["count"] = 1;
                    
                    if( isset( $day_types[ $tid ]["hours"] ) )
                        $day_types[ $tid ]["hours"] += $fact;
                            else
                                $day_types[ $tid ]["hours"] = $fact;

                }
            }
            else
                {
                    if( !isset( $day_types[ $tid ]["type"] ) )
                        $day_types[ $tid ]["type"] = $day_type ;
                    
                    if( $fact )
                    {
                        if( !isset( $day_types[ $tid ]["count"] ) )
                            $day_types[ $tid ]["count"] = 1;
                        
                        if( !isset( $day_types[ $tid ]["hours"] ) )
                            $day_types[ $tid ]["hours"] = $fact;       
                    }
                        else
                            {
                                if( !isset( $day_types[ $tid ]["count"] ) )
                                    $day_types[ $tid ]["count"] = 0;
                                
                                if( !isset( $day_types[ $tid ]["hours"] ) )
                                    $day_types[ $tid ]["hours"] = 0;       
                            }
                }
        }

        $this -> day_types = $day_types ;
    }

} //class BaseOrdersCalendar


