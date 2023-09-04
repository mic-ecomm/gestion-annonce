<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;


class AdController extends AbstractController
{
    /**
     * Affiche la liste de toutes les annonces.
     * 
     * @Route("/ads", name="ads_index")
     * 
     * @param AdRepository $repo Le repository pour les annonces
     * @return Response
     */
    public function index(AdRepository $repo): Response
    {
        // Récupère toutes les annonces à partir du repository
        $ads = $repo->findAll();

        // Affiche la vue "ad/index.html.twig" en passant les annonces à la vue
        return $this->render('ad/index.html.twig', [
            "ads" => $ads,
        ]);
    }

    /**
     * Permet de créer une nouvelle annonce.
     * 
     * @Route("/ads/new", name="ads_create")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {

        $ad = new Ad();

        $image = new Image();

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image) {
                # code...
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>" . $ad->getTitle() . "</strong> a bien été enregistrée"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Affiche les détails d'une annonce individuelle.
     * 
     * @Route("/ad/{slug}", name="ads_show")
     * 
     * @param Ad $ad L'annonce à afficher
     * @return Response
     */
    public function show(Ad $ad)
    { 
        return $this->render('ad/show.html.twig', [
            "ad" => $ad
        ]);
    }
}
