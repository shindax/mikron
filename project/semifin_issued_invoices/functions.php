<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
        $result = iconv("UTF-8", "Windows-1251", $str );
//        $result = $str ;
  return $result;
}

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}
    
function GetMonthOptions( $selected )
{
	global $MM_Name;
	$str = "<option value='0'>".conv("Все")."</option>";

	foreach ( $MM_Name AS $key => $value ) 
	{
		if( $key )
			$str .= "<option value='$key' ".( $selected == $key ? "selected" : "" ).">$value</option>";
	}

	return $str;
}

function GetYearOptions( $selected )
{
	$str = "<option value='0'>".conv("Все")."</option>";

	for ( $year = 2019; $year <= 2020; $year ++  ) 
		$str .= "<option value='$year' ".( $selected == $year ? "selected" : "" ).">$year</option>";

	return $str;
}

function get_warehouse_structure()
{
    global $pdo;

    $wh = [];
    $cells = [];
    $tiers = [];

        try
        {
            $query =
            "SELECT *
             FROM okb_db_sklades AS wh
             WHERE 1" ;
            $stmt = $pdo->prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            $wh[ $row -> ID ] = conv( $row -> NAME );

        try
        {
            $query =
            "SELECT *
             FROM okb_db_sklades_item AS cells
             WHERE 1" ;
            $stmt = $pdo->prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            $cells[ $row -> ID ] = conv( $row -> NAME );

        try
        {
            $query =
            "SELECT *
             FROM okb_db_sklades_yaruses AS tiers
             WHERE 1" ;
            $stmt = $pdo->prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            $tiers[ $row -> ID ] = $row -> ORD;

        return [ "wh" => $wh, "cells" => $cells, "tiers" => $tiers ];
} //function get_warehouse_structure()

function GetSelectedInvoiceDate( $inv )
{
     global $pdo;
     try
        {
          $query ="
                        SELECT 
                        DATE_FORMAT( date, '%Y') AS year,
                        DATE_FORMAT( date, '%m') AS month
                        FROM `okb_db_semifinished_store_issued_invoices`
                        WHERE id = $inv";

                        // echo $query ;

          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
        }

        $row = $stmt->fetch(PDO::FETCH_OBJ );

        return [ "year" => $row -> year, "month" => $row -> month ] ;
}

function GetUserInfo( $user_id  )
{
  $query = "   SELECT 
         users.FIO AS user_name, 
         resurs.GENDER AS gender
         FROM okb_users AS users
         LEFT JOIN okb_db_resurs AS resurs ON resurs.ID_users = users.ID
         WHERE users.ID = $user_id";
  
  $result = dbquery( $query );
  $row = mysql_fetch_assoc( $result );

    return [ 'name' => $row['user_name'], 'gender' => $row['gender'] ];

} // function GetUserInfo( $user_id  )
