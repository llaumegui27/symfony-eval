<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(FilmRepository $filmRepository): Response
    {
        $films = $filmRepository->findBy([], ['date' => 'DESC']);
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'FilmSerieController',
            'films' => $films,
        ]);
    }

}
