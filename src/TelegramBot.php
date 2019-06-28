<?php

require_once 'vendor/autoload.php';

use Telegram\Bot\Api;
use thewulf7\travelPayouts\Travel;

class TelegramBot
{
    protected $bot = null;
    protected $commands = [
        '/start' => 'CommandStart',
        '/startFind' => 'CommandStartFindFlight',
        '/find' => 'CommandFindFlight'
    ];
    protected $botResult = null;
    protected $chatId = null;
    protected $userName = null;
    protected $userMessage = null;
    protected $userCommand = null;

    protected $defaultKeyboard = [
        ['Найти авиабилет'],
        ['Отобразить статистику поиска']
    ];

    public function __construct(string $token)
    {
        try {
            $this->bot = new Telegram\Bot\Api($token);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function Load()
    {
        try {
            $this->botResult = $this->bot->getWebhookUpdates();
        } catch (Exception $e) {
            $this->botResult = null;
        }

        if ($this->botResult) {
            $this->InitBotProperties();
            $this->HandleCommand();
        } else {
            throw new Exception("Unable to load telegram api");
        }
    }

    private function HandleCommand()
    {
        if (isset($this->commands[$this->userCommand])) {
            $command = $this->commands[$this->userCommand];
            $this->$command();
        } else {
            $this->CommandDefault();
        }
    }

    private function InitBotProperties()
    {
        $this->chatId = $this->botResult['message']['chat']['id'];
        $this->userName = $this->botResult['message']['from']['username'];
        $this->userMessage = $this->botResult['message']['text'];
        $this->userCommand = $this->ParseCommandName();
    }

    private function BotMail(string $message, string $keyboard = null)
    {
        $this->bot->sendMessage([
            'chat_id' => $this->chatId,
            'text' => $message,
            'reply_markup' => $keyboard
        ]);
    }

    private function ParseCommandName(): string
    {
        return strtok($this->userMessage, ' ');
    }

    private function CommandStart()
    {
        $keyboard = $this->bot->replyKeyboardMarkup
        (
            [
                'keyboard' => $this->defaultKeyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => false
            ]
        );

        $this->BotMail("Hello, {$this->userName}", $keyboard);
    }

    private function CommandStartFindFlight()
    {
        $keyboard = $this->bot->replyKeyboardMarkup
        (
            [
                'keyboard' => $this->defaultKeyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => false
            ]
        );

        $this->BotMail("Введите команду вида: \"/find <Город вылета> <Город прилёта> <Дата вылета> <Дата возвращения> \", {$this->userName}", $keyboard);
    }

    private function CommandFindFlight()
    {
        $keyboard = $this->bot->replyKeyboardMarkup
        (
            [
                'keyboard' => $this->defaultKeyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => false
            ]
        );

        $this->BotMail("/startFind Поиск авиабилеты", $keyboard);
    }

    private function CommandDefault()
    {
        $keyboard = $this->bot->replyKeyboardMarkup
        (
            [
                'keyboard' => $this->defaultKeyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => false
            ]
        );

        $this->BotMail("Неизвестная команда. Пожалуйста, воспользуйтесь меню ниже.", $keyboard);
    }
}

/*
 * Todo
 * - проверить может ли отсутствовать username в telegram
 * - лучше сделать привязку по id
 *
 * */