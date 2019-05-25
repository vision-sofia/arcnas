<?php

namespace App\Controller\API;

use App\Entity\User\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/rm-test", name="api.rm-test.")
 */
class RemoteLoginTestController extends AbstractController
{
    protected $tokenStorage;
    protected $authenticationManager;
    protected $passwordEncoder;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->passwordEncoder = $passwordEncoder;
        $this->authenticationManager = $authenticationManager;
    }

    /**
     * @Route("", name="index")
     */
    public function index()
    {
        $username = 'demo';
        $password = 'demo';

        /** @var User $user */
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $username]);

        if ($user === null) {
            return new JsonResponse(['user not found']);
        }

        if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
            return new JsonResponse(['authentication error']);
        }

        $unauthenticatedToken = new UsernamePasswordToken('', '', 'main');
        $unauthenticatedToken->setUser($user);

        $this->tokenStorage->setToken($this->authenticationManager->authenticate($unauthenticatedToken));

        return $this->redirectToRoute('app.index');
    }
}
