<script type="text/javascript" src="/project/labor_regulations_violation/js/constants.js"></script>
<script type="text/javascript" src="/project/labor_regulations_violation/js/labor_regulations_violation.js"></script>
<script type="text/javascript" src="/project/labor_regulations_violation/js/jquery-ui.min.js"></script>

<link rel='stylesheet' href='/project/labor_regulations_violation/css/bootstrap.min.css'>
<link rel='stylesheet' href='/project/labor_regulations_violation/css/style.css'>

<?php

error_reporting( E_ALL );

require_once( "classes/db.php" );
require_once( "classes/class.LaborRegulationsViolationItem.php" );

$user_id = $user["ID"];

if( $user_id == 13 || $user_id == 214 || $user_id == 249 || $user_id == 1 )
	echo "<script>var can_edit = 1</script>";
	 else
		echo "<script>var can_edit = 0</script>";

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

$str  = "<div class='container'>";
$str .= 	"<div class='row'>
				 <div class='col-sm-12'>
					<h2>".conv("Нарушения трудового распорядка за ")."<input class='datepicker' />".conv(" смена : ")."<select class='shift_sel'><option value='1'>1</option><option value='2'>2</option></select></h2>
				</div>
			 </div><hr>
		</div>";

$str .= "<div class='container table_div'>";
//DebugData( $pdo, $str );
$str .= "</div>";

echo $str;


function DebugData2( $pdo, &$str )
{
	$date = "2018-07-31";
	 // $datestring = 20180702;
	 $shift = 1;
	$cp = new LaborRegulationsViolationItem( $pdo, 745, $date, $shift , 1);
	//debug( $cp -> GetData() );
	$str .= $cp -> GetTable();
	$str .= $cp -> GetViolationCount();
}

function DebugData( $pdo, &$str )
{
	 $date = "2018-07-31";
	 $datestring = 20180731;
	 $shift = 1;

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
          ORDER BY otdel.NAME, resurs.NAME
                 ";

        $stmt = $pdo->prepare( $query );
        $stmt -> execute();

        echo $query ;

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

}