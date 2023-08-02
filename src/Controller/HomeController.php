<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        $prenoms = ['Lior' => 31, 'Joseph' => 12, 'Anne' => 55];
        return $this->render(
            'home/index.html.twig',
            [
                'title' => 'Bonjour à tous',
                'age' => 31,
                'tableau' => $prenoms
            ]
        );
    }
}
