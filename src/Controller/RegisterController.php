<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Model\Dto\RegisterDto;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'user_register', methods: ['POST'])]
    public function register(#[MapRequestPayload] RegisterDto $registerDto, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = new User();

        $user->setEmail($registerDto->email);
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $registerDto->password
        );
        $user->setPassword($hashedPassword);
        $user->setFirstname($registerDto->firstname);
        $user->setLastname($registerDto->lastname);

        try {
            $entityManager->persist($user);
            $entityManager->flush();
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            return new JsonResponse("cet email est déja utilise", Response::HTTP_FORBIDDEN);
        } catch (\Doctrine\DBAL\Exception $e) {
            return new JsonResponse("ça marche pas", Response::HTTP_FORBIDDEN);
        }

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
