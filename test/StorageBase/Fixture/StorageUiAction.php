<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Test\Fixture;

use Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;

final class StorageUiAction implements UiActionInterface
{
    public static function class(): UiActionType
    {
        return new UiActionType(static::class);
    }
}
