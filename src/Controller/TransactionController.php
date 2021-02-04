<?php

namespace App\Controller;

use App\AppLogger;
use App\Entity\FundTransfer;
use App\Form\FundTransferType;
use App\Provider\BalanceProvider;
use App\Provider\FundTransferProvider;
use App\Provider\TransactionProvider;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends AbstractController
{
    /**
    * @Route("/acc_tran", name="acc_tran")
    */
    public function showAction(Request $request, TransactionProvider $provider)
    {
        $accountId = $request->get('accountId');
        $trans = $provider->loadTransactionsByAccountId($accountId);
        
        return $this->render('security/transactions.html.twig', array(
            'transactions' => $trans
        ));
    }
}
?>