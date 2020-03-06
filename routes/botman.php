<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hi|Hello', BotManController::class.'@startConversation');

$botman->fallback(function($bot) {
    $bot->reply('Sorry, I have not understand what you are saying come up again');
});
