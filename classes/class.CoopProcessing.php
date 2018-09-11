<?php

class CoopProcessing
{
	protected $process_id ;
	protected $pdo ;
	protected $items ;

	public function __construct( $id, $pdo )
	{
		$this -> process_id = $id ;
		$this -> pdo = $pdo ;	
	}

	private function CollectData()
	{

		        try
                {
                    $query = "SELECT id, material_id, width, pr1, pr2, pr3, actuality FROM `okb_db_coop_processing_items` WHERE processing_id = ".$this -> process_id;
                    $stmt = $this -> pdo -> prepare( $query );
                    $stmt -> execute();
                }
                catch (PDOException $e)
                {
                  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
                }

                while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
                	{
                		$id = $row -> id ;
                		$material_id = $row -> material_id;
                		$width = $row -> width ;
                		$pr1 = $row -> pr1;
                		$pr2 = $row -> pr2;
                		$pr3 = $row -> pr3;
                		$actuality = $row -> actuality ;
                		$this -> item[$material_id][] = 
                				[
                				'id' => $id, 
                				'width' => $width,
                				'pr1' => $pr1,
                				'pr2' => $pr2,
                				'pr3' => $pr3
                				];
                	}
                	debug( $this -> item );
	}


	private function GetTableHead()
	{
	}

	private function GetTableContent()
	{
	}

	private function GetTableEnd()
	{
		$str = "</table>"; 
		return $str ;
	}

	public function GetTable()
	{
		$str = GetTableHead();
		$str .= GetTableContent();
		$str = GetTableEnd();
		return $str ;
	}
}