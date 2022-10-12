<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType;

interface UiActionInterface
{
    /**
     * Returns a class string instance for the exact UI action interface.
     */
    public static function class(): UiActionType;
}
