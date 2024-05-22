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

class LeaguesResponder
{
    private const QUERY = '{
  leagues(request: {startDateTime: %s, endDateTime: %s}) {
    id
    displayName
    region
    startDateTime
    endDateTime
    tournamentUrl
    description
    tier
    matches(request: {take: 100, skip: 0}) {
      id
      startDateTime
      endDateTime
      tournamentId
      radiantTeam {
        id
        name
        url
      }
      direTeam {
        id
        name
        url
      }
    }
    prizePool
    prizePoolPercentages {
      leagueId
      index
      percentage
    }
    battlePass {
      count
      average
    }
    standings {
      leagueId
      teamId
      standing
      prizeAmount
    }
    streams {
      id
    }
    hasLiveMatches
    imageUri
    freeToSpectate
    stopSalesTime
    country
    banner
  }
}';

    public function __construct(
        private readonly StratzDataFetcher $stratzDataFetcher,
        private readonly Formatter $formatter,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function __invoke(int $startDate, int $endDate)
    {
        $requestBody = sprintf(self::QUERY, $startDate, $endDate);

        $response = $this->stratzDataFetcher->fetch($requestBody);

        if (!empty($response['data']['leagues'])) {
            foreach ($response['data']['leagues'] as &$league) {
                $league['startDateTime'] = $this->formatter->timestampToReadableDate(
                    $league['startDateTime']
                );
                $league['endDateTime']   = $this->formatter->timestampToReadableDate(
                    $league['endDateTime']
                );
                if (!empty($league['matches'])) {
                    foreach ($league['matches'] as &$match) {
                        $match['startDateTime'] = $this->formatter->timestampToReadableDate(
                            $match['startDateTime']
                        );
                        $match['endDateTime']   = $this->formatter->timestampToReadableDate(
                            $match['endDateTime']
                        );
                    }
                }
            }
        }

        return $response;
    }
}
