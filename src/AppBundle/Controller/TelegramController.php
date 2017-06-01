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

        /** @var Api $api */
        $api = new Api($telegramApiKey);

        /** @var TelegramBot $bot */
        $bot = $this->get('telegram_bot');

        /** @var LoanManager $lm */
        $lm = $this->get('loan_manager');

        $request = $api->getWebhookUpdates();

        $text = $request["message"]["text"];
        $chatId = $request["message"]["chat"]["id"];
        $name = $request["message"]["from"]["username"];

        $user = $lm->getOrCreateUser([
            'chatId' => $chatId,
            'name'   => $name,
        ]);

        list($command, $params) = $bot->parseTextToCommand($text);

        $response = $bot->handleCommand($command, $params, $user);

        return new Response(implode($params));
    }

    /**
     * @Route("/test", name="test")
     */
    public function test()
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find(12);
        return new Response($user);
    }
}