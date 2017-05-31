<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use AppBundle\Utils\LoanManager;
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
        /** @var LoanManager $lm */
        $lm = $this->get('loan_manager');

        $telegramApiKey = $this->getParameter('app.telegram_api_key');

        if ($apiKey != $telegramApiKey) {
            throw $this->createAccessDeniedException();
        }

        $telegram = new Api($telegramApiKey);

        $request = $telegram->getWebhookUpdates();

        $text = $request["message"]["text"];
        $chatId = $request["message"]["chat"]["id"];
        $name = $request["message"]["from"]["username"];

        $user = $lm->getOrCreateUser([
            'chatId' => $chatId,
            'name'   => $name,
        ]);

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
                case 'Создать группу':
                    if (false) {
                        $telegram->sendMessage([
                            'chat_id'      => $chatId,
                            'text'         => 'Вы уже состоите в группе!',
                        ]);
                    } else {
                        $band = $lm->createBand($user);

                        $telegram->sendMessage([
                            'chat_id'      => $chatId,
                            'text'         => 'Группа создана! Индивидуальный номер – ' . $band->getId(),
                        ]);
                    }
                    break;
            }
        } else {

        }

        return new Response($apiKey);
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