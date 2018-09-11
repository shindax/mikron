<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/common_functions.php" );

function getTable( $user_id )
{
  $str = getTableHead( $req_id );
  $str .= getTableContent( $user_id );
  $str .= getTableEnd();
  return $str ;
}

function getPageCaption( $user_id )
{
    $disabled = 'disabled';
    
    if( $user_id == 1 || $user_id == 157 )
        $disabled = '';
                

	$str = "<div class='row'><div class='col'><h2>".conv("База данных по логистике")."</h2></div></div>";

	$str .= "<hr><div class='row'><div class='col text-right'><button class='btn btn-small btn-primary' type='button' id='add_city' $disabled>".conv('Добавить город')."</button></div></div>";

	return $str ;
}

function getTableRow( $row, $line, $user_id )
{
    $disabled = 'disabled' ;
    $can_select = 'disabled' ;
    


    $img = "<img src='uses/del_dis.png' />";

    if( $user_id == 1 || $user_id == 157 )
    {
        $img = "<img class='del_city' src='uses/del.png' />";
        $can_select = '' ;                    
    }


    $date = $row -> date ;
    if( $date == "00.00.0000")
        $date = '';

    if( $user_id == 1  || $user_id == 157 )
        $disabled = '' ;

                    $str = "<tr data-id='".( $row -> id )."'>";
                    $str .= "<td class='text-center'><span class='line'>".( $line )."</span></td>";
                    $str .= "<td class='city'><input class='city_input' data-field='city' value='".conv( $row ->  city )."' $disabled/></td>";

                    $str .= "<td><input class='text_input' data-field='auto_delivery_eurovan' value='".conv( $row ->  auto_delivery_eurovan )."' $disabled/></td>";

                    $str .= "<td><input class='text_input' data-field='auto_delivery_oversize' value='".conv( $row ->   auto_delivery_oversize  )."' $disabled/></td>";

                    $str .= "<td><input class='text_input' data-field='railway_delivery_semiwagon' value='".conv( $row ->  railway_delivery_semiwagon )."' $disabled/></td>";

                    $str .= "<td><input class='text_input' data-field='assembly_cargo' value='".conv( $row ->  assembly_cargo )."' $disabled/></td>";

                    $str .= "<td><input class='text_input' data-field='avia_delivery' value='".conv( $row ->  avia_delivery )."' $disabled/></td>";

                    $str .= "<td><input class='datepicker' data-field='actuality' value='$date' $disabled/></td>";

                    $str .= "<td class='text-center'>$img</td>";
                    $str .= "</tr>";    

    return $str ;
}

function getTableContent( $user_id )
{	
    global $pdo ;
    $str = "";

    $query = "
                    SELECT *, DATE_FORMAT( actuality, '%d.%m.%Y') AS date
                    FROM `okb_db_logistic_rates`
                    WHERE 1
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
			<table id='logistic_table' data-id='$req_id' class='table table-striped'>
            <col width='2%'>
			<col width='36%'>
            <col width='10%'>
            <col width='10%'>
            <col width='10%'>                        
            <col width='10%'>
            <col width='10%'>
            <col width='10%'>
            <col width='2%'>
			  <thead>
			    <tr class='table-success'>
                  <th class='text-center'>#</th>                
			      <th class='text-center'>".conv( "Направление" )."</th>
			      <th class='text-center'>".conv( "Автодоставка<br>(еврофура)" )."</th>
			      <th class='text-center'>".conv( "Автодоставка<br>(негабарит)" )."</th>
			      <th class='text-center'>".conv( "ЖД доставка<br>(полувагон)" )."</th>
			      <th class='text-center'>".conv( "ТК<br>сборный груз" )."</th>
			      <th class='text-center'>".conv( "АВИА<br>доставка" )."</th>
                  <th class='text-center'>".conv( "Актуальность" )."</th>                  
                  <th class='text-center'></th>                                    
			    </tr>
			  </thead>
			  <tbody>
		";
}

