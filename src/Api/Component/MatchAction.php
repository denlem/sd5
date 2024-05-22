<?php
declare(strict_types = 1);

namespace App\Api\Component;

use App\Responder\Component\MatchResponder;
use App\Responder\ExceptionJsonResponder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class MatchAction
{
    #[OA\Get(path: '/api/component/match/7710066098')]
    #[OA\Response(response: '200', description: 'Match data')]
    #[Route(
        path: '/match/{matchId}',
        name: 'api_component_match',
        methods: ['GET']
    )]
    public function __invoke(
        Request $request,
        int $matchId,
        MatchResponder $matchResponder,
        ExceptionJsonResponder $exceptionResponder,
    ): JsonResponse {
        try {
            return new JsonResponse($matchResponder($matchId));
        } catch (\Throwable $exception) {
            return $exceptionResponder($exception);
        }
    }
}
