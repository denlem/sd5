<?php
declare(strict_types = 1);

namespace App\Service\Utils;

class Formatter
{
    public function timestampToReadableDate(int $timestamp): string
    {
        return (new \DateTime())->setTimestamp($timestamp)->format('Y-m-d H:i:s');
    }
}
