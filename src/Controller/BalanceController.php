<?php

namespace App\Controller;

use App\AppLogger;
use App\Provider\BalanceProvider;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class BalanceController extends AbstractController
{
    /**
    * @Route("/acc_bal", name="acc_bal")
    */
    public function showAction(Request $request, BalanceProvider $provider, SerializerInterface $serializer)
    {
      $accountId = $request->get('accountId');
      $balances = $provider->loadBalancesByAccountId($accountId);
      $response = new Response($serializer->serialize(array('balances' => $balances), 'json'));
      return $response;
    }
}
?>