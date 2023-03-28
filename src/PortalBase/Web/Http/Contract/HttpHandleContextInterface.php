<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalNodeContextInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Describes HTTP handler specific contexts.
 *
 * @see HttpHandlerContract
 * @see HttpHandlerStackInterface
 */
interface HttpHandleContextInterface extends PortalNodeContextInterface
{
    /**
     * @param UriInterface|string $uri
     * @param string $method
     * @param StreamInterface|array|string|null $body
     * @param array<string, string>|array<string, array<array-key, string>> $headers
     *
     * @return ResponseInterface
     */
    public function forward(
        $uri,
        string $method = 'GET',
        $body = null,
        array $headers = []
    ): ResponseInterface;
}
