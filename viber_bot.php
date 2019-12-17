<?php

require_once("/var/www/_scripts/viber_bot/vendor/autoload.php");

    $dblocation = "127.0.0.1";   
    $dbname = "okbdb_new"; 
    $charset = 'utf8';
    $dbuser = "okbmikron"; 
    $dbpasswd = "fm2TU9IMTB_hnI0Z"; 

    $dsn = "mysql:host=$dblocation;dbname=$dbname;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

        $pdo = new PDO($dsn,$dbuser, $dbpasswd, $opt);


use Viber\Bot;
use Viber\Api\Sender;

$apiKey = '48b6592a1be7d417-f2936e42f746c7c2-27f66c633bb38621';

// так будет выглядеть наш бот (имя и аватар - можно менять)
$botSender = new Sender([
    'name' => 'ООО ОКБ Микрон',
    'avatar' => 'https://developers.viber.com/img/favicon.ico',
]);

try {
    $bot = new Bot(['token' => $apiKey]);
    $bot
    ->onConversation(function ($event) use ($bot, $botSender) {
        // это событие будет вызвано, как только пользователь перейдет в чат
        // вы можете отправить "привествие", но не можете посылать более сообщений
        return (new \Viber\Api\Message\Text())
            ->setSender($botSender)
            ->setText("Доступные команды:" . PHP_EOL . "    температура");
    })
    ->onText('|температура|si', function ($event) use ($bot, $botSender) {
		global $pdo;
        // это событие будет вызвано если пользователь пошлет сообщение 
        // которое совпадет с регулярным выражением

		$row = $pdo->query("SELECT `temperature`, `humidity`, `date`
						FROM `workplace_environment` WHERE `zone_id` = 3 ORDER BY `id` DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
						
						
        $bot->getClient()->sendMessage(
            (new \Viber\Api\Message\Text())
            ->setSender($botSender)
            ->setReceiver($event->getSender()->getId())
            ->setText("Температура: " . $row['temperature'] . '°C' . PHP_EOL . 'Влажность: ' . $row['humidity'] . '%' . PHP_EOL . 'Дата измерения: ' . $row['date'])
        );
    })
    ->run();
} catch (Exception $e) {
    // todo - log exceptions
}