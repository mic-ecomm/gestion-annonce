<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\User;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;



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
     * @IsGranted("ROLE_USER")
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

            $ad->setAuthor($this->getUser());

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
     * @Route("/ads/{slug}/edit", name="ads_edit")
     *²@param Ad $ad L'annonce à éditer
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Cette annonce ne vous appartieent pas, vous ne pouvez pas la modifier")
     * @return Response
     * 
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager)
    {

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
                "Les modifications de l'annonce <strong>" . $ad->getTitle() . "</strong> ont bien été enregistrée !"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render(
            'ad/edit.html.twig',
            [
                'form' => $form->createView(),
                'ad' => $ad
            ]
        );
    }

    /**
     * Affiche les détails d'une annonce individuelle.
     * 
     * @Route("/ad/{slug}", name="ads_show")
     * 
     * @param Ad $ad L'annonce à afficher
     *
     * @return Response
     */
    public function show(Ad $ad)
    {
        return $this->render('ad/show.html.twig', [
            "ad" => $ad
        ]);
    }

    /**
     * Permet de supporimer une annonce
     *
     * @param Ad $ad
     * @param EntityManagerInterface $manager
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * @Security("is_granted('ROLE_USER') and user == ad.getAuthor()")
     * 
     * @return Response
     * 
     */
    public function delete(Ad $ad, EntityManagerInterface $manager) {
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'annonce <strong>". $ad->getTitle() ."</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute('ads_index');
    }
}
