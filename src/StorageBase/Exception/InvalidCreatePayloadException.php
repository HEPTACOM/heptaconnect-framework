<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Exception;

use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;

class InvalidCreatePayloadException extends CreateException
{
    public function __construct(
        private CreatePayloadInterface $payload,
        int $code,
        ?\Throwable $throwable = null
    ) {
        parent::__construct($code, $throwable);

        $this->message = 'Create payload cannot be processed as it contains invalid values';
    }

    public function getPayload(): CreatePayloadInterface
    {
        return $this->payload;
    }
}
