<?php
namespace App\Controller;

use App\AppLogger;
use App\Entity\Account;
use App\Form\AccountType;
use App\Provider\AccountProvider;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/acc_open", name="acc_open")
     */
    public function openAction() 
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        
        return $this->render('security/account.html.twig', array(
            'account' => $account,
            'form'   => $form->createView(),
            'mode' => 'new'
        ));
    }
    
    /**
     * @Route("/acc_create", name="acc_create")
     */
    public function createAction(Request $request, AccountProvider $provider)
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        
        if($form->isValid()) {
            $provider->createAccount($account);
            
            $form = $this->createForm(AccountType::class, $account, ['disabled' => true]);
            return $this->render('security/account.html.twig', array(
                'account' => $account,
                'form'   => $form->createView(),
                'mode' => 'created',
            ));
        } else {
            $form->addError(new FormError('Form validation is not passed. Something is wrong!!'));
            
            return $this->render('security/account.html.twig', array(
                'account' => $account,
                'form'   => $form->createView(),
                'mode' => 'new'
            ));
        }
    }
    
    /**
     * @Route("/acc_edit", name="acc_edit")
     */
    public function editAction(Request $request, AccountProvider $provider)
    {
        $accountId = $request->get('accountId');
        $account = $provider->loadAccountByAccountId($accountId);
        $form = $this->createForm(AccountType::class, $account);
        
        return $this->render('security/account.html.twig', array(
            'account' => $account,
            'form'   => $form->createView(),
            'mode' => 'edit'
        ));
    }
    
    /**
     * @Route("/acc_update", name="acc_update")
     */
    public function updateAction(Request $request, AccountProvider $provider)
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        
        if($form->isValid()) {
            $provider->updateAccount($account);
            
            $account = $provider->loadAccountByAccountId($account->getAccountId());
            
            $form = $this->createForm(AccountType::class, $account, ['disabled' => true]);
            return $this->render('security/account.html.twig', array(
                'account' => $account,
                'form'   => $form->createView(),
                'mode' => 'updated',
            ));
        } else {
            $form->addError(new FormError('Form validation is not passed. Something is wrong!!'));
            
            return $this->render('security/account.html.twig', array(
                'account' => $account,
                'form'   => $form->createView(),
                'mode' => 'edit'
            ));
        }
    }
    
    /**
     * @Route("/acc_list", name="acc_list")
     */
    public function listAction(Request $request, AccountProvider $provider)
    {
      $accounts = $provider->loadAccounts();
      return $this->render('security/account_list.html.twig', array('accounts' => $accounts));
    }
    
    /**
     * @Route("/acc_change_status", name="acc_change_status")
     */
    public function changeStatusAction(Request $request, AccountProvider $provider, SerializerInterface $serializer)
    {
      $accountId = $request->get('accountId');
      
      // Load Account Details
      $account = $provider->loadAccountByAccountId($accountId);
      
      // Change Account Status
      if($account->getStatus() == 'Active') {
        $account->setStatus('Inactive');
      } else {
        $account->setStatus('Active');
      }
      
      // Save Account Details
      $provider->updateAccount($account);
      
      // Update Response
      $response = new Response($serializer->serialize(array('account' => $account), 'json'));
      return $response;
    }
}
?>