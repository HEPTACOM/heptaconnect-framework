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
     * Attributes with this prefix are managed by HEPTAconnect and SHOULD NOT be set by portals.
     */
    public const REQUEST_ATTRIBUTE_PREFIX = '@heptaconnect_portal.';

    /**
     * Holds a value, that indicates whether the @see HttpHandlerStackInterface that was prepared
     * to handle the current request is empty.
     */
    public const REQUEST_ATTRIBUTE_IS_STACK_EMPTY = self::REQUEST_ATTRIBUTE_PREFIX . 'is_stack_empty';

    /**
     * Forwards a (new) request to a different @see HttpHandlerStackInterface and returns its response
     *
     * @param UriInterface|string                $uri
     * @param StreamInterface|array|string|null  $body
     * @param array<string, string|list<string>> $headers
     */
    public function forward(
        $uri,
        string $method = 'GET',
        $body = null,
        array $headers = []
    ): ResponseInterface;
}
