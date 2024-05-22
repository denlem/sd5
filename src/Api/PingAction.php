<?php
declare(strict_types = 1);

namespace App\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Возвращает 'pong'
 */
class PingAction extends AbstractController
{
    #[Route(
        path: '/ping',
        name: 'ping',
        methods: ['GET']
    )]
    public function __invoke(): JsonResponse {
        return new JsonResponse(['response' => 'pong']);
    }
}
