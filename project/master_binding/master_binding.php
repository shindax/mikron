<script type="text/javascript" src="/project/master_binding/js/master_binding.js"></script>
<link rel='stylesheet' href='/project/master_binding/css/style.css' type='text/css'>

<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
error_reporting( E_ALL );
ini_set('display_errors', true);

global $user, $pdo ;
$user_id = $user['ID'];
const MASTER_ID = [ 214 , 13, 249 ] ; // Трифонова, Рыбкина, Лаптева

function conv( $str )
{
    return iconv("UTF-8","Windows-1251",  $str );
}

echo "<script>var user_id = $user_id;</script>";

$dep = [];
$masters = [];

function GetMastersSelect( $id )
{
  global $masters, $user_id;
  
  $disabled = in_array( $user_id, MASTER_ID ) ? "" : "disabled";
  
  $str = "<select class='master_select' $disabled>";
  $str .= "<option value='0'>...</option>";
  
  foreach ( $masters as $key => $value ) 
  {
    $str .= "<option value='$key' ";
    
    if( $key == $id )
      $str .= "selected";
    
    $str .= ">$value</option >";
  }

  $str .= "</select>";
  return $str ;
}

  try
        {
            $query = "
                        SELECT ID, NAME, master_res_id
                        FROM okb_db_otdel
                        WHERE need_master = 1
                        ORDER BY NAME
                        ";

            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());

        }

        while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
            $dep[] = [ 
                        "id" => $row -> ID,
                        "name" => conv( $row -> NAME ),
                        "master_res_id" => conv( $row -> master_res_id )
                      ];

  try
        {
            $query = "
                        SELECT ID, NAME
                        FROM okb_db_resurs
                        WHERE 
                        ID_special IN ( 35,36,49,60,70,87,115,131 )
                        AND 
                        ID <> 0
                        ORDER BY NAME
                        ";

            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());

        }
        while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
            $masters[ $row -> ID ] = conv( $row -> NAME );


$str = "<div class='container'>";
$str .= "<div class='row'>";
$str .= "<div class='col-sm-12'><h2>".conv("Назначение мастеров подразделениям")."</h2></div>";
$str .= "<div class='table_div col-sm-12'>";

$str .= "<table class='tbl'>";
$str .= "<tr class='first'>";
$str .= "<td class='Field AC'>".conv("Подразделение")."</td>";
$str .= "<td class='Field AC'>".conv("Мастер")."</td>";
$str .= "</tr>";

foreach( $dep AS $key => $val )
{
    $str .= "<tr data-id='".$val["id"]."'>";
    $str .= "<td class='Field AL'>".$val["name"]."</td>";
    $str .= "<td class='Field AL'>".GetMastersSelect( $val["master_res_id"] )."</td>";
    $str .= "</tr>";
}

$str .= "</table>";

$str .= "</div>"; // table_div
$str .= "</div>"; // row
$str .= "</div>"; // container
echo $str;

