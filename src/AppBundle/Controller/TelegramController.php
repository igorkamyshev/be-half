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

        $keyboard = [
            ["Создать группу"],
            ["Вступить в группу"],
            ["Статистика"]
        ];

        if ($text) {
            switch ($text) {
                case '/start':
                    $replyMarkup = $telegram->replyKeyboardMarkup([
                        'keyboard' => $keyboard,
                        'resize_keyboard' => true,
                        'one_time_keyboard' => false
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

//
//        $bot->command('start', function ($message) use ($bot, &$controller) {
//            $answer = '';
//
//            $keyboard = new BotKeyboard([['/create', '/join']], null, true);
//
//            $bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
//        });
//
//        $bot->command('create', function ($message) use ($bot, &$controller) {
//            /** @var User $user */
//            $user = $controller
//                ->getOrCreateUser($message->getChat()->getId());
//
//            $group = $controller
//                ->createGroup($user);
//
//            $answer = 'Группа создана (id ' . $group->getId() . '). Чтобы пригласить друга в группу отправьте ему id. Для создания транзакций отправляйте мне сообщения с суммой долга и комментарием (Напрмиер: 300 булочки на ужин).';
//
//            $bot->sendMessage($message->getChat()->getId(), $answer);
//        });
//
//        $bot->command('join', function ($message) use ($bot, &$controller) {
//            $answer = 'joined!';
//
//            $bot->sendMessage($message->getChat()->getId(), $answer);
//        });
//
//        $bot->run();

        return new Response($apiKey);
    }

    private function getOrCreateUser($chatId) {
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