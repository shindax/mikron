<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/common_functions.php" );

function getDataFromZak( &$arr, $direction, $direction_stage, $data )
{
	global $pdo ;

	$data = getBreakApartPD( $data );

	foreach( $data AS $val )
	{
		$user = $val['user'];
		$query = "SELECT FIO FROM `okb_users` WHERE id=$user";

        try
        {
            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, in ".__FUNCTION__." function, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        if ( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
        	$user = conv( $row -> FIO );

    		$element = [ 
        						'cause' => '', 
        						'rate' => '',
        						'old_date' => $val['old_date'],
        						'new_date' => $val['new_date'],
        						'comment' => '',
        						'user' => $user,
        						'id' => ''
        					];		
			updateArr( $arr, $direction, $direction_stage, $element );        					
	}
}


function getBreakApartPD( $str )
{
		$arr = [];
        // Получаем начало PD : состояние и первая дата
        $state_and_dates = explode('#', $str ) ;

        unset( $state_and_dates[0] );
        unset( $state_and_dates[1] );

        foreach( $state_and_dates AS $key => $val )
        {
        	if( $key % 2 )
        		continue ;

			$dates = explode('|', $val ) ;
			$old_date = explode(' ', $dates[0] )[0];
			$new_date = explode(' ', $dates[1] )[0];

			if( strlen( $new_date ) )
			$arr[] = [ 'old_date' => $old_date, 'new_date' => $new_date, 'user' => $state_and_dates[ $key + 1 ]];
        }

        return $arr ;
}

function getTable( $req_id, $user_id = 0, $use_zak_table = 0 )
{
  $str = getTableHead( $req_id );
  $str .= getTableContent( $req_id, $user_id, $use_zak_table);
  $str .= getTableEnd();
  return $str ;
}

function getOrderDetails( $req_id )
{
	global $pdo ;
	$name = '';

    $query = "
                SELECT 
                zak.NAME AS ord_name,
                zak.DSE_NAME AS dse_name,                
                zak.DSE_OBOZ AS drawing,
                zak_type.description AS ord_type,
                client.NAME AS client_name

                FROM `okb_db_zak` AS zak
                LEFT JOIN `okb_db_zak_type` AS zak_type ON zak_type.id = zak.TID 
                LEFT JOIN `okb_db_clients` AS client ON client.id = zak.ID_clients
                WHERE  
                zak.ID = $req_id 
			";

        try
        {
            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, in ".__FUNCTION__." function, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        if ( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
        	$res = [
                        'ord_name' => conv( $row -> ord_name ),
                        'dse_name' => conv( $row -> dse_name ),
                        'ord_type' => conv( $row -> ord_type ),
                        'drawing' => conv( $row -> drawing ),
                        'client_name' => conv( $row -> client_name )
                    ] ;

     return $res ;
}

function getPageCaption( $req_id = 0 , $user_id = 0 )
{
    $req_details = getOrderDetails( $req_id ) ;

    $ord_name = $req_details['ord_name'];
    $ord_type = $req_details['ord_type'];    
    $dse_name = $req_details['dse_name'];
    $client_name = $req_details['client_name'];
    $drawing = $req_details['drawing'];    

	$str = "<div class='row'><div class='col'><h2>".conv("Просмотр переносов сроков по заказу ")."$ord_type $ord_name</h2>";
	$str .= "<h3>$dse_name $drawing $client_name</h3></div></div><hr>";

	return $str ;
}

function getTableRow( $row, $line, $user_id )
{
	$str = '';
    return $str ;
}

function getTableContent( $req_id, $user_id = 0, $use_zak_table = 0 )
{	
    global $pdo ;
    $str = "";
	$arr = [
			conv("Подготовка производства") => [],
			conv("Комплектация") => [],
			conv("Производство") => [],
			conv("Коммерция") => []
		];


    $query = "
				SELECT
				okb_db_plan_fact_carry_causes.cause,
				okb_db_plan_fact_carry_causes.rate,
				okb_db_zak_ch_date_history.id,
				okb_db_plan_fact_direction_stages.`name` AS direction_stage,
				okb_db_plan_fact_direction_stages.field,
				okb_db_plan_fact_directions.direction,
				okb_db_zak_ch_date_history.date_string,
				okb_db_zak_ch_date_history.`comment`,
				okb_users.FIO AS user_name
				FROM
				okb_db_zak_ch_date_history
				LEFT JOIN okb_db_plan_fact_carry_causes ON okb_db_zak_ch_date_history.cause = okb_db_plan_fact_carry_causes.id
				LEFT JOIN okb_db_plan_fact_direction_stages ON okb_db_plan_fact_carry_causes.direction_stage_id = okb_db_plan_fact_direction_stages.id
				LEFT JOIN okb_db_plan_fact_directions ON okb_db_plan_fact_direction_stages.direction_id = okb_db_plan_fact_directions.id
				LEFT JOIN okb_users ON okb_db_zak_ch_date_history.user_id = okb_users.ID
				WHERE
				okb_db_zak_ch_date_history.zak_id = $req_id 
				ORDER BY
				okb_db_plan_fact_directions.id ASC,
				okb_db_zak_ch_date_history.id ASC,
				okb_db_plan_fact_direction_stages.id ASC
                ";

        try
        {
            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, in ".__FUNCTION__." function, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }


        while ( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
        	{
        		$direction_name = conv( $row -> direction );
				$direction_stage_name = conv( $row -> direction_stage );

        		$cause = conv( $row -> cause );
        		$rate = $row -> rate ;
        		$id = $row -> id ;
        		$date_string = $row -> date_string ;
				$datearr = explode( " ", $date_string );
        		$comment = conv( $row -> comment );
				$user = conv( $row -> user_name );

        		$element = [ 
        						'cause' => $cause, 
        						'rate' => $rate,
        						'old_date' => $datearr[0],
        						'new_date' => $datearr[2],
        						'comment' => $comment,
        						'user' => $user,
        						'id' => $id
        					];

				updateArr( $arr, $direction_name, $direction_stage_name, $element );
        	}


 if( $use_zak_table )
  {
    $query = "SELECT PD1, PD2, PD3, PD4, PD7, PD12, PD8, PD13, PD9, PD10, PD11 
    		  FROM `okb_db_zak` WHERE id = $req_id";

        try
        {
            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, in ".__FUNCTION__." function, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }


        if ( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
		{
			getDataFromZak( $arr, conv("Подготовка производства"), conv("КД"),$row -> PD1 );
			getDataFromZak( $arr, conv("Подготовка производства"), conv("Нормы расхода"),$row -> PD2 );
			getDataFromZak( $arr, conv("Подготовка производства"), conv("МТК"),$row -> PD3 );


			getDataFromZak( $arr, conv("Комплектация"), conv("Проработка"),$row -> PD4 );
			getDataFromZak( $arr, conv("Комплектация"), conv("Поставка"),$row -> PD7 );

			getDataFromZak( $arr, conv("Производство"), conv("Дата начала"),$row -> PD12 );
			getDataFromZak( $arr, conv("Производство"), conv("Дата окончания"),$row -> PD8 );
			getDataFromZak( $arr, conv("Производство"), conv("Инструмент и оснастка"),$row -> PD13 );

			getDataFromZak( $arr, conv("Коммерция"), conv("Предоплата"),$row -> PD9 );
			getDataFromZak( $arr, conv("Коммерция"), conv("Окончательный расчет"),$row -> PD10 );
			getDataFromZak( $arr, conv("Коммерция"), conv("Поставка"),$row -> PD11 );

		}
	}// if( $use_zak_table )

	foreach( $arr AS $key => $val )
	{
		if( count( $val ) == 0 )
			continue;

		$str .= "<tr class='table-info'><td colspan='7'>$key</td></tr>";
		foreach( $val AS $elkey => $el )
		{
			$count = count( $el );
			$j = 0 ;
			foreach( $el AS $subelkey => $subel )
			{
			  	$str .= "<tr data-id='$subelkey'>";
;
			  	if( $j == 0 )
			  		$str .= "<td rowspan='$count'>$elkey</td>";

				$str .= "<td>".$subel['old_date']."</td>
						 <td>".$subel['new_date']."</td>
						 <td>".$subel['user']."</td>						 
						 <td>".$subel['cause']."</td>
						 <td>".$subel['rate']."</td>
						 <td>".$subel['comment']."</td>						 
						 </tr>";
				$j ++ ;
			}
		}
	}

    return $str;
}

function getTableEnd()
{	
	return "</body></table>";
}

function getTableHead( $req_id )
{
	return "
			<table id='requisition_tasks_table' data-id='$req_id' class='table table-striped'>
			<col width='10%'>
			<col width='5%'>
			<col width='5%'>
			<col width='10%'>
			<col width='30%'>
			<col width='5%'>
			<col width='30%'>
			  <thead>
			    <tr class='table-success'>
			      <th class='text-center'>".conv( "Этап" )."</th>
			      <th class='text-center'>".conv( "Дата изменения" )."</th>
			      <th class='text-center'>".conv( "Новая дата" )."</th>
			      <th class='text-center'>".conv( "Инициатор" )."</th>			     
			      <th class='text-center'>".conv( "Причина" )."</th>
			      <th class='text-center'>".conv( "Штраф" )."</th>
			      <th class='text-center'>".conv( "Комментарий" )."</th>			
			    </tr>
			  </thead>
			  <tbody>
		";
}

function updateArr( &$arr, $direction_name, $direction_stage_name, $element )
{
	$arr[ $direction_name ][ $direction_stage_name ][] = $element ;
}
