<?php
declare(strict_types = 1);

namespace App\Service\Logger;

use Psr\Log\LoggerInterface;

/**
 * Логирование ошибок
 */
class HealthLogger extends Logger
{
    /**
     * HealthLogger constructor.
     *
     * @param LoggerInterface $healthLogger
     */
    public function __construct(LoggerInterface $healthLogger)
    {
        $this->logger = $healthLogger;
    }
}