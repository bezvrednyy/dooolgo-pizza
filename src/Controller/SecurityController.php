<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/signin", name="signin")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function signin(AuthenticationUtils $authenticationUtils): Response
    {
        if ( $this->isGranted('IS_AUTHENTICATED_FULLY') )
        {
            return $this->redirectToRoute('menu');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('signin.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/signout", name="signout")
     */
    public function signout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
