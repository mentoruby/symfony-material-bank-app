<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends AbstractController
{
    /**
    * @Route("/welcome", name="welcome")
    */
    public function showAction(Request $request)
    {
        return $this->render('security/welcome.html.twig');
    }
}
?>