<?php

namespace App\Services\PcParts;

use RuntimeException;

class PcPartsApiException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly int $status,
        public readonly ?string $apiCode = null,
    ) {
        parent::__construct($message);
    }
}
