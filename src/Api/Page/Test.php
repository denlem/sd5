<?php
declare(strict_types = 1);

namespace App\Api\Page;

use App\Responder\ExceptionJsonResponder;
use App\Service\DataFetcher\StratzDataFetcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[OA\Info(title: "Demo API", version: "1.0")]
#[OA\Server(url: 'http://apidemo.ddns.net:8081', description: 'Local server')]
//#[OA\Server(url: 'http://api.esports.local:8081', description: 'Local server')]
class Test
{
//    #[OA\Get(path: '/api/page/homepage')]
//    #[OA\Response(response: '200', description: 'The data')]
    #[Route(
        path: '/homepage',
        name: 'api_page_homepage',
        methods: ['GET']
    )]
    public function __invoke(
        Request $request,
        StratzDataFetcher $stratzDataFetcher,
        ExceptionJsonResponder $exceptionResponder,
    ): JsonResponse {
        try {
            $bodyRequest = '{
   match(id: 7710066098) {
      id,
      didRadiantWin,
      durationSeconds,
      startDateTime,
      endDateTime,
      towerStatusRadiant,
      barracksStatusDire,
      clusterId,
      firstBloodTime,
      lobbyType,
      numHumanPlayers,
      gameMode,
      replaySalt,
      isStats,
      tournamentId,
      tournamentRound,
      actualRank,
      averageRank,
      averageImp,
      parsedDateTime,
      statsDateTime,
      leagueId,
      league {
        id
      },
      radiantTeamId,
      radiantTeam {
        id
      },
      direTeamId,
      direTeam {
        id
      },
      seriesId,
      series {
        id
      },
      gameVersionId,
      regionId,
      sequenceNum,
      rank,
      bracket
   }
}';

            return new JsonResponse($stratzDataFetcher->fetch($bodyRequest));
            //            return new JsonResponse(['content' => 'test content']);
        } catch (\Throwable $exception) {
            return $exceptionResponder($exception);
        }
    }
}
