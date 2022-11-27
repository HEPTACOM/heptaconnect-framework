<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;

final class RouteAlreadyExistsException extends \RuntimeException implements InvalidArgumentThrowableInterface
{
    public function __construct(
        private RouteKeyInterface $routeKey,
        int $code,
        ?\Throwable $previous = null
    ) {
        parent::__construct('The route already exists', $code, $previous);
    }

    public function getRouteKey(): RouteKeyInterface
    {
        return $this->routeKey;
    }
}
