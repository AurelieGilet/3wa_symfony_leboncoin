<?php

namespace App\Controller;

use App\Repository\AdvertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/', name: 'home')]
    public function index(AdvertRepository $advertRepository): Response
    {
        $adverts = $advertRepository->findVisibleAdverts();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'adverts' => $adverts,
        ]);
    }
}
