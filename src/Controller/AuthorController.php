<?php

namespace App\Controller;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Model\Dto\AuthorDto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

#[IsGranted('ROLE_ADMIN')]
class AuthorController extends AbstractController
{
    #[Route('/author/add', name: 'add_author', methods: ['POST'])]
    public function add_author(#[MapRequestPayload] AuthorDto $authorDto, EntityManagerInterface $entityManager): JsonResponse
    {
        $author = new Author();
        $author->setFirstname($authorDto->firstname);
        $author->setLastname($authorDto->lastname);
        $author->setBirthday($authorDto->birthday);

        $entityManager->persist($author);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }

    #[Route('/authors', name: 'list_authors', methods: ['GET'])]
    public function list_genres(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $author = $entityManager->getRepository(Author::class)->findAll();

        return JsonResponse::fromJsonString($serializer->serialize($author, 'json'));
    }
}