<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface HttpClientMiddlewareInterface
{
    /**
     * When an outbound HTTP request is performed using the @see \Psr\Http\Client\ClientInterface,
     * a chain containing every implementation of this interface is executed.
     *
     * - Implementations MAY modify the request before passing it to the handler.
     * - Implementations SHOULD pass the request to the handler, to let the chain execution continue.
     * - Implementations MAY modify the handler's return value before returning it.
     *
     * A possible implementation could look like this:
     *
     * ```
     *      // modify the request
     *      $request = $request->withHeader('User-Agent', 'HEPTAconnect middleware example');
     *
     *      // pass the request to the handler
     *      $response = $handler->sendRequest($request);
     *
     *      // modify the response
     *      $response = $response->withHeader('Server', 'HEPTAconnect middleware example');
     *
     *      // return the response
     *      return $response;
     * ```
     */
    public function process(RequestInterface $request, ClientInterface $handler): ResponseInterface;
}
