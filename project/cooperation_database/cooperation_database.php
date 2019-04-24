<script type="text/javascript" src="/project/cooperation_database/js/cooperation_database.js"></script>
<script type="text/javascript" src="/project/cooperation_database/js/bootstrap.min.js"></script>

<link rel='stylesheet' href='/project/cooperation_database/css/style.css?v=2' type='text/css'>
<link rel='stylesheet' href='/project/cooperation_database/css/bootstrap.min.css?v=2' type='text/css'>

<?php
error_reporting( E_ALL );
// error_reporting( 0 );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}


function GetExpired( $table )
{
    global $pdo;

    try
    {
        $query = "SELECT TIMESTAMPDIFF( MONTH, NOW(), actualization_date ) AS monthes
                  FROM $table
                  WHERE id = 1";
        $stmt = $pdo -> prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
      die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage().". Query : $query");
    }
    
    $row = $stmt->fetch( PDO::FETCH_OBJ );
    return $row -> monthes >= EXPIRE_MONTH ? 'expired' : '';
}

const EXPIRE_MONTH = 3 ;

$form1_expired = GetExpired( 'cooperation_database_form1' ) ;
$form2_expired = GetExpired( 'cooperation_database_form2' ) ;
$form3_expired = GetExpired( 'cooperation_database_form3' ) ;
$form4_expired = GetExpired( 'cooperation_database_form4' ) ;
$form5_expired = GetExpired( 'cooperation_database_form5' ) ;
$form6_expired = GetExpired( 'cooperation_database_form6' ) ;
$form7_expired = GetExpired( 'cooperation_database_form7' ) ;
?>

<div class="container">
  <h1><?= conv("База данных работ кооперационных цен"); ?></h1>
  <br>
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active <?= $form1_expired ?>" data-toggle="tab" href="#coop_Form1" data-form="Form1"><?= conv("Литье"); ?></a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $form2_expired ?>" data-toggle="tab" href="#coop_Form2" data-form="Form2"><?= conv("Термообработка"); ?></a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $form3_expired ?>" data-toggle="tab" href="#coop_Form3"  data-form="Form3"><?= conv("Гибка металла"); ?></a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $form4_expired ?>" data-toggle="tab" href="#coop_Form4"  data-form="Form4"><?= conv("Гальваника и защитные покрытия"); ?></a>
    </li>    
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $form5_expired ?>" data-toggle="tab" href="#coop_Form5"  data-form="Form5"><?= conv("Плазменная и газокислородная резка"); ?></a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $form6_expired ?>" data-toggle="tab" href="#coop_Form6"  data-form="Form6"><?= conv("Лазерная резка"); ?></a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $form7_expired ?>" data-toggle="tab" href="#coop_Form7"  data-form="Form7"><?= conv("Гидроабразивная резка"); ?></a>
    </li>    
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div id="coop_Form1" class="container tab-pane active"></div>
    <div id="coop_Form2" class="container tab-pane fade"></div>
    <div id="coop_Form3" class="container tab-pane fade"></div>
    <div id="coop_Form4" class="container tab-pane fade"></div>
    <div id="coop_Form5" class="container tab-pane fade"></div>
    <div id="coop_Form6" class="container tab-pane fade"></div>    
    <div id="coop_Form7" class="container tab-pane fade"></div>
  </div>
</div>

