<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http;

use Psr\Http\Message\UriInterface;

interface HttpHandlerUrlProviderInterface
{
    public function resolve(string $path): UriInterface;
}
