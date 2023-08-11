<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use GuzzleHttp\Psr7\Response as Psr7Response;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer les formulaires de connexion
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response
     * 
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render(
            'account/login.html.twig',
            [
                'hasError' => $error !== null,
                'username' => $username
            ]
        );
    }

    /**
     * Permet de se déconnecter
     * 
     * @Route("/logout", name="account_logout")
     * 
     * @return void
     * 
     */
    public function logout()
    {
        //Ne me rien ici
    }


    /**
     * Qui permet d'afficher le formulaire d'inscription
     * 
     * @Route("/register", name="account_register")
     * 
     * @return Response
     * 
     */
    public function register()
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        
        return $this->render(
            'account/registration.html.twig',
            [
                'form' => $form->createView()
            ]

        );
    }
}
