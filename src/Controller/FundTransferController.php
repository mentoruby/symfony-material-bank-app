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

class FundTransferController extends AbstractController
{
    /**
    * @Route("/fund_init", name="fund_init")
    */
    public function showAction(Request $request, BalanceProvider $provider)
    {
        $accountId = $request->get('accountId');
        $balances = $provider->loadBalancesByAccountId($accountId);
        
        $fundTransfer = new FundTransfer();
        $fundTransfer->setAccountId($accountId);
        $form = $this->createForm(FundTransferType::class, $fundTransfer);
        
        return $this->render('security/fund_transfer.html.twig', array(
            'balances' => $balances,
            'form'   => $form->createView()
        ));
    }
    
    /**
    * @Route("/fund_transfer", name="fund_transfer")
    */
    public function saveAction(Request $request, BalanceProvider $balProvider, FundTransferProvider $ftProvider, TransactionProvider $txnProvider)
    {
        $fundTransfer = new FundTransfer();
        $form = $this->createForm(FundTransferType::class, $fundTransfer);
        $form->handleRequest($request);
        
        if($form->isValid()) {
            $ftProvider->saveFund($fundTransfer, $balProvider);
            $txnProvider->saveTransaction($fundTransfer);
            
            // Clear Fields
            $fundTransfer->setCurrency(null);
            $fundTransfer->setAmount(null);
            $fundTransfer->setTransferType(null);
            $form = $this->createForm(FundTransferType::class, $fundTransfer);
            
            $this->addFlash('success', 'Fund Transfer is done successfully!');
        } else {
            $form->addError(new FormError('Form validation is not passed. Something is wrong!!'));
        }
        
        $accountId = $fundTransfer->getAccountId();
        $balances = $balProvider->loadBalancesByAccountId($accountId);

        return $this->render('security/fund_transfer.html.twig', array(
            'balances' => $balances,
            'form'     => $form->createView()
        ));
    }
}
?>