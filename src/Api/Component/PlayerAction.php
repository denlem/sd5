<?php
declare(strict_types = 1);

namespace App\Api\Component;

use App\Responder\Component\PlayerResponder;
use App\Responder\ExceptionJsonResponder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class PlayerAction
{
    #[OA\Get(path: '/api/component/player/25907144')]
    #[OA\Response(response: '200', description: 'Player data')]
    #[Route(
        path: '/player/{steamAccountId}',
        name: 'api_component_player',
        methods: ['GET']
    )]
    public function __invoke(
        Request $request,
        int $steamAccountId,
        PlayerResponder $playerResponder,
        ExceptionJsonResponder $exceptionResponder,
    ): JsonResponse {
        try {
            return new JsonResponse($playerResponder($steamAccountId));
        } catch (\Throwable $exception) {
            return $exceptionResponder($exception);
        }
    }
}
