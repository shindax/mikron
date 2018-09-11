<?php
// doc : http://phpword.readthedocs.io/en/latest/

error_reporting(0);
ini_set('display_errors', false );

require_once( $_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/common_functions.php" );
global $user, $pdo ;

$user_id = $_GET['user_id'];
$list = $_GET['list'];
$id = $_GET['id'];
$name = $_GET['name'];
$op_list = conv( $_GET['op_list'] );

$dse_arr = [];
$oper_arr = [];

// Get user operations
try
{
  $query ="SELECT LOWER( NAME ) AS name FROM `okb_db_oper` WHERE ID IN( $op_list )";
  $stmt = $pdo->prepare( $query );
  $stmt -> execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
}
 while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
  {
    $oper_arr[] = $row -> name ;
  }

$op_list = join(", ", $oper_arr );
  
// Get user name
try
{
  $query ="SELECT FIO FROM `okb_users` WHERE ID = $user_id";
  $stmt = $pdo->prepare( $query );
  $stmt -> execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
}
 if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
 	$user_name = $row -> FIO ;

// Get contracting organization name
try
{
  $query ="SELECT 
        clients.NAME client_name
        FROM okb_db_entrance_control_items items
        LEFT JOIN okb_db_entrance_control_pages pages ON pages.id = items.control_page_id 
        LEFT JOIN okb_db_clients clients ON clients.id = pages.client_id
        WHERE items.id IN ( $list )
        LIMIT 1
        ";
  $stmt = $pdo->prepare( $query );
  $stmt -> execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
}

 if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
     $client_name = $row -> client_name ;
      else
        $client_name = '';

try
{
  $query ="SELECT 
  			items.dse_name dse_name, 
  			items.dse_draw dse_draw,
  			items.count count,  			
  			zak.NAME zak_name, 
  			zak.DSE_NAME zak_dse_name, 
  			zak_type.description zak_type 
  		  FROM okb_db_entrance_control_items items 
			  LEFT JOIN okb_db_zakdet ON items.order_item_id = okb_db_zakdet.ID 
        LEFT JOIN okb_db_zak zak ON okb_db_zakdet.ID_zak = zak.ID 
			  LEFT JOIN okb_db_zak_type zak_type ON zak_type.id = zak.TID
  		  WHERE items.id IN ( $list )";
  $stmt = $pdo->prepare( $query );
  $stmt -> execute();
}
catch (PDOException $e)
{
  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
}
 
 while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
 {
     $dse_arr[] = [ 
     				'name' => $row -> dse_name, 
     				'zak_name' => $row -> zak_type."-".$row -> zak_name,
     				'zak_dse_name' => $row -> zak_dse_name,
     				'dse_draw' => $row -> dse_draw, 
     				'count' => $row -> count, 
     			  ];
 }

$phpWord = new  \PhpOffice\PhpWord\PhpWord();

 $phpWord->setDefaultFontName('Times New Roman');
 $phpWord->setDefaultFontSize(14);

 $properties = $phpWord->getDocInfo();

 $now_year = date("Y");
 $now_month = date("m");
 $now_day = date("d");

 $properties->setCreator( $user_name );
 $properties->setCompany('ООО "ОКБ Микрон"');
 $properties->setTitle('Служебная записка');
 $properties->setDescription('Служебная записка');
 $properties->setCategory('Входной контроль');
 $properties->setLastModifiedBy( $user_name );
 $properties->setCreated(mktime(0, 0, 0, $now_month, $now_day, $now_year));
 $properties->setModified(mktime(0, 0, 0, $now_month, $now_day, $now_year));
 $properties->setSubject('Служебная записка');
 $properties->setKeywords('Входной контроль');

 $sectionStyle = [
					'orientation' => 'portrait',
 					'marginTop' => \PhpOffice\PhpWord\Shared\Converter::pixelToTwip(10),
 					'marginLeft' => 1200,
 				    'marginRight' => 800,
 				    'colsNum' => 1,
				];
 $phpWord->addTitleStyle(1, ['name'=>'Open sans', 'size' => 16, 'bold' => true ], 
 	[ 'align' => 'center', 'spaceBefore'=> 500, 'spaceAfter'=> 50]);

$phpWord->addTitleStyle(2, ['name'=>'Open sans', 'size' => 12, 'bold' => true ], 
	[ 'align' => 'center', 'spaceBefore'=> 0]); 

$section = $phpWord->addSection($sectionStyle);
// ***************************************************************************
// Add first page header
$header = $section->addHeader();
$header->firstPage();


$tableStyle = [
                'cellMargin'  => 100
              ];

$phpWord->addTableStyle('myTable', $tableStyle );
$table = $header->addTable('myTable');
$table->addRow();
$cell = $table->addCell(400) ;
$cell ->addImage(
    $_SERVER['DOCUMENT_ROOT']."/uses/micron_logo.png",
    ['width' => 200, 'height' => 40, 'align' => 'left']
);

$cell = $table->addCell(500) ;
$cell -> addText("",
			$fontStyle,['align'=>'left','spaceAfter'=> 0] );

$fontStyle = ['name'=>'Open sans','size'=>8, 'bold' => true ];
$cell = $table->addCell(7000, ['valign' => 'center']);

$cell -> addText("Общество с ограниченной ответственностью «ОКБ МИКРОН»",
			$fontStyle,['align'=>'left','spaceAfter'=> 0] );
$cell -> addText("Тел.: +7 (391) 204-04-46, office@okbmikron.ru, okbmikron.ru",
 			$fontStyle, ['align'=>'left','spaceBefore'=> 0 ] );

// ***************************************************************************
$section->addTextBreak();
$table = $section->addTable(['width' => 100]);
$table->addRow();
$cell = $table->addCell(5000,['valign' => 'center']) ;
$cell -> addText("№_____от__________2018г.",
 			['name'=>'Open sans','size'=> 12, 'italic' => true ], 
 			['align'=>'left' ] );
$cell = $table->addCell(5000,['valign' => 'center']) ;
$cell -> addText("Начальнику ОТК",
 			['name'=>'Open sans','size'=> 12, 'italic' => true ], 
 			['align'=>'right', 'spaceAfter'=> 0] );
$cell -> addText("ОКБ Микрон",
 			['name'=>'Open sans','size'=> 12, 'italic' => true ], 
 			['align'=>'right'] );

// ***************************************************************************
$section->addTitle(htmlspecialchars('Служебная записка'), 1 );
$section->addTitle(htmlspecialchars("На проведение входного контроля. Лист № $name" ), 2 );
$section->addText(htmlspecialchars("Прошу осуществить входной контроль по операциям: $op_list следующих изделий:"), ['name'=>'Open sans','size'=> 12 ] );

 // ***************************************************************************

$styleTable = ['borderSize' => 1, 'borderColor' => '000000', 'cellMargin' => 10, 'width' => 100, 'valign' => 'center' ];

$styleCell = ['valign' => 'center', 'align' => 'center', 'spaceAfter'=> 0 ];

$phpWord->addTableStyle('Table', $styleTable, $styleCell);
$table = $section->addTable('Table');
$table->addRow();

$c1 = 300 ;
$c2 = 5000 ;
$c3 = 2000 ;
$c4 = 2000 ;
$c5 = 2000 ;
$c6 = 2000 ;
$c7 = 200 ;
$c8 = 200 ;

$fontStyle = ['align' => 'center', 'name'=>'Open sans','size'=> 11 ];

$cell = $table->addCell( $c1, $styleCell);
$cell->addText(htmlspecialchars('№'), $fontStyle, $styleCell );
$cell->addText(htmlspecialchars('п/п'), $fontStyle, $styleCell );

$table->addCell($c2, $styleCell)->addText(htmlspecialchars('Заказ'), $fontStyle, $styleCell);
$table->addCell($c3, $styleCell)->addText(htmlspecialchars('Наименование'), $fontStyle, $styleCell);
$table->addCell($c4, $styleCell)->addText(htmlspecialchars('Обозначение'), $fontStyle, $styleCell);
$table->addCell($c5, $styleCell)->addText(htmlspecialchars('Материал'), $fontStyle, $styleCell);
$table->addCell($c6, $styleCell)->addText(htmlspecialchars('Толщина'), $fontStyle, $styleCell);
$table->addCell($c7, $styleCell)->addText(htmlspecialchars('Кол. деталей план'), $fontStyle, $styleCell);
$table->addCell($c8, $styleCell)->addText(htmlspecialchars('Кол. деталей факт'), $fontStyle, $styleCell);

foreach ( $dse_arr AS $i => $item ) 
{
    $table->addRow();
    $table->addCell($c1, $styleCell)->addText($i + 1, $fontStyle, $styleCell);
    
    $cell = $table->addCell($c2, $styleCell);
    $cell->addText(htmlspecialchars( $item['zak_name'] ), $fontStyle, $styleCell );
    $cell->addText(htmlspecialchars( $item['zak_dse_name'] ), $fontStyle, $styleCell );

    $table->addCell($c3, $styleCell)->addText(htmlspecialchars( $item['name'] ), $fontStyle, $styleCell);
    $table->addCell($c4, $styleCell)->addText(htmlspecialchars( $item['dse_draw']), $fontStyle, $styleCell);
    $table->addCell($c5, $styleCell)->addText("", $fontStyle, $styleCell);
    $table->addCell($c6, $styleCell)->addText("", $fontStyle, $styleCell);
    $table->addCell($c7, $styleCell, $styleCell)->addText($item['count'], $fontStyle, $styleCell);
    $table->addCell($c8, $styleCell)->addText("", $fontStyle, $styleCell);    
}

// ***************************************************************************

$fontStyle = [ 'name'=>'Open sans','size'=> 12 ];


$section->addTextBreak();
$section -> addText(htmlspecialchars("Предприятие: $client_name"),$fontStyle, ['align'=>'left' ] );
$section -> addText(htmlspecialchars("Склад ОМТС"),$fontStyle, ['align'=>'left' ] );

$section->addTextBreak();
$table = $section->addTable(['width' => 100]);
$table->addRow();
$cell = $table->addCell(5000) ;
$cell -> addText(htmlspecialchars("Руководитель ОВК"),$fontStyle, ['align'=>'left' ] );

$cell = $table->addCell(5000) ;
$cell -> addText(htmlspecialchars("А. Л. Казаченко"), $fontStyle,['align'=>'right']);

  // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord,'Word2007');
  // $objWriter->save('doc.docx');

	$file = "Лист входного контроля № $name.docx";
	header("Content-Description: File Transfer");
	header('Content-Disposition: attachment; filename="' . $file . '"');
	header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Expires: 0');

 	$xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
 	$xmlWriter->save("php://output");

