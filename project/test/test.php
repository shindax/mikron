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

// 14
function hasCollision( $circle, $circlesLists)
{
    $res = "No" ;
   
    foreach( $circlesLists AS $value )
    {
        $x1 = $circle[0];
        $y1 = $circle[1];
        $r1 = $circle[2];

        $x2 = $value[0];
        $y2 = $value[1];
        $r2 = $value[2];

        $delta = $r1 + $r2;

        if( abs( $x1 - $x2 ) <= $delta && abs( $y1 - $y2 ) <= $delta )
            $res = "Yes";
    }

    return $res;
}

 // echo "Res : ".hasCollision([5,5,5],[[5,15,5]]);

// 15.
function sortMat( $mat )
{
    $arr = [];
    $resarr = [];
    $n = count( $mat[0] );
    $m = count( $mat );

    foreach( $mat AS $val )    
        foreach( $val AS $vval )
            $arr[] = $vval;

    sort( $arr );

    while( count( $arr ) )
    {
        $locarr = array_splice( $arr ,0 , $n );
        rsort( $locarr );
        $resarr[] = $locarr;
    }

    return $resarr;
}

$mat = [
    [6, 5, 13],
    [1, 4, 2],
    [3, 9, 8],
    [5, 10, 7]
];

// $arr = sortMat( $mat );
// debug( $arr );


?>

<script type="text/javascript" src="/js/luxon/luxon.min.js"></script>
<script>
var DateTime = luxon.DateTime;


var dt = DateTime.local();
var f = {month: 'short', day: 'numeric', year: 'numeric'};
console.log( dt.setLocale('ru-RU').toLocaleString(f))

</script>