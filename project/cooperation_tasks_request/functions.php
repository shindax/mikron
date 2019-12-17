<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/common_functions.php" );

function getTable( $req_id, $user_id )
{
  $str = getTableHead( $req_id );
  $str .= getTableContent( $req_id, $user_id );
  $str .= getTableEnd();
  return $str ;
}

function getCommentPriceOptions( $state )
{
    global $pdo ;
    $option = "<option value='0'>...</option>";

    $query = "SELECT * FROM `okb_db_coop_request_pricing` WHERE 1 ORDER BY descr";

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
                $id = $row -> id ;
                $descr = $row -> descr ;
                $option .= "<option value='$id' ".( $id == $state ? 'selected' : '' ).">$descr</option>" ;
            }

     return $option ;
}


function getCommentStateOptions( $state )
{
    global $pdo ;
    $option = "<option value='0'>...</option>";

    $query = "SELECT * FROM `okb_db_coop_request_task_state` WHERE 1 ORDER BY descr";

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
                $id = $row -> id ;
                $descr = $row -> descr ;
                $option .= "<option value='$id' ".( $id == $state ? 'selected' : '' ).">$descr</option>" ;
            }

     return $option ;
}


function getRequisitionName( $req_id )
{
	global $pdo ;
	$name = '';

    $query = "
                SELECT 
                req.NAME AS name,
                req.TXT AS description,
                req.OBOZ AS drawing,
                req.CDATE AS date,

                req.VIDRABOT AS work_kind,
                req.OPTIONS AS options, 
                -- zak.NAME AS ord_name,
                -- zak_type.description AS ord_type,
                users.FIO AS user_name 

                FROM `okb_db_koop_req_krz` AS req
                -- LEFT JOIN `okb_db_zak` AS zak ON zak.ID = req.ID_zak 
                -- LEFT JOIN `okb_db_zak_type` AS zak_type ON zak_type.id = zak.TID 
                LEFT JOIN `okb_users` AS users ON users.id = req.ID_users 

                WHERE  
                req.ID = $req_id 
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
                        'name' => conv( $row -> name ),
                        'description' => conv( $row -> description ),
                        'ord_type' => conv( $row -> ord_type ),
                        'ord_name' => conv( $row -> ord_name ),
                        'work_kind' => conv( $row -> work_kind ),
                        'options' => conv( $row -> options ),
                        'user_name' => conv( $row -> user_name ),
                        'drawing' => conv( $row -> drawing ),
                        'date' => $row -> date
                    ] ;

     return $res ;
}

function getRequisitionTasks( $req_id )
{
	global $pdo ;
	$data = [];

    $query = "
                SELECT *, clients.NAME as cagent_name 
                FROM okb_db_coop_request_tasks 
                LEFT JOIN okb_db_clients AS clients ON clients.ID = okb_db_coop_request_tasks.cagent_id
                WHERE  
                coop_req_id = $req_id 
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
        	$data[] = [
        				"id" => $row -> id,
        				"cagent_name" => conv( $row -> cagent_name ),
        				"req_send_date" => $row -> req_send_date,
        				"req_response_date" => $row -> req_response_date,
        				"state" => $row -> state,
        				"state_note" => conv( $row -> state_note ),
        				"pricing" => $row -> pricing,
        				"pricing_note" => conv( $row -> pricing_note )
        			];

     return $data ;
}

function strProcess( $str )
{
    $result = $str ;
    if( !strlen( $str ))
        $result = "&nbsp;";

    return $result;
}

function getPageCaption( $req_id , $user_id )
{
    if( $user_id == 5 || $user_id == 154 || $user_id == 228 || $user_id == 165 )
        $disabled = '';
            else
                $disabled = 'disabled';

    $req_details = getRequisitionName( $req_id ) ;

    // debug( $req_details );

    $name = strProcess($req_details['name']);
    $description = strProcess($req_details['description']);
    $ord_type = strProcess($req_details['ord_type']);
    $ord_name = strProcess($req_details['ord_name']);
    $work_kind = strProcess($req_details['name']);
    $options = strProcess($req_details['options']);
    $user_name = strProcess($req_details['user_name']);
    $drawing = strProcess( $req_details['drawing'] );

    $date = $req_details['date'];
    $date = substr($date, 6, 2 ).".".substr($date, 4, 2 ).".".substr($date, 0, 4 );

	$str = "<div class='row'><div class='col'><h2>".conv("Проработка и контрактация по заявке на проработку кооперации")."#".$name.conv(" от ").$date."</h2></div></div>";

    $str .= "<p><span>".conv("Заявитель : </span>").$user_name."</p>";
    $str .= "<p><span>".conv("Описание заявки : </span>").$description."</p>";  
    $str .= "<p><span>".conv("Заказ : </span>").$ord_type." ".$ord_name."</p>";  
    $str .= "<p><span>".conv("Чертеж : </span>").$drawing."</p>";    
    $str .= "<p><span>".conv("Вид работ : </span>").$work_kind."</p>";    
    $str .= "<p><span>".conv("Параметры детали : </span>").$options."</p>";

	$str .= "<hr><div class='row'><div class='col text-right'><button class='btn btn-small btn-primary-outline' type='button' id='add_cagent' $disabled>".conv('Добавить контрагента')."</button></div></div>";

	return $str ;
}

function getTableRow( $row, $line, $user_id )
{
    $disabled = 'disabled' ;
    $can_select = 'disabled' ;

    if( $user_id == 5 || $user_id == 154 || $user_id == 228 || $user_id == 165 )
        $disabled = '' ;

               if( $user_id == 5 )
                {
                    $img = "<img class='del_cagent' src='uses/del.png' />";
                    $can_select = '' ;                    
                }
                        else
                            $img = "<img src='uses/del_dis.png' />";                           

                $selected = $row -> selected ? 'checked' : '';

                $id = $row -> id;
                $state = $row -> state;
                $state_note = conv( $row -> state_note );

                if( $state == 1 )
                {
                    $state_comment_select_class = 'hidden';
                    $state_comment_input_class = '';
                }
                else
                {
                    $state_comment_select_class = '';
                    $state_comment_input_class = 'hidden';
                }


                $state_comment_select = "<select data-field='state' class='state_comment_select $state_comment_select_class' data-id='$id' $disabled>".conv( getCommentStateOptions( $state ))."</select>";
                $state_comment_input = "<input data-field='state' data-id='$id' class='state_comment_input $state_comment_input_class' value='$state_note'$disabled />";

                $state_comment_input = "<textarea rows='4' data-field='state' data-id='$id' class='state_comment_input $state_comment_input_class' $disabled>$state_note</textarea>";

                $pricing = $row -> pricing;
                $pricing_note = conv( $row -> pricing_note );

                if( $pricing == 1 )
                {
                    $pricing_select_class = 'hidden';
                    $pricing_input_class = '';
                }
                else
                {
                    $pricing_select_class = '';
                    $pricing_input_class = 'hidden';
                }

                $pricing_select = "<select data-field='pricing' class='pricing_select $pricing_select_class' data-id='$id' $disabled>".conv( getCommentPriceOptions( $pricing ))."</select>";
                $pricing_input = "<input data-field='pricing' data-id='$id' class='pricing_input $pricing_input_class' value='$pricing_note' $disabled/>";

                if( $row -> cagent_id == 0 && $disabled == '')
                    $client = getClientsSelect( $id );
                        else
                            $client = conv( $row -> client_name );

                    $str = "<tr data-id='$id'>";
                    $str .= "<td class='text-center'><span class='line'>".( $line ++ )."</span></td>";
                    $str .= "<td class='client'><span>$client</span></td>";
                    $str .= "<td><input class='datepicker' data-field='req_send_date' value='".( $row -> req_send_date )."' $disabled/></td>";
                    $str .= "<td><input class='datepicker' data-field='req_response_date' value='".( $row -> req_response_date )."' $disabled/></td>";
                    $str .= "<td>$state_comment_input $state_comment_select</td>";
                    $str .= "<td>$pricing_select $pricing_input</td>";
                    $str .= "<td class='text-center'><input class='selected' name='radio' type='checkbox' $selected $can_select /></td>";
                    $str .= "<td class='text-center'>$img</td>";
                    $str .= "</tr>";    

    return $str ;
}

function getClientsSelect( $id )
{   
    global $pdo ;
    $str = "<select class='combobox' data-id='$id'><option calue='0'></option>";

    $query = "
                    SELECT ID, NAME 
                    FROM `okb_db_clients` AS clients
                    WHERE 1
                    ORDER BY NAME
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
            $str .= "<option value='".( $row -> ID )."'>".conv( $row -> NAME )."</option>";
    
    $str .= "</select>";

    return $str ;
}

function getTableContent( $req_id, $user_id )
{	
    global $pdo ;
    $str = "";

    $query = "
                    SELECT *, 
                    DATE_FORMAT( tasks.req_send_date, '%d.%m.%Y') AS req_send_date,
                    DATE_FORMAT( tasks.req_response_date, '%d.%m.%Y') AS req_response_date,
                    clients.NAME AS client_name, tasks.cagent_id cagent_id
                    FROM `okb_db_coop_request_tasks` AS tasks
                    LEFT JOIN okb_db_clients clients ON clients.ID = tasks.cagent_id
                    WHERE tasks.coop_req_id = $req_id
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

        $line = 1 ;
        while ( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            $str .= getTableRow( $row, $line ++, $user_id );
	
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
			<col width='1%'>
			<col width='10%'>
			<col width='2%'>
			<col width='2%'>
			<col width='10%'>
			<col width='5%'>
			<col width='1%'>
            <col width='1%'>            
			  <thead>
			    <tr class='table-success'>
			      <th class='text-center'>".conv( "#" )."</th>
			      <th class='text-center'>".conv( "Контрагент" )."</th>
			      <th class='text-center'>".conv( "Дата отправки запроса" )."</th>
			      <th class='text-center'>".conv( "Дата ответа на запрос" )."</th>
			      <th class='text-center'>".conv( "Комментарий" )."</th>
			      <th class='text-center'>".conv( "Цена" )."</th>
			      <th class='text-center'><input type='checkbox' class='header' name='header' checked disabled/></th>      
            <th class='text-center'></th>                  
			    </tr>
			  </thead>
			  <tbody>
		";
}

