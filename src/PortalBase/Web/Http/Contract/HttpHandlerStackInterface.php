<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface HttpHandlerStackInterface
{
    public function next(
        ServerRequestInterface $request,
        ResponseInterface $response,
        HttpHandleContextInterface $context
    ): ResponseInterface;
}
