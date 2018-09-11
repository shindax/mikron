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

    public function __construct( $pdo, $res_id, $date, $shift, $can_edit = 0 )
    {
    	$this -> pdo = $pdo ;
    	$this -> res_id = $res_id ;
    	$this -> date = $date ;
        $this -> day_half = $shift == 1 ? 1 : 2;
        $this -> can_edit = $can_edit ;

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
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
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

    	try
            {
                $query = "
                            INSERT INTO labor_regulations_violation_items
							VALUES 
							(NULL,$res_id, '$date',1 ,$day_half,0,0,0,0,0,0,0,0,0,0,0,0,NOW()),
							(NULL,$res_id, '$date',2,$day_half,0,0,0,0,0,0,0,0,0,0,0,0,NOW()),
							(NULL,$res_id, '$date',3,$day_half,0,0,0,0,0,0,0,0,0,0,0,0,NOW()),
							(NULL,$res_id, '$date',4,$day_half,0,0,0,0,0,0,0,0,0,0,0,0,NOW()),
							(NULL,$res_id, '$date',5,$day_half,0,0,0,0,0,0,0,0,0,0,0,0,NOW()),
							(NULL,$res_id, '$date',6,$day_half,0,0,0,0,0,0,0,0,0,0,0,0,NOW()),
							(NULL,$res_id, '$date',7,$day_half,0,0,0,0,0,0,0,0,0,0,0,0,NOW()),
							(NULL,$res_id, '$date',8,$day_half,0,0,0,0,0,0,0,0,0,0,0,0,NOW()),
							(NULL,$res_id, '$date',9,$day_half,0,0,0,0,0,0,0,0,0,0,0,0,NOW())
						  ";
                            $stmt = $this -> pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
            }

    }

	public function GetTableHead( $caption = 1 )
	{
		$str = "<table class='tbl'>";

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

    			$str .= "<td class='field'>8.00-9.00</td>";
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

	public function GetTableContent()
	{
		$line = 1 ;

		$current = current( $this -> data );

		$str = "<tr data_id='".$current['id']."' data-row_id='".$current['row_id']."' data-res_id='".$this -> res_id."'>";
		$str .= "<td rowspan='10' class='field AC'><a target='_blank' href='index.php?do=show&formid=47&id=".$this -> res_id."'>".$this -> res_name."</a></td>";
		$total_work_stopped = 0 ;

		foreach( $this -> data AS $key => $val )
			if( $val['row_id'] == 1 || $val['row_id'] == 2 || $val['row_id'] == 4 )
				$total_work_stopped += $val['total'];

		foreach( $this -> data AS $key => $val )
		{
			if( $line != 1 )
				$str .= "<tr data_id='".$val['id']."' data-row_id='".$val['row_id']."' data-res_id='".$this -> res_id."'>";
			
			$name = $val['name'] ;
			$row_id = $val['row_id'] ;
            $total = $val['total'] ;
			$line ++ ;
			$rowspan = 1 ;

			if( $row_id == 2 || $row_id == 4 )
				$rowspan = 2 ;	

            if( $row_id == 3 || $row_id == 5 )
                $total = "-" ;  

			if( $row_id != 3 && $row_id != 5 )
			$str .= "<td rowspan='$rowspan' class='field AC'>$name</td>";

            if( $this -> can_edit )
                $cell = "cell";
                 else
                    $cell = "";

			$class = 'value';
			
			if( $row_id == 3 || $row_id == 5 )
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

			$str .= "<td class='field AC'><span class='by_shift'>".($total ? $total : "")."</span></td>";

			if( $line == 2 )	
				$str .= "<td class='field AC' rowspan='5'><span class='total_violations'>".( $total_work_stopped ? $total_work_stopped : "" )."</span></td>";	
			
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

	public function GetTableEnd()
	{
		$str = "</table>";
		return $str ;
	}


	public function GetTable( $caption = 1 )
	{
		$str = $this -> GetTableHead( $caption );
		$str .= $this -> GetTableContent();
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
							
							rows.id row_id,
							rows.name name,
							rows.type type
                            FROM labor_regulations_violation_items items
                            LEFT JOIN labor_regulations_violation_rows rows ON rows.id = items.row
                            WHERE
                            resource_id = $res_id
                            AND
                            date = '$date'
                            AND
                            day_half = $day_half
                            ";

                            //echo $query;

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
            }

//            debug( $this -> data );
    }

    public function GetData()
    {
    	return $this -> data ;
    }

	private function conv( $str )
	{
	    return iconv( "UTF-8", "Windows-1251",  $str );
	}
}

