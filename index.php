<?php

require_once 'vendor/autoload.php';
require_once 'src/config.inc.php';
require_once 'src/TelegramBot.php';

try
{
    $bot = new TelegramBot(TELEGRAM_API_TOKEN);
    $bot->Load();
}
catch (Exception $e)
{
    echo 'Welcome to telegram bot home page: Show Flight';
    echo "<!--{$e}-->";
}

