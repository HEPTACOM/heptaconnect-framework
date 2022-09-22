<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture\AdminUiAction;

use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class CoreTestUiActionPayload implements AuditableDataAwareInterface
{
    public function getAuditableData(): array
    {
        return [
            'foo' => 'bar',
            'hello' => 'world',
        ];
    }
}
