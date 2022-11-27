<?php

declare(strict_types=1);

namespace HeptacomFixture\Portal\A\AutomaticService;

use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpClientMiddlewareInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class OutboundHttpMiddleware implements HttpClientMiddlewareInterface
{
    public function process(RequestInterface $request, ClientInterface $handler): ResponseInterface
    {
        return $handler->sendRequest($request);
    }
}
