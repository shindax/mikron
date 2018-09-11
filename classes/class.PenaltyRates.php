<?php

class PenaltyRates
{
	private $direction ;
	private $direction_name ;
	private $direction_stages = [];
	private $common_penalties = [];

	private function conv( $str )
	{
	    return iconv( "UTF-8", "Windows-1251",  $str );
	}

	public function  getStages()
	{
		return $this -> direction_stages ;
	}

	public function  __construct( $pdo, $direction )
	{

        try
            {
                $query = "
                			SELECT note
                          	FROM okb_db_responsible_persons 
                            WHERE id = $direction
                          ";
                $stmt = $pdo -> prepare( $query );
                $stmt -> execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
            }

            $row = $stmt->fetch( PDO::FETCH_OBJ ); // One record
            
            $this -> direction_name = $this -> conv( $row -> note ) ;
            $this -> direction = $direction ;

        try
            {
                $query = "
                			SELECT *
                          	FROM okb_db_plan_fact_direction_stages 
                            WHERE direction_id = $direction
                          ";
                $stmt = $pdo -> prepare( $query );
                $stmt -> execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
            }

            while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
            {
            	$direction_stage_id = $row -> id ;
            	$direction_stage_name = $this -> conv ( $row -> name );
				$this -> direction_stages[ $direction_stage_id ] = [];
				$this -> direction_stages[ $direction_stage_id ][ 'direction_stage_name' ] = $direction_stage_name;
            }

            foreach( $this -> direction_stages AS $key => $stage )
            {
		        try
		            {
		           	$query = "
										SELECT id, cause, rate, note 
										FROM okb_db_plan_fact_carry_causes
										WHERE 
										direction_stage_id = $key
										AND
										cause <> ''
									";
		                $stmt = $pdo -> prepare( $query );
		                $stmt -> execute();
		            }
		            catch (PDOException $e)
		            {
		              die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
		            }

		            while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
		            {
		            	$this -> direction_stages[ $key ][ 'rates' ][] = 
							            	[ 
							            		'id' => $row -> id,
							            		'cause' => $this -> conv ( $row -> cause ) ,
							            		'rate' => $row -> rate,
												'note' => $this -> conv ( $row -> note )
							            	];
		            }

            }

        try
            {
                $query = "
                			SELECT *
                          	FROM okb_db_plan_fact_penalty_rates 
                            WHERE 1
                          ";
                $stmt = $pdo -> prepare( $query );
                $stmt -> execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
            }

            while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
            {
            	$this -> common_penalties[] = [
            			'id' => $row -> id,
            			'penalty_name' => $row -> penalty_name,
            			'rate' => $row -> rate,
            			'note' => $row -> note
            		];
            }

	}

	private function getCommonPenalties()
	{

	$str = "<div class='row'><h3>".conv("Ставки штрафов общие. ")."</h3></div>";
		$str .= "<div class='row'>
				<table id='common_penalties' class='table table-striped penalty'>
				<col width='2%'>
				<col width='30%'>
				<col width='5%'>
				<col width='20%'>

				  <thead>
				    <tr class='table-primary'>
				      <th>". $this -> conv( "№" ) ."</th>
				      <th>". $this -> conv( "Наименование" ) ."</th>
				      <th>". $this -> conv( "Сумма" ) ."</th>
				      <th>". $this -> conv( "Примечание" ) ."</th>
				    </tr>
				  </thead>
				  <tbody>";

			$line = 1 ;

			foreach( $this -> common_penalties AS $penalty )
            {
            	$penalty_name = conv( $penalty['penalty_name'] );
            	$id = $penalty['id'];
            	$rate = $penalty['rate'];
				$note = conv( $penalty['note'] );

             	$str .= "
                			<tr>
                  			<td class='AC'><span>$line</span></td>
                  			<td>$penalty_name</td>
                  			<td><input class='common_rate_input' data-id='$id' value='$rate' /></td>
                  			<td><input  class='common_note_input' data-id='$id' value='$note' /></td></tr>";	
				$line ++ ;
            }

	   $str .=
                "<tr><td colspan='4' class='AC'><span></span></td></tr>";

		$str .= "</tbody></table></div>"; 

		return $str ;
	}



	private function getTableBegin()
	{
		$str = "<div class='row'><h3>".conv("Ставки штрафов. ")." ".$this -> direction_name."</h3></div>";
		$str .= "<div class='row'>
				<table id='direction_".( $this -> direction )."' class='table table-striped penalty'>
				<col width='2%'>
				<col width='30%'>
				<col width='5%'>
				<col width='20%'>

				  <thead>
				    <tr class='table-primary'>
				      <th>". $this -> conv( "№" ) ."</th>
				      <th>". $this -> conv( "Причина" ) ."</th>
				      <th>". $this -> conv( "Сумма" ) ."</th>
				      <th>". $this -> conv( "Примечание" ) ."</th>
				    </tr>
				  </thead>
				  <tbody>";

		return $str ;
	}

	private function getTableContent()
	{
		$str = "";

		foreach( $this -> direction_stages AS $key => $stage )
		{
//			$id = $rate['id'];
			$stage_name = $stage['direction_stage_name'];
			   $str .=
                "<tr class='table-success'><td colspan='4' class='AC'><span>$stage_name</span></td></tr>";

                foreach( $stage['rates'] AS $rate )
                {
                	$line = 1 ;
                	$id = $rate['id'] ;
                	$str .= "
                			<tr>
                  			<td class='AC'><span>$line</span></td>
                  			<td>".( $rate['cause'])."</td>
                  			<td><input class='rate_input' data-id='$id' value='".( $rate['rate'])."' /></td>
                  			<td><input  class='note_input' data-id='$id' value='".( $rate['note'])."' /></td></tr>";
                	$line ++ ;                	
                }
            			   $str .=
                "<tr><td colspan='4' class='AC'><span></span></td></tr>";
		}
		return $str ;
	}

	private function getTableEnd()
	{
		return "</tbody></table></div>"; 
	}

	public function getHtml()
	{
		$str  = $this -> getCommonPenalties();
		$str .= $this -> getTableBegin();
		$str .= $this -> getTableContent();
		$str .= $this -> getTableEnd();		
		return $str ;
	}
}



