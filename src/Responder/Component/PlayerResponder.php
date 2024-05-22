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

class PlayerResponder
{
    private const QUERY = '{
  player(steamAccountId: %s) {
    steamAccountId
    identity {
      captainJackIdentityId
      name
      twitter
      facebook
      twitch
      youTube
      isAdmin
      steamAccountId
    }
    steamAccount {
      id
    }
    matchCount
    winCount
    imp
    firstMatchDate
    lastMatchDate
    lastMatchRegionId
    names(skip: 0, take: 5) {
      name
      lastSeenDateTime
    }
    badges {
      badgeId
      slot
      createdDateTime
    }
    behaviorScore
    team {
      firstMatchId
      firstMatchDateTime
      lastMatchId
      lastMatchDateTime
    }
    guildMember {
      guildId
      steamAccountId
      joinDateTime
      matchCount
      winCount
      imp
    }
    activity {
      activity
    }
    isFollowed
    simpleSummary {
      lastUpdateDateTime
      matchCount
      coreCount
      supportCount
      imp
      activity
    }
    performance {
      imp
      rank
      kills
      killsAverage
      deaths
      deathsAverage
      assists
      assistsAverage
      cs
      csAverage
      gpm
      gpmAverage
      xpm
      xpmAverage
    }
    dotaPlus {
      heroId
      steamAccountId
      level
      totalActions
      createdDateTime
    }
    feats(take: 10, skip: 0) {
      type
      value
      heroId
      matchId
    }
  }
}
';

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
    public function __invoke(int $steamAccountId)
    {
        $requestBody = sprintf(self::QUERY, $steamAccountId);
        $response = $this->stratzDataFetcher->fetch($requestBody);

        if (!empty($response['data']['player']['firstMatchDate'])) {
            $response['data']['player']['firstMatchDate'] = $this->formatter->timestampToReadableDate(
                $response['data']['player']['firstMatchDate']
            );
        }
        if (!empty($response['data']['player']['lastMatchDate'])) {
            $response['data']['player']['lastMatchDate'] = $this->formatter->timestampToReadableDate(
                $response['data']['player']['lastMatchDate']
            );
        }

        if (!empty($response['data']['player']['names'])) {
            foreach ($response['data']['player']['names'] as &$name) {
                if (!empty($name['lastSeenDateTime'])) {
                    $name['lastSeenDateTime'] = $this->formatter->timestampToReadableDate(
                        $name['lastSeenDateTime']
                    );
                }
            }
        }
        if (!empty($response['data']['player']['badges'])) {
            foreach ($response['data']['player']['badges'] as &$badge) {
                if (!empty($badge['createdDateTime'])) {
                    $badge['createdDateTime'] = $this->formatter->timestampToReadableDate(
                        $badge['createdDateTime']
                    );
                }
            }
        }

        if (!empty($response['data']['player']['dotaPlus'])) {
            foreach ($response['data']['player']['dotaPlus'] as &$dotaPl) {
                if (!empty($dotaPl['createdDateTime'])) {
                    $dotaPl['createdDateTime'] = $this->formatter->timestampToReadableDate(
                        $dotaPl['createdDateTime']
                    );
                }
            }
        }

        if (!empty($response['data']['player']['team']['firstMatchDateTime'])) {
            $response['data']['player']['team']['firstMatchDateTime'] = $this->formatter->timestampToReadableDate(
                $response['data']['player']['team']['firstMatchDateTime']
            );
        }

        if (!empty($response['data']['player']['team']['lastMatchDateTime'])) {
            $response['data']['player']['team']['lastMatchDateTime'] = $this->formatter->timestampToReadableDate(
                $response['data']['player']['team']['lastMatchDateTime']
            );
        }

        if (!empty($response['data']['player']['guildMember']['joinDateTime'])) {
            $response['data']['player']['guildMember']['joinDateTime'] = $this->formatter->timestampToReadableDate(
                $response['data']['player']['guildMember']['joinDateTime']
            );
        }

        if (!empty($response['data']['player']['simpleSummary']['lastUpdateDateTime'])) {
            $response['data']['player']['simpleSummary']['lastUpdateDateTime'] = $this->formatter->timestampToReadableDate(
                $response['data']['player']['simpleSummary']['lastUpdateDateTime']
            );
        }

        return $response;
    }
}
