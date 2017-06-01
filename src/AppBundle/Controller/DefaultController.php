<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Transaction;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $transactions = $this->getDoctrine()->getRepository(Transaction::class)->findAll();

        $userCount = count($this->getDoctrine()->getRepository(User::class)->findAll());
        $transactionCount = count($transactions);

        $sum = array_reduce($transactions, function ($carry, Transaction $item) {
            $carry += $item->getAmount();
            return $carry;
        });

        return $this->render(
            'landing.html.twig',
            [
                'userCount'        => $userCount,
                'transactionCount' => $transactionCount,
                'sum'              => $sum,
            ]
        );
    }
}
