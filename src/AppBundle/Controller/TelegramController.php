<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
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

        $telegram = new Api($telegramApiKey);

        $request = $telegram->getWebhookUpdates();

        $text = $request["message"]["text"];
        $chatId = $request["message"]["chat"]["id"];
        $name = $request["message"]["from"]["username"];

        $user = $this->getOrCreateUser($chatId, $name);

        if ($text) {
            switch ($text) {
                case '/start':
                    $replyMarkup = $telegram->replyKeyboardMarkup([
                        'keyboard' => [
                            ["Создать группу"],
                            ["Вступить в группу"],
                        ],
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true,
                    ]);
                    $telegram->sendMessage([
                        'chat_id'      => $chatId,
                        'text'         => 'Здравствуйте! Я – be-half, помогу следить за тратами "надвоих".',
                    ]);
                    $telegram->sendMessage([
                        'chat_id'      => $chatId,
                        'text'         => 'Для начала создайте группу, или присоединитесь к существующей.',
                        'reply_markup' => $replyMarkup,
                    ]);
                    break;
            }
        } else {

        }

        return new Response($apiKey);
    }

    private function getOrCreateUser($chatId, $name) {
        $user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['telegramChatId' => $chatId]);

        if (!$user) {
            $user = (new User())
                ->setTelegramChatId($chatId)
                ->setName($name);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);

            $em->flush();
        }

        return $user;
    }

    private function createGroup(User $user) {
//        $group = $user->getGroup();
//
//        if (!$group) {
//            $em = $this->getDoctrine()->getManager();
//
//            $group = new Group();
//            $em->persist($group);
//
//            $user->setGroup($group);
//              $group->addMember($user);
//
//            $em->flush();
//        }

        $em = $this->getDoctrine()->getManager();
        $group = new Group();
        $em->persist($group);
        $em->flush();

        return $group;
    }
}