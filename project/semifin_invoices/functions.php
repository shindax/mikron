<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
        $result = iconv("UTF-8", "Windows-1251", $str );
//        $result = $str ;
  return $result;
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
                                        FROM okb_db_semifinished_store_invoices
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
                        $query = "SELECT 
                        			id AS num
                                    FROM okb_db_semifinished_store_invoices
                                    WHERE YEAR(`create_date`) = '$cur_year'
                                    ORDER BY num";

                        // echo $query;

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

function GetSemifinishedStoreInvoicesNumberOptions( $numbers, $inv_id = 0 )
{
    global $pdo;

    // _debug( $numbers );

    $option = "<option value='0' ".( $inv_id ? '' : 'selected').">Все накладные</option>";

    foreach( $numbers AS $number )
    	   $option .= "<option value='$number' ".( $inv_id === $number ? 'selected' : '').">$number</option>";

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
                        $option .= "<option value='".$row->id."'>".conv($row->description)." (".conv($row->note).")</option>";
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

function GetOperationOptions( $id = 0 )
{
    global $pdo ;

    $option = "<option value='0' ".( $id ? "" : "selected" ).">...</option>";

    try
    {
        $query = "
                    SELECT 
                        oper.ID AS id, 
                        oper.NAME AS name,
                        kind.name AS kind_name
                    FROM `okb_db_oper` AS oper
                    LEFT JOIN okb_db_oper_kind AS kind ON kind.id = oper.TID
                    WHERE oper.ID NOT IN ( 8 )
                    ORDER BY kind.NAME, oper.NAME";
        $stmt = $pdo->prepare( $query );
        $stmt->execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }
    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
    {
        $name = conv($row -> name);
        $kind_name = conv($row -> kind_name);
        $kind_name = strlen( $kind_name ) ? "$kind_name - " : "";
        $option .= "<option value='$row->id' ".( $row->id == $id ? "selected" : "").">{$kind_name}{$name}</option>";
    }

return $option ;
}

function GetWarehouses( $id = 0 )
{
    global $pdo ;

    $option = "<option value='0'>...</option>";

    try
    {
        $query = "
                    SELECT *
                    FROM `okb_db_sklades`
                    WHERE 1
                    ORDER BY NAME";

        $stmt = $pdo->prepare( $query );
        $stmt->execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }
    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
    {
        $loc_id = $row->ID;
        $option .= "<option value='$loc_id' ";
        if( $loc_id == $id )
            $option .= "selected";

        $option .= ">".conv( $row->NAME )."</option>";
    }
return $option ;
} // function GetWarehouses()

function GetCells( $id = 0, $parent_id = 0 )
{
    global $pdo ;

    $option = "<option value='0'>...</option>";

    try
    {
        $query = "
                    SELECT *
                    FROM `okb_db_sklades_item`
                    WHERE 1
                    ORDER BY NAME";

        $stmt = $pdo->prepare( $query );
        $stmt->execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }
    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
        if( strlen( $row->NAME ) )
        {
            $loc_id = $row->ID;
            $loc_parent_id = $row->ID_sklad;
            if( $loc_parent_id == $parent_id )
                $hidden = '';
                else
                    $hidden = 'hidden' ;
            
            $option .= "<option class='$hidden' data-parent_id='$loc_parent_id' value='$loc_id'";
            
            if( $loc_id == $id )
                $option .= "selected";

            $option .= ">".conv( $row->NAME )."</option>";
        }

    return $option ;
} // function GetCells()

function GetTiers( $id = 0, $parent_id = 0 )
{
    global $pdo ;

    $option = "<option value='0'>...</option>";

    try
    {
        $query = "
                    SELECT *
                    FROM `okb_db_sklades_yaruses`
                    WHERE 1
                    ORDER BY ORD";

        $stmt = $pdo->prepare( $query );
        $stmt->execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }
    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
        {
            $ord = $row->ORD;
            if( $ord )
                $name = conv("Ярус $ord");
                else
                    $name = conv("Пол");

            $loc_id = $row->ID;
            $loc_parent_id = $row->ID_sklad_item;
            if( $loc_parent_id == $parent_id )
                $hidden = '';
                else
                    $hidden = 'hidden' ;
            
            $option .= "<option class='$hidden' class='hidden' data-parent_id='$loc_parent_id' value='$loc_id'";

            if( $loc_id == $id )
                $option .= "selected";

            $option .= ">$name</option>";
        }
return $option ;
} // function GetTiers()

function GetStoragePlaceDialogTableRow( $line_num = 1, $id = 0, $count = 0, $comment="", $wh = 0, $cell = 0, $tier = 0 )
{
    return 
          "<tr class='storage_place' data-id='$id'>
            <td class='field AC'><span class='num'>$line_num</span></td>
            <td class='field AC'><input class='storage_place_dialog_count_input' type='number' value='$count'/></td>
            <td class='field'>
              <select class='wh_select'>".
              GetWarehouses($wh)
              ."</select></td>
            <td class='field'>
              <select class='cell_select'>".
                GetCells( $cell, $wh)
              ."</select>
            </td>
            <td class='field'>
              <select class='tier_select'>".
                  GetTiers( $tier, $cell)
              ."</select></td>
            <td class='field'><input class='storage_place_dialog_comment_input' value='$comment' /></td>
          </tr>";
}

function GetStoragePlaceDialogTableBegin()
{
    return "<table class='tbl storage_place_table'>
          <col width = '3%' />          
          <col width = '5%' />
          <col width = '10%' />
          <col width = '10%' />
          <col width = '10%' />
          <col width = '20%' />

          <tr class='first'>
            <td class='field'>".conv("#")."</td>          
            <td class='field'>".conv("Кол-во")."</td>
            <td class='field'>".conv("Склад")."</td>
            <td class='field'>".conv("Ячейка")."</td>
            <td class='field'>".conv("Ярус")."</td>
            <td class='field'>".conv("Примечание")."</td>
          </tr>";
}

function GetStoragePlaceDialogTableEnd()
{
    return "</table>";
}

function FixActionInHistory( $action_id, $user_id, $id_zakdet, $dse_name, $count, $message, $from_tier = 0 , $to_tier = 0 )
{
    global $pdo;
    
    try
    {
        $query = "INSERT INTO okb_db_warehouse_action_history 
                ( action_type_id, user_id, from_tier, to_tier, id_zakdet, dse_name, count, comment )
                VALUES ( $action_id, $user_id, $from_tier, $to_tier, $id_zakdet, '$dse_name', $count, 
                '$message' )
              ";

        $stmt = $pdo -> prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
    }
}

function GetUserInfo( $user_id  )
{
    global $pdo;

    try
    {
        $query = "   SELECT 
                 users.FIO AS user_name, 
                 resurs.ID AS res_id,
                 resurs.GENDER AS gender
                 FROM okb_users AS users
                 LEFT JOIN okb_db_resurs AS resurs ON resurs.ID_users = users.ID
                 WHERE users.ID = $user_id";

        $stmt = $pdo -> prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
    }

    $row = $stmt->fetch( PDO::FETCH_OBJ );

    $name = $row -> user_name;
    $gender = $row -> gender;
    $res_id = $row -> res_id;

    if( $user_id == 1 )
    {
        $name = 'Admin';
        $gender = 'male';
        $res_id = 1;
    }

    return [ 'name' => $name, 'gender' => $gender, 'res_id' => $res_id ];

} // function GetUserInfo( $user_id  )

function GetMastersOptions()
{
    global $pdo ;

    $option = "<option value='0'>...</option>";

    try
    {
        $query = "
                    SELECT 
                    #special.NAME AS special_name,
                    #shtat.ID_special,
                    shtat.NAME AS res_name, 
                    shtat.ID_resurs AS res_id
                    FROM okb_db_shtat AS shtat
                    LEFT JOIN okb_db_special AS special ON special.ID = shtat.ID_special
                    WHERE 
                    ( ID_special = 36 OR ID_special = 171 )
                    AND
                    ID_resurs <> 0
                    ORDER BY shtat.NAME
                    ";

        $stmt = $pdo->prepare( $query );
        $stmt->execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }
    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
    {
        $name = conv($row -> res_name);
        $option .= "<option value='$row->res_id'>$name</option>";
    }

return $option ;
}


function ListUnique( $list )
{
    $arr = array_unique( explode( ",", $list ));
    return join( ",", $arr );
}