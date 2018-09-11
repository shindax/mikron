<?php
error_reporting( E_ALL );

class OperationalComplexityReport
{
    private $mysqli;
    private $dse = array();
    private $order_id;
    private $operations = array();
    
    public function __construct( 
                                    $mysqli,
                                    $db_prefix,
                                    $order_id
                                )
    {
        $this -> mysqli = $mysqli;
        $this -> db_prefix = $db_prefix;
        $this -> order_id = $order_id;
        $this -> CollectDSEItems();
        $this -> CollectData();
        $this -> CollectOperationsNames();
    }

// ************************************************************************    
    private function CollectDSEItems()
    {
        $order_id = $this -> order_id ;
        $table = $this -> db_prefix."db_zakdet";
        
        $query = "SELECT ID FROM `$table` WHERE ID_zak=$order_id" ;

        $result = $this -> mysqli -> query( $query );

        if( ! $result ) 
            exit("Connection error in ".__FILE__." at ".__LINE__." line. <br />Query is : $query <br />".$this -> mysqli->error); 
        
        if( $result -> num_rows )
        {            
            while( $row = $result -> fetch_object() )
            {
                $this -> dse[] = $row ;
            }
        }
   }

// ************************************************************************    
    public function GetDSEArr()
    {
        return $this -> dse ;
    }
   
// ************************************************************************    
    public function GetDSECount()
    {
        return count( $this -> dse );
    }

// ************************************************************************    
    public function GetOperationsArr()
    {
        return $this -> operations ;
    }
// ************************************************************************    
    public function GetOperationsCount()
    {
        return count( $this -> operations );
    }
    
// ************************************************************************    
    private function CollectData()
    {
        foreach( $this -> dse AS $key => $val )
        {
            $dse_id = $val -> ID ;
            $table = ( $this -> db_prefix )."db_operitems";
            $query = "SELECT ID_oper, NORM_ZAK as FACT FROM `$table` WHERE ID_zakdet = $dse_id" ;

            $result = $this -> mysqli -> query( $query );

            if( ! $result ) 
                exit("Connection error in ".__FILE__." at ".__LINE__." line. ".$this -> mysqli->error); 

            if( $result -> num_rows )
            {            
                while( $row = $result -> fetch_object() )
                {
                   $oper_id = $row -> ID_oper ;
                   if( $oper_id == 0 )
                      continue;
                   $fact = $row -> FACT ;
                   $this -> operations[ $oper_id ]['fact'] += $fact ;
                }
            }
        }
    }
// ************************************************************************    
    private function CollectOperationsNames()
    {
       
        foreach( $this -> operations AS $key => $val )
        {
            $table = ( $this -> db_prefix )."db_oper";            
            $query = "SELECT NAME, TID FROM `$table` WHERE ID = $key" ;

            $result = $this -> mysqli -> query( $query );

            if( ! $result ) 
                exit("Connection error in ".__FILE__." at ".__LINE__." line. ".$this -> mysqli->error); 

            if( $result -> num_rows )
            {
                $row = $result -> fetch_object();
                $tid = $row -> TID ;
                $this -> operations[ $key ]['name'] = iconv( "Windows-1251", "UTF-8", $row -> NAME  );
                $this -> operations[ $key ]['tid'] = $row -> TID ;
                $this -> operations[ $key ]['op_id'] = $key ;
            }
        }
    }
    
}
