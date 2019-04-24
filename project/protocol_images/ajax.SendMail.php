<?php
header('Content-Type: text/html');

require_once( $_SERVER['DOCUMENT_ROOT']."/includes/send_mail.php" );
require_once( "functions.php" );
error_reporting( 0 );

$id = $_POST['id'];
$what = $_POST['what'];
$date = $_POST['val'];
$email = $_POST['email'];

try
{
    $query ="
                        SELECT dep.department_name, DATE_FORMAT( NOW() , '%d.%m.%Y %H:%i') now
                        FROM okb_db_protocol_images img
                        INNER JOIN okb_db_protocol_departments dep ON img.department_id = dep.ID
                        WHERE img.ID = $id
            ";

    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch (PDOException $e)
{
    die("Can't get data: " . $e->getMessage());
}

$dep_name = "";
$now =  "";

if( $row = $stmt -> fetch(PDO::FETCH_OBJ)  )
    {
        $dep_name = $row -> department_name;
        $now =  $row -> now;
    }

$theme = 'Уведомление с сайта КИС ОКБ Микрон';
$description = "";

switch( $what )
{
    case 'project_plan'     :
                                   $description = "Загружен проект плана.";
                                    break ;
    case 'report'               :
                                   $description = "Загружен отчет.";
                                    break ;
    case 'data-confirmed'  :
                                    $description = "Получено подтверждение проекта плана.";
                                    break;
    default                        :
                                    $description = "Unexpected mail send.<br>What : $what";
                                    break;
}

$description .= "<br>Подразделение : $dep_name. <br>Введенная дата : $date";
$description .= "<br>Дата и время отправки : $now<br>
<a href='http://192.168.1.100/index.php?do=show&formid=221'>Переход на страницу внутреннего сайта</a><br>
<a href='https://internal.okbmikron.ru:777/index.php?do=show&formid=221'>Переход на страницу внешнего сайта</a>";

if( strlen( $description ) )
  SendMail( $email, $theme, $description );

echo iconv("UTF-8", "Windows-1251", $description  );
?>