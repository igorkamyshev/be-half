<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Band;
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
    /** @var  Api */
    private $telegram;

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

        $this->telegram = new Api($telegramApiKey);

        $request = $this->telegram->getWebhookUpdates();

        $text = $request["message"]["text"];

        try {
            $firstWord = strtolower(substr($text, 0, strpos($text, " ")));
        } catch (\Exception $e) {
            $firstWord = null;
        }

        $chatId = $request["message"]["chat"]["id"];
        $name = $request["message"]["from"]["username"];

        $user = $lm->getOrCreateUser([
            'chatId' => $chatId,
            'name'   => $name,
        ]);

        if ($text) {
            switch ($text) {
                case '/start':
                    $this->handleStart($chatId);
                    break;
                case 'Создать группу':
                    $this->handleCreateBand($chatId, $user);
                    break;
            }
            switch ($firstWord) {
                case 'вступить':
                    $this->handleJoinBand($chatId, $text, $user);
                    break;
            }
        } else {

        }

        return new Response($apiKey);
    }

    private function handleStart($chatId)
    {
        $replyMarkup = $this->telegram->replyKeyboardMarkup([
            'keyboard' => [
                ["Создать группу"],
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);
        $this->telegram->sendMessage([
            'chat_id'      => $chatId,
            'text'         => 'Здравствуйте! Я – be-half, помогу следить за тратами "надвоих".',
        ]);
        $this->telegram->sendMessage([
            'chat_id'      => $chatId,
            'text'         => 'Для начала, создайте группу, или присоединитесь к существующей.',
        ]);
        $this->telegram->sendMessage([
            'chat_id'      => $chatId,
            'text'         => 'Чтобы присоедениться к группе отправьте мне: вступить #, где # – индивидуальный номер группы.',
            'reply_markup' => $replyMarkup,
        ]);
    }

    private function handleCreateBand($chatId, User $user)
    {
        $band = $user->getBand();

        if ($band) {
            $this->telegram->sendMessage([
                'chat_id'      => $chatId,
                'text'         => 'Вы уже состоите в группе! Индивидуальный номер – ' . $band->getId(),
            ]);
        } else {
            /** @var LoanManager $lm */
            $lm = $this->get('loan_manager');

            $band = $lm->createBand($user);

            $this->telegram->sendMessage([
                'chat_id'      => $chatId,
                'text'         => 'Группа создана! Индивидуальный номер – ' . $band->getId(),
            ]);
        }
    }

    private function handleJoinBand($chatId, $text, User $user) {
        $band = $user->getBand();

        if ($band) {
            $this->telegram->sendMessage([
                'chat_id'      => $chatId,
                'text'         => 'Вы уже состоите в группе! Индивидуальный номер – ' . $band->getId(),
            ]);
        } else {
            /** @var LoanManager $lm */
            $lm = $this->get('loan_manager');

            try {
                $bandId = intval(substr($text, strpos($text, " ")));
            } catch (\Exception $e) {
                $bandId = 0;
            }

            $band = $this
                ->getDoctrine()
                ->getRepository(Band::class)
                ->find($bandId);

            if ($band) {
                $band = $lm->joinBand($band, $user);

                $this->telegram->sendMessage([
                    'chat_id'      => $chatId,
                    'text'         => 'Вы успешно присоеденились к группе!'
                ]);
            } else {
                $this->telegram->sendMessage([
                    'chat_id'      => $chatId,
                    'text'         => 'Группы с таким индивидуальным номером не существует.'
                ]);
            }
        }
    }
}