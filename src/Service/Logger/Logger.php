<?php
declare(strict_types = 1);

namespace App\Service\Logger;

use Psr\Log\LoggerInterface;

/**
 * Class Logger
 */
class Logger
{
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * Get LoggerInterface
     *
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}