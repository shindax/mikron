<?php

class LaborRegulationsViolationItemByMonth
{
	private $pdo;
    private $res_id;
    private $res_name;    

    private $month;
    private $year;    
    private $viol_type;

	private $data;
    private $max_day;
    private $field_list;
    private $field_arr;

    public $row_names;
    public $row_types;

    public function __construct( $pdo, $res_id, $month, $year, $viol_type = 0 )
    {
    	$this -> pdo = $pdo ;
    	$this -> res_id = $res_id ;
    	$this -> month = $month ;
        $this -> year = $year ;
        $this -> viol_type = $viol_type ;

//        $max_day = cal_days_in_month(CAL_GREGORIAN, $month, $year );
//        $max_day = (31 - (($month - 1) % 7 % 2) - ((($month == 2) << !!($year % 4))));
        
        $max_day = self :: GetMaxDay( $month, $year );

        $this -> max_day = $max_day;

        $month = $month < 10 ? "0$month" : $month;
        $from = "$year-$month-01";
        $to = "$year-$month-$max_day";

        $this -> field_arr = [ 1, 10, 20, 30, 40, 50, 60, 70, 80, 90 ];
        $this -> field_list = join(",", $this -> field_arr );

    		try
            {
                $query = "
                            SELECT resource_id, date, row, 
                            ( t_8_9 + t_9_10 + t_10_11 + t_11_12 + t_12_13 + t_13_14 + t_14_15 + t_15_16 +t_16_17 + t_17_18 + t_18_19 + t_19_20 ) total
                            FROM labor_regulations_violation_items
                            WHERE
                            resource_id = $res_id
                            AND
                            date BETWEEN '$from' AND '$to'
                            AND
                            row IN ( ".( $this -> field_list )." )
                            ORDER BY row, date
                            ";

//echo $query ;

                            $stmt = $this -> pdo->prepare( $query );
                            $stmt -> execute();
            }
            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage().". Query : $query");
            }

        $this -> GetNamesAndTypes();
		$this -> CollectData( $stmt );
    }

    private function GetNamesAndTypes()
    {
        try
            {
                $query = "
                            SELECT *
                            FROM labor_regulations_violation_rows
                            WHERE 
                            int_id IN ( ".( $this -> field_list )." )
                            ORDER BY id
                            ";

                            $stmt = $this -> pdo->prepare( $query );
                            $stmt -> execute();
            }
            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage().". Query : $query");
            }
            
            while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            {
                $this -> row_names[ $row -> int_id ] = conv( $row -> name );
                $this -> row_types[ $row -> int_id ] = conv( $row -> type );
            }

        try
            {
                $query = "
                            SELECT NAME name
                            FROM `okb_db_resurs` 
                            WHERE 
                            ID = ".$this -> res_id ."
                            ";

                            $stmt = $this -> pdo->prepare( $query );
                            $stmt -> execute();
            }
            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage().". Query : $query");
            }
            
            if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
                $this -> res_name = conv( $row -> name );

    }

    private function CollectData( $stmt )
    {

         while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            {

                if( isset( $this -> data[ $row -> row ][ 'total' ] ) )
                    $this -> data[ $row -> row ][ 'total' ] += $row -> total;
                        else
                            $this -> data[ $row -> row ][ 'total' ] = $row -> total;
                
                $day_index = 1 * substr( $row -> date, 8, 2 ) ;

                if( isset( $this -> data[ $row -> row ] ) )
                {
                    if( isset( $this -> data[ $row -> row ][ $day_index ] ) )
                        $this -> data[ $row -> row ][ $day_index ] += $row -> total ;
                         else
                            $this -> data[ $row -> row ][ $day_index ] = $row -> total ;
                }
                        else
                            $this -> data[ $row -> row ][ $day_index ] = $row -> total ;
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


    public function GetTableEnd()
    {
        $str = "</table>";
        return $str ;
    }

    public static function GetTemplateTableEnd()
    {
        $str = "</table>";
        return $str ;
    }


    public function GetTableHead()
    {
        $str = "<table id='".( $this -> res_id )."' class='tbl result_table'>";

        $str .= "
                           <col width='15%'>
                           <col width='7%'>
                           <col width='5%'>";
                           
        for( $i = 1 ; $i <= $this -> max_day ; $i ++ )
            $str .= "<col width='2%'>";

            $str .= "<col width='5%'>";
            $str .= "<col width='5%'>";            

            $str .= "<tr class='first'>";
            $str .= "<td class='field AC'>".conv("ФИО")."</td>";
            $str .= "<td class='field'></td>";
            $str .= "<td class='field'></td>";

        for( $i = 1 ; $i <= $this -> max_day ; $i ++ )
            $str .= "<td class='field AC'>$i</td>";

            $str .= "<td class='field AC'>".conv("Общий<br>простой")."</td>";
            $str .= "<td class='field AC'>".conv("Итого")."</td>";            
            $str .= "</tr>";

        return $str ;
    }

    public function GetPrintTableHead()
    {
        $str = "<table id='".( $this -> res_id )."' class='tbl result_table'>";

        $str .= "
                           <col width='15%'>
                           <col width='7%'>
                           <col width='5%'>";
                           
        for( $i = 1 ; $i <= $this -> max_day ; $i ++ )
            $str .= "<col width='2%'>";

            $str .= "<col width='5%'>";
            $str .= "<col width='5%'>";            

            $str .= "<tr class='first'>";
            $str .= "<td class='field AC'>".conv( "ФИО" )."</td>";
            $str .= "<td class='field'></td>";
            $str .= "<td class='field'></td>";

        for( $i = 1 ; $i <= $this -> max_day ; $i ++ )
            $str .= "<td class='field AC'>$i</td>";

            $str .= "<td class='field AC'>".conv( "Общий<br>простой" )."</td>";
            $str .= "<td class='field AC'>".conv( "Итого" )."</td>";            
            $str .= "</tr>";

        return $str ;
    }

    public function GetTable()
    {
       if( 
            ( $this -> viol_type == 1 && ! $this -> HasViolations() )
            ||
            ( $this -> viol_type == 2 && $this -> HasViolations() )
        )
            return "";

       $str = $this -> GetTableHead();
       $str .= $this -> GetTableContent();
       $str .= $this -> GetTableEnd();
        return $str ;
    }

    public function GetPrintTable()
    {
       if( 
            ( $this -> viol_type == 1 && ! $this -> HasViolations() )
            ||
            ( $this -> viol_type == 2 && $this -> HasViolations() )
        )
            return "";

       $str = $this -> GetPrintTableHead();
       $str .= $this -> GetPrintTableContent();
       $str .= $this -> GetTableEnd();
        return $str ;
    }


    public function GetTableContent()
    {
        $str = "";
        $line = 1 ;

        $str .= "<tr>";
        $str .= "<td class='field AC' rowspan='".( count( $this -> row_names ) + 1 )."'>".$this -> res_name."</td>";

        $final_results = $this -> GetFinalResults();

        foreach( $this -> data AS $key => $val )
        {

            if( $line ++ > 1 )
                $str .= "<tr>";

            $str .= "<td class='field AC'>".( $this -> row_names[ $key ])."</td>";
            $str .= "<td class='field AC'>".( $this -> row_types[ $key ])."</td>";

            for( $i = 1 ; $i <= $this -> max_day ; $i ++ )
            {

                if( isset( $this -> data[ $key ][ $i ] ) )
                {
                    $val = $this -> data[ $key ][ $i ];

                    $val = $val ? $val : '-';
                    $str .= "<td class='field AC'>$val</td>";
                }
                        else
                            $str .= "<td  class='field AC'>-</td>";
            }

            $total = $this -> ConvertTime( $key, $this -> data[ $key ][ 'total' ] );

            $str .= "<td  class='field AC'>$total</td>";
            
            if( $key == 1 )
                $str .= "<td  class='field AC' rowspan='5'>".$final_results['min']."</td>";

            if( $key == 50 )
                $str .= "<td  class='field AC' rowspan='5'>".$final_results['scores']."</td>";

            $str .= "</tr>";
        }

        return $str ;
    }


    public function GetPrintTableContent()
    {
        $str = "";
        $line = 1 ;

        $str .= "<tr>";
        $str .= "<td class='field AC' rowspan='".( count( $this -> row_names ) + 1 )."'>".$this -> res_name."</td>";

        $final_results = $this -> GetFinalResults();

        foreach( $this -> data AS $key => $val )
        {
            if( $line ++ > 1 )
                $str .= "<tr>";

            $str .= "<td class='field AC'>".( $this -> row_names[ $key ])."</td>";
            $str .= "<td class='field AC'>".( $this -> row_types[ $key ])."</td>";

            for( $i = 1 ; $i <= $this -> max_day ; $i ++ )
            {

                if( isset( $this -> data[ $key ][ $i ] ) )
                {
                    $val = $this -> data[ $key ][ $i ];
                    $val = $val ? $val : '-';
                    $str .= "<td class='field AC'>$val</td>";
                }
                        else
                            $str .= "<td  class='field AC'>-</td>";
            }

            $total = $this -> ConvertTime( $key, $this -> data[ $key ][ 'total' ] );

            $str .= "<td  class='field AC'>$total</td>";

            if( $key == 1 )
                $str .= "<td  class='field AC' rowspan='5'>".$final_results['min']."</td>";

            if( $key == 50 )
                $str .= "<td  class='field AC' rowspan='5'>".$final_results['scores']."</td>";

            $str .= "</tr>";
        }

        return $str ;
    }

    public function HasViolations()
    {
        $has_violations = 0 ;
        foreach( $this -> data AS $key => $val )
        {
            if( $val['total'])
                $has_violations = 1 ;
        }
        return $has_violations ;
    }

    private function GetFinalResults()
    {
        $final_min_result = 0;
        $final_score_result = 0;

        foreach( $this -> data AS $key => $val )
        {
            if( $key >= 1 && $key <= 40 )
                $final_min_result += $this -> data[ $key ][ 'total' ];

            if( $key >= 50 && $key <= 90 )
                $final_score_result += $this -> data[ $key ][ 'total' ];
        }

        $final_score_result = $final_score_result ? $final_score_result : '-';

        if( $final_min_result >= 60 )
        {
            $hours = floor( $final_min_result / 60 );
            $minutes = $final_min_result - $hours * 60 ;
            $final_min_result = conv("$hours ч.");
            if( $minutes )
                $final_min_result .=  $minutes.conv(" мин.");
        }
        else
            $final_min_result = $final_min_result ? $final_min_result.conv(" мин.") : '-';

        return ['min' => $final_min_result, 'scores' => $final_score_result ];
    }

    private function ConvertTime( $key, $total )
    {
        $min = ( $key >= 50 && $key <= 90 ) ? '' : conv(" мин");

        if( $total >= 60 )
        {
            $hours = floor( $total / 60 );
            $minutes = $total - $hours * 60 ;
            $total = conv( "$hours ч." );
            if( $minutes )
                $total .=  "$minutes $min";
        }
        else
            $total = $total ? $total." ".$min : "-";

        if( $total == 0 )
            $total = "-";

        return $total ;
    }

    public static function GetMaxDay( $month, $year )
    {
        return (31 - (($month - 1) % 7 % 2) - ((($month == 2) << !!($year % 4))));        
    }

    public static function GetTemplateTableHead( $id, $caption, $max_day )
    {
        $str = "<table id='$id' class='tbl result_table'>";

        $str .= "
                           <col width='7%'>
                           <col width='7%'>
                           <col width='5%'>";
                           
        for( $i = 1 ; $i <= $max_day ; $i ++ )
            $str .= "<col width='2%'>";

            $str .= "<col width='3%'>";
            $str .= "<col width='3%'>";            

            $str .= "<tr class='first'>";
            $str .= "<td class='field AC'>$caption</td>";
            $str .= "<td class='field'></td>";
            $str .= "<td class='field'></td>";

        for( $i = 1 ; $i <= $max_day ; $i ++ )
            $str .= "<td class='field AC'>$i</td>";

            $str .= "<td class='field AC'>".conv("Общий<br>простой")."</td>";
            $str .= "<td class='field AC'>".conv("Итого")."</td>";            
            $str .= "</tr>";

        return $str ;
    }
}

