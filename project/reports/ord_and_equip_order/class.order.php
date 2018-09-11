<?php
function str_out( $arg )
{
    return iconv( "UTF-8", "Windows-1251", $arg  );
}

class Orders
{
    private $mysqli;
    private $orders = array();

    public function __construct
    (
        $mysqli
    )
    {
        $this -> mysqli    = $mysqli;
        $this -> db_prefix = $db_prefix;

        $query = "SELECT zak.ID, zak.NAME, zak.TID, zak.DSE_NAME, zak.CDATE 
        FROM okb_db_zak zak 
        WHERE zak.EDIT_STATE='0' AND zak.PID = 0 ORDER BY zak.NAME DESC" ;

        $result = $this -> mysqli -> query( $query );

        if( ! $result )
            exit("Connection error in ".__FILE__." at ".__LINE__." line. <br />Query is : $query <br />".$this -> mysqli->error);

        if( $result -> num_rows )
        {
            while( $row = $result -> fetch_array() )
            {
                $this -> orders[] = $row ;
            }
        }
    }

    public function GetOrderCount()
    {
        return count( $this -> orders );
    }

    public function GetOrdersOptions()
    {
        $ord_type = array(" ","ОЗ","КР","СП","БЗ","ХЗ","ВЗ");
        $option = '';
        $sernum = 1 ;
        foreach( $this -> orders AS $order )
        {
            $name = $order['NAME'] ;
            $dse_name = $order['DSE_NAME'] ;
            $cdate = $order['CDATE'] ;
            $id = $order['ID'] ;
            $tid = $order['TID'] ;
            $type = str_out( $ord_type[ $tid ] );
            if( $tid == 0 )
                continue ;
            $option .= "<option value='$id' data-cdate='$cdate' data-sernum='$sernum'>$type $name $dse_name</option>";
            $sernum ++ ;
        }
        return $option;
    }

}