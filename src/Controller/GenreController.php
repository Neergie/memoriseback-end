<?php

namespace App\Controller;

use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class GenreController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/genre/{route_name}', name: 'add_genre', methods: ['POST'])]
    public function add_genre(string $route_name, EntityManagerInterface $entityManager): JsonResponse
    {
        $genre = new Genre();
        $genre->setName($route_name);

        $entityManager->persist($genre);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }

    #[Route('/genres', name: 'list_genre', methods: ['GET'])]
    public function list_genres(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $genres = $entityManager->getRepository(Genre::class)->findAll();

        return JsonResponse::fromJsonString($serializer->serialize($genres, 'json'));
    }
}