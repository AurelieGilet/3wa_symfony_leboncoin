<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Form\AdvertType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdvertController extends AbstractController
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    private function isRouteInsecure($object) 
    {
        if ($object->getUser() !== $this->getUser()) {
            $this->addFlash('error', "Vous ne pouvez pas modifier cette annonce");
            return $this->redirectToRoute('user_adverts');
        }       

        return false;
    }

    #[Route('/annonce/detail/{id}', name: 'advert_detail')]
    public function advertDetail(Advert $advert)
    {
        return $this->render('advert/advert_detail.html.twig', [
            'controller_name' => 'HomeController',
            'advert' => $advert,
        ]);
    }

    /**
     * @param Request $request
     * 
     * @return Response
     */
    #[Route('/compte/annonces/ajouter', name: 'add_advert')]
    public function addAdvert(Request $request): Response
    {
        $user = $this->getUser();

        $advert = new Advert();

        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $advert->setUser($user);

            $this->em->persist($advert);
            $this->em->flush();

            $this->addFlash('success', "L'annonce a bien été enregistrée");

            return $this->redirectToRoute('user_adverts');
        }

        return $this->render('advert/advert_form.html.twig', [
            'controller_name' => 'AdvertController',
            'form' => $form,
        ]);
    }

    /**
     * @param Request $request
     * @param Advert $advert
     * 
     * @return Response
     */
    #[Route('/compte/annonces/modifier/{id}', name: 'update_advert')]
    public function updateAdvert(Request $request, Advert $advert): Response
    {
        $isRouteInsecure = $this->isRouteInsecure($advert);

        if ($isRouteInsecure) {
            return $isRouteInsecure;
        }

        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($advert);
            $this->em->flush();

            $this->addFlash('success', "L'annonce ". $advert->getTitle() ." a bien été modifiée");

            return $this->redirectToRoute('user_adverts');
        }

        return $this->render('advert/advert_form.html.twig', [
            'controller_name' => 'AdvertController',
            'form' => $form,
        ]);
    }

    /**
     * @param Request $request
     * @param Advert $advert
     * 
     * @return Response
     */
    #[Route('/compte/annonces/publication/{id}', name: 'advert_visibility')]
    public function toggleAdvertVisibility(Request $request, Advert $advert): Response
    {
        $isRouteInsecure = $this->isRouteInsecure($advert);

        if ($isRouteInsecure) {
            return $isRouteInsecure;
        }

        $publishState = "dépubliée";

        $advert->setIsVisible(!$advert->isIsVisible());

        if ($advert->isIsVisible()) {
            $publishState = "publiée";
        }

        $this->em->persist($advert);
        $this->em->flush();
        $this->addFlash('success', "L'annonce ". $advert->getTitle() ." a bien été " . $publishState);

        return $this->redirectToRoute('user_adverts');
    }

    /**
     * @param Advert $advert
     * 
     * @return Response
     */
    #[Route('/admin/annonces/supprimer/{id}', name:"delete_advert")]
    public function deleteAdvert(Advert $advert): Response
    {
        $isRouteInsecure = $this->isRouteInsecure($advert);

        if ($isRouteInsecure) {
            return $isRouteInsecure;
        }

        if ($advert->getPurchase() != null) {
            $this->addFlash('error', "Vous ne pouvez pas supprimer cette annonce car elle fait partie d'un achat");
            return $this->redirectToRoute('user_adverts');
        }

        $this->em->remove($advert);
        $this->em->flush();

        $this->addFlash('success', "L'annonce ". $advert->getTitle() ." a bien été supprimée");

        return $this->redirectToRoute('user_adverts');
    }
}