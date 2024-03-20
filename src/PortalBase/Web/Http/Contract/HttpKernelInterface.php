<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract;

use Psr\Http\Server\RequestHandlerInterface;

/**
 * Responds to inbound PSR-7 HTTP requests.
 */
interface HttpKernelInterface extends RequestHandlerInterface
{
}
