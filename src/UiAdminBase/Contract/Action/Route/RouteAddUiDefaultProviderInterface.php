<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Route;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddDefault;

interface RouteAddUiDefaultProviderInterface
{
    /**
     * Gets any default values that are interesting for @see RouteAddUiActionInterface payloads.
     */
    public function getDefault(): RouteAddDefault;
}
