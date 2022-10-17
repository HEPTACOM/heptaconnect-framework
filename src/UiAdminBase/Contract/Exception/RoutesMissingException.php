<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception;

use Heptacom\HeptaConnect\Storage\Base\RouteKeyCollection;

final class RoutesMissingException extends \InvalidArgumentException implements InvalidArgumentThrowableInterface
{
    public function __construct(private RouteKeyCollection $routeKeys, int $code, ?\Throwable $previous = null)
    {
        parent::__construct('The given route keys do not exist', $code, $previous);
    }

    public function getRouteKeys(): RouteKeyCollection
    {
        return $this->routeKeys;
    }
}
