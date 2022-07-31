<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;

final class RouteMissingException extends \InvalidArgumentException implements InvalidArgumentThrowableInterface
{
    private RouteKeyInterface $routeKey;

    public function __construct(RouteKeyInterface $routeKey, int $code, ?\Throwable $previous = null)
    {
        parent::__construct('The given route key does not exist', $code, $previous);
        $this->routeKey = $routeKey;
    }

    public function getRouteKey(): RouteKeyInterface
    {
        return $this->routeKey;
    }
}
