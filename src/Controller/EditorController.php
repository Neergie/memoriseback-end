<?php

namespace App\Controller;

use App\Entity\Editor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Model\Dto\EditorDto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

#[IsGranted('ROLE_ADMIN')]
class EditorController extends AbstractController
{
    #[Route('/editor/add', name: 'add_editor', methods: ['POST'])]
    public function add_author(#[MapRequestPayload] EditorDto $editorDto, EntityManagerInterface $entityManager): JsonResponse
    {
        $author = new Editor();
        $author->setName($editorDto->name);

        $entityManager->persist($author);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }

    #[Route('/editors', name: 'list_editors', methods: ['GET'])]
    public function list_genres(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $editors = $entityManager->getRepository(Editor::class)->findAll();

        return JsonResponse::fromJsonString($serializer->serialize($editors, 'json'));
    }
}