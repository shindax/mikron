<?php

class AbstractBinaryTree
{
	private $data;
	private $pdo;
	private $table_name;
	private $id_field;
	private $parent_id_field;
	private $loc_tree_built;
	private $full_tree_built;	
	private $add_parameters;
	private $order;

	public function __construct( $pdo, $table_name, $id_field = "id", $parent_id_field = "parent_id", $add_parameters = [], $order = "", $user_query = NULL )
	{
		$this -> pdo = $pdo;
		$this -> table_name = $table_name;
		$this -> id_field = $id_field;
		$this -> parent_id_field = $parent_id_field;
		$this -> loc_tree_built = 0 ;
		$this -> full_tree_built = 0 ;
		$this -> add_parameters = $add_parameters ;
		$this -> order = $order ;
		$this -> get_raw_data( $user_query );
	}

	public function GetData()
	{
		return $this -> data ;
	}

	private function get_raw_data( $user_query )
	{
		$arr_cat = [];
		$id_field = $this -> id_field ;
		$parent_id = $this -> parent_id_field ;
		$table_name = $this -> table_name ;
		$add_parameters = "";
		$order = strlen( $this -> order ) ? $this -> order : "";

		if( count( $this -> add_parameters ) )
			$add_parameters = ",".join(",", $this -> add_parameters );

        $query = "SELECT $id_field, $parent_id $add_parameters FROM $table_name WHERE 1 $order";

        if( $user_query )
        	$query = $user_query ;
            try
            {
                $stmt = $this -> pdo -> prepare( $query );
                $stmt->execute();

            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }
            while( $row = $stmt->fetch(PDO::FETCH_ASSOC ) )
            	{
					$arr_cat[$row[ $id_field ]] = $row;
            	}
		$this -> data = $arr_cat;
	}

	public function GetFullMapTree() 
	{
		$this -> full_tree_built = 1 ;
		$dataset = $this -> data;
		$tree = [];
		$parent_id = $this -> parent_id_field ;

		foreach ($dataset as $id=>&$node) 
			if (!$node[ $parent_id ])
				$tree[$id] = &$node;
					else
	            		$dataset[$node[ $parent_id ]]['childs'][$id] = &$node;
	 	
	 	$this -> data = $tree;
	 	return $this -> data ;
	}

	private function cyclic_to_analysis( $dataset, &$to_analysis )
	{
		$id_field = $this -> id_field ;

	    foreach( $dataset AS $key => $value )
	    {
	        $to_analysis[] = $value[ $id_field ];
	        if( isset( $value['childs']) )
	                $dataset = $this -> cyclic_to_analysis( $value['childs'], $to_analysis );
	    }
	    return $dataset;
	}

	private function loc_map_tree( $dataset, $id ) 
	{
		$subtree = 0 ;
		$found = 0 ;
		$id_field = $this -> id_field;

		foreach ( $dataset as $key => $node ) 
		{
			if( $dataset[ $key ][ $id_field ] == $id )
				return $dataset[ $key ];
			
			if( isset( $dataset[ $key ]['childs'] ) )
				$subtree = $this -> loc_map_tree( $dataset[ $key ]['childs'], $id );	

			if( $subtree )
				break ;
		}

		return $subtree ;
	}

	public function GetLocMapTree( $id )
	{
		if( $this -> full_tree_built == 0 )
			$this -> GetFullMapTree();
		$dataset = $this -> loc_map_tree( $this -> data, $id ) ;
		$this -> data = $dataset;
		$this -> loc_tree_built = 1 ;
		return $this -> data;
	}

	public function GetIdsFromRoot( $root_id, $include_root = 0 )
	{
		if( $this -> loc_tree_built == 0 )
			$this -> GetLocMapTree( $root_id );
	    $dataset = $this -> data;
	    $id_field = $this -> id_field;

		$to_analysis = [];

		if( $include_root )
			$to_analysis[] = $dataset[ $id_field ];

		if( isset( $dataset['childs']) )
	          $this -> cyclic_to_analysis( $dataset['childs'], $to_analysis ) ;
      
		return  array_unique ( $to_analysis );
	}
}
