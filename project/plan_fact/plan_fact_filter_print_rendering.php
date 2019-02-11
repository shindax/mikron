<link rel='stylesheet' href='/project/coordination_page/css/bootstrap.min.css'>
<link rel='stylesheet' href='/project/coordination_page/css/print.css'>

<style>
@media print
{

    *
    {
        font-size: 12px;
        font-family: 'Times New Roman', Times, serif;
    }

    h2,h3
    {
      font-family: 'Times New Roman' !IMPORTANT;
    }

   .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-6 {
        float: left;
   }
   .col-sm-6 {
        width: 100%;
   }
   .col-sm-11 {
        width: 91.66666667%;
   }
   .col-sm-10 {
        width: 83.33333333%;
   }
   .col-sm-9 {
        width: 75%;
   }
   .col-sm-8 {
        width: 66.66666667%;
   }
   .col-sm-7 {
        width: 58.33333333%;
   }
   .col-sm-6 {
        width: 50%;
   }
   .col-sm-5 {
        width: 41.66666667%;
   }
   .col-sm-4 {
        width: 16%;
   }
   .col-sm-3 {
        width: 25%;
   }
   .col-sm-2 {
        width: 15%;
   }
   .col-sm-1 {
        width: 8.33333333%;
   }

    .sign
 {
    padding-bottom : 5px !important;
 }

  .sign div span
 {
    font-size:14px !important;
 }

    .col-sm-24 span
 {
    font-size:12px !important;
 }

  .sign span
 {
    margin-top : -30px !important;
 }

table
{
  margin-top: 10px !important;
  table-layout: fixed;
}

td.AC
{
    vertical-align: middle;
    text-align: center;
}

.tbl
{
    margin-top: 0 ! IMPORTANT;
    padding-top: 10px ! IMPORTANT;
}

h2,h3
{
    text-align: center;
}

.more 
{
     page-break-after: always;
} 

td.AL
{
  padding-left: 10px;
}
}

</style>

<?php

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
  global $dbpasswd ;

  if( strlen( $dbpasswd ) )
      return iconv( "UTF-8", "Windows-1251",  $str );
        else
          return $str;
}

$stages = [
            "Дата заключения договора",
            "Дата открытия заказа",
            "Дата планируемой отгрузки",
            "Начало производства",
            "Окончание производства"
          ];


$list = $_GET['p0'];
$type = $_GET['p1'];
$from = $_GET['p2'];
$to = $_GET['p3'];

$dates = "";
if( strlen( $from ) )
  $dates .= conv( " Период с $from " );

if( strlen( $to ) )
  $dates .= conv( " по $to " );

$data = [];

    try
    {
        $query = "
                    SELECT  type.description ord_type, zak.NAME ord_name, zak.DSE_NAME dse_name, zak.DSE_COUNT dse_count, stage.description stage
                    FROM `okb_db_zak` zak
                    LEFT JOIN `okb_db_zak_type` type ON type.id = zak.tid
                    LEFT JOIN `okb_db_zak_stages` stage ON stage.id = zak.ID_stage
                    WHERE  zak.ID IN ( $list )
                    ORDER BY ord_name
                    ";

        $stmt = $pdo -> prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");

    }

    while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
    {
      $data [] = [
                    "ord_name" => conv( $row -> ord_type )." ".conv( $row -> ord_name ),
                    "dse_name" => conv( $row -> dse_name ),
                    "dse_count" => conv( $row -> dse_count ),
                    "stage" => conv( $row -> stage ),
                 ];
    }

$table = "<table class='tbl'>";
$table .= "<col width='2%'>";
$table .= "<col width='15%'>";
$table .= "<col width='55%'>";
$table .= "<col width='5%'>";
$table .= "<col width='20%'>";

$table .= "<tr class='first'>";
$table .= "<td class='field AC'>#</td>";
$table .= "<td class='field AC'>".conv("Заказ")."</td>";
$table .= "<td class='field AC'>".conv("ДСЕ")."</td>";
$table .= "<td class='field AC'>".conv("Кол.")."</td>";
$table .= "<td class='field AC'>".conv("Текущий этап")."</td>";
$table .= "</tr>";

foreach ( $data as $key => $value ) 
{
  $table .= "<tr>";
  $table .= "<td class='field AC'>".( $key + 1 ) ."</td>";
  $table .= "<td class='field AC'>".$value['ord_name']."</td>";
  $table .= "<td class='field AL'>".$value['dse_name']."</td>";
  $table .= "<td class='field AC'>".$value['dse_count']."</td>";
  $table .= "<td class='field AL'>".$value['stage']."</td>";  
  $table .= "</tr>";  
}

$table .= "</table>";

$str = "<h2>".conv("Отчет по план-факту. Фильтр : ").conv( $stages[ $type - 1 ] ).".</h2><h3>$dates</h3>";
$str .= "<div class='container'>";
$str .= "<div class='row'>
          <div class='col-sm-12'>$table</div>
          </div>";
$str .= "</div>";

echo $str ;
