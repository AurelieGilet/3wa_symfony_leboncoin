<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wallet;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
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
     * @param UserPasswordHasherInterface $UserPasswordHasher
     * 
     * @return Response
     */
    #[Route('/inscription', name: 'register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
    ): Response
    {
        $user = new User();
        $wallet = new Wallet();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRoles(['ROLE_USER']);

            $wallet->setUser($user);
            $wallet->setAmount(100);

            $this->em->persist($user);
            $this->em->persist($wallet);

            $this->em->flush();

            $this->addFlash('success', "L'utilisateur ". $user->getAlias() . " a bien été enregistré");

            return $this->redirectToRoute('login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param UserPasswordHasherInterface $UserPasswordHasher
     * 
     * @return Response
     */
    #[Route('/compte/infos/modifier', name: 'update_user')]
    public function updateUser(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
    ): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Intelephense indicate an error, but it works perfectly
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->em->persist($user);

            $this->em->flush();

            $this->addFlash('success', "Vos informations ont bien été modifiées");

            return $this->redirectToRoute('user_account');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
