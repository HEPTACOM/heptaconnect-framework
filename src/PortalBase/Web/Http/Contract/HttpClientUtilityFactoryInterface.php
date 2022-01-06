<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract;

use Psr\Http\Client\ClientInterface;

interface HttpClientUtilityFactoryInterface
{
    public function createClientWithRequestHeaders(ClientInterface $client, array $headers): ClientInterface;

    public function createClientThrowingExceptionsOnErrorResponse(ClientInterface $client): ClientInterface;

    public function createClientFollowingRedirect(ClientInterface $client): ClientInterface;

    public function createClientRetryingOnErrorResponse(ClientInterface $client, int $maximumRetries): ClientInterface;

    public function createClientRetryingOnTooManyRequests(
        ClientInterface $client,
        int $maximumRetries,
        int $maximumWaitTimeSeconds
    ): ClientInterface;
}
