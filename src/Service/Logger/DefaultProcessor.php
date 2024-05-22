<?php
declare(strict_types = 1);

namespace App\Service\Logger;

/**
 * todo: написать описание для класса DefaultProcessor
 */
class DefaultProcessor
{
    /**
     * @var string
     */
    private $token;

    /**
     * Process record.
     *
     * @param array $record
     *
     * @return array
     */
    public function processRecord(array $record): array
    {
        $this->setToken($record);
        $this->setDateTimeWithMicro($record);
        $this->setServerName($record);
        $this->setRequestId($record);

        return $record;
    }

    /**
     * @param array $record
     */
    private function setToken(array &$record): void
    {
        if (null === $this->token) {
            $this->token .= \uniqid('', true);
        }
        $record['extra']['token'] = $this->token;
    }

    /**
     * Set dateTime with micro.
     *
     * @param array $record
     *
     * @return void
     */
    private function setDateTimeWithMicro(array &$record): void
    {
        $dateTime                             = (array) $record['datetime'];
        $record['extra']['dateTimeWithMicro'] = $dateTime['date'];
    }

    /**
     * Set a name of server (for prod).
     *
     * @param array $record
     */
    private function setServerName(array &$record): void
    {
        $record['extra']['serverName'] = \getenv('HOSTNAME');
    }

    /**
     * Set a requestId for Nginx.
     *
     * @param array $record
     */
    private function setRequestId(array &$record): void
    {
        $record['extra']['requestId'] = \getenv('REQUEST_ID');
    }

}