<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract;

use Psr\Http\Client\ClientInterface;

interface HttpClientInterface extends ClientInterface
{
    public function setRequestHeaders(array $headers): HttpClientInterface;

    public function throwExceptionsOnErrorResponse(): HttpClientInterface;

    public function followRedirect(): HttpClientInterface;

    public function retryOnErrorResponse(int $maximumRetries): HttpClientInterface;

    public function retryOnTooManyRequests(int $maximumRetries, int $maximumWaitTimeSeconds): HttpClientInterface;
}
