<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Psr7\Response as Psr7Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


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
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, MailerInterface $mailer)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $securityCode = rand(1000, 9999);
            $user->setSecurityCode($securityCode);
            $hash = $encoder->encodePassword($user, $user->getHash());
            // Envoyer l'e-mail avec le code d'accès
            $email = (new Email())
            ->from('jmslfoo@gmail.com') // Sender's email address
            ->to('jmslfoo@gmail.com')   // Recipient's email address
            ->subject('Subject of the email')
            ->text('This is the plain text body of the email.') 
            ->html('<p>This is the <b>HTML</b> body of the email.</p>');
            $mailer->send($email);
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();;

            $this->addFlash(
                'success',
                'Votre compte a été bien créer '
            );

            return $this->redirectToRoute('account_login');
        }


        return $this->render(
            'account/registration.html.twig',
            [
                'form' => $form->createView()
            ]

        );
    }
}
