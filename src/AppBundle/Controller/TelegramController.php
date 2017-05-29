<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TelegramController extends Controller
{
    /**
     * @Route("/telegram/{apiKey}", name="telegram")
     */
    public function telegramAction($apiKey)
    {
        return new Response($apiKey);
    }
}