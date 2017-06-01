<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Band;
use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use AppBundle\Utils\LoanManager;
use AppBundle\Utils\TelegramBot;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Telegram\Bot\Api;

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

        $request = (new Api($telegramApiKey))->getWebhookUpdates();

        $text = $request["message"]["text"];
        $chatId = $request["message"]["chat"]["id"];
        $name = $request["message"]["from"]["username"];

        $user = $this->get('loan_manager')->getOrCreateUser([
            'chatId' => $chatId,
            'name'   => $name,
        ]);

        /** @var TelegramBot $bot */
        $bot = $this->get('telegram_bot');

        list($command, $params) = $bot->parseTextToCommand($text);

        $response = $bot->handleCommand($command, $params, $user);

        return new Response('ok');
    }
}