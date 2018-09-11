<?php
//require_once("database.php");
// Including all required classes
require_once('class/BCGFontFile.php');
require_once('class/BCGColor.php');
require_once('class/BCGDrawing.php');

// Including the barcode technology
require_once('class/BCGcode39.barcode.php');

function make_barcode($in_code, $in_factory, $in_name, $in_path, $font, $color_black, $color_white)
{
    $label_code = new BCGLabel($in_code, $font, BCGLabel::POSITION_BOTTOM, BCGLabel::ALIGN_LEFT); // �������� ����� �� ����������� ������ �� ����� �� ��������� (� ��������� �����������)
    $label_factory = new BCGLabel($in_factory, $font, BCGLabel::POSITION_BOTTOM, BCGLabel::ALIGN_RIGHT);
    $label_name = new BCGLabel($in_name, $font, BCGLabel::POSITION_TOP, BCGLabel::ALIGN_LEFT);


    $drawException = null;
    try {
        $code = new BCGcode39();
        $code->setScale(2); // Resolution
        $code->setThickness(20); // Thickness
        $code->setForegroundColor($color_black); // Color of bars
        $code->setBackgroundColor($color_white); // Color of spaces
        $code->setFont($font); // Font (or 0)
        $code->parse($in_code); // Text
        //$code->setChecksum(1); ��� � ����������� ������
        //$code->setDisplayChecksum(1);
        $code->clearLabels(); // ������� ������� �����
        $code->addLabel($label_factory);
        $code->addLabel($label_name);
        $code->addLabel($label_code);
    } catch (Exception $exception) {
        $drawException = $exception;
    }

    /* Here is the list of the arguments
    1 - Filename (empty : display on screen)
    2 - Background color */
    $drawing = new BCGDrawing('', $color_white);
    if ($drawException) {
        $drawing->drawException($drawException);
    } else {
        $drawing->setBarcode($code);
        $drawing->setDPI(100);
        $drawing->draw();
    }

    $drawing->setFilename($in_path);
    $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

}

//db_connect();
$id_parent = $_GET['p0']; // �������� ��������

function get_code_array($id_parent)
{
    mysql_query("SET NAMES cp1251");
    $result = mysql_query(
        sprintf('SELECT
                    CONCAT(
                        rt.S_NAME, -- �������� �����������
                        \' \',
                        m.NAME, -- ��������
                        \' |\',
                        rt.n_param2, -- ��� ���������
                        \' |\',
                        rt.n_param3,
                        \' |\',
                        rt.n_param3
                    )  S_NAME,
                    rt.S_BARCODE
                FROM
                    okb_db_reference_tool rt,
                    okb_db_mat m,
                    (
                        SELECT
                            i.id
                        FROM
                            okb_db_inv_cat_tools i
                        WHERE
                            i.id = %1$d -- ���� ��� ����������� ��������
                        OR pid = %1$d -- (��� ����� ���� ��� ����� ��� � ���)
                    ) child_inv
                WHERE
                    rt.ID_inv_cat_tools = child_inv.id
                    and m.id = rt.id_material
                ORDER BY BINARY(rt.S_BARCODE)',
            $id_parent));
    $code_array = array();

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        array_push($code_array, $row);
    }
    return $code_array;
}

$serv_dir = $_SERVER['DOCUMENT_ROOT'];
//$serv_dir = 'C:/xampp/htdocs';
$dir = $serv_dir.'/okb2/barcode2/images_barcode/'; // ����� � ������������� ����������
$files = scandir($dir); // ���� �� ���������� ����������


for ($i = 0; $i < count($files); $i++) { // ���������� ��� �����
    if (($files[$i] != ".") && ($files[$i] != "..")) { // ������� ������� � ������������ ����������
        $path = $dir . $files[$i];
        $f = unlink($path); // ������� �� ������ ������
    }
}
// Loading Font
$font = new BCGFontFile($serv_dir.'/okb2/barcode2/font/ArialRegular.ttf', 8);
//$font = new BCGFontFile('./font/ArialRegular.ttf', 14);


//$font = new BCGFontFile('./font/ARICYR.TTF', 20);

// The arguments are R, G, B for color.
$color_black = new BCGColor(0, 0, 0);
$color_white = new BCGColor(255, 255, 255);

$factory_name = '��� ������ ';// � ����� ������ ���� ����������� ������!

// Don't forget to sanitize user inputs
//$code = 'T0101001';
//$tool_name = '����� �������� ';
//$path = $dir . "2" . ".png";

$source_array = get_code_array($id_parent);
//$source_array = get_code_array(20);

$i = 0;
foreach ($source_array as $sa):
    $code = $sa['S_BARCODE'];
    $tool_name = $sa['S_NAME'];
    $path = $dir . $i . ".png";
    make_barcode($code, $factory_name, $tool_name, $path, $font, $color_black, $color_white);
    $i++;
endforeach;


//make_barcode($code, $factory_name, $tool_name, $path, $font, $color_black, $color_white);


$files = scandir($dir); // ���� �� ���������� ����������

$files_bar = array();
foreach ($files as $f) {
    if (($f != ".") && ($f != "..")) {
        //array_push($files_bar, 'images_barcode/' . $f);
        array_push($files_bar, 'barcode2/images_barcode/' . $f);
    }
}

include($serv_dir.'/okb2/barcode2/views/stickers.php');

?>