<?php
declare(strict_types = 1);

namespace App\Api\Component;

use App\Responder\Component\LeaguesResponder;
use App\Responder\ExceptionJsonResponder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class LeaguesAction
{
    #[OA\Get(path: '/api/component/leagues')]
    #[OA\Response(response: '200', description: 'Leagues data')]
    #[Route(
        path: '/leagues',
        name: 'api_component_leagues',
        methods: ['GET']
    )]
    public function __invoke(
        Request $request,
        LeaguesResponder $leaguesResponder,
        ExceptionJsonResponder $exceptionResponder,
    ): JsonResponse {
        try {
            $startDate = $request->query->getString('start_date');
            $endDate   = $request->query->getString('end_date');

            // print_r($startDate);

            $startDate = $startDate !== '' ? (new \Datetime($startDate))->getTimestamp() : time();
            $endDate   = $endDate !== '' ? (new \Datetime($endDate))->getTimestamp() : time() + 86400*30;

            return new JsonResponse($leaguesResponder($startDate, $endDate));
        } catch (\Throwable $exception) {
            return $exceptionResponder($exception);
        }
    }
}
