<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class HttpClientInterfaceDecorator implements ClientInterface
{
    public function __construct(private ClientInterface $decorated)
    {
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->decorated->sendRequest($request);
    }
}
