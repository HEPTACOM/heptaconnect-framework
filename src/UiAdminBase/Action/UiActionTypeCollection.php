<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractClassStringReferenceCollection;

/**
 * @extends AbstractClassStringReferenceCollection<UiActionType>
 */
final class UiActionTypeCollection extends AbstractClassStringReferenceCollection
{
    protected function getT(): string
    {
        return UiActionType::class;
    }
}
