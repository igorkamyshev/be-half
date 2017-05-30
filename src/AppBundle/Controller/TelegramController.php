<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \TelegramBot\Api\Client as BotClient;

class TelegramController extends Controller
{
    /**
     * @Route("/telegram/{apiKey}", name="telegram")
     */
    public function telegramAction($apiKey)
    {
        $telegramApiKey = $this->getParameter('app.telegram_api_key');

        if ($apiKey != $telegramApiKey) {
            throw $this->createAccessDeniedException();
        }

        $bot = new BotClient($telegramApiKey);

        $bot->command('start', function ($message) use ($bot) {
            $answer = 'It works!';
            $bot->sendMessage($message->getChat()->getId(), $answer);
        });

        $bot->run();

        return new Response($apiKey);
    }
}