<?php

namespace App\Controller;

use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Already authenticated → redirect to home
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(LoginFormType::class, null, [
            // Tell Symfony to use _username / _password field names expected
            // by the form_login firewall OR keep custom names and configure
            // security.yaml accordingly (see comment in security.yaml below).
        ]);

        // Last authentication error (wrong credentials, etc.)
        $error         = $authenticationUtils->getLastAuthenticationError();
        $lastUsername  = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', [
            'form'          => $form->createView(),
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Intercepted by Symfony's firewall — no code needed here.
        throw new \LogicException('This method should never be reached.');
    }
}
