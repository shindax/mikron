<?php

class AbstractBinaryTree
{
	private $pdo ;
	private $tablename ;
	private $id_name ;
	private $parent_name ;

	private $data = [];

	public function __construct( $pdo, $tablename, $id_name, $parent_name, $order_by_name = "")
    {
		$this -> pdo = $pdo;
		$this -> tablename = $tablename ;
		$this -> id_name = $id_name ;
		$this -> parent_name = $parent_name;
		$this -> order_by_name = $order_by_name;
		$this -> GetCat();
		$this -> FullMapTree();
    }

    public function GetData()
    {
    	return $this -> data ;
    }

    private function GetCat()
	{
	            try
	            {
	                $query = "SELECT ".$this -> id_name.", ".$this -> parent_name." FROM ".$this -> tablename." WHERE 1 ";
	                if( strlen( $this -> order_by_name) )
	                	$query .= "ORDER BY ".$this -> id_name;
	                $stmt = $this -> pdo->prepare( $query );
	                $stmt->execute();
	            }
	            catch (PDOException $e)
	            {
	              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
	            }
	            while( $row = $stmt->fetch(PDO::FETCH_ASSOC ) )
	            	{
						$this -> data[$row['id']] = $row;
	            	}
	}

	private function FullMapTree() 
	{
		$dataset = $this -> data ;
		$tree = [];

		foreach ($dataset as $id=>&$node) 
			if (!$node[ $this -> parent_name ])
				$tree[$id] = &$node;
					else
	            		$dataset[$node[ $this -> parent_name ]]['childs'][$id] = &$node;
	 	$this -> data = $tree;
	}

	public function GetLocTree( $id ) 
	{
		$dataset = $this -> data;
		$subtree = 0 ;
		$found = 0 ;

		foreach ( $dataset as $key => $node ) 
		{
			if( $dataset[ $key ]['id'] == $id )
				return $dataset[ $key ];
			
			if( isset( $dataset[ $key ]['childs'] ) )
				$subtree = loc_map_tree( $dataset[ $key ]['childs'], $id );	

			if( $subtree )
				break ;
		}

		return $subtree ;
	}

	private function cyclic_pass( $dataset, &$to_pass )
	{
	    foreach( $dataset AS $key => $value )
	    {
	        $to_pass[] = $value['id'];
	        if( isset( $value['childs']) )
	                $this -> cyclic_pass( $value['childs'], $to_pass );
	    }
	}

	private function pass( $dataset )
	{
		$arr = [];

		$arr[] = $dataset['id'];

		if( isset( $dataset['childs']) )
	          $this -> cyclic_pass( $dataset['childs'], $arr ) ;

	    return $arr ;
	}

	public function GetIDs( $dataset )
	{
		return $this -> pass( $dataset );
	}
}