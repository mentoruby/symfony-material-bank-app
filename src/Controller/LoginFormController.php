<?php

namespace App\Controller;

use App\AppLogger;
use App\Service\MessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class LoginFormController extends AbstractController
{
    /**
     * @Route("/", name="app_login")
     * @Route("/login", name="app_login")
     * @Route("/login_check", name="security_login_check")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        AppLogger::getLogger()->debug('Calling LoginFormController.login');
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['username' => $lastUsername, 'error' => $error]);
    }

    /**
     * 
     */
    public function loginCheckAction()
    {

    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $this->get('security.context')->setToken(null);
        $request->getSession()->invalidate();
        
        // Clearing the cookies.
        $cookieNames = [
            $this->container->getParameter('session.name'),
            $this->container->getParameter('session.remember_me.name'),
        ];
        foreach ($cookieNames as $cookieName) {
            $response->headers->clearCookie($cookieName);
        }
    }
}
