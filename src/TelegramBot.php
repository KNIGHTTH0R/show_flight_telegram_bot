<?php

require_once 'vendor/autoload.php';

use Telegram\Bot\Api;

class TelegramBot
{
    protected $bot = null;
    protected $commands = [
      '/start' => 'CommandStart'
    ];
    protected $botResult = null;
    protected $chatId = null;
    protected $userName = null;
    protected $userMessage = null;
    protected $userCommand = null;

    protected $defaultKeyboard = [
        ['Найти рейс на конкретную дату'],
        ['Найти тур в интервале дат']
    ];

    public function __construct(string $token)
    {
        $this->bot = new Telegram\Bot\Api($token);
    }

    public function Load()
    {
        $this->botResult = $this->bot->getWebhookUpdates();
        if ($this->botResult)
        {
            $this->InitBotProperties();
            $this->HandleCommand();
        }
        else
        {
            echo 'Welcome to telegram bot home page: Show Flight';
        }
    }

    private function HandleCommand()
    {
        if (isset($this->commands[$this->userCommand]))
        {
            $command = $this->commands[$this->userCommand];
            $this->$command();
        }
        else
        {
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

    private function  CommandStart()
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

    private function BotMail(string $message, string $keyboard = null)
    {
        $this->bot->sendMessage([
            'chat_id' => $this->chatId,
            'text' => $message,
            'reply_markup' => $keyboard
        ]);
    }

    private function ParseCommandName() : string
    {
        return strtok($this->userMessage, ' ');
    }
}