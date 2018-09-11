<link rel="stylesheet" href="/project/semifin_invoices/css/bootstrap.min.css" media="screen">
<link rel="stylesheet" href="/project/semifin_invoices/css/style.css" media="screen">

<style>
.container
{
	width : 1800px !IMPORTANT;
}
.container table
{
	background: #FFF;
}


.container table td, .container table span
{
	font-size:16px !IMPORTANT;
}
.container table td.AL
{
	vertical-align: middle !IMPORTANT;
}

.container table td div.warehouse
{
	display: flex;
	justify-content : space-between ;
}

.container table td div.warehouse div
{
	font-size : 16px !IMPORTANT;
}

.container table th
{
	font-size : 12px !IMPORTANT;
}


.wh_select, .cell_select, .tier_select, .op_select
{
	border-radius: 4px;	
}

.wh_select
{
	width : 120px;	

}

.cell_select
{
	width : 120px;	
}

.tier_select
{
	width : 60px;	
}

.op_select
{
	width : 100%;	
}


</style>

<?php
declare(strict_types=1);

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.SemifinInvoice.php" );

global $pdo ;


function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
//    return $str ;
}


$str = "<div class='container'>";

//             try
//             {
//                 $query	 = 	"SELECT DISTINCT inv_num FROM `okb_db_semifinished_store_invoices` WHERE 1";
//                 $stmt = $pdo->prepare( $query );
//                 $stmt->execute();
//             }
//             catch (PDOException $e)
//             {
//               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
//             }
//             while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
//             	{
//             		$inv = new SemifinInvoice( $pdo, $row -> inv_num );
//             		$str .= $inv -> GetTable();
//             	}


$inv = new SemifinInvoice( $pdo, 281 );
$str .= $inv -> GetTable();

$str .= "</div>";
echo $str ;

