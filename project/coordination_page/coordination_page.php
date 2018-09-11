<script type="text/javascript" src="/project/coordination_page/js/constants.js"></script>
<script type="text/javascript" src="/project/coordination_page/js/coordination_page.js"></script>
<script type="text/javascript" src="/project/coordination_page/js/jquery-ui.min.js"></script>

<link rel='stylesheet' href='/project/coordination_page/css/bootstrap.min.css'>
<link rel='stylesheet' href='/project/coordination_page/css/style.css'>

<?php
require_once( "classes/db.php" );
require_once( "classes/class.CoordinationPage.php" );

$user_id = $user["ID"];
$krz2_id = $_GET['id'];

echo "<script>var user_id = $user_id</script>";

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

$str = "<h2>".conv("Лист согласования № ").$krz2_common_data['krz2_name']."</h2>";
$str .= "<div class='container'>";
$str .= "<hr>";

$str .= "<div class='row'>
                <div class='col-sm-2'>".conv("СОГЛАСОВАНО")."</div>
                <div class='col-sm-2'>" ;
                
                $completed = $cp -> IsPageCoordinated();

                if( $completed )
                    $completed_date = $completed ;

                    else
                if( $cp -> IsKrz2Completed() && $user_id == 4 ) // Рудых
                    $completed_date = "<input class='datepicker' id='coordinated_input' data-id='".$cp -> GetPageId()."'/>";
                        else $completed_date = "<input class='datepicker' id='coordinated_input' disabled />";

                $str .= "$completed_date</div>
                <div class='col-sm-8'>".conv("Директор ООО \"ОКБ Микрон\" Рудых М.Г.")."</div>
            </div><hr>";


$str .= "<div class='row'>
                <div class='col-sm-2'>".conv("Заказчик:")."</div>
                <div class='col-sm-8'>".$krz2_common_data['krz2_client_name']."</div>
            </div>";

$str .= "<div class='row'>
                <div class='col-sm-2 gray'>".conv("Наименование изделия:")."</div>
                <div class='col-sm-8 gray'>".$krz2_common_data['krz2_unit_name']."</div>
            </div>";

$str .= "<div class='row'>
                <div class='col-sm-2'>".conv("Количество:")."</div>
                <div class='col-sm-8'>".$krz2_common_data['krz2_count']."</div>
            </div>";



// $str .= "<div class='row'>
//                 <div class='col-sm-2 gray'>".conv("№ КРЗ:")."</div>
//                 <div class='col-sm-8 gray'><a href='index.php?do=show&formid=33&id=".$krz2_common_data['krz2_id']."' target='_blank'>".$krz2_common_data['krz2_name']."</a></div>
//             </div>";

$str .= "<div class='row'>
                <div class='col-sm-2 gray'>".conv("№ КРЗ:")."</div>
                <div class='col-sm-8 gray'><a href='index.php?do=show&formid=232&id=".$krz2_common_data['krz2_det_id']."&&p0=form' target='_blank'>".$krz2_common_data['krz2_name']."</a></div>
            </div>";

$str .= "<div class='row'>
                <div class='col-sm-2'>".conv("№ Чертежа:")."</div>
                <div class='col-sm-8'>".$krz2_common_data['krz2_draw']."</div>
            </div>";

$str .= "<div class='row'>
                <div class='col-sm-2 gray'>".conv("Примечание:")."</div>
                <div class='col-sm-8 gray'>".$krz2_common_data['krz2_comment']."</div>
            </div>";

$str .= "<div class='row'>
                <div class='col-sm-2'>".conv("Путь к папке с документами:")."<button id='doc_path_copy'><img src='uses/file_copy.png' title='".conv("Скопировать путь")."'/></button></div>
                <div class='col-sm-8'><input data-id='".$krz2_common_data['id']."' id='doc_path_input' value='".conv( $krz2_common_data['doc_path'])."' ".( $cp -> IsUserCanPathAdd() ? "" : "disabled")."/></div>
            </div>";


$str .= "<div class='row'>
                <div class='col-sm-1 offset-sm-11'>
                    <button class='btn btn-big btn-primary float-right' id='print_button' data-id='".$cp -> GetKrz2Id()."'>".conv("Распечатать")."</button>
                </div>
            </div>
        ";

 $str .= "<div class='row'>
                <div id='table_div' class='col-sm-12'>".$cp -> GetTable()."</div>
            </div>";
$str .= "</div>";

echo $str ;


