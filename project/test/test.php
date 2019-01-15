<link rel="stylesheet" href="/project/semifin_invoices/css/bootstrap.min.css" media="screen">

<?php
error_reporting( E_ALL );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.AbstractBinaryTree.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemDiscussion.php" );

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


// $str = "<h3>".conv("Информация о сотруднике")."</h3>";
// $str .= "<div class='container'>";
// $str .= "<div class='row'>";

// $str .= "<div class='col-lg-2'>";
// $str .= "<span>".conv("ФИО")."</span>";
// $str .= "</div>"; // <div class='col-lg-12'>

// $str .= "<div class='col-lg-10'>";
// $str .= "<input class='user_name' />";
// $str .= "</div>"; // <div class='col-lg-12'>

// $str .= "</div>"; // <div class='row'>
// $str .= "</div>"; // <div class='container'>

// echo $str;

// $el = new AbstractBinaryTree( $pdo, 'dss_discussions', 'id', 'parent_id', 'id' );
// debug( $el -> GetLocTree( 1 ));

// $el = new DecisionSupportSystemDiscussion( $pdo, 428, 1 );
// debug( $el );
// echo join(",", $el -> GetIDs());

$arg = 'T';
$vehicle = 
    ( $arg == 'B' ) ? 'bus' : ( $arg == 'A' ) ? 'airplane' : ( $arg == 'T' ) ? 'train' : ( $arg == 'C' ) ? 'car' : ( $arg == 'H' ) ? 'horse' : 'feet' ;

$vehicle = 'feet';
switch( $arg )
{
    case 'B' : $vehicle = 'bus'; break ;
    case 'A' : $vehicle = 'airplane'; break ;
    case 'T' : $vehicle = 'train'; break ;        
    case 'C' : $vehicle = 'car'; break ;
    case 'H' : $vehicle = 'horse'; break ;    
}

echo "$vehicle<br>";

if( 123 == "123foo")
    echo "YEAH!";
        else
            echo "NO!";

if( "123" == "123foo")
    echo "YEAH!";
        else
            echo "NO!";

//echo $arg == 'T' ? 'train' : 'no train' ;

?>
<script type="text/javascript" src="/js/luxon/luxon.min.js"></script>
<script>
var DateTime = luxon.DateTime;


var dt = DateTime.local();
var f = {month: 'short', day: 'numeric', year: 'numeric'};
console.log( dt.setLocale('ru-RU').toLocaleString(f))

</script>