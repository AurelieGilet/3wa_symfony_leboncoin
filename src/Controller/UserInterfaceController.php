<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserInterfaceController extends AbstractController
{
    #[Route('/compte', name: 'user_account')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('user_interface/account.html.twig', [
            'controller_name' => 'UserInterfaceController',
            'user' => $user,
        ]);
    }
}
