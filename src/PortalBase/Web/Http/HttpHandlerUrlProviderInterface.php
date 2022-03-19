<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http;

use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract;
use Psr\Http\Message\UriInterface;

interface HttpHandlerUrlProviderInterface
{
    /**
     * Resolves a given path to an HTTP handler to an absolute URL that is ready to be used by other HTTP clients.
     *
     * @see HttpHandlerContract
     */
    public function resolve(string $path): UriInterface;
}
