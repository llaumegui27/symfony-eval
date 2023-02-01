<?php

namespace App\Controller;

use App\Entity\Film;
use App\Form\FilmSerieType;
use App\Repository\FilmRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class FilmSerieController extends AbstractController
{
    
    #[Route('/create', name: 'app_add', methods: ['POST'])]
    public function add(Request $request, ManagerRegistry $doctrine): JsonResponse      // Requete sur Postman
    {
        $entityManager = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        $fs = new Film();
        $fs->setNom($data['nom']);
        $fs->setSynopsis($data['synopsis']);
        $fs->setType($data['type']);
        $fs->setDate(DateTime::createFromFormat('Y-m-d', $data['date']));
        
        $entityManager->persist($fs);
        $entityManager->flush();

        return new JsonResponse('status : 201, success');

    }
    
    #[Route('/getall', name: 'app_get_all', methods: ['GET'])]
    public function getAll(FilmRepository $filmRepository) : Response
    {
      
        $fs = $filmRepository->findAll();
        $data = [];
        for($i = 0; $i < count($fs); $i++){
            $data[] = [
                'nom' => $fs[$i]->getNom(),
                'synopsis' => $fs[$i]->getSynopsis(),
                'type' => $fs[$i]->getType(),
                'date' => $fs[$i]->getDate(),
            ];
        }

        return $this->json($data, Response::HTTP_OK);      
    
    }

    #[Route('/get/{id}', name: 'app_get_id', methods: ['GET'])]
    public function getById(FilmRepository $filmRepository, $id): Response
    {
        if (!$filmRepository->find($id)) {
            throw $this->createNotFoundException(
                'Aucun(e) film ou série avec l\'id '.$id
            );
        }
  
        $fs = $filmRepository->find($id);    
        $data = [];

        $data[] = [
            'nom' => $fs->getNom(),
            'synopsis' => $fs->getSynopsis(),
            'type' => $fs->getType(),
            'date' => $fs->getDate(),
        ];
        
        return $this->json($data, Response::HTTP_OK);   
    }

}
