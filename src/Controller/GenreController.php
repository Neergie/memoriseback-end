<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Genre;
use Symfony\Component\HttpFoundation\Request;

class GenreController extends AbstractController
{
    #[Route('/genre/{route_name}', name: 'app_genre', methods: ['POST'])]
    public function index(string $route_name, EntityManagerInterface $entityManager): JsonResponse   
    {
        $genre = new Genre();
        $genre->setName($route_name);

        $entityManager->persist($genre);
        $entityManager->flush();

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/GenreController.php',
            
        ]);
    }
}
