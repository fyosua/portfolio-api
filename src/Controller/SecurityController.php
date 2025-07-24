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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

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

    #[Route('/api/auth/validate', name: 'api_auth_validate', methods: ['GET'])]
    public function validate(
        Request $request,
        JWTTokenManagerInterface $jwtManager,
        TokenStorageInterface $tokenStorage
    ): JsonResponse
    {
        try {
            // Get the Authorization header
            $authHeader = $request->headers->get('Authorization');
            
            if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
                return new JsonResponse([
                    'valid' => false,
                    'message' => 'Missing or invalid Authorization header'
                ], 401);
            }

            // Extract the token
            $token = substr($authHeader, 7); // Remove "Bearer " prefix

            if (empty($token)) {
                return new JsonResponse([
                    'valid' => false,
                    'message' => 'Empty token'
                ], 401);
            }

            // Validate token structure and signature
            try {
                $payload = $jwtManager->parse($token);
            } catch (\Exception $e) {
                return new JsonResponse([
                    'valid' => false,
                    'message' => 'Invalid token format or signature'
                ], 401);
            }

            // Check if token is expired
            $currentTime = time();
            if (isset($payload['exp']) && $payload['exp'] < $currentTime) {
                return new JsonResponse([
                    'valid' => false,
                    'message' => 'Token has expired'
                ], 401);
            }

            // Get current user from token storage (if authenticated)
            $tokenObj = $tokenStorage->getToken();
            if (!$tokenObj || !$tokenObj->getUser()) {
                return new JsonResponse([
                    'valid' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $user = $tokenObj->getUser();

            // Return success with user info
            return new JsonResponse([
                'valid' => true,
                'message' => 'Token is valid',
                'user' => [
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                    'roles' => $user->getRoles()
                ],
                'expires_at' => $payload['exp'] ?? null
            ], 200);

        } catch (AuthenticationException $e) {
            return new JsonResponse([
                'valid' => false,
                'message' => 'Authentication failed: ' . $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            return new JsonResponse([
                'valid' => false,
                'message' => 'Token validation error: ' . $e->getMessage()
            ], 500);
        }
    }
}
