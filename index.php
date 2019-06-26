<?php

require_once('vendor/autoload.php');

use Telegram\Bot\Api;

$telegram = new Api('895714546:AAHdtxNN2dTVX03xP5F4hc7WJmfHXq6dzak');
$result = $telegram -> getWebhookUpdates();

$text = $result["message"]["text"];
$chat_id = $result["message"]["chat"]["id"];
$name = $result["message"]["from"]["username"];

$keyboard = [["Answer me"]];

if ($text)
{
    $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Hello, {$name}" ]);
}
else
{
    $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Send some message" ]);
}

