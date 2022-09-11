<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\Route;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddDefault;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionContextInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;

interface RouteAddUiDefaultProviderInterface extends UiActionInterface
{
    /**
     * Gets any default values that are interesting for @see RouteAddUiActionInterface payloads.
     */
    public function getDefault(UiActionContextInterface $context): RouteAddDefault;
}
