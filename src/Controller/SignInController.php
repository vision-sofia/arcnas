<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SignInController extends AbstractController
{
    protected $authUtils;

    public function __construct(AuthenticationUtils $authUtils)
    {
        $this->authUtils = $authUtils;
    }

    /**
     * @Route("/sign-in", name="app.sign-in")
     */
    public function index(): Response
    {
#        $error = $this->authUtils->getLastAuthenticationError();

#        $lastUsername = $this->authUtils->getLastUsername();

        return $this->render('sign-in/index.html.twig', [
           # 'last_username' => $lastUsername,
          #  'error'         => $error,
            'destination'   => $this->generateUrl('app.sign-in'),
        ]);
    }


    /**
     * @Route("/sign-in/check", name="app.login.check")
     */
    public function check()
    {
        return $this->redirectToRoute('app.sign-up');
    }
}
