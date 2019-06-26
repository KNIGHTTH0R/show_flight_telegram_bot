<?php

require_once('vendor/autoload.php');

use Telegram\Bot\Api;

$telegram = new Api('895714546:AAHdtxNN2dTVX03xP5F4hc7WJmfHXq6dzak');
$result = $telegram->getWebhookUpdates();

$text = $result["message"]["text"];
$chat_id = $result["message"]["chat"]["id"];
$name = $result["message"]["from"]["username"];

$keyboard = [["Answer me"]];

if ($text) {
    switch ($text) {
        case '/start':
            {
                $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Hello, {$name}"]);
                $telegram->replyKeyboardMarkup
                (
                    [
                        'keyboard' => [['Найти тур на конкретную дату'], ['Найти тур в интервал дат']],
                        'resize_keyboard' => true,
                        'one_time_keyboard' => false
                    ]
                );
                break;
            }
        default:
            $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Invalid command"]);
    }


} else {
    $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Send some message"]);
}

