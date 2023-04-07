<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Entity\Purchase;
use App\Form\AdvertType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseController extends AbstractController
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * @param Request $request
     * @param Advert $advert
     * 
     * @return Response
     */
    #[Route('/compte/achat/{id}', name: 'purchase_advert')]
    public function purchaseAdvert(Request $request, Advert $advert): Response
    {
        if ($advert->getUser() == $this->getUser()) {
            $this->addFlash('error', "Vous ne pouvez pas acheter votre propre bien");
            return $this->redirectToRoute('advert_detail', ['id' => $advert->getId()]);
        }

        $purchase = new Purchase();

        $form = $this->createForm(PurchaseType::class, $purchase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO : sub purchase amount from user wallet_form
            // TODO : set advert isVisible to false and prevent futur modifications
            // TODO : display advert status (available/sold) in user adverts interface
            $this->em->persist($purchase);
            $this->em->flush();

            $this->addFlash('success', "L'achat a bien été réalisé");

            return $this->redirectToRoute('user_purchases');
        }

        return $this->render('purchase/purchase_form.html.twig', [
            'controller_name' => 'PurchaseController',
            'form' => $form,
            'advert' => $advert,
        ]);
    }
}
