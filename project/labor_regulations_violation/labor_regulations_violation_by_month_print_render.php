<link rel='stylesheet' href='/project/labor_regulations_violation/css/bootstrap.min.css'>
<link rel='stylesheet' href='/project/labor_regulations_violation/css/print.css?v=2'>

<style>

*
{
  margin-left : 0px !IMPORTANT;
  padding : 0 !IMPORTANT;
  -webkit-print-color-adjust: exact;
  -webkit-region-break-inside: avoid;  
}

.container
{
  /*margin: -100px !IMPORTANT;*/
  padding:0 !IMPORTANT;
}

div.table_div table.tbl
{
  width : 1050px !IMPORTANT;*/
  margin: 0px !IMPORTANT;
  padding:0 !IMPORTANT;
}

 .chief_name, .chief_name u
 {
  font-size: 18px;
 }

.center
{
  text-align: center !IMPORTANT;
  padding : 0 10px 0 10px !IMPORTANT;  
  margin : 0 10px 0 10px !IMPORTANT;  
}

.fio
{
  text-align: center !IMPORTANT;
  border-bottom: 1px solid black;
  padding : 0 10px 0 10px !IMPORTANT;
  margin : 0 10px 0 10px !IMPORTANT;
}

 .sign
 {
  font-size: 10px;
 }

.up
{
    margin-top : 0px !IMPORTANT;
}

.page_of
{
  text-align: right  !IMPORTANT;
}

.row
{
  display: flex;
}

.col-sm-10
{
  width : 80% !IMPORTANT;
}

.col-sm-2
{
  width : 16% !IMPORTANT;
}

.more 
{
    display: block ;
    page-break-after: always;
}

/*
tr.first td.field
{
  background: #ddd !IMPORTANT ;
}
*/

div.page
  {
    width : 100%;
    page-break-before: always;
    page-break-inside: avoid;
  }


.col-sm-1
{
  width : 8%;
}

.col-sm-2
{
  width : 16%;
}

.col-sm-3
{
  width : 24%;
}

.col-sm-10
{
  width : 80%;
}

</style>

<?php
error_reporting( E_ALL );
require_once( "classes/db.php" );
require_once( "classes/class.LaborRegulationsViolationItemByMonth.php" );

global $pdo ;

function conv( $str )
{
   return iconv( "UTF-8", "Windows-1251",  $str );
}

$dep_id = $_GET['p0'] ;
$year = $_GET['p1'] ;
$month = $_GET['p2'] ;

$month_names = [
  'январь',
  'февраль',
  'март',
  'апрель',
  'май',
  'июнь',
  'июль',
  'август',
  'сентябрь',
  'октябрь',
  'ноябрь',
  'декабрь'
];

$month = $month < 10 ? "0$month" : $month;
$viol_type = $_GET['p3'] ;

//$max_day = cal_days_in_month(CAL_GREGORIAN, $month, $year );
$max_day = (31 - (($month - 1) % 7 % 2) - ((($month == 2) << !!($year % 4))));

$month = $month < 10 ? "0$month" : $month;
$from = "$year-$month-01";
$to = "$year-$month-$max_day";

$str_arr = [];

$chief_name = "";

$query = '';

  try
    {

        $query = "
          SELECT resource_id, shtat.ID_otdel dep_id, otdel.NAME dep_name
          FROM `labor_regulations_violation_items` items
          LEFT JOIN okb_db_shtat shtat ON shtat.ID_resurs = items.resource_id
          LEFT JOIN okb_db_otdel otdel ON shtat.ID_otdel = otdel.ID          
          LEFT JOIN okb_db_resurs res ON res.ID = items.resource_id
          WHERE 
          items.date BETWEEN '$from' AND '$to'
          AND
          row IN ( 1, 10, 20, 30, 40, 50, 60, 70, 80, 90 )
          AND
          res.TID <> 1
          AND
          shtat.ID_otdel = $dep_id
          GROUP BY resource_id
          ORDER BY dep_name, res.NAME
                 ";

         $stmt = $pdo->prepare( $query );
         $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }

    $deps = [];

    $dep_name = "";

    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
    {
          $deps[ $row -> dep_id ]['name'] = conv( $row -> dep_name );
          $deps[ $row -> dep_id ]['childs'][] = $row -> resource_id ;
          $dep_name = $deps[ $row -> dep_id ]['name'];
    }

$items = 0 ;

foreach ( $deps as $key => $val) 
{ 
  $line = 1 ;
      foreach ( $val['childs'] as $skey => $sval ) 
      {
        $cp = new LaborRegulationsViolationItemByMonth( $pdo, $sval, $month, $year, $viol_type );
        $table = $cp -> GetPrintTable();

        if( strlen( $table ))
            $str_arr[ $items ++ ] = $table;
      }
}

$page_count = ceil( ( $items + 1 ) / 2 );
$str = "";

$curgpage = 1 ;

for( $i = 0 ; $i < $items ; $i ++ )
{
 if( !( $i % 2 )  )
  {
    if( $curgpage > 1 )
      $str .="<span class='more'></span>";    

    $str .= "<div class='row'>
                   <div class='col-sm-12'>
                      <h2>".conv("Нарушения трудового распорядка за ".( $month_names[ $month - 1 ] )." $year.")."</h2>
                  </div>
          </div>";// <div class='row'>

    $str .= "<div class='row'>
               <div class='col-sm-10'>
                  <h4>".conv("Подразделение : ")."$dep_name</h4>
              </div>
            <div class='col-sm-2 page_of'>
                  <h4 class='float-right'>".conv("Стр :").( $curgpage ++ ).conv(" из ")."$page_count</h4>
              </div>
            </div>"; // <div class='row'>
  }

  $str .= "<br>".$str_arr[ $i ];
}

  try
    {
//        $query = "SELECT NAME chief_name FROM `okb_db_shtat` WHERE `ID_otdel`=$dep_id AND `BOSS` = 1";

        $query = "SELECT res.NAME chief_name
                  FROM `okb_db_otdel` otdel
                  LEFT JOIN okb_db_resurs res ON res.ID = otdel.master_res_id
                  WHERE otdel.ID = $dep_id";

                    $stmt = $pdo->prepare( $query );
                    $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }
    $chief_name = "";

    if( $stmt -> rowCount() )
    {
      $row = $stmt->fetch(PDO::FETCH_OBJ );
      $chief_name = conv( $row -> chief_name );
    }
/*  
  if ($dep_id == 82) {
    $chief_name = conv('Мальцев А. И.');
  }

  if ( $dep_id == 63 ) {
    $chief_name = conv('Устьянцев Н. Н.');
  }
  
  if ($dep_id == 108 || $dep_id == 78) {
    $chief_name = conv('Седнев С. В.');
  }
*/  
  // 2.4 Участок механической обработки - мальцев 
  // 2.6 Участок доводки, сборки, упаковки -- устьянцев
      // else
      //     $chief_name = conv("Филоненко С.А."); 



$str .= "<br><br>";

$str .= "<div class='row'>
                 <div class='col-sm-10'>
                    <span class='chief_name'>".conv("Ознакомлены")."</span>
                </div>
        </div>";

$str .= "<div class='row'>
                 <div class='col-sm-2'>
                    <span class='chief_name'>".conv("Мастер")."</span>
                </div>
                 <div class='col-sm-3 fio'>
                    <span class='chief_name'>$chief_name</span>
                </div>
                 <div class='col-sm-1 fio'>
                    <span class='chief_name'></span>
                </div>
                 <div class='col-sm-1 fio'>
                    <span class='chief_name'></span>
                </div>
        </div>";

$str .= "<div class='row'>
                 <div class='col-sm-2'>
                 </div>
                 <div class='col-sm-3 center up'>
                    <span class='sign'>".conv("ФИО")."</span>
                </div>
                 <div class='col-sm-1 center up'>
                    <span class='sign'>".conv("Подпись")."</span>
                </div>
                 <div class='col-sm-1 center up'>
                    <span class='sign'>".conv("Дата")."</span>
                </div>
        </div>";

$str .= "<div class='row'>
                 <div class='col-sm-10'>
                    <span class='chief_name'>".conv("Согласовано")."</span>
                </div>
        </div>";

$str .= "<div class='row'>
                 <div class='col-sm-2'>
                    <span class='chief_name'>".conv("Нач. произв")."</span>
                </div>
                 <div class='col-sm-3 fio'>
                    <span class='chief_name'>".conv("Филоненко С.А.")."</span>
                </div>
                 <div class='col-sm-1 fio'>
                    <span class='chief_name'></span>
                </div>
                 <div class='col-sm-1 fio'>
                    <span class='chief_name'></span>
                </div>
        </div>";

$str .= "<div class='row'>
                 <div class='col-sm-2'>
                 </div>
                 <div class='col-sm-3 center up'>
                    <span class='sign'>".conv("ФИО")."</span>
                </div>
                 <div class='col-sm-1 center up'>
                    <span class='sign'>".conv("Подпись")."</span>
                </div>
                 <div class='col-sm-1 center up'>
                    <span class='sign'>".conv("Дата")."</span>
                </div>
        </div>";

$str .= "<div class='row'>
                 <div class='col-sm-2'>
                    <span class='chief_name'>".conv("Нач. ПДО")."</span>
                </div>
                 <div class='col-sm-3 fio'>
                    <span class='chief_name'>".conv("Матикова Т.Д.")."</span>
                </div>
                 <div class='col-sm-1 fio'>
                    <span class='chief_name'></span>
                </div>
                 <div class='col-sm-1 fio'>
                    <span class='chief_name'></span>
                </div>
        </div>";

$str .= "<div class='row'>
                 <div class='col-sm-2'>
                 </div>
                 <div class='col-sm-3 center up'>
                    <span class='sign'>".conv("ФИО")."</span>
                </div>
                 <div class='col-sm-1 center up'>
                    <span class='sign'>".conv("Подпись")."</span>
                </div>
                 <div class='col-sm-1 center up'>
                    <span class='sign'>".conv("Дата")."</span>
                </div>
        </div>";


$str .= "</div>";

echo "$str";
