<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
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

        $controller = $this;

        $bot->command('start', function ($message) use ($bot, &$controller) {
            $answer =
                'Здравствуйте! 
                Я – be-half, помогу вам следить за тратами "надвоих". 
                Для начала создайте группу, или присоединитесь к существующей.';

            /** @var User $user */
            $user = $controller->getOrCreteUser($message->getChat()->getId());

            $answer = $answer . $user->getId();

            $bot->sendMessage($message->getChat()->getId(), $answer);
        });

        $bot->run();

        return new Response($apiKey);
    }

    private function getOrCreteUser($chatId) {
        $user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['telegramChatId' => $chatId]);

        if (!$user) {
            $user = (new User())->setTelegramChatId($chatId);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $user;
    }
}