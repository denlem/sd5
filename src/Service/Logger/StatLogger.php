<?php
declare(strict_types = 1);

namespace App\Service\Logger;

use Psr\Log\LoggerInterface;

/**
 * Логирование статистической информации
 */
class StatLogger extends Logger
{
    /**
     * StatLogger constructor.
     *
     * @param LoggerInterface $statLogger
     */
    public function __construct(LoggerInterface $statLogger)
    {
        $this->logger = $statLogger;
    }
}