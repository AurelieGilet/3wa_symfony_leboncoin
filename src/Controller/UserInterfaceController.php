<?php

namespace App\Controller;

use App\Entity\Wallet;
use App\Entity\Address;
use App\Form\WalletType;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserInterfaceController extends AbstractController
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
            $this->addFlash('error', "Vous ne pouvez pas modifier cet élément");
            return $this->redirectToRoute('user_account');
        }       

        return false;
    }
    
    /**
     * @return Response
     */
    #[Route('/compte', name: 'user_account')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('user_interface/account.html.twig', [
            'controller_name' => 'UserInterfaceController',
            'user' => $user,
        ]);
    }

    /**
     * @return Response
     */
    #[Route('/compte/annonces', name: 'user_adverts')]
    public function userAdverts(): Response
    {
        $user = $this->getUser();

        return $this->render('user_interface/adverts.html.twig', [
            'controller_name' => 'UserInterfaceController',
            'user' => $user,
        ]);
    }

    /**
     * @return Response
     */
    #[Route('/compte/achats', name: 'user_purchases')]
    public function userPurchases(): Response
    {
        $user = $this->getUser();

        return $this->render('user_interface/purchases.html.twig', [
            'controller_name' => 'UserInterfaceController',
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * 
     * @return Response
     */
    #[Route('/compte/adresse/ajouter', name: 'add_address')]
    public function addAddress(Request $request): Response
    {
        $user = $this->getUser();

        $address = new Address();

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($user);

            $this->em->persist($address);
            $this->em->flush();

            $this->addFlash('success', "L'adresse a bien été enregistrée");

            return $this->redirectToRoute('user_account');
        }

        return $this->render('user_interface/address_form.html.twig', [
            'controller_name' => 'UserInterfaceController',
            'form' => $form,
        ]);
    }

    /**
     * @param Request $request
     * @param Address $address
     * 
     * @return Response
     */
    #[Route('/compte/adresse/modifier/{id}', name: 'update_address')]
    public function updateAddress(Request $request, Address $address): Response
    {
        $isRouteInsecure = $this->isRouteInsecure($address);

        if ($isRouteInsecure) {
            return $isRouteInsecure;
        }
        
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($address);
            $this->em->flush();

            $this->addFlash('success', "L'adresse a bien été modifiée");

            return $this->redirectToRoute('user_account');
        }

        return $this->render('user_interface/address_form.html.twig', [
            'controller_name' => 'UserInterfaceController',
            'form' => $form,
        ]);
    }

    /**
     * @param Request $request
     * @param Wallet $wallet
     * 
     * @return Response
     */
    #[Route('/compte/portefeuille/crediter/{id}', name: 'update_wallet')]
    public function updateWallet(Request $request, Wallet $wallet): Response
    {
        $isRouteInsecure = $this->isRouteInsecure($wallet);

        if ($isRouteInsecure) {
            return $isRouteInsecure;
        }
        
        $form = $this->createForm(WalletType::class, $wallet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $amountToCredit = $form['amountToCredit']->getData();

            $wallet->setAmount($wallet->getAmount() + $amountToCredit);

            $this->em->persist($wallet);
            $this->em->flush();

            $this->addFlash('success', "Le portefeuille a bien été crédité");

            return $this->redirectToRoute('user_account');
        }

        return $this->render('user_interface/wallet_form.html.twig', [
            'controller_name' => 'UserInterfaceController',
            'form' => $form,
        ]);
    }
}
