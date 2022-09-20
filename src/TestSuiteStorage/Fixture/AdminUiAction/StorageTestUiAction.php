<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Fixture\AdminUiAction;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;

final class StorageTestUiAction implements UiActionInterface
{
    public static function class(): UiActionType
    {
        return new UiActionType(static::class);
    }
}
