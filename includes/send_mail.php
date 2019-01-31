<?php

require_once( $_SERVER['DOCUMENT_ROOT']."/includes/phpmailer/PHPMailerAutoload.php" );

	function SendMail( $recipients, $theme, $description )
	{
				$description .= " : ".join(",", $recipients );
		   		$recipients = [ "shindax@okbmikron.ru" ];

	            $mail = new PHPMailer();
	            $mail->CharSet = 'UTF-8';
 
	            $mail->IsSMTP();
	            $mail->Host       = 'smtp.yandex.com';

	            $mail->SMTPSecure = 'ssl';
	            $mail->Port       = 465;
	            $mail->SMTPDebug  = 2;
	            $mail->SMTPAuth   = true;

	            $mail->Username   = 'notice@okbmikron.ru';
	            $mail->Password   = '9ab124557_b12D57a';

	            $mail->isHTML(true);

	            $mail->SetFrom('notice@okbmikron.ru', 'Уведомление с сайта КИС ОКБ Микрон');
	            $mail->Subject = $theme;
	            $mail->MsgHTML($description );

	      
	      foreach($recipients as $recipient) 
	        $mail->AddAddress( $recipient, $recipient);
	    
	    // $mail->AddAddress( 'emv@okbmikron.ru', 'emv@okbmikron.ru');
	    // $mail->AddAddress( 'pimenov.r.a@okbmikron.ru', 'pimenov.r.a@okbmikron.ru');
	    $mail->send();
	}