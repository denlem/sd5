<?php
declare(strict_types = 1);

namespace App\Service\DataFetcher;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class StratzDataFetcher
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly string $stratzApiToken,
        private readonly string $stratzApiUrl,
    ) {
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function fetch(string $graphQLRequestBody): array
    {
        $response = $this->client->request('POST', $this->stratzApiUrl, [
            'headers'     => [
                'Content-Type' => 'application/graphql',
            ],
            'auth_bearer' => $this->stratzApiToken,
            'body'        => $graphQLRequestBody,
        ]);

        return $response->toArray();
    }

    //    private function test()
    //    {
    //        $response = $this->client->request(
    //            'GET',
    //            'https://api.github.com/repos/symfony/symfony-docs'
    //        );
    //
    //        $statusCode = $response->getStatusCode();
    //        // $statusCode = 200
    //        $contentType = $response->getHeaders()['content-type'][0];
    //        // $contentType = 'application/json'
    //        //        $content = $response->getContent();
    //        $content = '{"id":521583, "name":"symfony-docs", ...}'
    //        $content = $response->toArray();
    //        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
    //    }

}
