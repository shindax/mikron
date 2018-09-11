<?php

require('./includes/phpmailer/PHPMailerAutoload.php');
$mail=new PHPMailer();
$mail->CharSet = 'UTF-8';

$body = 'This is the message Тес 123';

$mail->IsSMTP();
$mail->Host       = 'smtp.yandex.com';

$mail->SMTPSecure = 'ssl';
$mail->Port       = 465;
$mail->SMTPDebug  = 2;
$mail->SMTPAuth   = true;

$mail->Username   = 'notice@okbmikron.ru';
$mail->Password   = 'wIMkFw8i2q9sE4nGhEXp';

$mail->isHTML(true);

$mail->SetFrom('notice@okbmikron.ru', 'Уведомление с сайта КИС ОКБ Микрон'); 
$mail->Subject = 'Тест';
$mail->MsgHTML($body);
 
$mail->AddAddress('pimenov.r.a@okbmikron.ru', 'title2'); /* ... */ 
 
$mail->send(); 