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

    h2
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
    margin:20px 0 20px 0 !important;
    width: 95% !important;
    border-collapse: collapse !important;
}

td, th
{
    padding: 3px !important;
    border: 1px solid black !important; 
}


table
{
    display : none ;
}

table.table
{
    display : block ;
}

td.AC
{
    vertical-align: middle;
    text-align: center;
}

.offset
{
    padding-left : 20px !IMPORTANT;
    margin: -5px 0 0 0 !IMPORTANT;
}

.offset, .offset b
{
    font-size: 14px;
}

.head, .head b
{
     font-size: 16px;
}

#coord_table
{
    margin-top: 0 ! IMPORTANT;
    padding-top: 10px ! IMPORTANT;
}

h2
{
    text-align: center;
}

.more 
{
     page-break-after: always;
} 
}

</style>

<?php
require_once( "classes/db.php" );
require_once( "classes/class.CoordinationPage.php" );

$krz2_id = $_GET['p0'];

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

$cp = new CoordinationPage( $pdo, $user_id, $krz2_id );
$krz2_common_data = $cp -> GetKrz2CommomData();

//debug( $cp -> GetData() );

$str = "<h2>".conv("Лист согласования № ").$krz2_common_data['krz2_name']."</h2>";
$str .= "<div class='container'>";
$str .= "<hr>";

$str .= "<div class='row'>
        <div class='col-sm-12 head'>".conv("СОГЛАСОВАНО : ")."<b>".$cp -> IsPageCoordinated()."</b>".conv(" Директор ООО \"ОКБ Микрон\" <b>Рудых М.Г.</b>")."</div></div><hr>";


$str .= "<div class='row'>
                <div class='col-sm-12 offset hat'>".conv("Заказчик: ")."<b>".$krz2_common_data['krz2_client_name']."</b></div>
        </div>";

$str .= "<div class='row'>
                <div class='col-sm-12 offset'>".conv("Наименование изделия: ")."<b>".$krz2_common_data['krz2_unit_name']."</b></div>
        </div>";

$str .= "<div class='row'>
                <div class='col-sm-12 offset'>".conv("Количество:")."<b>".$krz2_common_data['krz2_count']."</b></div>
        </div>";

$str .= "<div class='row'>
                <div class='col-sm-12 offset'>".conv("№ КРЗ: ")."<b>".$krz2_common_data['krz2_name']."</b></div>
        </div>";

$str .= "<div class='row'>
                <div class='col-sm-12 offset'>".conv("№ Чертежа: ")."<b>".$krz2_common_data['krz2_draw']."</b></div>
            </div>";

$str .= "<div class='row'>
                <div class='col-sm-12 offset'>".conv("Примечание: ")."<b>".$krz2_common_data['krz2_comment']."</b></div>
        </div>";

$str .= "<div class='row'>
                <div id='table_div' class='col-sm-12'>".$cp -> GetPrintTable()."</div>
            </div>";
$str .= "</div>";

echo $str ;
