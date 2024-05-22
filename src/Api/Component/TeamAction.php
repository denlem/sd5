<?php
declare(strict_types = 1);

namespace App\Api\Component;

use App\Responder\Component\TeamResponder;
use App\Responder\ExceptionJsonResponder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class TeamAction
{
    #[OA\Get(path: '/api/component/team/9247354')]
    #[OA\Response(response: '200', description: 'Team data')]
    #[Route(
        path: '/team/{teamId}',
        name: 'api_component_team',
        methods: ['GET']
    )]
    public function __invoke(
        Request $request,
        int $teamId,
        TeamResponder $teamResponder,
        ExceptionJsonResponder $exceptionResponder,
    ): JsonResponse {
        try {
            return new JsonResponse($teamResponder($teamId));
        } catch (\Throwable $exception) {
            return $exceptionResponder($exception);
        }
    }
}
