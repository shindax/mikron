<?php

class ServiceNote
{
	protected $id ;
	protected $pdo ;
	protected $data ;
	protected $receivers ;

	public function __construct( $pdo, $id )
	{
		$this -> id = $id ;
		$this -> pdo = $pdo ;	
		$this -> CollectData();
	}

	protected function CollectData()
	{
		$data = [];
		$receivers = [];

		try
		{
		$query ="
                SELECT 
				sn.*,
				cr_res.NAME AS creator_name,
				res.NAME AS res_name,
				res.ID AS res_id
				FROM service_notes sn
				LEFT JOIN okb_db_resurs res ON JSON_CONTAINS(sn.receivers_res_id, CAST( res.id AS JSON ), '$')
				LEFT JOIN okb_db_resurs cr_res ON cr_res.ID = sn.creator_res_id
				WHERE sn.id = ".$this -> id." ORDER BY res_name";
                $stmt = $this -> pdo->prepare( $query );
                $stmt -> execute();
            }
            catch (PDOException $e)
            {
                die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
            }

        while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
        {
        	$data = [
        						'id' => $row -> id, 
        						'note_number' => $row -> note_number,         						
        						'creator_res_id' => $row -> creator_res_id,
        						'creator_name' => $row -> creator_name,
        						'creation_date' => $row -> creation_date,
        						'description' => $row -> description,
        						'note_scan_name' => $row -> note_scan_name,
        						'executed' => $row -> executed,
        						'timestamp' => $row -> timestamp,
        					];

				$receivers[ $row -> res_id ] = $row -> res_name ;
        }

       $this -> data = [ 'note_data' => $data, 'receivers' => $receivers ];
	
	} // protected function CollectData()

	public function GetData()
	{
		return $this -> data;
	}
}