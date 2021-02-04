<?php

namespace App\Security;

use App\AppLogger;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    public const LOGIN_ROUTE = 'app_login';

    private $router;
    private $encoder;

    public function __construct(RouterInterface $router, UserPasswordEncoderInterface $encoder)
    {
        $this->router = $router;
        $this->encoder = $encoder;

        AppLogger::getLogger()->debug('Calling LoginFormAuthenticator constructor');
    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        AppLogger::getLogger()->debug('Calling LoginFormAuthenticator.getCredentials');

        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password')
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        AppLogger::getLogger()->debug('Calling LoginFormAuthenticator.getUser', $credentials);

        // Load / create our user however you need.
        // You can do this by calling the user provider, or with custom logic here.
        $user = $userProvider->loadUserByUsername($credentials['username']);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Username could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        AppLogger::getLogger()->info('Calling LoginFormAuthenticator.checkCredentials', ['username' => $user->getUsername()]);
        
        // Check the user's password or other credentials and return true or false
        // If there are no credentials to check, you can just return true
        $plainPassword = $credentials['password'];
        
        if($this->encoder->isPasswordValid($user, $plainPassword)) {
          
          if($user instanceof AdvancedUserInterface) {
            
            if($user->isEnabled()) {
              return true;
            } else {
              throw new DisabledException();
            }
            
          } else {
            AppLogger::getLogger()->info('$user NOT instanceof AdvancedUserInterface');
            return true;
          }
          
        }
        
        throw new BadCredentialsException();
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        AppLogger::getLogger()->info('Calling LoginFormAuthenticator.onAuthenticationSuccess');

        // if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
        //     return new RedirectResponse($targetPath);
        // }

        $url = $this->router->generate('welcome');

        return new RedirectResponse($url);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    { 
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        $url = $this->router->generate('login');

        return new RedirectResponse($url);
    }

    protected function getLoginUrl()
    {
        return $this->router->generate(self::LOGIN_ROUTE);
    }
}
