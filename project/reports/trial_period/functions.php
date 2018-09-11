<?php

function secondsToTime($inputSeconds) {

    $secondsInAMinute = 60;
    $secondsInAnHour  = 60 * $secondsInAMinute;
    $secondsInADay    = 24 * $secondsInAnHour;

    // extract days
    $days = floor($inputSeconds / $secondsInADay);

    // extract hours
    $hourSeconds = $inputSeconds % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);

    // extract minutes
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);

    // extract the remaining seconds
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);

    // return the final array
    $obj = array(
        'd' => (int) $days,
        'h' => (int) $hours,
        'm' => (int) $minutes,
        's' => (int) $seconds,
    );
    return $obj;
}


function SendMail( $recipients, $theme, $description )
{
              $mail=new PHPMailer();
            $mail->CharSet = 'UTF-8';

 
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

			
			foreach($recipients as $recipient) {
				$mail->AddAddress( $recipient, $recipient);
			}
				$mail->AddAddress( 'emv@okbmikron.ru', 'emv@okbmikron.ru');
				$mail->AddAddress( 'pimenov.r.a@okbmikron.ru', 'pimenov.r.a@okbmikron.ru');

            $mail->send();
}