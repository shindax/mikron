<?php
error_reporting( 0 );

class SemifinInvoice
{
	private $pdo;
	private $invoice_id;	
	private $user_id;
	private $invoice_number;
	private $invoice_items;

	// static public $operations = 0 ;
	// static private $warehouses = 0 ;
	// static private $cells = 0 ;
	// static private $tiers = 0 ;	

	public $operations = 0 ;
	private $warehouses = 0 ;
	private $cells = 0 ;
	private $tiers = 0 ;
	private $disabled ;	
	private $issued ;

	public function __construct( $pdo, $invoice_number, $disabled = false )
	{
		$this -> pdo = $pdo ;
		$this -> invoice_id = $invoice_number ;
		$this -> invoice_number = $invoice_number ;
		$this -> disabled = $disabled;
		$this -> GetData();
		
		// if( ! $this -> operations )
		// 	$this -> operations = $this -> GetOptions( 'okb_db_oper', 'NAME' );

		if( ! $this -> operations )
			$this -> operations = $this -> GetOperations();
		
		if( ! $this -> warehouses )
			$this -> warehouses = $this -> GetOptions( 'okb_db_sklades', 'ORD' );
		
		if( ! $this -> cells )
			$this -> cells = $this -> GetOptions( 'okb_db_sklades_item', 'NAME', 'ID_sklad');

		if( ! $this -> tiers )
			$this -> tiers = $this -> GetOptions( 'okb_db_sklades_yaruses', '( ORD + 0 )', 'ID_sklad_item');

	}

	private function GetData()
	{
            try
            {
                $query	 = 		"SELECT
								inv.id,
								inv.user_id,
								inv.id_zadan,
								inv.dse_name,
								inv.order_name,
								inv.draw_name,
								inv.part_num,
								inv.count,
								
								inv.accepted_by_QCD,
								inv.accepted_by_QCD_res_id,
								DATE_FORMAT( inv.accepted_by_QCD_date, '%d.%m.%Y %H:%i' ) AS accepted_by_QCD_date,
								res2.NAME AS accepted_by_QCD_res_name,

								inv.storage_place,						
								inv.transfer_place,
								inv.note AS invoice_note,

								inv.host_master_id AS host_master_id,
								inv.host_master_ack AS host_master_ack,
								DATE_FORMAT( inv.host_master_ack_datetime, '%d.%m.%Y %H:%i' ) AS host_master_ack_datetime,

								stype.description AS storage_time_name,
								stype.id AS storage_time_id,
								warehouse.`NAME` AS warehouse_name,
								warehouse.ID AS warehouse_id,
								warehouse_cell.`NAME` AS warehouse_cell_name,
								warehouse_cell.ID AS warehouse_cell_id,
								warehouse_tier.ID AS warehouse_tier_id,
								okb_db_sklades_detitem.`NAME` AS warehouse_item_name,
								okb_db_sklades_detitem.ID AS warehouse_item_id,
								oper.`NAME` AS operation_name,
								oper.ID AS operation_id,

								res.NAME AS host_master_name

								FROM
								okb_db_semifinished_store_invoices AS inv
								LEFT JOIN okb_db_semifinished_store_type AS stype ON inv.storage_time = stype.id
								LEFT JOIN okb_db_sklades_detitem ON inv.warehouse_item_id = okb_db_sklades_detitem.ID
								LEFT JOIN okb_db_sklades_yaruses AS warehouse_tier ON okb_db_sklades_detitem.ID_sklades_yarus = warehouse_tier.ID
								LEFT JOIN okb_db_sklades_item AS warehouse_cell ON warehouse_tier.ID_sklad_item = warehouse_cell.ID
								LEFT JOIN okb_db_sklades AS warehouse ON warehouse_cell.ID_sklad = warehouse.ID
								LEFT JOIN okb_db_oper AS oper ON inv.operation_id = oper.ID
								LEFT JOIN okb_db_resurs AS res ON res.ID = inv.host_master_id
								LEFT JOIN okb_db_resurs AS res2 ON res2.ID = inv.accepted_by_QCD_res_id
								WHERE
								inv.id = {$this->invoice_id}
								ORDER BY
								inv.id ASC
								";
                $stmt = $this -> pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query" );
            }
            while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            	{
            		$this -> user_id = $row -> user_id ;
            		$this -> invoice_items[] = 
            		[
            			"id" => $row -> id,
            			"id_zadan" => $row -> id_zadan,
            			"dse_name" => conv( $row -> dse_name ),
            			"order_name" => conv( $row -> order_name ),
            			"draw_name" => conv( $row -> draw_name ),
            			"part_num" => conv( $row -> part_num ),
            			"transfer_place" => conv( $row -> transfer_place ),
            			"count" => $row -> count ,
            			
            			"accepted_by_QCD" => $row -> accepted_by_QCD,
						"accepted_by_QCD_res_id" => $row -> accepted_by_QCD_res_id,
						"accepted_by_QCD_res_name" => conv( $row -> accepted_by_QCD_res_name ),
						"accepted_by_QCD_date" => $row -> accepted_by_QCD_date,

						"host_master_id" => $row -> host_master_id,
						"host_master_name" => conv( $row -> host_master_name ),
						"host_master_ack" => $row -> host_master_ack,
						"host_master_ack_datetime" => $row -> host_master_ack_datetime,

            			"storage_place" => json_decode( $row -> storage_place, true ),
            			"storage_time_id" => $row -> storage_time_id,            			
            			"storage_time_name" => conv( $row -> storage_time_name ),

            			"warehouse_id" => $row -> warehouse_id,
            			"warehouse_name" => conv( $row -> warehouse_name ),

            			"warehouse_cell_id" => $row -> warehouse_cell_id,
            			"warehouse_cell_name" => conv( $row -> warehouse_cell_name ),

            			"warehouse_tier_id" => conv( $row -> warehouse_tier_id ),
            			"warehouse_item_name" => conv( $row -> warehouse_item_name ),

            			"operation_id" => $row -> operation_id,
            			"operation_name" => conv( $row -> operation_name ),

            			"note" => conv( $row -> invoice_note )
            		];
            	}
	}

	public function GetUserId()
	{
		return $this -> user_id;
	}

	public function GetIssuedItems()
	{
		$data = [];
            try
            {
                $query	 = 		"SELECT 
                					iss_inv.id AS id,
                					iss_inv.name AS name,
                					iss_inv.issued_from AS issued_from,
									DATE_FORMAT( iss_inv.date, '%d.%m.%Y %H:%i' ) AS date,
									iss_inv.issued_user_id AS issued_user_id,
									user.FIO AS issued_user_name
								FROM
								okb_db_semifinished_store_issued_invoices AS iss_inv
								LEFT JOIN okb_users AS user ON user.ID = iss_inv.issued_user_id
								WHERE
								iss_inv.created_from = {$this->invoice_id}
								";
                $stmt = $this -> pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query" );
            }
            while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            	{
            		$data['issues'][] = 
            		[
            			"id" => $row -> id,
            			"name" => $row -> name,
            			"issued_from" => json_decode( $row -> issued_from, true ),
            			"date" => $row -> date,
            			"issued_user_id" => $row -> issued_user_id,
            			"issued_user_name" => $row -> issued_user_name
            		];
            	}

          $total_count = 0 ;

          foreach( $data["issues"] AS $key => $value )
          	foreach( $value["issued_from"] AS $skey => $svalue )
          		$total_count += $svalue["count"];

		 $data['total_count'] = $total_count;

          return $data;
	}

	public function GetTable()
	{
		$str = '';
		$str .= $this -> GetTableCaption();
		$str .= $this -> GetTableContent();
		$str .= $this -> GetTableEnd();				
		return $str ;
	}

	public function GetPrintTable()
	{
		$str = '';
		$str .= $this -> GetTableCaption( 1 );
		$str .= $this -> GetTableContent( 1 );		
		$str .= $this -> GetTableEnd();				
		return $str ;
	}


	private function GetTableCaption( $print_table = 0 )
	{
		$str = "
					<table class='table table-striped table-bordered'>
					<col width='1%'>
					<col width='20%'>
					<col width='8%'>
					<col width='10%'>
					<col width='5%'>
					<col width='2%'>
					<col width='2%'>
					<col width='2%'>
					<col width='2%'>					
					<col width='10%'>
					<col width='10%'>
					<col width='20%'>
					<col width='18%'>										
					 <thead>";

		if( ! $print_table )
				$str .= "<tr class='order_row success'>
					<td colspan='14' class='AL'>
					<div><span>".conv("Накладная № ").( $this -> invoice_number )."</span><button class='btn btn-small btn-primary pull-right print' type='button' data-id='".($this -> invoice_number)."'>".conv("Распечатать")."</button></div>
					 </td>
					 </tr>";


		$str .= "<tr class='info'>
					  <th class='AC'>".conv('№')."</th>
					  <th class='AC'>".conv('Наименование ДСЕ')."</th>

					  <th class='AC'>".conv('№ Заказа')."</th>
					  <th class='AC'>".conv('№ Чертежа')."</th>
					  <th class='AC'>".conv('№ партии')."</th>
					  <th class='AC'>".conv('Количество')."</th>
					  <th class='AC'>".conv('Принято ОТК')."</th>
					  <th class='AC'>".conv('Распределено')."</th>
					  <th class='AC'>".conv('Выдано')."</th>

					  <th class='AC'>".conv('Мастер/подтв.')."</th>
					  <th class='AC'>".conv('Место передачи')."</th>
			  
					  <th class='AC'>".conv('Срок хранения')."</th>
					  <th class='AC'>".conv('Операция')."</th>					  
					  <th class='AC'>".conv('Комментарии')."</th>
					</tr>
					 </thead>";
		return $str;
	}

	private function GetTableContent( $print_table = 0 )
	{
		$str = '';
		$line = 1 ;

		foreach( $this -> invoice_items AS $item )
		{
			   $id = $item['id'];
			   $id_zadan = $item['id_zadan'];			   
			   $operation_id = $item['operation_id'];
			   $operation_name = $item['operation_name'];
			   $warehouse_id = $item['warehouse_id'];
			   $cell_id = $item['warehouse_cell_id'];
			   $tier_id = $item['warehouse_tier_id'];

			   $accepted_by_QCD = + $item['accepted_by_QCD'];
			   $accepted_by_QCD_res_id = $item['accepted_by_QCD_res_id'];
			   
			   if( $accepted_by_QCD_res_id == 1 )
			   	$accepted_by_QCD_res_name = "Admin";
			   		else
			   			$accepted_by_QCD_res_name = $item['accepted_by_QCD_res_name'];
			   
			   $accepted_by_QCD_date = $item['accepted_by_QCD_date'];

			   $storage_place = $item['storage_place'];
			   $populated = 0 ;
			   $populated_in = count( $storage_place ) ;
			   foreach ( $storage_place AS $value ) 
			   		$populated += + $value["count"];

			   $count = + $item['count'];
			   $disabled = $this -> disabled ? "disabled" : "" ;

			   $issued_data = $this -> GetIssuedItems();
			   $issued_count = $issued_data['total_count'];
			   
			   if( $issued_count )
			   		$disabled = "disabled";
			   		else
			   			$issued_count = "-";

			   if( $print_table )
			   {
					$oper_select = $operation_name ;
					$cell_select = "Aaa";
					$tier_select = "Bbb";
					$warehouse_div = $item['warehouse_name']."&nbsp;".$item['warehouse_cell_name']."&nbsp;".$item['warehouse_tier_id'];
			   }
			   else
			   {
				   $oper_select = $this -> MakeSelect( $accepted_by_QCD, 'op_select', $this -> operations, $operation_id );

				   $warehouse_div = "<div><a class='storage_place_button' type='button' data-id='$id'>$populated</a></div>";
				}

				if( $accepted_by_QCD )
				{
					$accepted_by_QCD_td = "<span class='accepted-by-qcd-count'>$accepted_by_QCD</span><span class='accepted-by-qcd'>".conv('шт.')."</span><br>";
					if( strlen( $accepted_by_QCD_res_name ) )
						$accepted_by_QCD_td .= "<span class='accepted-by-qcd'>$accepted_by_QCD_res_name</span><br>";
					if( strlen( $accepted_by_QCD_date != "00.00.0000 00:00" ) )
						$accepted_by_QCD_td .= "<span class='accepted-by-qcd'>$accepted_by_QCD_date</span>";

					$host_master_td = ( strlen( $item['host_master_name'] ) ? $item['host_master_name']."</span><br>" : "" );
					$host_master_td .= ( $item['host_master_ack'] ? "<span class='host_master_ack_datetime'>{$item['host_master_ack_datetime']}</span><br>" : "" );
					$host_master_td .= "<input class='host_master_ack' ".( $item['host_master_ack'] ? "checked disabled" : "" )." data-host_master_id='{$item['host_master_id']}' type='checkbox' disabled />";
				}
				else
				{
					$staff_arr = array_keys( $this -> GetQCDStaff() );
				 	$accepted_by_QCD_td = "<a class='accepted_by_QCD_a' data-max='$count' data-staff='".( join(",", $staff_arr ))."'>0</a>";
				 	$warehouse_div = "<span class='accepted_empty'>-</span>";
				 	$host_master_td = ( strlen( $item['host_master_name'] ) ? $item['host_master_name']."</span><br>" : "" );
				}

// <input data-max='$count' type='number' class='accepted-by-QCD' value='$accepted_by_QCD' $disabled/>

               $str .= "<tr class='order_row ".( $count == $accepted_by_QCD ? "" : "not_completely" )."'  data-id='$id' data-id_zadan='$id_zadan'>
                            <td class='AC'>$line</td>
                            <td class='AL'><span class='dse_name'>". $item['dse_name'] ."</span></td>
                            <td class='AC'><span class='order_name'>". $item['order_name'] ."</span></td>
                            <td class='AC'><span class='draw_name'>". $item['draw_name'] ."</span></td>
                            <td><span class='part_num'>". $item['part_num'] ."</span></td>
                            <td class='AC'><span class='count'>$count</span></td>
                            <td class='AC'>$accepted_by_QCD_td</td>
                            <td class='AC'>
                            	<div class='warehouse'>$warehouse_div</div>
                            </td>

                            <td class='AC'>
                            	<div class='issued'><span class='issued_count'>$issued_count</span></div>
                            </td>

							<td class='AC'><span class='host_master_span'>$host_master_td
							</td>

                            <td class='AC'><span  class='transfer_place'>".$item['transfer_place']."</span></td>                            
                            <td class='AC'><span>".$item['storage_time_name']."</span></td>
                            <td class='AC'>$oper_select</td>                            
                            <td  class='AL'><span class='note'>".$item['note']."</span></td>
                            </tr>";
               $line ++ ;
		}

		return $str;
	}
	private function GetTableEnd()
	{
		$str = "</table>";
		return $str;
	}

	private function GetOperations( )
	{
		$arr = [];

	    try
	    {
	        $query = "
	                    SELECT 
	                        oper.ID AS id, 
	                        oper.NAME AS name,
	                        kind.name AS kind_name
	                    FROM `okb_db_oper` AS oper
	                    LEFT JOIN okb_db_oper_kind AS kind ON kind.id = oper.TID
	                    WHERE oper.ID NOT IN ( 8 )
	                    ORDER BY kind.NAME, oper.NAME";
	        
	        $stmt = $this -> pdo->prepare( $query );
	        $stmt->execute();
	    }
	    catch (PDOException $e)
	    {
	      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
	    }
	    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
	    {
	        $op_name = conv($row -> name);
	        $kind_name = conv($row -> kind_name);
	        $kind_name = strlen( $kind_name ) ? "$kind_name - " : "";
	        $arr[ $row -> id ] = ['name' => $kind_name.$op_name, 'parent_id' => 0 ];
	    }

		return $arr ;
	
	} //private function GetOperations( )

	private function GetOptions( $table, $ord, $parent = '' )
	{

		$arr = [];
		$qparent = '';

		if( strlen( $parent ) )
		{
			$qparent = ", $parent ";
		}


		try
            {
                $query	 = 		"SELECT ID, NAME $qparent FROM $table 
                				WHERE 1
								ORDER BY $ord";

			if( $table == 'okb_db_sklades_yaruses' )
				$query	 = 		"SELECT ID, NAME, ORD $qparent FROM $table 
				                				WHERE 1
												ORDER BY $ord";

                $stmt = $this -> pdo->prepare( $query );
                $stmt->execute();

            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
            }
            while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            	{
            		if( strlen( $row -> NAME ) )
            		{
            			$name = conv( $row -> NAME );

						$ord = "0";
            			
            			if( isset( $row -> ORD ) )
            				$ord = $row -> ORD;
						
						if( $ord == "0")
            				$ord = conv( "Пол" );

            			if( strlen( $parent ) )
            			{
            				if( $table == 'okb_db_sklades_yaruses' )
            					$arr[ $row -> ID ] = ['name' => $ord, 'parent_id' => $row -> $parent ];
            					else
								$arr[ $row -> ID ] = ['name' => $name, 'parent_id' => $row -> $parent ];
						}
							else
							{
								$arr[ $row -> ID ] = ['name' => conv( $row -> NAME ), 'parent_id' => 0 ];
							}
            		}
            	}

          return $arr ;
	} // private function GetOptions( $table, $ord, $parent = '' )

	private function MakeSelect( $enabled, $class, $arr, $id, $in_parent_id = 0 )
	{
	   $disabled = $enabled ? "" : "disabled" ;
	   $select = "";

	   foreach( $arr AS $key => $val )
	   {
	   			$parent_id = $val['parent_id'];
	   			$select .= "<option value='$key'";

	   			if( $parent_id )
	   				$select .= "data-parent_id='$parent_id'";

	   			if( $parent_id != $in_parent_id )
					$select .= "class='hidden'";	   				
		   		
		   		if( $id == $key )
		   		{
		   			$select .= " selected";
		   			$disabled = " disabled";
		   		}

				$select .= ">".$val['name']."</option>";
	   }

	   $select .= "</select>";

	   $main_select = "<select class='$class' $disabled><option value='0'>...</option>";
	   return $main_select.$select ;
	}

	private function GetQCDStaff()
	{
		$arr = [];

	    try
	    {
	        $query = "
	        			SELECT 
	        				NAME AS name, 
	        				ID_resurs AS id_res
	        			FROM okb_db_shtat
	        			WHERE 
	        			ID_otdel = 105 
	                    ";
	        
	        $stmt = $this -> pdo->prepare( $query );
	        $stmt->execute();
	    }
	    catch (PDOException $e)
	    {
	      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
	    }
	    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
	    	if( strlen( $row -> name ) )
	        $arr[ $row -> id_res ] = ['name' => conv( $row -> name ) ];

	    $arr[1] = 'Admin';
 		return $arr ;
	}

}

