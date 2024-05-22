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

class TeamResponder
{
    private const QUERY = '{
  team(teamId: %s) {
    id
    name
    tag
    dateCreated
    isPro
    isLocked
    countryCode
    url
    logo
    baseLogo
    bannerLogo
    winCount
    lossCount
    lastMatchDateTime
    countryName
    coachSteamAccountId
    coachSteamAccount {
      id
    }
    leagues {
      id
      displayName
    }
    members(skip: 0, take: 100) {
      steamAccountId
      steamAccount {
        id
      }
      player {
        steamAccountId
        matchCount
        winCount
        imp
        firstMatchDate
        lastMatchDate
        lastMatchRegionId
        behaviorScore
        isFollowed
      }
      teamId
      firstMatchId
      firstMatchDateTime
      lastMatchId
      lastMatchDateTime
      team {
        id
      }
    }
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
    public function __invoke(int $teamId)
    {
        $requestBody = sprintf(self::QUERY, $teamId);
        $response = $this->stratzDataFetcher->fetch($requestBody);

        if (!empty($response['data']['team']['firstMatchDateTime'])) {
            $response['data']['team']['firstMatchDateTime'] = $this->formatter->timestampToReadableDate(
                $response['data']['team']['firstMatchDateTime']
            );
        }

        if (!empty($response['data']['team']['lastMatchDateTime'])) {
            $response['data']['team']['lastMatchDateTime'] = $this->formatter->timestampToReadableDate(
                $response['data']['team']['lastMatchDateTime']
            );
        }

        if (!empty($response['data']['team']['members'])) {
            foreach ($response['data']['team']['members'] as &$member) {
                if (!empty($member['firstMatchDateTime'])) {
                    $member['firstMatchDateTime'] = $this->formatter->timestampToReadableDate(
                        $member['firstMatchDateTime']
                    );
                }
                if (!empty($member['lastMatchDateTime'])) {
                    $member['lastMatchDateTime'] = $this->formatter->timestampToReadableDate(
                        $member['lastMatchDateTime']
                    );
                }

                if (!empty($member['player']['firstMatchDate'])) {
                    $member['player']['firstMatchDate'] = $this->formatter->timestampToReadableDate(
                        $member['player']['firstMatchDate']
                    );
                }
                if (!empty($member['player']['lastMatchDate'])) {
                    $member['player']['lastMatchDate'] = $this->formatter->timestampToReadableDate(
                        $member['player']['lastMatchDate']
                    );
                }
            }
        }

        return $response;
    }
}
