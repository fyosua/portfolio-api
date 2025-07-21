<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (null === $email || null === $password) {
            return new JsonResponse(['message' => 'Missing credentials'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(['message' => 'Invalid credentials'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = $jwtManager->create($user);

        return new JsonResponse(['token' => $token]);
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (null === $email || null === $password) {
            return new JsonResponse(['message' => 'Missing credentials for new user'], Response::HTTP_BAD_REQUEST);
        }

        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser) {
            return new JsonResponse(['message' => 'User with this email already exists'], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword(
            $passwordHasher->hashPassword($user, $password)
        );

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'User created successfully'], Response::HTTP_CREATED);
    }

    #[Route('/api/users/{id}', name: 'api_user_change_password', methods: ['PATCH'])]
    public function changePassword(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $newPassword = $data['password'] ?? null;

        if (null === $newPassword) {
            return new JsonResponse(['message' => 'New password not provided'], Response::HTTP_BAD_REQUEST);
        }

        $user->setPassword(
            $passwordHasher->hashPassword($user, $newPassword)
        );

        $entityManager->flush();

        return new JsonResponse(['message' => 'Password updated successfully']);
    }
}
