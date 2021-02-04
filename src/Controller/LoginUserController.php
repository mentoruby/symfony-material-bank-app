<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\LoginUserType;
use App\Provider\LoginUserProvider;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginUserController extends AbstractController
{
    /**
     * @Route("/user_open", name="user_open")
     */
    public function openAction() 
    {
        $user = new User();
        $form = $this->createForm(LoginUserType::class, $user, ['action' => 'new']);
        
        return $this->render('security/login_user.html.twig', array(
            'form'   => $form->createView(),
            'mode' => 'new'
        ));
    }
    
    /**
     * @Route("/user_create", name="user_create")
     */
    public function createAction(Request $request, LoginUserProvider $provider, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(LoginUserType::class, $user, ['action' => 'new']);
        $form->handleRequest($request);
        
        if($provider->usernameExist($user->getUsername())) {
          $form['username']->addError(new FormError('Login ID already exists. Please use another one.'));
        }
        
        if($form->isValid()) {
            $provider->createUser($user, $encoder);
            
            $form = $this->createForm(LoginUserType::class, $user, ['disabled' => true]);
            return $this->render('security/login_user.html.twig', array(
                'form'   => $form->createView(),
                'mode' => 'created',
            ));
        } else {
            $form->addError(new FormError('Form validation is not passed. Something is wrong!!'));
            
            return $this->render('security/login_user.html.twig', array(
                'form'   => $form->createView(),
                'mode' => 'new'
            ));
        }
    }
    
    /**
     * @Route("/user_edit", name="user_edit")
     */
    public function editAction(Request $request, LoginUserProvider $provider)
    {
        $username = $request->get('username');
        $user = $provider->loadUserByUsername($username);
        $form = $this->createForm(LoginUserType::class, $user);
        
        return $this->render('security/login_user.html.twig', array(
            'form'   => $form->createView(),
            'mode' => 'edit'
        ));
    }
    
    /**
     * @Route("/user_update", name="user_update")
     */
    public function updateAction(Request $request, LoginUserProvider $provider, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(LoginUserType::class, $user);
        $form->handleRequest($request);
        
        if($form->isValid()) {
            $provider->updateUser($user, $encoder);
            
            $user = $provider->loadUserByUsername($user->getUsername());
            
            $form = $this->createForm(LoginUserType::class, $user, ['disabled' => true]);
            return $this->render('security/login_user.html.twig', array(
                'form'   => $form->createView(),
                'mode' => 'updated',
            ));
        } else {
            $form->addError(new FormError('Form validation is not passed. Something is wrong!!'));
            
            return $this->render('security/login_user.html.twig', array(
                'form'   => $form->createView(),
                'mode' => 'edit'
            ));
        }
    }
    
    /**
     * @Route("/user_list", name="user_list")
     */
    public function listAction(Request $request, LoginUserProvider $provider)
    {
      $users = $provider->loadUsers();
      return $this->render('security/user_list.html.twig', array('users' => $users));
    }
    
    /**
     * @Route("/show_change_password", name="show_change_password")
     */
    public function showChangePasswordAction(Request $request, LoginUserProvider $provider)
    {
        $username = $request->get('username');
        $user = $provider->loadUserByUsername($username);
        $form = $this->createForm(LoginUserType::class, $user, ['action' => 'pwd']);
        
        return $this->render('security/login_user.html.twig', array(
            'form'   => $form->createView(),
            'mode' => 'change_password'
        ));
    }
    
    /**
     * @Route("/update_password", name="update_password")
     */
    public function updatePasswordAction(Request $request, LoginUserProvider $provider, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(LoginUserType::class, $user);
        $form->handleRequest($request);
        
        if($form->isValid()) {
            $provider->updateUser($user, $encoder);
            
            $user = $provider->loadUserByUsername($user->getUsername());
            
            $form = $this->createForm(LoginUserType::class, $user, ['disabled' => true]);
            return $this->render('security/login_user.html.twig', array(
                'form'   => $form->createView(),
                'mode' => 'updated',
            ));
        } else {
            $form = $this->createForm(LoginUserType::class, $user, ['action' => 'pwd']);
            
            $form->addError(new FormError('Form validation is not passed. Something is wrong!!'));
            
            return $this->render('security/login_user.html.twig', array(
                'form'   => $form->createView(),
                'mode' => 'change_password'
            ));
        }
    }
}
?>