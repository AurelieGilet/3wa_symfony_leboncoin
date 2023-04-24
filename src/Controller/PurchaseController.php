<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Advert;
use App\Entity\Purchase;
use App\Form\AdvertType;
use App\Form\PurchaseType;
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

        $user = $this->getUser();
        $wallet = $user->getWallet();

        $purchase = new Purchase();

        $form = $this->createForm(PurchaseType::class, $purchase, [
            'userAddresses' => $user->getAddresses(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingAddress = $form['existing_address']->getData();
            $newAddress['street'] = $form['street']->getData();
            $newAddress['city'] = $form['city']->getData();
            $newAddress['zip'] = $form['zip']->getData();

            if ($existingAddress && ($newAddress['street'] != null || $newAddress['city'] != null || $newAddress['zip'] != null)) {
                $this->addFlash('error', "Vous ne devez renseigner qu'une seule adresse");
                return $this->redirectToRoute('purchase_advert', ['id' => $advert->getId()]);
            }

            if (!$existingAddress && ($newAddress['street'] == null || $newAddress['city'] == null || $newAddress['zip'] == null)) {
                $this->addFlash('error', "Vous devez choisir une adresse existante ou en renseigner une nouvelle");
                return $this->redirectToRoute('purchase_advert', ['id' => $advert->getId()]);
            } 

            $purchase->setUser($user);
            $purchase->setAdvert($advert);

            if ($existingAddress) {
                $purchase->setAddress($existingAddress);
            } else {
                $address = new Address();
                $address->setStreet($newAddress['street'])
                        ->setZip($newAddress['zip'])
                        ->setCity($newAddress['city'])
                        ->setUser($user);

                $this->em->persist($address);

                $purchase->setAddress($address);   
            }

            $wallet->setAmount($wallet->getAmount() - $advert->getPrice());

            $advert->setIsVisible(false);

            $this->em->persist($purchase);
            $this->em->persist($wallet);
            $this->em->persist($advert);

            $this->em->flush();

            $this->addFlash('success', "L'achat a bien été réalisé");

            return $this->redirectToRoute('user_purchases');

            // TODO : display advert status (available/sold) in user adverts interface ?

            $this->addFlash('success', "L'achat a bien été réalisé");

            return $this->redirectToRoute('user_purchases');
        }

        return $this->render('purchase/purchase_form.html.twig', [
            'controller_name' => 'PurchaseController',
            'form' => $form,
            'advert' => $advert,
            'user' => $user,
        ]);
    }
}
