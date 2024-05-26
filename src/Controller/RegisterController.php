<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'user_register', methods: ['PUT'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = new User();

        $user->setEmail($request->getPayload()->get('email'));
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $request->getPayload()->get('password')
        );
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => $hashedPassword,
            'path' => $user->getEmail(),
        ]);
    }
}
