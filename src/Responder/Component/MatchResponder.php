<?php
declare(strict_types = 1);

namespace App\Responder\Component;

use App\Service\DataFetcher\StratzDataFetcher;
use App\Service\Utils\Formatter;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MatchResponder
{
    private const QUERY = '{
  match(id: %s) {
    id
    didRadiantWin
    durationSeconds
    startDateTime
    endDateTime
    towerStatusRadiant
    barracksStatusDire
    clusterId
    firstBloodTime
    lobbyType
    numHumanPlayers
    gameMode
    replaySalt
    isStats
    tournamentId
    tournamentRound
    actualRank
    averageRank
    averageImp
    parsedDateTime
    statsDateTime
    leagueId
    league {
      id
    }
    radiantTeamId
    radiantTeam {
      id
      name
      url
    }
    direTeamId
    direTeam {
      id
      name
      url
    }
    seriesId
    series {
      id
    }
    players(steamAccountId: %s) {
      matchId
      playerSlot
      steamAccountId
      isRadiant
      isVictory
      heroId
      gameVersionId
      kills
      deaths
      assists
      leaverStatus
      numLastHits
      numDenies
      goldPerMinute
      networth
      experiencePerMinute
      level
      gold
      goldSpent
      heroDamage
      towerDamage
      heroHealing
      partyId
      isRandom
      lane
      position
      streakPrediction
      intentionalFeeding
      role
      roleBasic
      imp
    }
    gameVersionId
    regionId
    sequenceNum
    rank
    bracket
  }
}';

    public function __construct(
        private readonly StratzDataFetcher $stratzDataFetcher,
        private readonly Formatter $formatter,
        private readonly int $steamAccountId,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function __invoke(int $matchId)
    {
        $requestBody = sprintf(self::QUERY, $matchId, $this->steamAccountId);

        $response = $this->stratzDataFetcher->fetch($requestBody);

        if (!empty($response['data']['match']['startDateTime'])) {
            $response['data']['match']['startDateTime'] = $this->formatter->timestampToReadableDate(
                $response['data']['match']['startDateTime']
            );
        }
        if (!empty($response['data']['match']['endDateTime'])) {
            $response['data']['match']['endDateTime'] = $this->formatter->timestampToReadableDate(
                $response['data']['match']['endDateTime']
            );
        }
        if (!empty($response['data']['match']['parsedDateTime'])) {
            $response['data']['match']['parsedDateTime'] = $this->formatter->timestampToReadableDate(
                $response['data']['match']['parsedDateTime']
            );
        }
        if (!empty($response['data']['match']['statsDateTime'])) {
            $response['data']['match']['statsDateTime'] = $this->formatter->timestampToReadableDate(
                $response['data']['match']['statsDateTime']
            );
        }

        return $response;
    }
}
