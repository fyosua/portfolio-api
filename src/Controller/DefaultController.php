<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'api_root', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Yosua Ferdian Portfolio API',
            'version' => '1.0',
            'docs' => '/api',
        ]);
    }
}