<?php

class LaborRegulationsViolationItem
{
    private $pdo;
    private $res_id;
    private $date;
    private $data;
    private $day_half;
    private $res_name;
    private $can_edit;
    private $row_count;
    private $collapsed;

    public function __construct( $pdo, $res_id, $date, $shift, $can_edit = 0 )
    {
    	$this -> pdo = $pdo ;
    	$this -> res_id = $res_id ;
    	$this -> date = $date ;
        $this -> day_half = $shift == 1 ? 1 : 2;
        $this -> can_edit = $can_edit ;

        $this -> row_count = count( $this -> GetRowCount() );

    		try
            {
                $query = "
                            SELECT * FROM labor_regulations_violation_items
                            WHERE
                            resource_id = $res_id
                            AND
                            date = '$date'
                            AND
                            day_half = ".$this -> day_half;
                            $stmt = $this -> pdo->prepare( $query );
                            $stmt -> execute();
            }
            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage().". Query : $query");
            }
       
        if( ! $stmt -> rowCount() )
			$this -> InsertItem();

		$this -> CollectData();
    }

    private function InsertItem()
    {
    	$res_id = $this -> res_id ;
		$date = $this -> date ;
        $day_half = $this -> day_half ;
        $row_count = $this -> row_count;

        $row_data = $this -> GetRowCount();
        $row_count = count( $row_data );

        $query = "INSERT INTO labor_regulations_violation_items VALUES ";

        foreach( $row_data AS $key => $val )
        {
            $query .= "(NULL,$res_id, '$date', $val ,$day_half ,0,0,0,0,0,0,0,0,0,0,0,0,NOW(), 0)";
            if( $key + 1 != $row_count )
                $query .= ",";
        }

    	try
            {
                            $stmt = $this -> pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage().". Query : $query");
            }

    }

    public function GetTableEnd()
    {
        $str = "</table>";
        return $str ;
    }

	public function GetTableHead( $caption = 1 )
	{
		$str = "<table id='".( $this -> res_id )."' class='tbl'>";

        $str .= "
                           <col width='15%'>
                           <col width='8%'>
                           <col width='8%'>
                           <col width='5%'>
                           <col width='5%'>
                           <col width='5%'>
                           <col width='5%'>
                           <col width='5%'>
                           <col width='5%'>
                           <col width='5%'>
                           <col width='5%'>
                           <col width='5%'>
                           <col width='5%'>
                           <col width='5%'>
                           <col width='5%'>
                           <col width='5%'>
                           <col width='5%'>";

        if( $caption )
		{
            $str .= "<tr class='first'>";
            $str .= "<td class='field AC'>".$this -> conv("ФИО")."</td>";
            $str .= "<td class='field'></td>";
            $str .= "<td class='field'></td>";
            
            if( $this -> day_half == 1 )
            {

    			$str .= "<td class='field vert'>8.00-9.00</td>";
    			$str .= "<td class='field'>9.00-10.00</td>";
    			$str .= "<td class='field'>10.00-11.00</td>";
    			$str .= "<td class='field'>11.00-12.00</td>";
    			$str .= "<td class='field'>12.00-13.00</td>";		
    			$str .= "<td class='field'>13.00-14.00</td>";
    			$str .= "<td class='field'>14.00-15.00</td>";
    			$str .= "<td class='field'>15.00-16.00</td>";		
    			$str .= "<td class='field'>16.00-17.00</td>";
    			$str .= "<td class='field'>17.00-18.00</td>";
    			$str .= "<td class='field'>18.00-19.00</td>";
    			$str .= "<td class='field'>19.00-20.00</td>";
            }
            else
            {
                $str .= "<td class='field'>20.00-21.00</td>";
                $str .= "<td class='field'>21.00-22.00</td>";
                $str .= "<td class='field'>22.00-23.00</td>";
                $str .= "<td class='field'>23.00-00.00</td>";
                $str .= "<td class='field'>00.00-01.00</td>";       
                $str .= "<td class='field'>01.00-02.00</td>";
                $str .= "<td class='field'>02.00-03.00</td>";
                $str .= "<td class='field'>03.00-04.00</td>";       
                $str .= "<td class='field'>04.00-05.00</td>";
                $str .= "<td class='field'>05.00-06.00</td>";
                $str .= "<td class='field'>06.00-07.00</td>";
                $str .= "<td class='field'>07.00-08.00</td>";
            }
                $str .= "<td class='field AC'>".$this -> conv("За смену")."</td>";
                $str .= "<td class='field AC'>".$this -> conv("Общий<br>простой")."</td>";
                $str .= "</tr>";
		}
		return $str ;
	}
    public function GetPrintTableHead( $caption = 1 )
    {
        $str = "<table class='tbl'>";

        $str .= "
                           <col width='8%'>
                           <col width='2%'>
                           <col width='6%'>
                           <col width='6%'>
                           <col width='6%'>
                           <col width='6%'>
                           <col width='6%'>
                           <col width='6%'>
                           <col width='6%'>
                           <col width='6%'>
                           <col width='6%'>
                           <col width='6%'>
                           <col width='6%'>
                           <col width='6%'>
                           <col width='6%'>
                           <col width='3%'>
                           <!--col width='5%'-->
                           <col width='3%'>";

        if( $caption )
        {
            $str .= "<tr class='first'>";
            $str .= "<td class='field AC'>".$this -> conv("ФИО")."</td>";
            $str .= "<td class='field'></td>";
            $str .= "<td class='field'></td>";
            
            if( $this -> day_half == 1 )
            {

                $str .= "<td class='field vert'>8.00-9.00</td>";
                $str .= "<td class='field'>9.00-10.00</td>";
                $str .= "<td class='field'>10.00-11.00</td>";
                $str .= "<td class='field'>11.00-12.00</td>";
                $str .= "<td class='field'>12.00-13.00</td>";       
                $str .= "<td class='field'>13.00-14.00</td>";
                $str .= "<td class='field'>14.00-15.00</td>";
                $str .= "<td class='field'>15.00-16.00</td>";       
                $str .= "<td class='field'>16.00-17.00</td>";
                $str .= "<td class='field'>17.00-18.00</td>";
                $str .= "<td class='field'>18.00-19.00</td>";
                $str .= "<td class='field'>19.00-20.00</td>";
            }
            else
            {
                $str .= "<td class='field'>20.00-21.00</td>";
                $str .= "<td class='field'>21.00-22.00</td>";
                $str .= "<td class='field'>22.00-23.00</td>";
                $str .= "<td class='field'>23.00-00.00</td>";
                $str .= "<td class='field'>00.00-01.00</td>";       
                $str .= "<td class='field'>01.00-02.00</td>";
                $str .= "<td class='field'>02.00-03.00</td>";
                $str .= "<td class='field'>03.00-04.00</td>";       
                $str .= "<td class='field'>04.00-05.00</td>";
                $str .= "<td class='field'>05.00-06.00</td>";
                $str .= "<td class='field'>06.00-07.00</td>";
                $str .= "<td class='field'>07.00-08.00</td>";
            }
                $str .= "<td class='field AC'>".$this -> conv("За смену")."</td>";
                $str .= "<td class='field AC'>".$this -> conv("Общий<br>простой")."</td>";
//                $str .= "<td class='field AC'>".$this -> conv("Подпись")."</td>";
                $str .= "</tr>";
        }
        return $str ;
    }


	public function GetTableContent()
	{
		$line = 1 ;
        $coll = $this -> collapsed ;
		$current = current( $this -> data );
        $img = "";

        if( $coll )
        {
            if( $this -> can_edit )
                $img = "<img class='expand_image' src='uses/down.png' />";
            $str = "<tr data_id='".$current['id']."' data-row_id='".$current['row_id']."' data-res_id='".$this -> res_id."'>";
            $str .= "<td class='field AL collapsed_field'><div class='name_div'><a target='_blank' href='index.php?do=show&formid=47&id=".$this -> res_id."'>".$this -> res_name."</a>$img</div></td><td class='field after_collapsed_field' colspan='16'></td></tr>";
        }
        else
        {
            if( $this -> can_edit )
                $img = "<img class='collapse_image' src='uses/up.png' />";
		$str = "<tr data_id='".$current['id']."' data-row_id='".$current['row_id']."' data-res_id='".$this -> res_id."'>";
		$str .= "<td rowspan='".( $this -> row_count )."' class='field AC'><div class='name_div'><a target='_blank' href='index.php?do=show&formid=47&id=".$this -> res_id."'>".$this -> res_name."</a>$img</div></td>";

		$total_work_stopped = 0 ;

// ряды : 
//            10 - Курение ( время мин )
//            20 - Простой ( время мин )
//            30 - Простой по вине мастера ( время мин )            

		foreach( $this -> data AS $key => $val )
			// if( $val['row_id'] == 1 || $val['row_id'] == 10 || $val['row_id'] == 20 || $val['row_id'] == 30 )
            if( $val['row_id'] == 1 || $val['row_id'] == 10 || $val['row_id'] == 20 )
				$total_work_stopped += $val['total'];

		foreach( $this -> data AS $key => $val )
		{
            $name = $val['name'] ;
            $row_id = $val['row_id'] ;
            $total = $val['total'] ;

            $row_class = '';
            if( ( $row_id == 80 || $row_id == 90 ) && $total )
                $row_class = 'table-danger';

			if( $line != 1 )
				$str .= "<tr class='$row_class' data_id='".$val['id']."' data-row_id='".$val['row_id']."' data-res_id='".$this -> res_id."'>";
			
			$line ++ ;
            $rowspan = 1 ;

// ряды : 
//            10 - Курение ( время мин )
//            20 - Простой ( время мин )
//            30 - Простой по вине мастера ( время мин )            

            if( $row_id == 10 || $row_id == 20 || $row_id == 30 )
				$rowspan = 2 ;	

// ряды : 
//            11 - Курение ( Интервал )
//            21 - Простой ( Интервал )
//            31 - Простой по вине мастера ( Интервал )            

            if( $row_id == 11 || $row_id == 21 || $row_id == 31 )
                $total = "-" ;  

			if( $row_id != 11 && $row_id != 21 && $row_id != 31 )
			 $str .= "<td rowspan='$rowspan' class='field AC'>$name</td>";

            if( $this -> can_edit )
                $cell = "cell";
                 else
                    $cell = "";

			$class = 'value';

// ряды : 
//            11 - Курение ( Интервал )
//            21 - Простой ( Интервал )
//            31 - Простой по вине мастера ( Интервал )            
		
			if( $row_id == 11 || $row_id == 21 || $row_id == 31 )
				$class = 'interval';

			$str .= "<td class='field AC'>".$val['type']."</td>";
			$str .= "<td class='field AC $cell'>".$this -> GetInputDetails( $val, 't_8_9', $class)."</td>";
			$str .= "<td class='field AC $cell'>".$this -> GetInputDetails( $val, 't_9_10', $class )."</td>";
			$str .= "<td class='field AC $cell'>".$this -> GetInputDetails( $val, 't_10_11', $class )."</td>";
			$str .= "<td class='field AC $cell'>".$this -> GetInputDetails( $val, 't_11_12', $class )."</td>";
			$str .= "<td class='field AC $cell'>".$this -> GetInputDetails( $val, 't_12_13', $class )."</td>";
			$str .= "<td class='field AC $cell'>".$this -> GetInputDetails( $val, 't_13_14', $class )."</td>";
			$str .= "<td class='field AC $cell'>".$this -> GetInputDetails( $val, 't_14_15', $class )."</td>";
			$str .= "<td class='field AC $cell'>".$this -> GetInputDetails( $val, 't_15_16', $class )."</td>";
			$str .= "<td class='field AC $cell'>".$this -> GetInputDetails( $val, 't_16_17', $class )."</td>";
			$str .= "<td class='field AC $cell'>".$this -> GetInputDetails( $val, 't_17_18', $class )."</td>";
			$str .= "<td class='field AC $cell'>".$this -> GetInputDetails( $val, 't_18_19', $class )."</td>";
			$str .= "<td class='field AC $cell'>".$this -> GetInputDetails( $val, 't_19_20', $class )."</td>";

            $total = $this -> ConvertTime( $total );

			$str .= "<td class='field AC'><span class='by_shift'>$total</span></td>";

// rowspan = '7' : Опоздание + 2 * Курение + 2 * Простой + 2 * Простой по вине мастера
// rowspan = '5' : Опоздание + 2 * Курение + 2 * Простой

            $total_work_stopped = $this -> ConvertTime( $total_work_stopped );

			if( $line == 2 )	
				// $str .= "<td class='field AC' rowspan='7'><span class='total_violations'>$total_work_stopped</span></td>";	
                $str .= "<td class='field AC' rowspan='5'><span class='total_violations'>$total_work_stopped</span></td>";               
			
            // if( $line >= 9 )
			if( $line >= 7 )
				$str .= "<td class='field AC'>-</td>";	

			$str .= "</tr>";
		}
      }// else
		return $str;
	} // public function GetTableContent()

    public function GetPrintTableContent()
    {
        $line = 1 ;
        $current = current( $this -> data );

        $str = "<tr>";
        $str .= "<td rowspan='".( $this -> row_count )."' class='field AC'>".$this -> res_name."</td>";
        $total_work_stopped = 0 ;

// ряды : 
//            1 - Опоздание ( время мин )
//            10 - Курение ( время мин )
//            20 - Простой ( время мин )
//            30 - Простой по вине мастера ( время мин )            

        foreach( $this -> data AS $key => $val )
            // if( $val['row_id'] == 1 || $val['row_id'] == 10 || $val['row_id'] == 20 || $val['row_id'] == 30 )
            if( $val['row_id'] == 1 || $val['row_id'] == 10 || $val['row_id'] == 20 )
                $total_work_stopped += $val['total'];
        
        foreach( $this -> data AS $key => $val )
        {
            if( $line != 1 )
                $str .= "<tr>";
            
            $name = $val['name'] ;
            $row_id = $val['row_id'] ;
            $total = $val['total'] ;
            $line ++ ;
            $rowspan = 1 ;

// ряды для объединения ячеек
//  10 - Курение
//  20 - Простой
//  30 - Простой по вине мастера

            if( $row_id == 10 || $row_id == 20 || $row_id == 30 )
                $rowspan = 2 ;  

// ряды для интервалов
//  11 - Курение
//  21 - Простой
//  31 - Простой по вине мастера

            if( $row_id == 11 || $row_id == 21 || $row_id == 31 )
                $total = "-" ;  

            if( $row_id != 11 && $row_id != 21 && $row_id != 31 )
                $str .= "<td rowspan='$rowspan' class='field AC'>$name</td>";

            $str .= "<td class='field AC'>".$val['type']."</td>";
            $str .= "<td class='field AC'>".$this -> GetPrintDetails( $val, 't_8_9' )."</td>";
            $str .= "<td class='field AC'>".$this -> GetPrintDetails( $val, 't_9_10' )."</td>";
            $str .= "<td class='field AC'>".$this -> GetPrintDetails( $val, 't_10_11' )."</td>";
            $str .= "<td class='field AC'>".$this -> GetPrintDetails( $val, 't_11_12' )."</td>";
            $str .= "<td class='field AC'>".$this -> GetPrintDetails( $val, 't_12_13' )."</td>";
            $str .= "<td class='field AC'>".$this -> GetPrintDetails( $val, 't_13_14' )."</td>";
            $str .= "<td class='field AC'>".$this -> GetPrintDetails( $val, 't_14_15' )."</td>";
            $str .= "<td class='field AC'>".$this -> GetPrintDetails( $val, 't_15_16' )."</td>";
            $str .= "<td class='field AC'>".$this -> GetPrintDetails( $val, 't_16_17' )."</td>";
            $str .= "<td class='field AC'>".$this -> GetPrintDetails( $val, 't_17_18')."</td>";
            $str .= "<td class='field AC'>".$this -> GetPrintDetails( $val, 't_18_19' )."</td>";
            $str .= "<td class='field AC'>".$this -> GetPrintDetails( $val, 't_19_20' )."</td>";

            $str .= "<td class='field AC'><span class='by_shift'>".($total ? $total : "")."</span></td>";

            if( $line == 2 )    
            {
                $str .= "<td class='field AC' rowspan='5'><span>".( $total_work_stopped ? $total_work_stopped : "" )."</span></td>";   
//                $str .= "<td class='field AC' rowspan='".( $this -> row_count )."'><span></span></td>";
            }

// $line >= '7' : Опоздание + 2 * Курение + 2 * Простой + 2 * Простой по вине мастера
            
            if( $line >= 7  )   
                $str .= "<td class='field AC'>-</td>";  
            
            $str .= "</tr>";
        }
        return $str;
    }


	private function GetInputDetails( $val, $field, $class = 'value' )
	{
		$value = $val[ $field ] == "0" ? "" : $val[ $field ];
		return "<span>$value</span><input class='$class hidden' data-field='$field' value='$value' />";
	}

    private function GetPrintDetails( $val, $field )
    {
        $value = $val[ $field ] == "0" ? "" : $val[ $field ];
        return "<span>$value</span>";
    }


	public function GetTable( $caption = 1 )
	{
		$str = $this -> GetTableHead( $caption );
		$str .= $this -> GetTableContent();
		$str .= $this -> GetTableEnd();
		return $str ;
	}

    public function GetPrintTable( $caption = 1 )
    {
        $str = $this -> GetPrintTableHead( $caption );
        $str .= $this -> GetPrintTableContent();
        $str .= $this -> GetTableEnd();
        return $str ;
    }


    private function CollectData()
    {
		$res_id = $this -> res_id ;
		$date = $this -> date ;
        $day_half = $this -> day_half ;

			try
            {
                $query = "
                            SELECT NAME name
                            FROM okb_db_resurs items
                            WHERE ID = ".$this -> res_id;

                            $stmt = $this -> pdo->prepare( $query );
                            $stmt -> execute();

            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
            }
            
            if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            	$this -> res_name = conv( $row -> name );

			try
            {
                $query = "
                            SELECT 
                            items.id id,
                            items.id row,

                            items.t_8_9 t_8_9,
                            items.t_9_10 t_9_10,
                            items.t_10_11 t_10_11,
                            items.t_11_12 t_11_12,
                            items.t_12_13 t_12_13,
                            items.t_13_14 t_13_14,
                            items.t_14_15 t_14_15,
                            items.t_15_16 t_15_16,
                            items.t_16_17 t_16_17,
                            items.t_17_18 t_17_18,
                            items.t_18_19 t_18_19,
                            items.t_19_20 t_19_20,
                            items.collapsed collapsed,
							
							rows.int_id row_id,
							rows.name name,
							rows.type type
                            FROM labor_regulations_violation_items items
                            LEFT JOIN labor_regulations_violation_rows rows ON rows.int_id = items.row
                            WHERE
                            resource_id = $res_id
                            AND
                            date = '$date'
                            AND
                            day_half = $day_half
                            ";

                            $stmt = $this -> pdo->prepare( $query );
                            $stmt -> execute();

            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
            }
            while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            {
            	$total = $row -> t_8_9 + $row -> t_9_10 + $row -> t_10_11 + $row -> t_11_12 + $row -> t_12_13 + $row -> t_13_14 + $row -> t_14_15 + $row -> t_15_16 + $row -> t_16_17 + $row -> t_17_18 + $row -> t_18_19 + $row -> t_19_20 ;
            	
                $this -> data[ $row -> row ] = 
            	[
            		'id' => $row -> id,
            		'row_id' => $row -> row_id,
            		'name' => $this -> conv( $row -> name ),
            		'type' => $this -> conv( $row -> type ),
            		't_8_9'   => conv( $row -> t_8_9 ),
            		't_9_10'  => conv( $row -> t_9_10 ),
            		't_10_11' => conv( $row -> t_10_11 ),
            		't_11_12' => conv( $row -> t_11_12 ),
            		't_12_13' => conv( $row -> t_12_13 ),
            		't_13_14' => conv( $row -> t_13_14 ),
            		't_14_15' => conv( $row -> t_14_15 ),
            		't_15_16' => conv( $row -> t_15_16 ),
            		't_16_17' => conv( $row -> t_16_17 ),
            		't_17_18' => conv( $row -> t_17_18 ),
            		't_18_19' => conv( $row -> t_18_19 ),
            		't_19_20' => conv( $row -> t_19_20 ),
            		'total' => $total
            	];
                $this -> collapsed = 1 * $row -> collapsed;                
            }
    }

    public function GetData()
    {
    	return $this -> data ;
    }

	private function conv( $str )
	{
	    return iconv( "UTF-8", "Windows-1251",  $str );
	}

    public function GetViolationCount()
    {
        $total = 0 ;
        
        foreach ( $this -> data as $key => $value ) 
            $total += $value['total'] < 0 ? - 1 * $value['total'] : $value['total'];

        return $total ;
    }

    private function GetRowCount()
    {
        $data = [];
        try
        {
            $query = "
                        SELECT int_id FROM labor_regulations_violation_rows WHERE 1 ORDER BY int_id";
                        $stmt = $this -> pdo->prepare( $query );
                        $stmt -> execute();
        }
        catch (PDOException $e)
        {
           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage().". Query : $query");
        }
     
        while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            $data[] = $row -> int_id;

        $this -> row_count =  count( $data );
        return $data;
    }

    public function IsCollapsed()
    {
        return $this -> collapsed;
    }

    private function ConvertTime( $total, $key = 0 )
    {
        $min = ( $key >= 50 && $key <= 90 || $key == 30 ) ? '' : conv(" мин");

        if( $total >= 60 )
        {
            $hours = floor( $total / 60 );
            $minutes = $total - $hours * 60 ;
            $total = conv( "$hours ч." );
            if( $minutes )
                $total .=  $minutes < 10 ? "0$minutes $min" : "$minutes $min";
        }
        else
            $total = $total ? $total." ".$min : "-";

        if( $total == 0 )
            $total = "-";
        return $total ;
    }

}

