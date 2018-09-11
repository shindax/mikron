<?php
require_once( "db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/includes/phpmailer/PHPMailerAutoload.php" );

function SendMail( $recipient, $theme, $description )
{
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
            $mail->Subject = $theme;
            $mail->MsgHTML($description );

            $mail->AddAddress( $recipient, 'title2');

            $mail->send();
}


function conv( $str )
{
  return iconv("UTF-8", "Windows-1251", $str );
}

function ajax_conv( $str )
{
    return $str;
}


function DateConvert( $date )
{
    return date("Y-m-d", strtotime( $date ));
}

function MakeDateWithDot( $date, $day = 0 )
{
    $timestamp = strtotime( $date );

    if( $day )
        $out_date = $day ;
        else
            $out_date = date('d', $timestamp);

    $out_date .= ".".date('m', $timestamp) . "." . date('Y', $timestamp);

    return $out_date;
}


function MakeDateWithDash( $date, $day = 0 )
{
    $timestamp = strtotime( $date );
    $out_date = date('Y', $timestamp) . "-" . date('m', $timestamp) . "-";
    if( $day )
        $out_date .= $day ;
    else
        $out_date .= date('d', $timestamp);
    return $out_date;
}
