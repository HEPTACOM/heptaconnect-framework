<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext;
use Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext;

trait UiActionTestTrait
{
    private function createUiActionContext(): UiActionContext
    {
        return new UiActionContext(new UiAuditContext('test', 'phpunit'));
    }
}
