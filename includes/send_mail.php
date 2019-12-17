<?php
require_once( "/var/www/test.okbmikron/www/includes/phpmailer/PHPMailerAutoload.php" );

function SendMail( $recipients, $theme, $description, $attachments = null )
{
	// $description .= " : ".join(",", $recipients );

    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';

    $mail->IsSMTP();
    $mail->Host       = 'smtp.yandex.com';

    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;
    $mail->SMTPDebug  = 0;
    $mail->SMTPAuth   = true;

    $mail->Username   = 'notice@okbmikron.ru';
    $mail->Password   = '4ee62_D0f611';	            

    $mail->isHTML(true);

    $mail->SetFrom('notice@okbmikron.ru', 'Уведомление с сайта КИС ОКБ Микрон');
    $mail->Subject = $theme;
    $mail->MsgHTML($description );
	
	if( $attachments )
  		$mail->AddAttachment( $attachments );
      
      foreach($recipients as $recipient) 
        $mail->AddAddress( $recipient, $recipient);
    	
    // $mail->AddAddress( 'shindax@okbmikron.ru' );
    // $mail->AddAddress( 'emv@okbmikron.ru', 'emv@okbmikron.ru');
    // $mail->AddAddress( 'pimenov.r.a@okbmikron.ru', 'pimenov.r.a@okbmikron.ru');
    
    $mail->send();
}