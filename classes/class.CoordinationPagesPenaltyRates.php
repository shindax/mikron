<?php

class CoordinationPagesPenaltyRates
{
	protected $data;

	private function conv( $str )
	{
	    return iconv( "UTF-8", "Windows-1251",  $str );
	}

	public function  __construct( $pdo )
	{
        try
            {
                $query = "
                			SELECT ord, caption, minutes_to_penalty, penalty, penalty2, penalty3
                          	FROM coordination_pages_rows
                            WHERE 1
                            ORDER BY ord
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
            	$this -> data[ $row -> ord ] = 
            	[
            		'caption' => $row -> caption,
            		'minutes_to_penalty' => $row -> minutes_to_penalty,
            		'penalty' => $row -> penalty,
            		'penalty2' => $row -> penalty2,
            		'penalty3' => $row -> penalty3
            	];
            }
	}

	private function getTableBegin()
	{
		$str = "<div class='row'><h3>".conv("Ставки штрафов за просрочку в листах согласования.")."</h3></div>";
		$str .= "<div class='row'>
				<table id='' class='table table-striped penalty'>
				<col width='60%'>
				<col width='10%'>
				<col width='10%'>
				<col width='10%'>
				<col width='10%'>								

				  <thead>
				    <tr class='table-primary'>
				      <th>". $this -> conv( "Ответственный" ) ."</th>
				      <th>". $this -> conv( "Время проработки, мин." ) ."</th>
				      <th>". $this -> conv( "Штраф 1" ) ."</th>
				      <th>". $this -> conv( "Штраф 2" ) ."</th>
				      <th>". $this -> conv( "Штраф 3" ) ."</th>				      				      
				    </tr>
				  </thead>
				  <tbody>";

		return $str ;
	}

	private function getTableContent()
	{
		$str = "";
		$classes = ['ord', 'even'];

		foreach( $this -> data AS $key => $value )
		{
			   $str .=
                "<tr data-ord='$key' class='".$classes[ $key % 2 ]."'>
                <td class='AC'>".conv( $value['caption'] )."</span></td>
                <td class='AC'><input type='number' class='coor_pages_min_to_penalty' value='".$value['minutes_to_penalty']."'/></td>
                <td class='AC'><input type='number' class='coor_pages_penalty' data-field='penalty' value='".$value['penalty']."'/></td>
                <td class='AC'><input type='number' class='coor_pages_penalty' data-field='penalty2' value='".$value['penalty2']."'/></td>
                <td class='AC'><input type='number' class='coor_pages_penalty' data-field='penalty3' value='".$value['penalty3']."'/></td>                                
                </tr>";
		}
		return $str ;
	}

	private function getTableEnd()
	{
		return "</tbody></table></div>"; 
	}

	public function getHtml()
	{
		$str = "";
		$str .= $this -> getTableBegin();
		$str .= $this -> getTableContent();
		$str .= $this -> getTableEnd();		
		return $str ;
	}
	public function getData()
	{
		return $this -> data ;
	}
}



