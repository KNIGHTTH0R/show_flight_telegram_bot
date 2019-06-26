<?php

require_once 'vendor/autoload.php';
require_once 'src/config.inc.php';
require_once 'src/TelegramBot.php';

$bot = new TelegramBot(TELEGRAM_API_TOKEN);
$bot->Load();