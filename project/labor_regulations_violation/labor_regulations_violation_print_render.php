<link rel='stylesheet' href='/project/labor_regulations_violation/css/bootstrap.min.css'>
<link rel='stylesheet' href='/project/labor_regulations_violation/css/print.css?v=2'>

<style>

*
{
  margin-left : 0px !IMPORTANT;
  padding : 0 !IMPORTANT;
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

.more 
{
    display: block ;
    page-break-after: always;
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
require_once( "classes/class.LaborRegulationsViolationItem.php" );

global $pdo ;

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

$user_id = $user["ID"];

$dep_id = $_GET['p0'] ;
$day = $_GET['p1'] ;
$day = $day < 10 ? "0$day" : $day;

$month = $_GET['p2'] ;
$month = $month < 10 ? "0$month" : $month;

$year = $_GET['p3'] ;
$shift = $_GET['p4'] ;

$datestring = $year.$month.$day;
$date = "$year-$month-$day";
$ourdate = "$day.$month.$year";

try
    {
        $query = "
                SELECT resurs.NAME resurs_name, resurs.ID resurs_id, otdel.ID otdel_id, otdel.NAME otdel_name
                FROM `okb_db_zadanres` zadan
                LEFT JOIN okb_db_resurs resurs ON resurs.ID = zadan.ID_resurs
                LEFT JOIN okb_db_shtat shtat ON shtat.ID_resurs = zadan.ID_resurs
                LEFT JOIN okb_db_otdel otdel ON shtat.ID_otdel = otdel.ID
                WHERE 
                zadan.SMEN = $shift
                AND
                zadan.DATE = $datestring
                AND
                otdel.ID = $dep_id
                GROUP BY resurs_id
                ORDER BY otdel.NAME, resurs.NAME
                 ";


                 //echo $query;

                    $stmt = $pdo->prepare( $query );
                    $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }

    $data = [];

    while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
    {
        if( 1 * $row -> resurs_id )
        {
          $data[ $row -> otdel_id ]['name'] = conv( $row -> otdel_name );
          $data[ $row -> otdel_id ]['id'] = conv( $row -> otdel_id );
          $data[ $row -> otdel_id ]['childs'][] = $row -> resurs_id ;
        }
    }

  try
    {
        $query = "
                    SELECT otdel.NAME dep_name
                    FROM `okb_db_otdel` otdel
                    WHERE otdel.ID = $dep_id
                 ";

                    $stmt = $pdo->prepare( $query );
                    $stmt -> execute();
    }

    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
    }

    if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
        $dep_name = $row -> dep_name;


  try
    {
        $query = "SELECT NAME chief_name FROM `okb_db_shtat` WHERE `ID_otdel`=$dep_id AND `BOSS` = 1";

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
	
	if ($dep_id == 82) {
		$chief_name = conv('Мальцев А. И.');
	}

	if ( $dep_id == 63 ) {
		$chief_name = conv('Устьянцев Н. Н.');
	}
	
	if ($dep_id == 108 || $dep_id == 78) {
		$chief_name = conv('Седнев С. В.');
	}
	// 2.4 Участок механической обработки - мальцев 
	// 2.6 Участок доводки, сборки, упаковки -- устьянцев
      // else
      //     $chief_name = conv("Филоненко С.А."); 

$str = "<div class='container table_div'>";

$pass = 0 ;
$res_data = [];

  foreach( $data AS $key => $val )
  {
      foreach( $val['childs'] AS $ckey => $cval ) 
      {
        
        $cp = new LaborRegulationsViolationItem( $pdo, $cval, $date, $shift );
        //if( $cp -> GetViolationCount() )
        if( !$cp -> IsCollapsed() )
            $res_data[] = $cval;
     }
  }

$page_count = ceil( count( $res_data ) / 2 );
$page = 1;

foreach( $res_data AS $key => $val ) 
{
  if( ! ( $key % 3 ) )
      $str .= "<span class='more'></span>".caption( $page ++, $page_count, $dep_name, $ourdate, $shift );

  $cp = new LaborRegulationsViolationItem( $pdo, $val, $date, $shift );
  $str .= $cp -> GetPrintTable();
}
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
echo $str;

function caption( $page, $page_of, $dep_name, $ourdate, $shift )
{
  $str = "<div class='row'>
                 <div class='col-sm-12'>
                    <h2>".conv("Нарушения трудового распорядка от $ourdate за $shift смену.")."</h2>
                </div>
        </div>";// <div class='row'>
  $str .= "<div class='row'>
                 <div class='col-sm-10'>
                    <h4>".conv("Подразделение : $dep_name")."</h4>
                </div>
                <div class='col-sm-2 page_of'>
                    <h4 class='float-right'>".conv("Стр : $page из $page_of")."</h4>
                </div>
        </div>"; // <div class='row'>

  return $str ;
}