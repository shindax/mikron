<?php

class SemifinInvoice
{
	private $pdo;
	private $invoice_number;

	private $invoice_items;

	static public $operations = 0 ;
	static private $warehouses = 0 ;
	static private $cells = 0 ;
	static private $tiers = 0 ;	

	public function __construct( $pdo, $invoice_number )
	{
		$this -> pdo = $pdo ;
		$this -> invoice_number = $invoice_number ;
		$this -> GetData();
		
		if( ! $this -> operations )
			$this -> operations = $this -> GetOptions( 'okb_db_oper', 'NAME' );
		
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
								inv.id_zadan,
								inv.dse_name,
								inv.order_name,
								inv.draw_name,
								inv.part_num,
								inv.count,
								inv.transfer_place,
								inv.note AS invoice_note,
								stype.description AS storage_time_name,
								stype.id AS storage_time_id,
								warehouse.`NAME` AS warehouse_name,
								warehouse.ID AS warehouse_id,
								warehouse_cell.`NAME` AS warehouse_cell_name,
								warehouse_cell.ID AS warehouse_cell_id,
								warehouse_tier.ID AS warehouse_tier_id,
								okb_db_sklades_detitem.`NAME` AS warehouse_item_name,
								okb_db_sklades_detitem.ID AS warehouse_item_id,
								okb_db_oper.`NAME` AS operation_name,
								okb_db_oper.ID AS operation_id 
								FROM
								okb_db_semifinished_store_invoices AS inv
								LEFT JOIN okb_db_semifinished_store_type AS stype ON inv.storage_time = stype.id
								LEFT JOIN okb_db_sklades_detitem ON inv.warehouse_item_id = okb_db_sklades_detitem.ID
								LEFT JOIN okb_db_sklades_yaruses AS warehouse_tier ON okb_db_sklades_detitem.ID_sklades_yarus = warehouse_tier.ID
								LEFT JOIN okb_db_sklades_item AS warehouse_cell ON warehouse_tier.ID_sklad_item = warehouse_cell.ID
								LEFT JOIN okb_db_sklades AS warehouse ON warehouse_cell.ID_sklad = warehouse.ID
								LEFT JOIN okb_db_oper ON inv.operation_id = okb_db_oper.ID
								WHERE
								inv.inv_num = ".( $this -> invoice_number )."
								ORDER BY
								inv.id ASC
								";
                $stmt = $this -> pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }
            while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            	{
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
					<col width='10%'>
					<col width='8%'>
					<col width='10%'>
					<col width='5%'>
					<col width='2%'>
					<col width='10%'>
					<col width='25%'>
					<col width='10%'>
					<col width='10%'>
					<col width='20%'>										
					 <thead>";

		if( ! $print_table )
				$str .= "<tr class='order_row success'>
					<td colspan='11' class='AL'>
					<div><span>".conv("Накладная № ").( $this -> invoice_number )."</span><button class='btn btn-small btn-primary pull-right print' type='button' data-id='".($this -> invoice_number)."'>".conv("Распечатать")."</button></div>
					 </td>
					 </tr>";


		$str .= "<tr class='info'>
					  <th class='AC'>".conv('№')."</th>
					  <th class='AC'>".conv('Материальные ценности')."<br>".conv('Наименование')."</th>

					  <th class='AC'>".conv('№ Заказа')."</th>
					  <th class='AC'>".conv('№ Чертежа')."</th>
					  <th class='AC'>".conv('№ партии')."</th>
					  <th class='AC'>".conv('Количество')."</th>
					  <th class='AC'>".conv('Место передачи')."</th>
					  <th class='AC'>".conv('Место хранения')."</th>					  
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
			   $operation_id = $item['operation_id'];
			   $operation_name = $item['operation_name'];
			   $warehouse_id = $item['warehouse_id'];
			   $cell_id = $item['warehouse_cell_id'];
			   $tier_id = $item['warehouse_tier_id'];

			   if( $print_table )
			   {
					$oper_select = $operation_name ;
					$cell_select = "Aaa";
					$tier_select = "Bbb";
					$warehouse_div = $item['warehouse_name']."&nbsp;".$item['warehouse_cell_name']."&nbsp;".$item['warehouse_tier_id'];
			   }
			   else
			   {
				   $oper_select = $this -> MakeSelect( 'op_select', $this -> operations, $operation_id );

				   $warehouse_select = $this -> MakeSelect( 'wh_select', $this -> warehouses, $warehouse_id );

				   $cell_select = $this -> MakeSelect( 'cell_select', $this -> cells, $cell_id, $warehouse_id );

				   $tier_select = $this -> MakeSelect( 'tier_select', $this -> tiers, $tier_id, $cell_id );

				   $warehouse_div = "<div>".conv("Скл. ")."$warehouse_select"."</div>
                            	<div>".conv(" Яч. ")."$cell_select"."</div>
                            	<div>".conv(" Ярус. ")."$tier_select</div>";
				}

               $str .= "<tr class='order_row $row_style'  data-id='$id' data-inv-num='$inv_num'>
                            <td class='AC'>$line</td>
                            <td class='AL'><span class='dse_name'>". $item['dse_name'] ."</span></td>
                            <td class='AC'><span class='order_name'>". $item['order_name'] ."</span></td>
                            <td class='AC'><span class='draw_name'>". $item['draw_name'] ."</span></td>
                            <td><span class='part_num'>". $item['part_num'] ."</span></td>
                            <td class='AC'><span class='count'>".$item['count']."</span></td>
                            <td class='AC'><span  class='transfer_place'>".$item['transfer_place']."</span></td>
                            <td class='AL'>
                            	<div class='warehouse'>$warehouse_div</div>
                            </td>                            
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
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }
            while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            	{
            		if( strlen( $row -> NAME ) )
            		{
            			$name = conv( $row -> NAME );

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
	}

	private function MakeSelect( $class, $arr, $id, $in_parent_id = 0 )
	{
	   $select = "<select class='$class'><option value='0'>...</option>";

	   foreach( $arr AS $key => $val )
	   {
	   			$parent_id = $val['parent_id'];
	   			$select .= "<option value='$key'";

	   			if( $parent_id )
	   				$select .= "data-parent_id='$parent_id'";

	   			if( $parent_id != $in_parent_id )
					$select .= "class='hidden'";	   				
		   		
		   		if( $id == $key )
		   			$select .= " selected";

				$select .= ">".$val['name']."</option>";
	   }

	   $select .= "</select>";

	   return $select ;
	}
}
