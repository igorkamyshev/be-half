<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use \TelegramBot\Api\Client as BotClient;
use \TelegramBot\Api\Types\ReplyKeyboardMarkup as BotKeyboard;

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
            $answer = 'Здравствуйте!\n Я – be-half, помогу вам следить за тратами "надвоих".\n Для начала создайте группу, или присоединитесь к существующей.';

            $keyboard = new BotKeyboard([['/create', '/join']], null, true);

            $bot->sendMessage($message->getChat()->getId(), $answer, false, null, null, $keyboard);
        });

        $bot->command('create', function ($message) use ($bot, &$controller) {
            /** @var User $user */
            $user = $controller
                ->getOrCreateUser($message->getChat()->getId());

            $group = $controller
                ->createGroup($user);

            $answer = 'Группа создана (id ' . $group->getId() . ').\n Чтобы пригласить друга в группу отправьте ему id.\n Для создания транзакций отправляйте мне сообщения с суммой долга и комментарием (Напрмиер: 300 булочки на ужин).';

            $bot->sendMessage($message->getChat()->getId(), $answer);
        });

        $bot->command('join', function ($message) use ($bot, &$controller) {
            $answer = 'joined!';

            $bot->sendMessage($message->getChat()->getId(), $answer);
        });

        $bot->run();

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
        $group = $user->getGroup();

        if (!$group) {
            $em = $this->getDoctrine()->getManager();

            $group = (new Group())->addMember($user);
            $em->persist($group);

            $user->setGroup($group);
            $em->persist($user);

            $em->flush();
        }

        return $group;
    }
}