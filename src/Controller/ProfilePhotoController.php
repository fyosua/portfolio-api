<?php

namespace App\Controller;

use App\Entity\Profile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;

class ProfilePhotoController extends AbstractController
{
    #[Route('/api/profiles/{id}/photo', name: 'api_profile_upload_photo', methods: ['POST'])]
    public function uploadPhoto(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $profile = $entityManager->getRepository(Profile::class)->find($id);

        if (!$profile) {
            return new JsonResponse(['message' => 'Profile not found'], Response::HTTP_NOT_FOUND);
        }

        $uploadedFile = $request->files->get('photo');
        if (!$uploadedFile) {
            return new JsonResponse(['message' => 'No file uploaded (use field name "photo")'], Response::HTTP_BAD_REQUEST);
        }

        // Validate MIME type
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $mimeType = $uploadedFile->getMimeType();
        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            return new JsonResponse([
                'message' => 'Invalid file type. Allowed: JPEG, PNG, WebP',
            ], Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        }

        // Validate file size (5MB cap)
        $maxSize = 5 * 1024 * 1024;
        if ($uploadedFile->getSize() > $maxSize) {
            return new JsonResponse([
                'message' => 'File too large. Maximum size is 5MB',
            ], Response::HTTP_REQUEST_ENTITY_TOO_LARGE);
        }

        // Generate safe filename: never trust original filename
        $extension = match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            default      => 'jpg',
        };
        $filename = ByteString::fromRandom(32)->toString() . '.' . $extension;

        // Ensure upload directory exists
        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/profile-photos';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        // Move file
        $uploadedFile->move($uploadDir, $filename);

        // Delete old photo file if exists
        $oldPhoto = $profile->getPhoto();
        if ($oldPhoto) {
            $oldPath = $this->getParameter('kernel.project_dir') . '/public' . $oldPhoto;
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        // Update entity
        $relativePath = '/uploads/profile-photos/' . $filename;
        $profile->setPhoto($relativePath);
        $entityManager->flush();

        return new JsonResponse([
            'id' => $profile->getId(),
            'photo' => $relativePath,
        ], Response::HTTP_OK);
    }

    #[Route('/api/profiles/{id}/photo', name: 'api_profile_delete_photo', methods: ['DELETE'])]
    public function deletePhoto(
        int $id,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $profile = $entityManager->getRepository(Profile::class)->find($id);

        if (!$profile) {
            return new JsonResponse(['message' => 'Profile not found'], Response::HTTP_NOT_FOUND);
        }

        $oldPhoto = $profile->getPhoto();
        if ($oldPhoto) {
            $oldPath = $this->getParameter('kernel.project_dir') . '/public' . $oldPhoto;
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
            $profile->setPhoto(null);
            $entityManager->flush();
        }

        return new JsonResponse(['message' => 'Photo deleted'], Response::HTTP_OK);
    }
}