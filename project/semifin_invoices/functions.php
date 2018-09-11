<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
        $result = iconv("UTF-8", "Windows-1251", $str );
//        $result = $str ;
  return $result;
}

function debug($arr)
{
    echo '<pre>' . print_r($arr, true) . '</pre>';
}

function GetSemifinishedStoreUsedDate( $cur_year )
{
    global $pdo;
    $option = '';
    $year = 0;

                    try
                    {
                        $query = "SELECT
                                        DISTINCT YEAR(`create_date`) date
                                        FROM `okb_db_semifinished_store_invoices`
                                        WHERE 1
                                        order by date DESC";
                        $stmt = $pdo->prepare( $query );
                        $stmt->execute();
                    }
                    catch (PDOException $e)
                    {
                      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                    }

                    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
                    {
                    	$selected = '';
                    	
                    	if( $row -> date > $year )
							$year = $row->date ;

						if( $cur_year == $row->date )
						{
							$selected = ' selected ';
							$year = $row->date ;
						}

                        $option .= "<option value='".$row->date."' $selected>".$row->date."</option>";
                        $years[] = $row->date ;
                    }

    return [ 'option' => $option, 'year' => $year ];
}

function GetSemifinishedStoreInvoicesNumber( $cur_year )
{
    global $pdo;
    $numbers = [];

                    try
                    {
                        $query = "SELECT DISTINCT( inv_num ) num
                                        FROM `okb_db_semifinished_store_invoices`
                                        WHERE YEAR(`create_date`) = '$cur_year'
                                        order by num";
                        $stmt = $pdo->prepare( $query );
                        $stmt->execute();
                    }
                    catch (PDOException $e)
                    {
                      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                    }
                    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
                    	$numbers[] = $row->num ;

    return $numbers;
}

function GetSemifinishedStoreInvoicesNumberOptions( $numbers )
{
    global $pdo;
    $option = "<option value='0' selected>Все накладные</option>";

    foreach( $numbers AS $number )
    	$option .= "<option value='$number'>$number</option>";

    return $option;

}

function GetSemifinishedStoreType( $id )
{
    global $pdo;
    $option = '';

    if( $id == 'option' )
         {
            $option = "<option value='0'>...</option>";

                       try
                    {
                        $query = "SELECT * FROM `okb_db_semifinished_store_type` WHERE 1 ORDER BY id";
                        $stmt = $pdo->prepare( $query );
                        $stmt->execute();
                    }
                    catch (PDOException $e)
                    {
                      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                    }
                    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
                        $option .= "<option value='".$row->id."'>".conv($row->description)."</option>";
         }
            else
            {
                if( $id == 0 )
                     $option = "...";
                 else
                {
                   try
                    {
                        $query = "SELECT * FROM `okb_db_semifinished_store_type` WHERE id = $id ";
                        $stmt = $pdo->prepare( $query );
                        $stmt->execute();
                    }
                    catch (PDOException $e)
                    {
                      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                    }
                        $row = $stmt->fetch(PDO::FETCH_OBJ ) ;
                        $option = conv($row->description);
                }
            }

    return $option;
}

function GetOperationOptions()
{
    global $pdo ;

    $option = "<option value='0'>...</option>";

    try
    {
        $query = "SELECT ID id, NAME name FROM `okb_db_oper` WHERE 1 ORDER BY NAME";
        $stmt = $pdo->prepare( $query );
        $stmt->execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }
    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
        $option .= "<option value='".$row->id."'>".conv($row -> name)."</option>";

return $option ;
}