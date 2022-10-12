<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture\AdminUiAction;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;

final class CoreTestUiAction implements UiActionInterface
{
    public static function class(): UiActionType
    {
        return new UiActionType(static::class);
    }
}
