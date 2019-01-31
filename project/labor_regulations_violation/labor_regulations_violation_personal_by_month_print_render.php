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

table.tbl
{
  width : 1000px !IMPORTANT;
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

.shift1
{
  text-decoration : underline;
}

.shift_total
{
  background: #DDDDDD;
  width: 50px;
  margin:20px auto;
}

.viol_total
{
  font-weight: bold;
  margin-bottom:10px;
}

</style>

<?php
error_reporting( E_ALL );
require_once( "classes/db.php" );
require_once( "classes/class.LaborRegulationsViolationItemByMonth.php" );

global $pdo ;

function conv( $str )
{
  global $dbpasswd;
    
    if( strlen( $dbpasswd ) )
      $str = iconv( "UTF-8", "Windows-1251",  $str );

    return $str;
}

$chief_name = "";
$user_name = "";
$user_id = $_GET['p0'] ;
$year = $_GET['p1'] ;
$month = $_GET['p2'] ;
$dep_id = $_GET['p3'] ;

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

$max_day = (31 - (($month - 1) % 7 % 2) - ((($month == 2) << !!($year % 4))));

$month = $month < 10 ? "0$month" : $month;
$from = "$year-$month-01";
$to = "$year-$month-$max_day";

$chief_name = "";

$cp = new LaborRegulationsViolationItemByMonth( $pdo, $user_id, $month, $year );
$user_name = $cp -> GetUserName();

  try
    {
        $query = "SELECT res.NAME chief_name, otdel.NAME dep_name
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
    
    if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
    {
      $chief_name = conv( $row -> chief_name );
      $dep_name = conv( $row -> dep_name );      
    }
    else
    {
      $chief_name = conv( "Не назначен" );
      $dep_name = conv( "Не назначено" );      
    }

$str = "";

    $str .="<span class='more'></span>";    

    $str .= "<div class='row'>
                   <div class='col-sm-12'>
                      <h2>".conv("Нарушения трудового распорядка за ".( $month_names[ $month - 1 ] )." $year.")."</h2>
                  </div>
          </div>";// <div class='row'>

    $str .= "<div class='row'>
               <div class='col-sm-10'>
                  <h4>".conv("Подразделение : ")."$dep_name.".conv(" Сотрудник: ")."$user_name</h4>
              </div>
            <div class='col-sm-2 page_of'>
              </div>
            </div>"; // <div class='row'>

$str .= "<br>";

$str .= $cp -> GetPrintTable();

// $str .= "<span class='more'></span>";

$str .= "<br>";
$str .= "<div class='row'>
                 <div class='col-sm-10'>
                    <span class='chief_name'>".conv("Согласовано")."</span>
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
                    <span class='chief_name'>".conv("Ознакомлены")."</span>
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

echo $str;
