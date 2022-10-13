<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action;

use Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\UiActionInterface;

/**
 * @extends SubtypeClassStringContract<UiActionInterface>
 * @psalm-method class-string<UiActionInterface> __toString()
 * @psalm-method class-string<UiActionInterface> jsonSerialize()
 */
final class UiActionType extends SubtypeClassStringContract
{
    public function getExpectedSuperClassName(): string
    {
        return UiActionInterface::class;
    }
}
